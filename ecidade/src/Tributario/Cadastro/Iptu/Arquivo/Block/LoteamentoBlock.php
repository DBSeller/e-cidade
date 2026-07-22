<?php 

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Block;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Block\Block;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Layout;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;

final class LoteamentoBlock extends Block
{
    public function __construct(Layout $layout, Converter $converter)
    {
        $this->layout = $layout;
        $this->converter = $converter;
    }
}
