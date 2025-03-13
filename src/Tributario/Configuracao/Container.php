<?php

namespace ECidade\Tributario\Configuracao;

use ECidade\Tributario\Library\Container as ContainerAbstract;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'DbsysfuncoesRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_db_sysfuncoes();

                return new \ECidade\Tributario\Configuracao\Repository\DbsysfuncoesRepository($dataBase, $dao);
            },
            'InstituicaoRepository' => function ($container) {
                $dataBase = $container->get('DataBase');

                return new \ECidade\Tributario\Configuracao\Entity\Repository\InstituicaoRepository($dataBase);
            },
            'Workflow\Repository\Acoes' => function ($container) {

                return new \ECidade\Configuracao\Workflow\Repository\Acao();
            },
            'Workflow\Service\Transicao' => function ($container) {

                $acaoRepository = $container->get('Workflow\Repository\Acoes');

                return new \ECidade\Configuracao\Service\Transicao($acaoRepository);
            }
        );
    }
}
