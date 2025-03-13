<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class Taxa extends Entity
{
    private $descricao;

    private $quantidade;

    private $valorTotal;

    private $valorParcela;

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function setValorTotal($valorTotal)
    {
        $this->valorTotal = $valorTotal;
    }

    public function setValorParcela($valorParcela)
    {
        $this->valorParcela = $valorParcela;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    public function getValorParcela()
    {
        return $this->valorParcela;
    }
}
