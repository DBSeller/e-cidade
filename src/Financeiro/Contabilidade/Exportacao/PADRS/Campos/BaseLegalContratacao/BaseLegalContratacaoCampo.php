<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\BaseLegalContratacao;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\AbstractCampo;

class BaseLegalContratacaoCampo extends AbstractCampo
{
    public function __construct($exercicio, $empenho = null)
    {
        $this->estrategia = new BaseLegalContratacaoEstrategia($empenho);

        if ($exercicio >= 2018 && $this->pluginEstaAtivo()) {
            $this->estrategia = new BaseLegalContratacao2018Estrategia($empenho);
        }
    }
}
