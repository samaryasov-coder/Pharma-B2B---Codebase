<?php

class pb2bFileDriver implements pb2bFileSystemInterface
{
    protected string $root;
    protected ?string $baseUrl;

    public function __construct(string $root, ?string $baseUrl = null)
    {
        $this->root = rtrim($root, '/');
        $this->baseUrl = $baseUrl ? rtrim($baseUrl, '/') . '/' : null;

        if (!is_dir($this->root)) {
            mkdir($this->root, 0777, true);
        }
    }

    public function path(string $path): string
    {
        return $this->root . '/' . ltrim($path, '/');
    }

    public function exists($path)
    {
        return file_exists($this->path($path));
    }

    public function get($path)
    {
        $full = $this->path($path);
        return file_exists($full) ? file_get_contents($full) : null;
    }

    public function readStream($path)
    {
        $full = $this->path($path);
        return file_exists($full) ? fopen($full, 'rb') : null;
    }

    public function put($path, $contents, $options = [])
    {
        $full = $this->path($path);
        $dir = dirname($full);
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        if (is_resource($contents)) {
            return file_put_contents($full, stream_get_contents($contents)) !== false;
        } elseif (is_string($contents)) {
            return file_put_contents($full, $contents) !== false;
        } elseif ($contents instanceof \SplFileObject) {
            return file_put_contents($full, $contents->fread($contents->getSize())) !== false;
        }
        return false;
    }

    public function writeStream($path, $resource, array $options = [])
    {
        $full = $this->path($path);
        $dir = dirname($full);
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        return file_put_contents($full, stream_get_contents($resource)) !== false;
    }

    public function prepend($path, $data)
    {
        $full = $this->path($path);
        $contents = file_exists($full) ? file_get_contents($full) : '';
        return file_put_contents($full, $data . $contents) !== false;
    }

    public function append($path, $data)
    {
        $full = $this->path($path);
        return file_put_contents($full, $data, FILE_APPEND) !== false;
    }

    public function delete($paths)
    {
        $paths = (array)$paths;
        $success = true;
        foreach ($paths as $path) {
            $full = $this->path($path);
            if (file_exists($full)) {
                $success = $success && unlink($full);
            }
        }
        return $success;
    }

    public function copy($from, $to)
    {
        $fullFrom = $this->path($from);
        if (!file_exists($fullFrom)) return false;
        $fullTo = $this->path($to);
        $dir = dirname($fullTo);
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        return copy($fullFrom, $fullTo);
    }

    public function move($from, $to)
    {
        $fullFrom = $this->path($from);
        $fullTo = $this->path($to);
        $dir = dirname($fullTo);
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        return rename($fullFrom, $fullTo);
    }

    public function size($path)
    {
        $full = $this->path($path);
        return file_exists($full) ? filesize($full) : 0;
    }

    public function lastModified($path)
    {
        $full = $this->path($path);
        return file_exists($full) ? filemtime($full) : 0;
    }

    public function files($directory = null, $recursive = false, callable $filter = null, bool $absolute = false)
    {
        $dir = $directory ? $this->path($directory) : $this->root;
        if (!is_dir($dir)) return [];

        $files = [];
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..')
                continue;
            $fullPath = $dir . '/' . $item;
            $relative = ltrim(str_replace($this->root . '/', '', $fullPath), '/');
            if (is_file($fullPath)) {
                if ($filter === null || call_user_func($filter, $relative, $fullPath, $item)) {
                    $files[] = $absolute ? $fullPath : $relative;
                }
            } elseif ($recursive && is_dir($fullPath)) {
                $files = array_merge($files, $this->files($relative, true, $filter));
            }
        }

        return $files;
    }


    public function directories($directory = null, $recursive = false)
    {
        $dir = $directory ? $this->path($directory) : $this->root;
        if (!is_dir($dir)) return [];

        $dirs = [];
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $fullPath = $dir . '/' . $item;
            if (is_dir($fullPath)) {
                $dirs[] = str_replace($this->root . '/', '', $fullPath);
                if ($recursive) {
                    $dirs = array_merge($dirs, $this->directories(str_replace($this->root . '/', '', $fullPath), true));
                }
            }
        }
        return $dirs;
    }


    public function makeDirectory($path)
    {
        $full = $this->path($path);
        return is_dir($full) || mkdir($full, 0777, true);
    }

    public function deleteDirectory($directory)
    {
        $full = $this->path($directory);
        if (!is_dir($full)) return false;

        $it = new RecursiveDirectoryIterator($full, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }
        return rmdir($full);
    }

    public function url($path = '')
    {
        return $this->baseUrl ? $this->baseUrl . ltrim($path, '/') : null;
    }

    public function putFile($path, $file, $name = null)
    {
        if ($file instanceof waRequestFile) {
            if (!$file->uploaded()) {
                return false;
            }

            $name = $name ?: $file->name;
            $fullPath = rtrim($path, '/') . '/' . $name;
            $contents = file_get_contents($file->tmp_name);
            return $this->put($fullPath, $contents) ? $fullPath : false;
        }


        $name = $name ?: basename((string)$file);
        $fullPath = rtrim($path, '/') . '/' . $name;
        return $this->put($fullPath, $file) ? $fullPath : false;
    }
}
