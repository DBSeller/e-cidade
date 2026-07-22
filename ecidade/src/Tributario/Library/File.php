<?php 

namespace ECidade\Tributario\Library;

final class File
{
    public function create($path)
    {
        return file_put_contents($path, "");
    }

    public function write($path, $value, $content = FILE_APPEND, $lock = LOCK_EX)
    {
        return file_put_contents($path, $value, $content | $lock);
    }

    public function read($path)
    {
        return file_get_contents($path);
    }
}
