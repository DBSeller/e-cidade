<?php

namespace App\Domain\Tributario\Arrecadacao\Repositories;

use App\Domain\Tributario\Arrecadacao\Models\Disarq;

class DisarqRepository
{
    private $disarq;

    public function __construct()
    {
        $this->disarq = new Disarq();
    }

    public function getCodclaByCodretAndInstit($codret, $instit, $colunas = ["*"])
    {
        $cl_disarq = new \cl_disarq();

        $sSql = $cl_disarq->sqlCodclaByCodretAndInstit($codret, $instit, implode(",", $colunas));

        $rResult = db_query($sSql);

        if (!$rResult) {
            throw new \Exception("Erro ao buscar o codcla!");
        }

        return \db_utils::fieldsMemory($rResult, 0);
    }

    public function salvar(Disarq $entity)
    {
        $cl_disarq = new \cl_disarq();

        $cl_disarq->id_usuario = $entity->getIdUsuario();
        $cl_disarq->k15_codbco = $entity->getCodbco();
        $cl_disarq->k15_codage  = $entity->getCodage();
        $cl_disarq->codret = $entity->getCodret();
        $cl_disarq->arqret  = $entity->getArqret();
        $cl_disarq->textoret  = $entity->getTextoret();
        $cl_disarq->dtretorno  = $entity->getDtretorno();
        $cl_disarq->dtarquivo  = $entity->getDtarquivo();
        $cl_disarq->k00_conta = $entity->getConta();
        $cl_disarq->autent = $entity->isAutent();
        $cl_disarq->instit  = $entity->getInstit();
        $cl_disarq->md5  = $entity->getMd5();

        $cl_disarq->incluir(null);

        if ($cl_disarq->erro_status == "0") {
            throw new \Exception("Erro ao inserir os dados na tabela disarq.\\n{$cl_disarq->erro_msg}");
        }

        return $cl_disarq->codret;
    }
}
