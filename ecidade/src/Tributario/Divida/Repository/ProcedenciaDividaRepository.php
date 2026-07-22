<?php

namespace ECidade\Tributario\Divida\Repository;

use ECidade\Tributario\Divida\Procedencia;
use Exception;

/**
 * Repositório para a tabela "divida.proced"
 *
 * Class TermoRepositoryFactory
 *
 * @package ECidade\Tributario\Divida\Repository
 */
class ProcedenciaDividaRepository
{
    /**
     * @var ProcedenciaDividaRepository
     */
    private static $instance;

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Busca em divida.proced por v03_codigo
     *
     * @param  int $codigo
     * @return Procedencia
     */
    public function find($codigo)
    {
        $dao = new \cl_proced();
        $sql = $dao->sql_query_file($codigo);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar a procedência indicada.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Procedencia::fromState($resultado);
    }
}
