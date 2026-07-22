<?php

namespace ECidade\Tributario\Issqn\Inscricao\Atividades\Collection;

use ECidade\Lib\Collection\Collection;

/**
 *
 */
class Atividades extends Collection
{
    public function set($itens)
    {
        $this->itens = $itens;
        $this->size  = count($itens);
    }

    public function add($index, $item)
    {
        if (!empty($index) && !empty($item)) {
            if (empty($this->itens[$index])) {
                $this->size++;
            }
            
            $this->itens[$index] = $item;
        }
    }
}
