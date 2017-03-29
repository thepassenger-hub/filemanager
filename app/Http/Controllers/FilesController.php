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
        $file = request('file');
        try {
            \File::put($path . '/' . $file, '');
        }
        catch (Exception $e) {
            return 'Error';
        }
    }

    public function destroy()
    {
        $path = request('path');
        if (\File::exists($path)) {
            try {
                \File::delete($path);
            }
            catch (Exception $e) {
                return 'Error';
            };
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
        $newName = request('newName');
        $file = request('file');
        if (\File::exists('newName')){
            return response('Error: There is already another file with that name', 409);
        }
        if (\File::exists($file)) {
            try {
                $path = parentPath($file);
                $newPath = $path . '/' . $newName;
                \File::move($file, $newPath);
            }
            catch (Exception $e) {
                return response('Something went wrong. Try again', 500);
            }
        }
    }
}
