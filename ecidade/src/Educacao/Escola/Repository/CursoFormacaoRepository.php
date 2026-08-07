<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 26/04/2019
 * Time: 12:33
 */

namespace ECidade\Educacao\Escola\Repository;

use cl_cursoformacao;
use ECidade\Educacao\Escola\Model\CursoFormacao;

class CursoFormacaoRepository
{
    /**
     * @param $id
     * @return CursoFormacao|null
     */
    public static function find($id)
    {
        $dao = new cl_cursoformacao();
        $rs = db_query($dao->sql_query_file($id));
        if (!$rs || pg_num_rows($rs) === 0) {
            return null;
        }

        return CursoFormacao::fromState(pg_fetch_array($rs));
    }
}
