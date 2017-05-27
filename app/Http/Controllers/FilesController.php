<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Files\Repositories\FileRepository;

class FilesController extends Controller
{
    function __construct(FileRepository $files) {
        $this->files = $files;
    }

    public function index()
    {
        $path = request('path') ? request('path') : getenv("HOME");
        return $this->files->all($path);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|string',
            'type' => 'required|string'
        ]);
        $error = $this->files->create(request('file'), request('type'));
        if ($error) return response($error['message'], $error['code']);
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'path' => 'required|string',
            'type' => 'required|string'
        ]);
        $error = $this->files->delete(request('path'), request('type'));
        if ($error) return response($error['message'], $error['code']);
    }

    public function move(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|string',
            'path' => 'required|string'
        ]);
        $error = $this->files->move(request('file'), request('path'));
        if ($error) return response($error['message'], $error['code']);
    }

    public function copy(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|string',
            'path' => 'required|string',
            'type' => 'required|string'
        ]);
        $error = $this->files->copy(request('file'), request('path'), request('type'));
        if ($error) return response($error['message'], $error['code']);
    }

    public function rename(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|string',
            'newName' => 'required|string'
        ]);
        $error = $this->files->rename(request('file'), request('newName'));
        if ($error) return response($error['message'], $error['code']);
    }

    public function permission(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|string',
            'permission' => 'required|string'
        ]);
        $error = $this->files->chmod(request('file'), request('permission'));
        if ($error) return response($error['message'], $error['code']);
    }

    public function open(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|string'
        ]);
        $error = $this->files->open(request('file'));
        if ($error) return response($error['message'], $error['code']);
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file',
            'path' => 'required|string'
        ]);

        if (request()->file('file')->isValid()) {
            $file = request()->file('file');
            $originalName = $file->getClientOriginalName();
            $file = base_path('storage/app/') . $file->storeAs('public', $originalName);
            $error = $this->files->move($file, request('path'));
            if ($error) return response($error['message'], $error['code']);
        }
    }
}
