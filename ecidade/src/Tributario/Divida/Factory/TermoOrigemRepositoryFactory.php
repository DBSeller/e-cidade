<?php

namespace ECidade\Tributario\Divida\Factory;

use ECidade\Tributario\Divida\Repository\DiversosRepository;
use ECidade\Tributario\Divida\Repository\Divida as DividaRepository;
use ECidade\Tributario\Juridico\Inicial\Repository\Inicial as InicialRepository;

/**
 * Class TermoRepositoryFactory
 *
 * @package ECidade\Tributario\Divida\Factory
 */
class TermoOrigemRepositoryFactory
{
    const DIVIDA = 'termodiv';
    const REPARCELAMENTO = 'termoreparc';
    const INICIAL = 'termoini';
    const DIVERSOS = 'termodiver';
    const CONTRIBUICAO = 'termocontrib';

    /**
     * Códigos de acordo com a pl fc_excluiparcelamento
     *
     * @return array
     */
    public static function getTipoPorTabela()
    {
        return array(
            static::REPARCELAMENTO => 2,
            static::DIVIDA => 1,
            static::INICIAL => 3,
            static::DIVERSOS => 4,
            static::CONTRIBUICAO => 5,
        );
    }

    /**
     * @param  $tipo
     * @return DiversosRepository | DividaRepository | InicialRepository
     */
    public static function get($tipo)
    {
        $repository = null;
        switch ($tipo) {
            case static::DIVIDA:
                $repository = DividaRepository::getInstance();
                break;
            case static::DIVERSOS:
                $repository = DiversosRepository::getInstance();
                break;
            case static::INICIAL:
                $repository = InicialRepository::getInstance();
                break;
        }

        return $repository;
    }
}
