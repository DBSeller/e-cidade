<?php

namespace ECidade\Configuracao;

use ECidade\Tributario\Library\Container as ContainerAbstract;
use ECidade\Configuracao\Workflow\Repository\Acao as AcaoRepository;
use ECidade\Configuracao\Workflow\Service\Transicao as TransicaoService;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'DataBaseLegacy' => function ($container) {
                return \ECidade\V3\Datasource\Database::getInstance();
            },
            'Workflow\Repository\Acoes' => function ($container) {

                $database = $container->get('DataBaseLegacy');

                return new AcaoRepository($database);
            },
            'Workflow\Service\Transicao' => function ($container) {

                $acaoRepository = $container->get('Workflow\Repository\Acoes');

                return new TransicaoService($acaoRepository);
            }
        );

        foreach ($this->content as $name => $value) {
            $this->register($name, $value);
        }
    }
}
