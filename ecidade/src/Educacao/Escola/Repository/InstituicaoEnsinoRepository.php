<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 26/04/2019
 * Time: 12:19
 */

namespace ECidade\Educacao\Escola\Repository;


use cl_censoinstsuperior;
use ECidade\Educacao\Escola\Model\InstituicaoEnsino;

class InstituicaoEnsinoRepository
{
    public static function find($id)
    {
        $dao = new cl_censoinstsuperior();
        $rs = db_query($dao->sql_query_file($id));
        if (!$rs || pg_num_rows($rs) === 0) {
            return null;
        }

        return InstituicaoEnsino::fromState(pg_fetch_array($rs));
    }

}
