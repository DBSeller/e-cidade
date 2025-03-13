<?php 

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Block;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Layout;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;

abstract class Block 
{
    protected $layout;

    protected $converter;

    public function __construct(Layout $layout, Converter $converter)
    {
        $this->layout = $layout;
        $this->converter = $converter;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function getConverter()
    {
        return $this->converter;
    }
}
