<?php


namespace ECidade\Educacao\Escola\Repository;

use ECidade\Educacao\Escola\Model\AreaConhecimento;
use Exception;

/**
 * Class AreaConhecimentoRespository
 * @package ECidade\Educacao\Escola\Repository
 */
class AreaConhecimentoRepository extends Repository
{

    /**
     * @param $id
     * @return AreaConhecimento
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new \cl_areaconhecimento();
        $rs = db_query($dao->sql_query_file($id));
        if (!$rs) {
            throw new Exception("Erro ao buscar área de conhecimento.");
        }

        return AreaConhecimento::fromState(pg_fetch_array($rs));
    }
}
