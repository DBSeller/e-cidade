<?php

namespace ECidade\Financeiro\Orcamento\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Model\Complemento;
use Exception;

/**
 * Class ComplementoRepository
 * @package ECidade\Financeiro\Repository
 */

class ComplementoRepository extends Repository
{
    /**
     * @return Complemento[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_complementofonterecurso();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar complementos");
        }

        $complementos = [];
        while ($state = pg_fetch_array($rs)) {
            $complementos[] = Complemento::fromState($state);
        }

        return $complementos;
    }

    /**
     * @return Complemento[]
     * @throws Exception
     */
    public function all()
    {
        $this->resetScopes();
        return $this->get();
    }

    /**
     * @param $id
     * @return Complemento
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new \cl_complementofonterecurso();
        $sql = $dao->sql_query_file(null, '*', null, "o200_sequencial = {$id}");
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar complemento do recurso.");
        }
        return Complemento::fromState(pg_fetch_array($rs));
    }
}
