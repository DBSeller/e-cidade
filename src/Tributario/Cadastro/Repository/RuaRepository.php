<?php


namespace ECidade\Tributario\Cadastro\Repository;

use cl_ruas;
use ECidade\Tributario\Cadastro\Model\Rua;
use Exception;

/**
 * Class RuaRepository
 * @package ECidade\Tributario\Cadastro\Repository
 */
class RuaRepository
{
    /**
     * @param integer $id
     * @return Rua
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_ruas();
        $rs = db_query($dao->sql_query_file($id));

        if (!$rs) {
            throw new Exception("Erro ao buscar Rua.");
        }

        return Rua::fromState(pg_fetch_array($rs));
    }
}
