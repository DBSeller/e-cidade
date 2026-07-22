<?php

namespace ECidade\Library\File;

use ECidade\Tributario\Library\Service;

class FileService extends Service
{
    private $file;

    private $path;

    private $lines;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function create($path)
    {
        $this->path = $path;
        $this->open();
    }

    public function open()
    {
        $this->file->create($this->path);
    }

    public function addLine($line)
    {
        $this->lines .= $line."\r\n";
    }

    public function write()
    {
        if (!empty($this->lines)) {
            $this->file->write($this->path, $this->lines);

            $this->lines = "";
        }
    }

    public function path()
    {
        return $this->path;
    }

    public function toArray()
    {
        return $this->file->toArray($this->path);
    }
}
