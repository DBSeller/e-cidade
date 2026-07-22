<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Campos;

use Plugin;

abstract class AbstractCampo
{
    protected $estrategia;

    public function getValor()
    {
        return $this->estrategia->getValor();
    }

    public function getDescricao()
    {
        return $this->estrategia->getDescricao();
    }

    protected function pluginEstaAtivo()
    {
        $plugin = new Plugin(null, 'ContratosPADRS');

        return $plugin->isAtivo();
    }
}
