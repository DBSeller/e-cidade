<?php
namespace ECidade\File\Txt;

class LerTxt
{
    /**
     * @var false|resource
     */
    private $file;

    public function __construct($file)
    {
        $this->file = fopen($file, "r");
    }

    /**
     * @return \Generator
     */
    public function read()
    {
        while (!feof($this->file)) {
            yield str_replace(["\n", "\r"], "", fgets($this->file));
        }
    }
}
