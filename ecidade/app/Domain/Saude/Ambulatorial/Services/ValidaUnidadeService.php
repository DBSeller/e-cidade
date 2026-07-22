<?php

namespace App\Domain\Saude\Ambulatorial\Services;

use App\Domain\Configuracao\Departamento\Templates\ValidaDepartamentoTemplate;

class ValidaUnidadeService extends ValidaDepartamentoTemplate
{
    protected $mensagem = 'O Departamento logado não é uma unidade de saúde.';

    /**
     * Valida se o Departamento é uma unidade de Saúde
     */
    protected function isValido()
    {
        $dao = new \cl_unidades();

        $sql = $dao->sql_query_file(db_getsession('DB_coddepto'));
        $result = $dao->sql_record($sql);

        if (!$result) {
            return false;
        }
        
        return true;
    }
}
