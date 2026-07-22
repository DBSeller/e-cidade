<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Arrecadacao\Repository\Arrecad;
use App\Domain\Tributario\Arrecadacao\Contracts\AcaoControleParcelamento;
use App\Domain\Tributario\Arrecadacao\Models\AgendamentoControleParcelamento;
use db_utils;

class AntecipaVencimentoDataTermoService implements AcaoControleParcelamento
{
    public function processar(AgendamentoControleParcelamento $agendamento)
    {
        $daoArrecad = new \cl_arrecad;
        $cltermo = new \cl_termo;
        $idAgendamento = $agendamento->ar49_id;
        $diasMargem = $agendamento->ar49_margem_dias;
        $diasPrazo = $agendamento->ar49_prazo_dias;
        $regraParc = $agendamento->ar49_regra_parcelamento;
        $numParcVenc = $agendamento->ar49_parcelas_vencidas;
        $data = new \DateTime(date('Y-m-d', db_getsession("DB_datausu")));
        $dataProc = $data->format('Y-m-d');
        $data->modify("-{$diasMargem} days");
        $dataPrazo = $data->format('Y-m-d');
        
        $where  = "k00_numpre in 
                    (select
                        k00_numpre
                    from
                        (select 
                            count(*) as qtd,
                            v07_parcel,
                            v07_desconto,
                            k00_numpre,
                            k00_tipo
                        from
                            (select distinct 
                                v07_parcel,
                                v07_desconto,
                                k00_numpre,
                                k00_numpar,
                                k00_tipo
                            from
                                termo
                            inner join arrecad on
                                v07_numpre = k00_numpre
                            where
                                v07_desconto = {$regraParc}
                                and k00_dtvenc < '{$dataProc}' 
                                and v07_parcel in 
                                    (select distinct 
                                        v07_parcel
                                    from
                                        termo
                                    inner join arrecad on
                                        v07_numpre = k00_numpre
                                    where
                                        v07_desconto = {$regraParc}
                                        and k00_dtvenc >= '{$dataProc}')
                                        and v07_parcel not in 
                                                (select distinct 
                                                    v07_parcel
                                                from
                                                    termo
                                                inner join recibopaga on
                                                    v07_numpre = k00_numpre
                                                inner join recibopagaboleto on
                                                    k00_numnov = k138_numnov
                                                where
                                                    v07_desconto = {$regraParc}
                                                    and k138_data >= '{$dataPrazo}'
                                            union all
                                                select distinct 
                                                    v07_parcel
                                                from
                                                    termo
                                                inner join arrecad a on 
                                                    v07_numpre = a.k00_numpre 
                                                inner join recibopaga b on 
                                                    v07_numpre = b.k00_numpre
                                                where
                                                    v07_desconto = {$regraParc}
                                                    and b.k00_dtvenc <= '{$dataProc}'
                                                    and b.k00_dtpaga >= current_date + {$diasPrazo}))
                                                        as y
                        group by
                            v07_parcel,
                            v07_desconto,
                            k00_numpre,
                            k00_tipo) as z
                        where
                            qtd >= {$numParcVenc})";
        $sql = $daoArrecad->sql_query_file(null, '*', null, $where);
        $rs = $daoArrecad->sql_record($sql);
        if ($daoArrecad->numrows == 0) {
            $time = date('d/m/Y H:i:s');
            $msg = "[{$time}] Não foram encontrados registros para o processamento {$idAgendamento}.";
            file_put_contents('tmp/controleparcelamentovencidotas.log', "$msg \n", FILE_APPEND);
        }

        $parcelas = db_utils::getCollectionByRecord($rs);

        foreach ($parcelas as $parcela) {
            $sWhere = "v07_numpre = {$parcela->k00_numpre}";
            $sSqlDataTermo = $cltermo->sql_query_file(null, 'v07_dtlanc', null, $sWhere);
            $rsDataTermo   = $cltermo->sql_record($sSqlDataTermo);
            if ($cltermo->numrows == 0) {
                continue;
            }
            $dataTermo = \db_utils::fieldsMemory($rsDataTermo, 0);
 
            $repository = new Arrecad(DataBase::getInstance(), new \cl_arrecad);
            $model = $repository->make($parcela);
        
            if (!RegistroOriginalService::salvar($model, $dataTermo->v07_dtlanc, $agendamento->ar49_id, $dataProc)) {
                continue;
            }

            $model->setDataVencimento($dataTermo->v07_dtlanc);

            $where = [];
            $where[] = "k00_numpre = {$model->getNumpre()}";
            $where[] = "k00_numpar = {$model->getNumpar()}";
            $where[] = "k00_receit = {$model->getReceita()}";

            $where = implode(' AND ', $where);

            $repository->alterar($model, $where);
        }
    }
}
