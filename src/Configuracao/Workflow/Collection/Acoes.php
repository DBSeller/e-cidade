<?php

namespace ECidade\Configuracao\Workflow\Collection;

use ECidade\Lib\Collection\Collection;

class Acoes extends Collection
{
    public function set($itens)
    {
        $this->itens = $itens;
        $this->size  = count($itens);
    }

    public function add($item)
    {
        $this->itens[] = $item;
        $this->size++;
    }
}
