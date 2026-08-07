<?php


namespace ECidade\Educacao\Escola\Repository;

use ECidade\Educacao\Escola\Model\Ensino;
use Exception;

/**
 * Class EnsinoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class EnsinoRepository extends Repository
{
    private static $campos = ["ensino.*", "tipoensino.*"];
    private static $orderBy = ["ed10_ordem"];

    /**
     * @param integer $id
     * @return Ensino
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new \cl_ensino();
        $sql = $dao->sql_query($id, implode(', ', self::$campos), implode(', ', self::$orderBy));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Ensino.");
        }

        return Ensino::fromState(pg_fetch_array($rs));
    }
}
