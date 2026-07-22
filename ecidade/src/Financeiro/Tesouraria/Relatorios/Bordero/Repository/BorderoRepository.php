<?php

namespace ECidade\Financeiro\Tesouraria\Relatorios\Bordero\Repository;

use DBDate;
use Exception;

/**
 * Class BorderoRepository
 */
class BorderoRepository
{
    /**
     * @param DBDate $dataInicial
     * @param DBDate $dataFinal
     * @param $instituicao
     * @param $conta
     * @return array
     * @throws Exception
     */
    public function getDados(DBDate $dataInicial, DBDate $dataFinal, $instituicao, $conta)
    {
        $whereReduzido = "";
        if (!empty($conta) && (int) $conta) {
            $whereReduzido = " and db83_sequencial = {$conta}";
        }

        $sql = "select arquivo,
       conta_pagadora,
       data_pagamento,
       data_geracao,
       movimento,
       empenho,
       slip,
       ocorrencia,
       credor,
       valor,
       db83_sequencial,
       db83_descricao
from (select  e75_codgera as arquivo,
        'Banco: '||db89_db_bancos||'/'||db89_codagencia||'-'||db89_digito||'/'||db83_conta||'-'||db83_dvconta
            as conta_pagadora,
        c70_data as data_pagamento,
        e87_data as data_geracao,
        e76_codmov as movimento,
        empenho,
        slip,
        e02_errobanco||'-'||e92_descrerro as ocorrencia,
        z01_numcgm||'-'||z01_nome as credor,
        db83_sequencial,
        db83_descricao,
        c70_valor as valor
      from  (select  e75_codgera,
                     e76_codmov,
                     e02_errobanco,
                     e92_descrerro,
                     c70_valor,
                     c70_data,
                     e87_data,
                     c82_reduz,
                     db89_db_bancos,
                     db89_codagencia,
                     db89_digito,
                     db83_conta,
                     db83_dvconta,
                     z01_numcgm,
                     z01_nome,
                     db83_sequencial,
                     db83_descricao,
                     '' as empenho,
                     (select e89_codigo
                      from empageslip
                      where e89_codmov = e76_codmov)::varchar as slip
             from conlancamcorrente
                      inner join corlanc on c86_id = k12_id and c86_data = k12_data and c86_autent = k12_autent
                      inner join conlancam on c86_conlancam = c70_codlan
                      inner join conlancaminstit on c02_codlan = c70_codlan
                      inner join conlancampag on c82_codlan = c70_codlan
                      inner join conlancamcgm on c76_codlan = c70_codlan
                      inner join cgm on z01_numcgm = c76_numcgm
                      inner join conplanocontabancaria on c56_anousu = c70_anousu and c56_reduz = c82_reduz
                      inner join contabancaria on db83_sequencial = c56_contabancaria
                      inner join bancoagencia on db83_bancoagencia = db89_sequencial
                      inner join conlancamslip on c84_slip = k12_codigo and c84_conlancam = c86_conlancam
                      inner join empageslip on e89_codigo = k12_codigo
                      inner join empagedadosretmov on e76_codmov = e89_codmov
                            and e76_processado = true and e76_dataefet = k12_data
                      inner join empagedadosret on e75_codret = e76_codret
                      inner join empagegera on e75_codgera = e87_codgera
                      inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret
                            and e02_empagedadosretmov = e76_codmov and e02_errobanco = 269
                      inner join errobanco on e92_sequencia = e02_errobanco
             where k12_data between '{$dataInicial->getDate()}' and '{$dataFinal->getDate()}'
               and c02_instit = {$instituicao} {$whereReduzido}
             union

             select  e75_codgera,
                     e76_codmov,
                     e02_errobanco,
                     e92_descrerro,
                     c70_valor,
                     c70_data,
                     e87_data,
                     c82_reduz,
                     db89_db_bancos,
                     db89_codagencia,
                     db89_digito,
                     db83_conta,
                     db83_dvconta,
                     z01_numcgm,
                     z01_nome,
                     db83_sequencial,
                     db83_descricao,
                     (select e60_codemp || '/' || e60_anousu
                      from empempenho
                      where e81_numemp = e60_numemp) as empenho,
                     '' as slip
             from conlancamcorgrupocorrente
                      inner join conlancam on c23_conlancam = c70_codlan
                      inner join conlancaminstit on c02_codlan = c70_codlan
                      inner join conlancampag on c82_codlan = c70_codlan
                      inner join conlancamcgm on c76_codlan = c70_codlan
                      inner join cgm on z01_numcgm = c76_numcgm
                      inner join conplanocontabancaria on c56_anousu = c70_anousu and c56_reduz = c82_reduz
                      inner join contabancaria on db83_sequencial = c56_contabancaria
                      inner join bancoagencia on db83_bancoagencia = db89_sequencial
                      inner join corgrupocorrente on k105_sequencial = c23_corgrupocorrente
                      inner join corempagemov on k12_id = k105_id and k12_data = k105_data and k12_autent = k105_autent
                      inner join empagedadosretmov on e76_codmov = k12_codmov
                            and e76_processado = true and e76_dataefet = k105_data
                      inner join empagedadosret on e75_codret = e76_codret
                      inner join empagegera on e75_codgera = e87_codgera
                      inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret
                            and e02_empagedadosretmov = e76_codmov and e02_errobanco = 269
                      inner join errobanco on e92_sequencia = e02_errobanco
                      inner join empagemov on e76_codmov = e81_codmov
             where k12_data between '{$dataInicial->getDate()}' and '{$dataFinal->getDate()}'
               and c02_instit = {$instituicao} {$whereReduzido}
            ) as x
     ) as xx order by data_geracao, arquivo, data_pagamento";

        $rs = pg_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar dados da conta {$conta}");
        }

        $dados = [];
        while ($row = pg_fetch_array($rs)) {
            $dados[] = $row;
        }

        return $dados;
    }
}
