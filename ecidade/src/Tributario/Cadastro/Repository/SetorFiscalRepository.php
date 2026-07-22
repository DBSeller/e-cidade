<?php

namespace ECidade\Tributario\Cadastro\Repository;

class SetorFiscalRepository extends \BaseClassRepository
{
    public function getSetorFiscalByMatric($matricula)
    {
        $dao = new \cl_setorfiscal();

        $sql = $dao->getSetorFiscalByMatricula($matricula);

        $result = db_query($sql);

        if (!$result) {
            throw new \Exception("Erro ao buscar o setor fiscal da matricula {$matricula}. Erro: ".pg_last_error());
        }

        return \db_utils::fieldsMemory($result, 0);
    }
}
