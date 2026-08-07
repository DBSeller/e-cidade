<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout\Layout;
use ECidade\Tributario\Library\ArrayCollection;
use ECidade\Tributario\Library\Entity;

abstract class CollectionConverter extends ArrayCollection
{
    protected $layout;

    public function __construct(array $collection, Layout $layout)
    {
        parent::__construct($collection);
        $this->layout = $layout;
    }
}
