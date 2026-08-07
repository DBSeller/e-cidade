<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\TipoInstrumentoContratual;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\AbstractCampo;

class TipoInstrumentoContratualCampo extends AbstractCampo
{
    public function __construct($exercicio, $lancamento = null)
    {
        $this->estrategia = new TipoInstrumentoContratualEstrategia($lancamento);

        if ($exercicio >= 2018 && $this->pluginEstaAtivo()) {
            $this->estrategia = new TipoInstrumentoContratual2018Estrategia($lancamento);
        }
    }
}
