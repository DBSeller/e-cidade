<?php


namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use cl_bnccdisciplinas;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Enum\Educacao\BNCC\EnsinoEnum;
use Exception;

/**
 * Class DisciplinaRepository
 * @package ECidade\Educacao\Secretaria\BNCC\Repository
 */
class DisciplinaRepository extends Repository
{
    /**
     * @var string
     */
    private $ordem = "ed149_ensino, ed149_nome";

    /**
     * @return Disciplina[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_bnccdisciplinas();
        $sql = $dao->sql_query_file(null, '*', $this->ordem, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar disciplinas da BNCC.");
        }

        $disciplinas = [];
        while ($state = pg_fetch_array($rs)) {
            $disciplinas[] = Disciplina::fromState($state);
        }

        return $disciplinas;
    }
    /**
     * @param $id
     * @return Disciplina
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_bnccdisciplinas();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar disciplina da BNCC.");
        }

        return Disciplina::fromState(pg_fetch_array($rs));
    }

    /**
     * @param EnsinoEnum $ensinoEnum
     * @return $this
     */
    public function scopeEnsino(EnsinoEnum $ensinoEnum)
    {
        $this->scopes[] = "ed149_ensino = '{$ensinoEnum->value()}'";
        return $this;
    }
}
