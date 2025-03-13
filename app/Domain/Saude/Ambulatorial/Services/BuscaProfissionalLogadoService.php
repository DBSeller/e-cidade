<?php

namespace App\Domain\Saude\Ambulatorial\Services;

use App\Domain\Patrimonial\Protocolo\Contracts\BuscaCgmLogado;

class BuscaProfissionalLogadoService implements BuscaCgmLogado
{
    /**
     * Retorna o id do profissional
     * @return int
     */
    public function getCgm()
    {
        $idUsuario = db_getsession('DB_id_usuario');
        $dao = new \cl_medicos();
        $where = "db_usuacgm.id_usuario = {$idUsuario}";
        $sql = $dao->sql_query_profissional_saude('', 'sd03_i_codigo', '', $where);
        $result = $dao->sql_record($sql);
        
        if (!$result) {
            return '';
        }

        return \db_utils::fieldsMemory($result, 0)->sd03_i_codigo;
    }
}
