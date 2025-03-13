<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\IdentificadorDespesaFuncionario;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos\AbstractCampo;

class IdentificadorDespesaFuncionarioCampo extends AbstractCampo
{
    public function __construct($exercicio, $empenho = null)
    {
        $this->estrategia = new IdentificadorDespesaFuncionarioEstrategia($empenho);

        if ($exercicio >= 2018 && $this->pluginEstaAtivo()) {
            $this->estrategia = new IdentificadorDespesaFuncionario2018Estrategia($empenho);
        }
    }

    public function setValor($valor = 'X')
    {
        $this->estrategia->setValor($valor);
    }
}
