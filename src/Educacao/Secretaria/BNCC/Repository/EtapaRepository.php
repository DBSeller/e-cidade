<?php

namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\Etapa;
use ECidade\Enum\Educacao\BNCC\EnsinoEnum;
use Exception;

/**
 * Class EtapaRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class EtapaRepository extends Repository
{
    /**
     * @var string
     */
    private $ordem = 'ed152_ensino, ed152_etapa';

    /**
     * @param integer $id
     * @return Etapa
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new \cl_bnccetapas();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar as etapas do ensino.");
        }

        return Etapa::fromState(pg_fetch_array($rs));
    }

    /**
     * @return Etapa[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_bnccetapas();
        $sql = $dao->sql_query_file(null, '*', $this->ordem, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar as etapas da BNCC.");
        }

        $etapas = [];
        while ($state = pg_fetch_array($rs)) {
            $etapas[] = Etapa::fromState($state);
        }

        return $etapas;
    }

    /**
     * @param EnsinoEnum $ensino
     * @param string $operador
     * @return EtapaRepository
     */
    public function scopeEnsino(EnsinoEnum $ensino, $operador = '=')
    {
        $this->scopes['ensino_bncc'] = "ed152_ensino {$operador} '{$ensino->value()}'";
        return $this;
    }
}
