<?php


namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;

class ConfiguracaoUsuario extends Configuracao
{
    /**
     * @param array $state
     * @return ConfiguracaoPadrao
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('o133_sequencial', $state)) {
            $self->setSequencial($state['o133_sequencial']);
        }

        if (array_key_exists('o133_orcparamrel', $state)) {
            $relatorio = RelatorioRegistry::get($state['o133_orcparamrel']);
            $self->setRelatorio($relatorio);

            if (array_key_exists('o133_orcparamseq', $state)) {
                $self->setLinha(LinhaRegistry::get($relatorio, $state['o133_orcparamseq']));
            }
        }

        if (array_key_exists('o133_anousu', $state)) {
            $self->setAno($state['o133_anousu']);
        }

        if (array_key_exists('o133_filtro', $state)) {
            $self->setFiltro($state['o133_filtro']);
        }

        return $self;
    }
}
