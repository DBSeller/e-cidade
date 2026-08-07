<?php

namespace ECidade\Patrimonial\Material\Repositories;

use cl_db_almox;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Patrimonial\Material\Models\Deposito;
use Exception;

class DepositoRepository extends Repository
{
    /**
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_db_almox();
        $sql = $dao->sql_query(null, 'm91_codigo, coddepto', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Depósitos.");
        }

        $depositos = [];

        while ($state = pg_fetch_array($rs)) {
            $depositos[] = Deposito::fromState($state);
        }

        return $depositos;
    }

    /**
     * @return Deposito|null
     * @throws Exception
     */
    public function first()
    {
        $depositos = $this->get();
        if (empty($depositos)) {
            return null;
        }

        return array_shift($depositos);
    }

    /**
     * @param $key
     * @return Deposito
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new cl_db_almox();
        $sql = $dao->sql_query($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Depósito.");
        }

        return Deposito::fromState(pg_fetch_array($rs));
    }

    /**
     * @param $codigoDepartamento
     * @return $this
     */
    public function scopeDepartamento($codigoDepartamento)
    {
        $this->scopes['departamento'] = "m91_depto = {$codigoDepartamento}";
        return $this;
    }
}
