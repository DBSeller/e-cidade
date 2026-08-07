<?php


namespace ECidade\Educacao\Escola\Repository;

use cl_base;
use ECidade\Educacao\Escola\Model\BaseCurricular;
use Escola;
use Exception;

/**
 * Class BaseCurricularRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class BaseCurricularRepository extends Repository
{


    /**
     * @param $key
     * @return BaseCurricular
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new cl_base();
        $sql = $dao->sql_query_file($key, "*");
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Base Curriculares");
        }

        return BaseCurricular::fromState(pg_fetch_array($rs));
    }

    /**
     * @return BaseCurricular[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_base();
        $sql = $dao->sql_query_file(null, "*", null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Base Curriculares");
        }

        $bases = [];
        while ($state = pg_fetch_array($rs)) {
            $bases[] = BaseCurricular::fromState($state);
        }
        return $bases;
    }

    /**
     * @param Escola $escola
     * @return $this
     */
    public function scopeEscola(Escola $escola)
    {
        $this->scopes['escola'] = "
            exists (
                select 1
                  from escolabase
                  where escolabase.ed77_i_base = base.ed31_i_codigo
                  and escolabase.ed77_i_escola = {$escola->getCodigo()}
         )";

        return $this;
    }
}
