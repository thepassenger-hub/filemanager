<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilesController extends Controller
{
    public function index()
    {
        $path = request('path');
        $path = $path ? $path : getenv("HOME")."/Desktop";
        $files = \File::files($path);
        $directories = \File::directories($path);
        $parentPath = \File::dirname($path);
        $parentDir = \File::exists($parentPath);
        $parentDir = $parentDir ? $parentPath : false;
        return compact('directories', 'files', 'parentDir');
    }

    public function store()
    {
        $path = request('path');
        $path = $path ? $path : getenv("HOME")."/Desktop";
        $file = $path . '/' . request('file');
        $type = request('type');
        try {
            if (\File::exists($file)) {
                return response('This file or directory already exists.', 400);
            }
            if ($type == 'file') {
                \File::put($file, '');
            }
            else if ($type == 'folder') {
                \File::makeDirectory($file);
            };
        }
        catch (Exception $e) {
            return response('There was an error performing your request.', 500);
        }
    }

    public function destroy()
    {
        $path = request('path');
        $type = request('type');
        if (\File::exists($path)) {
            try {
                if (\File::isFile($path)) {
                    \File::delete($path);
                }
                else if (\File::isDirectory($path)) {
                    \File::deleteDirectory($path);
                };
            }
            catch (Exception $e) {
                return response('There was an error processing your request. Try again later.', 500);
            };
        }
        else {
            return response('The file or directory doesn\'t exist.', 400);
        };
    }

    public function move()
    {
        $path = request('path') ? request('path') : getenv("HOME")."/Desktop";
        $file = request('file');
        $fileName = explode('/', $file);
        $fileName = array_pop($fileName);
        $path = $path . '/' . $fileName;
        if (\File::exists($file)){
            try {
                \File::move($file, $path);
            }
            catch (Exception $e) {
                return $e;
            }
        };
    }

    public function copy()
    {
        $path = request('path') ? request('path') : getenv("HOME")."/Desktop";
        $file = request('file');
        $fileName = explode('/', $file);
        $fileName = array_pop($fileName);
        $path = $path . '/' . $fileName;
        if (\File::exists($file)){
            try {
                \File::copy($file, $path);
            }
            catch (Exception $e) {
                return $e;
            }
        };
    }

    public function rename()
    {
        $file = request('file');
        $newName = request('newName');
        $path = parentPath($file);
        $newPath = $path . '/' . $newName;
        
        if (\File::exists($newPath)){
            return response('Error: There is already another file with that name', 409);
        }
        if (\File::exists($file)) {
            try {
                \File::move($file, $newPath);
            }
            catch (Exception $e) {
                return response('Something went wrong. Try again', 500);
            }
        }
    }

    public function permission()
    {
        $file = request('file');
        $permission = request('permission');
        if (preg_match('/^[0-7]{3}$/', $permission) && \File::exists($file)){
            if (\File::chmod($file, octdec($permission))) {
                return response('Permission change successful', 200);
            }
            else {
                return response('Something went wrong. Try again later', 500);
            };
        }
        else {
            return response('The file entered doesn\'t exist or the permission number is invalid', 400);
        };
    }
}
