<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Library\Format;
use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Cadastro\IPTU\Arquivo\Layout\Layout;

abstract class Converter
{
    protected $format;
    protected $layout;

    /**
     * @todo - layout resebeu remoção de type e default null para migração de classes legadas
     */
    public function __construct(Format $format, $layout = null)
    {
        $this->format = $format;
        $this->layout = $layout;
    }

    abstract public function get(Entity $entity);
}
