<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 23/08/18
 * Time: 14:37
 */

namespace ECidade\RecursosHumanos\ESocial\Model;


use Job;

class JobEsocial extends Job
{
    public function excluir()
    {
        return true;
    }
}
