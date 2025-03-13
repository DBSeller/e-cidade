<?php


namespace ECidade\Financeiro\Tesouraria\Repository;

use cl_linha_tef_processado;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Tesouraria\Models\LinhaTefProcessado;

class LinhaTefProcessadoRepository extends Repository
{
    public function save(LinhaTefProcessado $linhaTefProcessado)
    {
        $dao = new cl_linha_tef_processado();
        $dao->id = $linhaTefProcessado->getId();
        $dao->conlancam_id = $linhaTefProcessado->getCodigoLancamento();
        $dao->linha_tef_id = $linhaTefProcessado->getArquivoTefId();
        if (empty($dao->id)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->id);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception('Erro ao salvar', 403);
        }

        $linhaTefProcessado->setId($dao->id);

        return $linhaTefProcessado;
    }
}
