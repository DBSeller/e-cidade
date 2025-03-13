<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\ExisteContratoTermo;

class ExisteContratoTermoEstrategia
{
    protected $lancamento;

    public function __construct($lancamento)
    {
        $this->lancamento = $lancamento;
    }

    public function getValor()
    {
        return '';
    }
}
