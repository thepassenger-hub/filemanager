<?php
namespace App\Files\Repositories;

use App\Files\Contracts\FileRepositoryInterface;

class FileRepository implements FileRepositoryInterface {

    /**
     * Get all files and directories inside a path.
     *
     * @param  string  $path
     * @return  array  Contains all files,directories, parent path and current path.
     **/
    public function all($path)
    {
        $path = $path ? $path : getenv("HOME");

        $files = collect((\File::files($path)))
                    ->values()
                    ->map(function($file){
                        return [
                            'path' => $file, 
                            'size'=>\File::size($file),
                            'lastModified' => \File::lastmodified($file)];
                        });

        $dirs = collect(\File::directories($path))
                    ->map(function($dir){
                        return [
                            'path' => $dir,
                            'lastModified' => \File::lastmodified($dir)
                            ];
                    });

        $parentDir = \File::exists(\File::dirname($path)) ? \File::dirname($path) : false;

        return compact('dirs', 'files', 'parentDir', 'path');
    }

    /**
     * Create a new empty file or an empty folder at the given path.
     *
     * @param  string  $file  The file or directory path.
     * @param  string  $type  The type of the element. {"folder" or "file"}
     **/
    public function create($file, $type)
    {
        if (\File::exists($file)) 
            return $this->notify('This file or directory already exists', 409);

        try {
            $type === 'file' ? \File::put($file, '') : \File::makeDirectory($file);
        } catch (Exception $e) {
            return $this->notify($e->getMessage(), 500);
        }
    }

    /**
     * Delete a file or a directory.
     *
     * @param  string  $path  The file or directory path.
     * @param  string  $type  The type of the element. {"folder" or "file"}
     **/
    public function delete($path, $type)
    {
        if (! \File::exists($path)) return $this->notify('The file or directory doesn\'t exist.', 400);
        try {
            \File::isFile($path) ? \File::delete($path) : \File::deleteDirectory($path);
        }
        catch (Exception $e) {
            return $this->notify($e->getMessage(), 500);
        };
    }

    /**
     * Move a file or a directory.
     *
     * @param  string  $file  The file or directory path.
     * @param  string  $path  The new file or directory location.
     **/
    public function move($file, $path)
    {
        if (! \File::exists($file)) return $this->notify('The file or directory doesn\'t exist.', 400);        
        try {
            \File::move($file, $path . '/' . fileName($file));
        }
        catch (Exception $e) {
            return $this->notify($e->getMessage(), 500);
        }
    }

    /**
     * Copy a file or a directory.
     *
     * @param  string  $file  The file or directory path.
     * @param  string  $path  The new file or directory location.
     * @param  string  $type  The type of the element. {"folder" or "file"}
     **/
    public function copy($file, $path, $type)
    {
        if (! \File::exists($file)) return $this->notify('The file or directory doesn\'t exist.', 400);        
        try {
            $type === 'file' ? \File::copy($file, $path . '/' . fileName($file)) : \File::copyDirectory($file, $path . '/' . fileName($file));
        }
        catch (Exception $e) {
            return $this->notify($e->getMessage(), 500);
        }
    }

    /**
     * Rename a file or a directory.
     *
     * @param  string  $file  The file or directory path.
     * @param  string  $name  The new file or directory name.
     **/
    public function rename($file, $name)
    {
        if (\File::exists(parentPath($file) . '/' . $name))
            return $this->notify('There is already another file with that name', 409);
        
        try {
            \File::move($file, parentPath($file) . '/' . $name);
        }
        catch (Exception $e) {
            return $this->notify($e->getMessage(), 500);
        }
    }

    /**
     * Change a file or a directory permission.
     *
     * @param  string  $file  The file or directory path.
     * @param  string  $permission  The new file or directory permission {000-777}.
     **/
    public function chmod($file, $permission)
    {
        if (! (preg_match('/^[0-7]{3}$/', $permission) && \File::exists($file)))
            return $this->notify('The file entered doesn\'t exist or the permission number is invalid', 400);
            
        try {
            if (! \File::chmod($file, octdec($permission)))
                return $this->notify('Something went wrong. Try again later', 500);
        } catch (Exception $e) {
            return $this->notify($e->getMessage(), 500);
        };
    }

    /**
     * Open a file or a directory with default program for debian OS.
     *
     * @param  string  $file  The file or directory path.
     **/
    public function open($file)
    {
        if (!$file || ! \File::exists($file))
            return $this->notify('The file does not exist.', 400);

        try {
            shell_exec("xdg-open $file");
        } catch(Exception $e) {
            return $this->notify($e->getMessage(), 500);
        }
    }

    /**
     * Create an array with a message and a status code.
     *
     * @param  string  $message
     * @param  int  $code  The http status code to send to response.     
     * @return  array 
     **/
    public function notify($message, $code)
    {
        return compact('message', 'code');
    }
}
