<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\TipoInstrumentoContratual;

class TipoInstrumentoContratualEstrategia
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
