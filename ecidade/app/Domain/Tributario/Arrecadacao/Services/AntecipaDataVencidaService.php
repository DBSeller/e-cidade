<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Arrecadacao\Repository\Arrecad;
use App\Domain\Tributario\Arrecadacao\Contracts\AcaoControleParcelamento;
use App\Domain\Tributario\Arrecadacao\Models\AgendamentoControleParcelamento;
use db_utils;

class AntecipaDataVencidaService implements AcaoControleParcelamento
{
    const MAIOR_DATA = 1;
    const MENOR_DATA = 2;

    private $orderBy;

    public function __construct($tipo)
    {
        switch ($tipo) {
            case self::MAIOR_DATA:
                $this->orderBy = " order by k00_dtvenc desc";
                break;
            case self::MENOR_DATA:
                $this->orderBy = " order by k00_dtvenc asc";
                break;
            default:
                break;
        }
    }
    public function processar(AgendamentoControleParcelamento $agendamento)
    {
        $daoArrecad = new \cl_arrecad;
        $idAgendamento = $agendamento->ar49_id;
        $diasMargem = $agendamento->ar49_margem_dias;
        $diasPrazo = $agendamento->ar49_prazo_dias;
        $regraParc = $agendamento->ar49_regra_parcelamento;
        $numParcVenc = $agendamento->ar49_parcelas_vencidas;
        $data = new \DateTime(date('Y-m-d', db_getsession("DB_datausu")));
        $dataProc = $data->format('Y-m-d');
        
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
                                                    and k138_data >= current_date - {$diasMargem}
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
        $where .= " and k00_dtvenc >= '{$dataProc}'";
        $sql = $daoArrecad->sql_query_file(null, '*', null, $where);
        $rs = $daoArrecad->sql_record($sql);
        if ($daoArrecad->numrows == 0) {
            $time = date('d/m/Y H:i:s');
            $msg = "[{$time}] Não foram encontrados registros para o processamento {$idAgendamento}.";
            file_put_contents('tmp/controleparcelamentovencidotas.log', "$msg \n", FILE_APPEND);
        }

        $parcelas = db_utils::getCollectionByRecord($rs);

        foreach ($parcelas as $parcela) {
            $dataVencimento = $this->buscaDataVencimento($parcela->k00_numpre, $dataProc);
            $repository = new Arrecad(DataBase::getInstance(), new \cl_arrecad);
            $model = $repository->make($parcela);
            
            if (!RegistroOriginalService::salvar($model, $dataVencimento, $agendamento->ar49_id)) {
                continue;
            }

            $model->setDataVencimento($dataVencimento);

            $where = [];
            $where[] = "k00_numpre = {$model->getNumpre()}";
            $where[] = "k00_numpar = {$model->getNumpar()}";
            $where[] = "k00_receit = {$model->getReceita()}";

            $where = implode(' AND ', $where);

            $repository->alterar($model, $where);
        }
    }

    private function buscaDataVencimento($numpre, $dataProc)
    {
        $repository = new Arrecad(DataBase::getInstance(), new \cl_arrecad);

        $where = "k00_numpre = {$numpre} and k00_dtvenc <= '{$dataProc}'";
        $arrecads = $repository->findAll($where . $this->orderBy);
        $first = array_shift($arrecads);
        
        return $first->getDataVencimento();
    }
}
