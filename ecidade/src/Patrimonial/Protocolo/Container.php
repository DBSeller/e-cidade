<?php

namespace ECidade\Patrimonial\Protocolo;

use ECidade\Tributario\Library\Container as ContainerAbstract;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Repository\ConsultaProcesso as RepositoryConsultaProcesso;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Service\Service as ProcessoEletronico;
use ECidade\Patrimonial\Protocolo\Servicos\InclusaoCgmLegacy;
use ECidade\Patrimonial\Protocolo\Servicos\InclusaoProcesso;

final class Container extends ContainerAbstract
{
    /**
     * @todo rever uma forma de gerar container do protocolo para poder segmentar os servicos
     */
    public function charge()
    {
        $this->content = array(
            'DataBaseLegacy' => function ($container) {
                return \ ECidade\V3\Datasource\Database::getInstance();
            },
            'Processo\ProcessoEletronico\Service\ConsultaProcessos' => function ($container) {

                $recurso =  'Processo
                            \ProcessoEletronico
                            \Repository
                            \ConsultaProcessos';
                $recurso = preg_replace("/[\\n ]*/", '', $recurso);

                $consultaProcessosRepository = $container->get($recurso);

                return new ProcessoEletronico($consultaProcessosRepository);
            },
            'Processo\ProcessoEletronico\Repository\ConsultaProcessos' => function ($container) {

                $dataBase = $container->get('DataBase');

                return new RepositoryConsultaProcesso($dataBase);
            },
            'Processo\ProcessoEletronico\Filter\ListagemProcessos' => function ($container) {

                return new FiltroListagemProcessos();
            },
            'Servicos\InclusaoCgmLegacy' => function ($container) {

                return new InclusaoCgmLegacy();
            },
            'Servicos\InclusaoProcesso' => function ($container) {

                return new InclusaoProcesso();
            },
        );
    }
}
