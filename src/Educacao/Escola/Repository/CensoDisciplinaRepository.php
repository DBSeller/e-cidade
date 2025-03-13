<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 26/04/2019
 * Time: 15:20
 */

namespace ECidade\Educacao\Escola\Repository;

use cl_censodisciplina;
use ECidade\Educacao\Escola\Model\CensoDisciplina;
use ECidade\Educacao\Escola\Model\ComponenteCurricular;
use Exception;

/**
 * Class CensoDisciplinaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class CensoDisciplinaRepository extends Repository
{

    public static function find($id)
    {
        $dao = new cl_censodisciplina();
        $rs = db_query($dao->sql_query_file($id));

        if (!$rs) {
            throw new Exception("Erro ao buscar a disciplina.");
        }

        return CensoDisciplina::fromState(pg_fetch_array($rs));
    }

    /**
     * @return CensoDisciplina[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_censodisciplina();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar a disciplina do censo.");
        }

        $disciplinas = [];
        while ($state = pg_fetch_array($rs)) {
            $disciplinas[] = CensoDisciplina::fromState($state);
        }

        return $disciplinas;
    }

    public function scopeComponenteCurricular(ComponenteCurricular $componenteCurricular)
    {
        $this->scopes['disciplina'] = "
            exists (select 1 from censocaddisciplina
                where censocaddisciplina.ed294_censodisciplina = censodisciplina.ed265_i_codigo
                 and censocaddisciplina.ed294_caddisciplina = {$componenteCurricular->getCodigo()}
            )";
        return $this;
    }
}
