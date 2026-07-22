<?php

namespace ECidade\Tributario\Cadastro\Repository;

use cl_bairro;
use ECidade\Tributario\Cadastro\Model\Bairro;
use Exception;

/**
 * Class BairroRepository
 * @package ECidade\Tributario\Cadastro\Repository
 */
class BairroRepository
{
    /**
     * @param $id
     * @return Bairro
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_bairro();
        $rs = db_query($dao->sql_query_file($id));
        if (!$rs) {
            throw new Exception("Erro ao buscar o bairro.");
        }

        return Bairro::fromState(pg_fetch_array($rs));
    }
}
