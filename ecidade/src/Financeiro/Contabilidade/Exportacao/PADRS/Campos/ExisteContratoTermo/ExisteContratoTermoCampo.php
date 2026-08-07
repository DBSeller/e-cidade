<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\ExisteContratoTermo;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\AbstractCampo;

class ExisteContratoTermoCampo extends AbstractCampo
{
    public function __construct($exercicio, $lancamento)
    {
        $this->estrategia = new ExisteContratoTermoEstrategia($lancamento);

        if ($exercicio >= 2018 && $this->pluginEstaAtivo()) {
            $this->estrategia = new ExisteContratoTermo2018Estrategia($lancamento);
        }
    }
}
