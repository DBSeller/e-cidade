<?php
include (modification("classes/db_conciliapendcorrente_classe.php"));

$clconciliapendcorrente = new cl_conciliapendcorrente();

db_inicio_transacao();

// incluir autenticacoes do dia com pendencia do dia
$data_ultima_proc = "";
// select na corrente por data e conta e for duplicando as pendencias de conciliacoes anteriores para essa em questao
$sqlPendCorrente = " select k68_data as data_ultima_proc";
$sqlPendCorrente .= "     from concilia ";
$sqlPendCorrente .= "    where k68_data = (select k68_data
                                             from concilia
                                            where k68_data < '" . $data . "'
                                              and k68_contabancaria = $conta
                                            order by k68_data
                                             desc limit 1)";
$sqlPendCorrente .= "      and k68_contabancaria = " . $conta;

$rsCorrente = $clconcilia->sql_record($sqlPendCorrente);
$intNumrows = $clconcilia->numrows;
for ($i = 0; $i < $intNumrows; $i ++) {
    db_fieldsmemory($rsCorrente, $i);
}

// incluido para juntar todos os dias ate a conciliacao
$nao_soma_um_dia = 1;
if (! isset($data_ultima_proc) or $data_ultima_proc == "") {
    $data_ultima_proc = $data;
    $nao_soma_um_dia = 0;
}

$sqlAutentica = " select caixa,                                  ";
$sqlAutentica .= "        autent,                                 ";
$sqlAutentica .= "        arquivo,                                ";
$sqlAutentica .= "        data,                                   ";
$sqlAutentica .= "        sum(valor_debito) as valor_debito,      ";
$sqlAutentica .= "        sum(valor_credito) as valor_credito,    ";
$sqlAutentica .= "        cheque,                                 ";
$sqlAutentica .= "        credor,                                 ";
$sqlAutentica .= "        min(detalhe) as detalhe,                ";
$sqlAutentica .= "        classe,                                 ";
$sqlAutentica .= "        itemconciliacao,                        ";
$sqlAutentica .= "        erro,                                    ";
$sqlAutentica .= "        justificativa                                    ";
$sqlAutentica .= "   from ( select distinct ";
$sqlAutentica .= "                 caixa as caixa, ";
$sqlAutentica .= "                 autent as autent, ";
$sqlAutentica .= "                 arquivo as arquivo, ";
$sqlAutentica .= "                 data as data, ";
$sqlAutentica .= "                 valor_debito as valor_debito, ";
$sqlAutentica .= "                 valor_credito as valor_credito, ";
$sqlAutentica .= "                 receita as receita, ";
$sqlAutentica .= "                 cheque as cheque, ";
$sqlAutentica .= "                 credor as credor, ";
$sqlAutentica .= "                 detalhe as detalhe, ";
$sqlAutentica .= "                 case ";
$sqlAutentica .= "                   when x.classe = 'conciliado' then 'conciliado' ";
$sqlAutentica .= "                   when ( k86_data is not null and k86_documento is not null ) then 'preselecionado' ";
$sqlAutentica .= "                   else x.classe ";
$sqlAutentica .= "                 end as classe, ";
$sqlAutentica .= "                 itemconciliacao, ";
$sqlAutentica .= "                 justificativa, ";
$sqlAutentica .= "                 erro as erro ";
$sqlAutentica .= "            from ( ";

// pendentes
$sqlAutentica .= "                   select distinct ";
$sqlAutentica .= "                          ricaixa           as caixa, ";
$sqlAutentica .= "                          riautent          as autent, ";
$sqlAutentica .= "                          ( select e75_codgera from conlancamcorrente inner join corlanc on c86_id = k12_id and c86_data = k12_data and c86_autent = k12_autent inner join conlancamslip on c84_slip = k12_codigo and c84_conlancam = c86_conlancam inner join empageslip on e89_codigo = k12_codigo inner join empagedadosretmov on e76_codmov = e89_codmov and e76_processado = true and e76_dataefet = k12_data inner join empagedadosret on e75_codret = e76_codret inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret and e02_empagedadosretmov = e76_codmov and e02_errobanco in (2, 269) where corlanc.k12_data = ridata and corlanc.k12_id = ricaixa and corlanc.k12_autent = riautent union select e75_codgera from conlancamcorgrupocorrente inner join corgrupocorrente on k105_sequencial = c23_corgrupocorrente inner join corempagemov  on k12_id = k105_id and k12_data = k105_data and k12_autent = k105_autent inner join empagedadosretmov on e76_codmov = k12_codmov and e76_processado = true and e76_dataefet = k105_data inner join empagedadosret on e75_codret = e76_codret inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret and e02_empagedadosretmov = e76_codmov and e02_errobanco in (2, 269) where corempagemov.k12_data = ridata and corempagemov.k12_id = ricaixa and corempagemov.k12_autent = riautent ) as arquivo, ";
$sqlAutentica .= "                          ridata            as data, ";
$sqlAutentica .= "                          rnvalordebito     as valor_debito, ";
$sqlAutentica .= "                          rivalorcredito    as valor_credito, ";
$sqlAutentica .= "                          rireceita         as receita, ";
$sqlAutentica .= "                          richeque          as cheque, ";
$sqlAutentica .= "                          rtcredor          as credor, ";
$sqlAutentica .= "                          rtdetalhe         as detalhe, ";
$sqlAutentica .= "                          k89_justificativa as justificativa, ";
$sqlAutentica .= "                          'pendente'        as classe,";
$sqlAutentica .= "                          0                 as itemconciliacao, ";
$sqlAutentica .= "                          rberro            as erro ";
$sqlAutentica .= "                     from conciliapendcorrente ";
$sqlAutentica .= "                          inner join concilia on k68_sequencial = k89_concilia ";
$sqlAutentica .= "                          inner join ( select * from fc_extratocaixa(" . db_getsession('DB_instit') . ",$conta,'" . $data_ultima_proc . "'::date + $nao_soma_um_dia,'" . $data . "',false ) ) as x ";
$sqlAutentica .= "                                                          on ricaixa  = k89_id ";
$sqlAutentica .= "                                                         and riautent = k89_autent ";
$sqlAutentica .= "                                                         and ridata   = k89_data ";
$sqlAutentica .= "                           left join conciliacor          on ricaixa  = k84_id ";
$sqlAutentica .= "                                                         and riautent = k84_autent ";
$sqlAutentica .= "                                                         and ridata   = k84_data ";
$sqlAutentica .= "                           left join conciliaitem         on k83_sequencial = k84_conciliaitem ";
$sqlAutentica .= "                                                         and k83_concilia = (select k68_sequencial ";
$sqlAutentica .= "                                                                               from concilia  ";
$sqlAutentica .= "                                                                              where k68_contabancaria = {$conta} ";
$sqlAutentica .= "                                                                                and k68_data = '" . $data . "' ) ";
$sqlAutentica .= "                     where ( k83_sequencial is null ) ";
$sqlAutentica .= "                       and k68_sequencial = " . $concilia;

$sqlAutentica .= "                     union all ";

// conciliados
$sqlAutentica .= "                    select distinct ";
$sqlAutentica .= "                           ricaixa        as caixa, ";
$sqlAutentica .= "                           riautent       as autent, ";
$sqlAutentica .= "                           ( select e75_codgera from conlancamcorrente inner join corlanc on c86_id = k12_id and c86_data = k12_data and c86_autent = k12_autent inner join conlancamslip on c84_slip = k12_codigo and c84_conlancam = c86_conlancam inner join empageslip on e89_codigo = k12_codigo inner join empagedadosretmov on e76_codmov = e89_codmov and e76_processado = true and e76_dataefet = k12_data inner join empagedadosret on e75_codret = e76_codret inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret and e02_empagedadosretmov = e76_codmov and e02_errobanco in (2, 269) where corlanc.k12_data = ridata and corlanc.k12_id = ricaixa and corlanc.k12_autent = riautent union select e75_codgera from conlancamcorgrupocorrente inner join corgrupocorrente on k105_sequencial = c23_corgrupocorrente inner join corempagemov  on k12_id = k105_id and k12_data = k105_data and k12_autent = k105_autent inner join empagedadosretmov on e76_codmov = k12_codmov and e76_processado = true and e76_dataefet = k105_data inner join empagedadosret on e75_codret = e76_codret inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret and e02_empagedadosretmov = e76_codmov and e02_errobanco in (2, 269) where corempagemov.k12_data = ridata and corempagemov.k12_id = ricaixa and corempagemov.k12_autent = riautent ) as arquivo, ";
$sqlAutentica .= "                           ridata         as data, ";
$sqlAutentica .= "                           rnvalordebito  as valor_debito, ";
$sqlAutentica .= "                           rivalorcredito as valor_credito, ";
$sqlAutentica .= "                           rireceita      as receita, ";
$sqlAutentica .= "                           richeque       as cheque, ";
$sqlAutentica .= "                           rtcredor       as credor, ";
$sqlAutentica .= "                           rtdetalhe      as detalhe, ";
$sqlAutentica .= "                           ''             as justificativa, ";
$sqlAutentica .= "                           'conciliado'   as classe, ";
$sqlAutentica .= "                           k83_sequencial as itemconciliacao, ";
$sqlAutentica .= "                           rberro         as erro ";
$sqlAutentica .= "                      from conciliacor ";
$sqlAutentica .= "                           inner join conciliaitem on k83_sequencial = k84_conciliaitem ";
$sqlAutentica .= "                           inner join concilia     on k83_concilia   = k68_sequencial ";
$sqlAutentica .= "                           inner join fc_extratocaixa(" . db_getsession('DB_instit') . ",$conta,'" . $data_ultima_proc . "'::date + $nao_soma_um_dia,'" . $data . "',false ) ";
$sqlAutentica .= "                                                   on k84_id     = ricaixa ";
$sqlAutentica .= "                                                  and k84_autent = riautent ";
$sqlAutentica .= "                                                  and k84_data   = ridata ";
$sqlAutentica .= "                     where k68_sequencial = " . $concilia;

$sqlAutentica .= "                     union all ";

// registros normais
$sqlAutentica .= "                    select distinct ";
$sqlAutentica .= "                           ricaixa        as caixa, ";
$sqlAutentica .= "                           riautent       as autent, ";
$sqlAutentica .= "                           ( select e75_codgera from conlancamcorrente inner join corlanc on c86_id = k12_id and c86_data = k12_data and c86_autent = k12_autent inner join conlancamslip on c84_slip = k12_codigo and c84_conlancam = c86_conlancam inner join empageslip on e89_codigo = k12_codigo inner join empagedadosretmov on e76_codmov = e89_codmov and e76_processado = true and e76_dataefet = k12_data inner join empagedadosret on e75_codret = e76_codret inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret and e02_empagedadosretmov = e76_codmov and e02_errobanco in (2, 269) where corlanc.k12_data = ridata and corlanc.k12_id = ricaixa and corlanc.k12_autent = riautent union select e75_codgera from conlancamcorgrupocorrente inner join corgrupocorrente on k105_sequencial = c23_corgrupocorrente inner join corempagemov  on k12_id = k105_id and k12_data = k105_data and k12_autent = k105_autent inner join empagedadosretmov on e76_codmov = k12_codmov and e76_processado = true and e76_dataefet = k105_data inner join empagedadosret on e75_codret = e76_codret inner join empagedadosretmovocorrencia on e02_empagedadosret = e76_codret and e02_empagedadosretmov = e76_codmov and e02_errobanco in (2,269) where corempagemov.k12_data = ridata and corempagemov.k12_id = ricaixa and corempagemov.k12_autent = riautent ) as arquivo, ";
$sqlAutentica .= "	 		                     ridata         as data, ";
$sqlAutentica .= "  			                   rnvalordebito  as valor_debito, ";
$sqlAutentica .= "  			                   rivalorcredito as valor_credito, ";
$sqlAutentica .= "			                     rireceita      as receita, ";
$sqlAutentica .= "			                     richeque       as cheque, ";
$sqlAutentica .= "			                     rtcredor       as credor, ";
$sqlAutentica .= "			                     rtdetalhe      as detalhe, ";
$sqlAutentica .= "                           ''             as justificativa, ";
$sqlAutentica .= "                           'normal'       as classe, ";
$sqlAutentica .= "                           0              as itemconciliacao, ";
$sqlAutentica .= "			                     rberro         as erro ";
$sqlAutentica .= "                      from fc_extratocaixa(" . db_getsession('DB_instit') . ",$conta,'" . $data_ultima_proc . "'::date + $nao_soma_um_dia,'" . $data . "',false ) ";
$sqlAutentica .= "                           left join conciliacor          on ricaixa    = k84_id       ";
$sqlAutentica .= "                                                         and riautent   = k84_autent   ";
$sqlAutentica .= "                                                         and ridata     = k84_data     ";
$sqlAutentica .= "                           left join conciliaitem         on k83_sequencial = k84_conciliaitem ";
$sqlAutentica .= "                                                         and k83_concilia = (select k68_sequencial ";
$sqlAutentica .= "                                                                               from concilia  ";
$sqlAutentica .= "                                                                              where k68_contabancaria = {$conta} ";
$sqlAutentica .= "                                                                                and k68_data = '" . $data . "' ) ";
$sqlAutentica .= "                           left join conciliapendcorrente on k89_id     = ricaixa    ";
$sqlAutentica .= "                                                         and k89_autent = riautent   ";
$sqlAutentica .= "			                                                   and k89_data   = ridata     ";
$sqlAutentica .= "                                                         and k89_concilia = (select k68_sequencial ";
$sqlAutentica .= "                                                                               from concilia  ";
$sqlAutentica .= "                                                                              where k68_contabancaria = {$conta} ";
$sqlAutentica .= "                                                                                and k68_data = '" . $data . "' ) ";
$sqlAutentica .= "                     where ( k89_id is null and k89_autent is null and k89_data is null ) ";
$sqlAutentica .= "                       and ( k83_sequencial is null ) ";
$sqlAutentica .= "                       and not exists (select 1  ";
$sqlAutentica .= "                                         from conciliacor ";
$sqlAutentica .= "                                         inner join conciliaitem  on k83_sequencial    = k84_conciliaitem ";
$sqlAutentica .= "                                         inner join concilia      on k68_sequencial    = k83_concilia ";
$sqlAutentica .= "                                                                and k68_contabancaria = {$conta} ";
$sqlAutentica .= "                                                                and k68_data          = '" . $data . "' ";
$sqlAutentica .= "                                                              where k84_id     = ricaixa ";
$sqlAutentica .= "                                                                and k84_autent = riautent ";
$sqlAutentica .= "                                                                and k84_data   = ridata ";
$sqlAutentica .= "  ) ";
$sqlAutentica .= "                 ) as x ";
$sqlAutentica .= "                 left join extratolinha         on lpad(trim(x.cheque::varchar),20,'0') = lpad(trim(k86_documento),20,'0') ";
$sqlAutentica .= "                                               and k86_contabancaria = $conta ";
$sqlAutentica .= "                                               and (k86_data = x.data or k86_data <= '" . $data . "') ";
$sqlAutentica .= "                                               and x.cheque <> 0 ";
$sqlAutentica .= "                                               and k86_documento <> '0' ";
$sqlAutentica .= "        ) as x ";
$sqlAutentica .= " where not exists (select 1
                                       from corgrupocorrente
                                      where k105_autent = autent
                                        and k105_id     = caixa
					and k105_data   = data
                                        and ( ( ( k105_corgrupotipo in (2,3,5,6) and extract(year from k105_data) <= 2012 ) )
                                        or ( k105_corgrupotipo in (2,3) ) )
                                        )  ";
$sqlAutentica .= "  group by caixa, autent, arquivo, data, cheque, credor, classe, itemconciliacao, erro, justificativa ";
$sqlAutentica .= "  order by data, autent";

$rsCorrente = pg_query($sqlAutentica);
$intNumrows = pg_num_rows($rsCorrente);
for ($i = 0; $i < $intNumrows; $i ++) {
    db_fieldsmemory($rsCorrente, $i);

    $sSql = "select k89_id
			from conciliapendcorrente
			where k89_id = $caixa
			  and k89_data = '$data'
                          and k89_autent = $autent ";
    $rSql = pg_query($sSql);
    if ($rSql and pg_num_rows($rSql) == 0) {

        $scSql = "select k84_id
			from conciliacor
			where k84_id = $caixa
			  and k84_data = '$data'
                          and k84_autent = $autent ";

        $rcSql = pg_query($scSql);
        if ($rcSql and pg_num_rows($rcSql) == 0) {
            $clconciliapendcorrente->k89_concilia = $concilia;
            $clconciliapendcorrente->k89_id = $caixa;
            $clconciliapendcorrente->k89_data = $data;
            $clconciliapendcorrente->k89_autent = $autent;
            $clconciliapendcorrente->k89_justificativa = '';
            $clconciliapendcorrente->k89_conciliaorigem = 1;
            $clconciliapendcorrente->incluir(null);
            if ($clconciliapendcorrente->erro_status == 0) {
                $erromsg = $clconciliapendcorrente->erro_msg;
                $sqlerro = true;
                break;
            }
        }
    }
}

db_fim_transacao(false);

