<?php

/**
 * Class cl_lotelancamento
 * @property int h23_sequencial
 * @property string h23_data
 * @property int h23_instituicao
 * @property int h23_tipoassentamento
 */
class cl_lotelancamento extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("recursoshumanos.lotelancamento");
    }

    public function sql_query_lotes($h23_sequencial = null, $campos="*", $ordem=null, $where = null)
    {
        $sql = "SELECT {$campos}
                FROM lotelancamento
                LEFT JOIN db_config ON db_config.codigo = lotelancamento.h23_instituicao
                LEFT JOIN tipoasse ON tipoasse.h12_codigo = lotelancamento.h23_tipoassentamento 
                LEFT JOIN tipoassedb_depart ON tipoassedb_depart.rh184_tipoasse = tipoasse.h12_codigo ";

        if (empty($where)) {
            if (!empty($h23_sequencial)) {
                $sql .= " WHERE h23_sequencial = {$h23_sequencial} ";
            }
        } else {
            $sql .= " WHERE {$where} ";
        }

        if (!empty($ordem)) {
            $sql .= " ORDER BY {$ordem} ";
        }

        return $sql;
    }

    public function sql_query_lote_por_assentamento($codigoAssentamento)
    {
        $sql = "SELECT h23_sequencial as codigo 
                FROM lotelancamento
                INNER JOIN loteassentamento ON h24_lotelancamento = h23_sequencial
                WHERE h24_assenta = {$codigoAssentamento}
                LIMIT 1;
                ";
        return $sql;
    }

    public function sql_query_lote_por_lotacao($lotacao)
    {
        $sql = "
           select
               distinct h23_sequencial from recursoshumanos.lotelancamento
               inner join recursoshumanos.loteassentamento on h24_lotelancamento = h23_sequencial
               inner join recursoshumanos.assenta on h24_assenta = h16_codigo
               inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo
               inner join pessoal.rhpessoalmov on h16_regist = rh02_regist
                   and rh02_anousu = " . DBPessoal::getAnoFolha() . "
                   and rh02_mesusu = " . DBPessoal::getMesFolha() . "
               inner join pessoal.rhlota on rh02_lota = r70_codigo
           where
               r70_codigo in ($lotacao)
       ";

        return $sql;
    }

    public function sql_query_lote_assentamento_efetividade()
    {
        $sql = "
            select 
                distinct h23_sequencial from lotelancamento
                inner join loteassentamento on h24_lotelancamento = h23_sequencial
                inner join assenta on h24_assenta = h16_codigo
                left join assentamentofuncional on rh193_assentamento_funcional = h16_codigo
                inner join rhpessoalmov on h16_regist = rh02_regist
                    AND rh02_anousu = " . DBPessoal::getAnoFolha() . "
                    AND rh02_mesusu = " . DBPessoal::getMesFolha() . "
                WHERE 
                    rh193_assentamento_funcional is null;
       ";

        return $sql;
    }
}
