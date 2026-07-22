<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Arrecadacao\Repository\Arrecad;
use App\Domain\Tributario\Arrecadacao\Contracts\AcaoControleParcelamento;
use App\Domain\Tributario\Arrecadacao\Models\AgendamentoControleParcelamento;
use db_utils;
use ECidade\Tributario\Arrecadacao\Model\Arrecad as ModelArrecad;

class AnulacaoParcelamento implements AcaoControleParcelamento
{

    public function processar(AgendamentoControleParcelamento $agendamento)
    {
        $dao = new \cl_arrecad;
        $diasMargem = $agendamento->ar49_margem_dias;
        $diasPrazo = $agendamento->ar49_prazo_dias;
        $tipoParc = $agendamento->ar49_tipo_parcelamento;
        $numParcVenc = $agendamento->ar49_parcelas_vencidas;
        $data = new \DateTime(date('Y-m-d', db_getsession("DB_datausu")));
        $data->modify("-{$diasMargem} days");
        $dataOperacao = $data->format('Y-m-d');

        $where = "k00_tipo = {$tipoParc}";
        $where .="having count((k00_dtvenc + INTERVAL'{$diasPrazo} days') <= {$dataOperacao}) >= {$numParcVenc}";

        $sql = $dao->sql_query_file(null, 'k00_numpre', null, $where);
        $rs = $dao->sql_record($sql);
        if (!$rs) {
            return;
        }

        $parcelas = db_utils::getCollectionByRecord($rs);
    }

    public function salvarRegistroOriginal(ModelArrecad $arrecad)
    {
        if ($achouachou) {
            return false;
        }

        return true;
    }

    private function buscaDataVencimento($numpre)
    {
        $repository = new Arrecad(new DataBase, new \cl_arrecad);

        $where = "k00_numpre = {$numpre}";
        $arrecads = $repository->findAll($where . $this->orderBy);
        $first = array_shift($arrecads);

        return $first->k00_dtvenc;
    }

    private function salvarDataVencimento($numpre, $dataVencimento)
    {
        $repository = new Arrecad(new DataBase, new \cl_arrecad);

        $where = "k00_numpre = {$numpre}";
        $arrecads = $repository->findAll($where . $this->orderBy);

        foreach ($arrecads as $arrecad) {
            if (!$this->salvarRegistroOriginal($arrecad)) {
                continue;
            }

            $arrecad->setDataVencimento($dataVencimento);
            $repository->persist($arrecad);
        }
    }
}
