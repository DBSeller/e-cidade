<?php

namespace ECidade\Financeiro\Empenho\Repository;

use cl_empempenhooutrosdados;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Empenho\Model\OutrosDados;

class OutrosDadosRepository extends Repository
{
    public function findEmenho($id)
    {
        $dao = new cl_empempenhooutrosdados();
        $sql = $dao->sql_query_file(null, '*', null, "e171_numemp = $id");
        $rs = db_query($sql);

        if ($rs && pg_num_rows($rs) === 1) {
            return OutrosDados::fromState(pg_fetch_assoc($rs));
        }

        return false;
    }

    public function salvar(OutrosDados $outrosDados)
    {
        $dao = new cl_empempenhooutrosdados();
        $dao->e171_numdadosemp = $outrosDados->getCodigo();
        $dao->e171_numemp = $outrosDados->getEmpenho();
        $dao->e171_dados = json_encode($outrosDados->getOutrosDados());
        if (empty($dao->e171_numdadosemp)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->e171_numdadosemp);
        }

        $outrosDados->setCodigo($dao->e171_numdadosemp);

        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao salvar outros dados do empenho.");
        }

        return $outrosDados;
    }
}
