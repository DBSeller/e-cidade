<?php

namespace ECidade\Tributario\Inflatores;

use ECidade\Tributario\Library\Container as ContainerAbstract;

final class Container extends ContainerAbstract
{
    public function charge()
    {
        $this->content = array(
            'InflaRepository' => function ($container) {

                $dataBase = $container->get('DataBase');
                $dao = new \cl_infla();

                return new \ECidade\Tributario\Inflatores\Repository\InflaRepository($dataBase, $dao);
            }
        );
    }
}
