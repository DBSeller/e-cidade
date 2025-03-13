<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Service;

use ECidade\Library\File\File;
use ECidade\Tributario\Library\Service;

class ArquivoTxtService extends Service
{
    private $file;
    private $path;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function path($path)
    {
        if (!empty($path)) {
            $this->path = $path;
            return $this;
        }

        return $this->path;
    }

    public function toArray()
    {
        return $this->file->toArray($this->path);
    }
}
