<?php

namespace ECidade\Tributario\Configuracao\Entity;

use \ECidade\Tributario\Library\Entity;

class Instituicao extends Entity
{
    private $codigo;
    private $nome;

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }
}