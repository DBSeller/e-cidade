<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity;

use ECidade\Tributario\Library\Entity;

class Trailer extends Entity
{
    const TIPO_REGISTRO_TRAILER = 'TIPOREGISTRO';
    const QUANTIDADE_REGISTROS  = 'QUANTIDADEREGISTROS';
    const RESERVADO             = 'RESERVADO';
    const SEQUENCIAL            = 'SEQUENCIAL';

    private $sequencial;
    private $quantidadeRegistros;

    /**
     * @return mixed
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param mixed $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidadeRegistros;
    }

    /**
     * @param mixed $quantidadeRegistros
     */
    public function setQuantidade($quantidadeRegistros)
    {
        $this->quantidadeRegistros = $quantidadeRegistros;
    }
}