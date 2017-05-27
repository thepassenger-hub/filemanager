<?php

namespace App\Files\Contracts;

interface FileRepositoryInterface {
    public function all($path);
    public function create($file, $path);
    public function delete($file, $type);
    public function move($file, $path);
    public function copy($file, $path, $type);
    public function rename($file, $name);
    public function chmod($file, $permission);
    public function open($file);
}