<?php

function parentPath($path){
    $path = explode('/', $path);
    // if (count($path) > 1) {
    array_pop($path);
    $parentDir = implode($path, '/');
    return $parentDir;
    // };
    // return '';
}

function fileName($path){
    $fileName = explode('/', $path);
    $fileName = array_pop($fileName);
    return $fileName;
}