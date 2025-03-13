<?php

namespace ECidade\Saude\Ambulatorial\Repository;

use ECidade\Saude\Ambulatorial\Model\CgsAuditoria;

class CgsAuditoriaRepository extends Repository
{

    public function salvar($numCgs)
    {
        $daoCgsAuditoria = new \cl_cgsauditoria();
        
        $daoCgsAuditoria->z18_cgs = $numCgs;
        $daoCgsAuditoria->z18_usuario = db_getsession('DB_login');
        $daoCgsAuditoria->incluir(null);
    }

    public static function find($id)
    {
        if (is_null($id)) {
            return null;
        }

        $dao = new \cl_cgsauditoria();
        $rs = db_query($dao->sql_query_file($id));
        if (!$rs || pg_num_rows($rs) === 0) {
            return null;
        }

        return CgsAuditoria::fromState(pg_fetch_array($rs));
    }

    public static function getUltimoRegistroByCgs($numCgs)
    {

        $daoCgsAuditoria = new \cl_cgsauditoria();
        
        $rs = db_query(
            $daoCgsAuditoria->sql_query_file(
                null,
                "*",
                "1 desc limit 1",
                "z18_cgs = {$numCgs}"
            )
        );

        if (!$rs || pg_num_rows($rs) === 0) {
            return null;
        }

        return CgsAuditoria::fromState(pg_fetch_array($rs));
    }
}
