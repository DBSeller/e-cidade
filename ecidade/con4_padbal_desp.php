<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

use ECidade\Financeiro\Orcamento\Recurso\Recurso as RecursoFinanceiro;
use ECidade\Financeiro\Orcamento\Registry\ComplementoRegistry;

class bal_desp
{

    var $arq = null;

    protected $tipoRaterio = 0;

    function __construct($header)
    {
        umask(74);
        $this->arq = fopen("tmp/BAL_DESP.TXT", 'w+');
        fputs($this->arq, $header);
        fputs($this->arq, "\r\n");
    }

    /**
     * @return int
     */
    public function getTipoRaterio()
    {
        return $this->tipoRaterio;
    }

    /**
     * @param int $tipoRaterio
     */
    public function setTipoRaterio($tipoRaterio)
    {
        $this->tipoRaterio = $tipoRaterio;
    }

    function processa($instit = 1, $data_ini = "", $data_fim = "", $orgaotrib = null, $subelemento = "")
    {
        global $o58_codigo, $o58_orgao, $o58_unidade, $o58_funcao, $o58_subfuncao, $o58_programa, $o58_projativ, $o58_elemento, $o58_codele;
        global $dot_ini, $suplementado_acumulado, $reduzido_acumulado, $empenhado, $anulado, $liquidado, $pago, $o58_especificacao;
        global $contador, $o58_coddot, $conn, $valor_suplementado_recurso, $valor_reduzido_recurso, $o58_complemento;
        $contador = 0;

        $tipo_mesini = 1;
        $tipo_mesfim = 1;
        $tipo_agrupa = 3;
        $tipo_nivel = 6;

        $qorgao = 0;
        $qunidade = 0;

        $xtipo = 0;
        $origem = "B";
        $opcao = 3;

        $anoSessao = db_getsession("DB_anousu");
        $sql_teste = "
         select orcdotacao.o58_coddot,
                (select count(*)
                   from conlancamdot
                  where conlancamdot.c73_data between '$data_ini' and '$data_fim'
                    and conlancamdot.c73_coddot = orcdotacao.o58_coddot) as quant,
                orcdotacao.o58_orgao,
                orcdotacao.o58_unidade,
                orcdotacao.o58_funcao,
                orcdotacao.o58_subfuncao,
                orcdotacao.o58_programa,
                orcdotacao.o58_projativ,
                orcdotacao.o58_codele,
                orcdotacao.o58_codigo,
                orcdotacao.o58_valor
         from ( select o58_orgao,
                       o58_unidade,
                       o58_funcao,
                       o58_subfuncao,
                       o58_programa,
                       o58_projativ,
                       o58_codele,
                       o58_codigo,
                       count(*)
                  from orcdotacao
                  where o58_anousu = " . $anoSessao . "
                  group by o58_orgao,
                           o58_unidade,
                           o58_funcao,
                           o58_subfuncao,
                           o58_programa,
                           o58_projativ,
                           o58_codele,
                           o58_codigo
                    having count(*) > 1
                ) as x
           inner join orcdotacao on orcdotacao.o58_orgao = x.o58_orgao
                          and orcdotacao.o58_unidade = x.o58_unidade
                          and orcdotacao.o58_funcao = x.o58_funcao
                          and orcdotacao.o58_subfuncao = x.o58_subfuncao
                          and orcdotacao.o58_programa = x.o58_programa
                          and orcdotacao.o58_projativ = x.o58_projativ
                          and orcdotacao.o58_codele = x.o58_codele
                          and orcdotacao.o58_codigo = x.o58_codigo
           where orcdotacao.o58_anousu = " . $anoSessao . "
           order by orcdotacao.o58_orgao, orcdotacao.o58_unidade, orcdotacao.o58_funcao, orcdotacao.o58_subfuncao,
           orcdotacao.o58_programa, orcdotacao.o58_projativ, orcdotacao.o58_codele, orcdotacao.o58_codigo
        ";
        $result_teste = db_query($sql_teste) or die($sql_teste);
        //db_criatabela($result_teste);exit;
        if (pg_numrows($result_teste) > 0) {
            $dotacoes = "";

            db_fieldsmemory($result_teste, 0);
            $ult_estrut = formatar($o58_orgao, 2, 'n') . formatar($o58_unidade, 2, 'n') . formatar($o58_funcao, 2, 'n') . formatar($o58_subfuncao, 3, 'n') . formatar($o58_programa, 4, 'n') . formatar($o58_projativ, 5, 'n') . formatar($o58_codele, 10, 'n') . formatar($o58_codigo, 4, 'n');

            for ($x = 0; $x < pg_numrows($result_teste); $x++) {
                db_fieldsmemory($result_teste, $x);

                $atu_estrut = formatar($o58_orgao, 2, 'n') . formatar($o58_unidade, 2, 'n') . formatar($o58_funcao, 2, 'n') . formatar($o58_subfuncao, 3, 'n') . formatar($o58_programa, 4, 'n') . formatar($o58_projativ, 5, 'n') . formatar($o58_codele, 10, 'n') . formatar($o58_codigo, 4, 'n');

                if ($ult_estrut === $atu_estrut) {
                    //	  echo "igual - ultimo: $ult_estrut - atu: $atu_estrut <br><br><br><br><br>";
                } else {
                    //	  echo "dif - ultimo: $ult_estrut - atu: $atu_estrut <br><br><br><br><br>";
                    //          $dotacoes .= "<br> === <br>";
                    $dotacoes .= "<br>";
                }

                $dotacoes .= $o58_coddot . " - ";

                $ult_estrut = formatar($o58_orgao, 2, 'n') . formatar($o58_unidade, 2, 'n') . formatar($o58_funcao, 2, 'n') . formatar($o58_subfuncao, 3, 'n') . formatar($o58_programa, 4, 'n') . formatar($o58_projativ, 5, 'n') . formatar($o58_codele, 10, 'n') . formatar($o58_codigo, 4, 'n');

            }
            echo "<font color='red'><br><b>DOTACOES DUPLICADAS:</b><br>$dotacoes<br></font>";

        }

        $sele_work = ' w.o58_instit in (' . str_replace('-', ', ', $instit) . ') ';

        $anousu = db_getsession("DB_anousu");
        db_query("begin");
        db_query("create temp table t as select * from orcdotacao where o58_anousu = {$anousu}");

        $sele_work = " w.o58_instit in ($instit)";

        if ($subelemento == "sim") {
            $sql = db_dotacaosaldo(8, 1, 4, true, $sele_work, $anousu, $data_ini, $data_fim, '8', '0', true, '1', true, "sim");
        } else {
            $sql = db_dotacaosaldo(8, 1, 4, true, $sele_work, $anousu, $data_ini, $data_fim, '8', '0', true);
        }

        /**
         * 1 Criar uma tabela com os dados do empenho
         */
        $sqlCriarTabela = "
            drop table if exists w_baldesp;
            create table w_baldesp (
              o58_coddot integer,
              o58_orgao integer,
              o58_unidade integer,
              o58_funcao integer,
              o58_subfuncao integer,
              o58_programa integer,
              o58_projativ integer,
              o58_elemento varchar,
              o58_codigo integer,
              recurso varchar,
              o58_complemento integer,
              dot_ini numeric(15,2)default 0,
              reduzido_acumulado numeric(15,2)default 0,
              empenhado numeric(15,2)default 0,
              anulado numeric(15,2)default 0,
              liquidado numeric(15,2)default 0,
              pago numeric(15,2)default 0,
              valor_suplementado_recurso numeric(15,2) default 0,
              valor_reduzido_recurso numeric(15,2) default 0,
              transferencia numeric(15,2)default 0,
              transposicao numeric(15,2)default 0,
              remanejamento numeric(15,2)default 0
        )";

        $sqlDadosComplemento = <<<SQL
select o58_orgao,
       o58_unidade,
       o58_funcao,
       o58_subfuncao,
       o58_programa,
       o58_projativ,
       o56_elemento as o58_elemento,
       o58_coddot,
       o15_codigo as o58_codigo,
       o15_recurso as recurso,
       o200_sequencial as complemento,
       sum(0)                                                                                 as dot_ini,
       sum(case when c53_tipo = 10 then c70_valor end)                                        as empenhado,
       sum(case when c53_tipo = 11 then c70_valor end)                                        as anulado,
       sum(case when c53_tipo = 20 then c70_valor when c53_tipo = 21 then c70_valor * -1 end) as liquidado,
       sum(case when c53_tipo = 30 then c70_valor when c53_tipo = 31 then c70_valor * -1 end) as pago,
       sum(case when c53_tipo = 10 then c70_valor end)                                        as empenhado_acumulado,
       sum(case when c53_tipo = 11 then c70_valor end)                                        as anulado_acumulado,
       sum(case when c53_tipo = 20 then c70_valor when c53_tipo = 21 then c70_valor * -1 end) as liquidado_acumulado,
       sum(case when c53_tipo = 30 then c70_valor when c53_tipo = 31 then c70_valor * -1 end) as pago_acumulado,
       sum(0)                                                                                 as suplementado_acumulado,
       sum(0)                                                                                 as reduzido_acumulado
      from origemcomplementorecurso
         inner join conlancamemp on c75_numemp = o206_numero
                                and o206_origem = 1
         inner join empenho.empempenho on c75_numemp = e60_numemp
         inner join orcamento.orcdotacao on o58_anousu = empempenho.e60_anousu
                                        and o58_coddot = e60_coddot
         inner join orcorgao o on o40_anousu = o58_anousu and o.o40_orgao = o58_orgao
         inner join orcunidade u on o41_anousu = o58_anousu
                                and u.o41_orgao = o58_orgao and u.o41_unidade = o58_unidade
         inner join orcfuncao f on f.o52_funcao = o58_funcao
         inner join orcsubfuncao s on o53_subfuncao = o58_subfuncao
         inner join orcprograma p on o54_anousu = o58_anousu and o54_programa = o58_programa
         inner join orcprojativ pa on o55_anousu = o58_anousu and o55_projativ = o58_projativ
         inner join orcelemento oe on oe.o56_codele = o58_codele
                                  and oe.o56_anousu = o58_anousu
         inner join orctiporec otr on o15_codigo = o206_recurso
         left join orcamento.complementofonterecurso on o200_sequencial = o15_complemento
         inner join conlancamdoc on c71_codlan = c75_codlan
         inner join conlancam on c70_codlan = c75_codlan
         inner join conhistdoc on c53_coddoc = c71_coddoc
where o58_anousu = {$anousu}
      and o200_tribunal is true
      and c70_data between '{$data_ini}' and '{$data_fim}'
      and o58_coddot = #dotacao#
      and o206_complementorecurso <> #complemento#
group by o58_orgao,
         o58_unidade,
         o58_funcao,
         o58_subfuncao,
         o58_programa,
         o58_projativ,
         o58_elemento,
         o58_coddot,
         o15_codigo,
         o15_recurso,
         o200_sequencial
SQL;


//echo "<br><br>$sqlDadosComplemento<br><br>"; die();


        db_query($sqlCriarTabela);
        $consultaBalver = db_query($sql);
        $totalRegistros = pg_num_rows($consultaBalver);

        $sqlSuplementacao = "
                              select o58_orgao,
                                     o58_unidade,
                                     o58_funcao,
                                     o58_subfuncao,
                                     o58_programa,
                                     o58_projativ,
                                     o58_codele as o58_elemento,
                                     o58_coddot,
                                     o58_codigo,
                                     sum(case when c79_codlan is not null and o46_tiposup = 1014 and c71_coddoc = o48_coddocsup
                                                   then c70_valor
                                              else 0
                                         end) as transferencia,
                                     sum(case when c79_codlan is not null and o46_tiposup = 1016 and c71_coddoc = o48_coddocsup
                                                   then c70_valor
                                              else 0
                                         end) as transposicao,
                                     sum(case when c79_codlan is not null and o46_tiposup = 1015 and c71_coddoc = o48_coddocsup
                                                   then c70_valor
                                              else 0
                                         end) as remanejamento
                              from orcdotacao
                                   inner join conlancamdot  on conlancamdot.c73_anousu = orcdotacao.o58_anousu
                                                           and conlancamdot.c73_coddot = orcdotacao.o58_coddot
                                   inner join conlancam     on conlancam.c70_codlan = conlancamdot.c73_codlan
                                   inner join conlancamsup  on conlancamsup.c79_codlan = conlancam.c70_codlan
                                   inner join conlancamdoc  on conlancamdoc.c71_codlan = conlancam.c70_codlan
                                   inner join orcsuplem     on orcsuplem.o46_codsup = conlancamsup.c79_codsup
                                   inner join orcsuplemtipo on orcsuplemtipo.o48_tiposup = orcsuplem.o46_tiposup
                              where o58_coddot = #dotacao#
                                and o58_anousu = {$anousu}
                                and c70_data between '{$data_ini}' and '{$data_fim}'
                              group by o58_orgao, o58_unidade, o58_funcao, o58_subfuncao, o58_programa,
                                       o58_projativ, o58_elemento, o58_coddot, o58_codigo ;
        ";

        for ($i = 0; $i < $totalRegistros; $i++) {
            $linha = db_utils::fieldsmemory($consultaBalver, $i);

            if ($linha->o58_coddot == 0) {
                continue;
            }

            $recurso = \ECidade\Financeiro\Orcamento\Repository\RecursoRepository::getByCodigo($linha->o58_codigo);
            $fonteRecurso = $recurso->getRecurso();

            $complemento = ComplementoRegistry::get($recurso->getComplemento());
            $idComplemento = $complemento->isTribunal() ? $complemento->getCodigo() : 0;

            if (empty($idComplemento)) {
                $idComplemento = 0;
            }
            $consultaDadosEmpenhos = str_replace(
                ["#dotacao#", "#complemento#"],
                [$linha->o58_coddot, $idComplemento],
                $sqlDadosComplemento
            );

            $rsConsultaEmpenhos = db_query($consultaDadosEmpenhos);
            $totalLinhasEmpenho = pg_num_rows($rsConsultaEmpenhos);
            $valorTotalEmpenhado = 0;
            if ($totalLinhasEmpenho > 0) {
                for ($j = 0; $j < $totalLinhasEmpenho; $j++) {
                    $dadosEmpenho = db_utils::fieldsMemory($rsConsultaEmpenhos, $j);

                    $valorTotalEmpenhado += (float)$dadosEmpenho->empenhado - (float)$dadosEmpenho->anulado;
                    $valorSuplementado = 0;
                    $dotacaoInicial = 0;

                    switch ($this->tipoRaterio) {
                        case 2:
                            $dotacaoInicial = (float)$dadosEmpenho->empenhado - (float)$dadosEmpenho->anulado;
                            break;
                        case 3:
                            $valorSuplementado = (float)$dadosEmpenho->empenhado - (float)$dadosEmpenho->anulado;
                            break;
                    }

                    $insert = [
                        "o58_coddot" => '',
                        "o58_orgao" => $dadosEmpenho->o58_orgao,
                        "o58_unidade" => $dadosEmpenho->o58_unidade,
                        "o58_funcao" => $dadosEmpenho->o58_funcao,
                        "o58_subfuncao" => $dadosEmpenho->o58_subfuncao,
                        "o58_programa" => $dadosEmpenho->o58_programa,
                        "o58_projativ" => $dadosEmpenho->o58_projativ,
                        "o58_elemento" => $dadosEmpenho->o58_elemento,
                        "o58_codigo" => $dadosEmpenho->o58_codigo,
                        "recurso" => $dadosEmpenho->recurso,
                        "o58_complemento" => $dadosEmpenho->complemento,
                        "dot_ini" => $dotacaoInicial,
                        "reduzido_acumulado" => $dadosEmpenho->reduzido_acumulado,
                        "empenhado" => $dadosEmpenho->empenhado,
                        "anulado" => $dadosEmpenho->anulado,
                        "liquidado" => $dadosEmpenho->liquidado,
                        "pago" => $dadosEmpenho->pago,
                        "valor_suplementado_recurso" => $valorSuplementado,
                        "valor_reduzido_recurso" => 0,
                    ];
                    pg_insert($conn, 'w_baldesp', $insert);
                    $linha->empenhado -= $dadosEmpenho->empenhado;
                    $linha->anulado -= $dadosEmpenho->anulado;
                    $linha->liquidado -= $dadosEmpenho->liquidado;
                    $linha->pago -= $dadosEmpenho->pago;
                }
            }

            $sqlSuplementacaoDotacao = str_replace(["#dotacao#"], [$linha->o58_coddot], $sqlSuplementacao);
            $rsSuplementacaoDotacao = db_query($sqlSuplementacaoDotacao);

            $linha->transferencia = 0;
            $linha->transposicao = 0;
            $linha->remanejamento = 0;
            if (pg_num_rows($rsSuplementacaoDotacao) > 0) {
                $suplementacao = db_utils::fieldsMemory($rsSuplementacaoDotacao, 0);
                $linha->transferencia = $suplementacao->transferencia;
                $linha->transposicao = $suplementacao->transposicao;
                $linha->remanejamento = $suplementacao->remanejamento;
            }

            $dotacaoInicial = $linha->dot_ini;
            $valorSuplementadoRecurso = 0;
            $valorReduzidoRecurso = 0;
            switch ($this->tipoRaterio) {
                case 2:
                    $dotacaoInicial = $linha->dot_ini - $valorTotalEmpenhado;
                    break;
                case 3:
                    $valorReduzidoRecurso = $valorTotalEmpenhado;
                    break;
            }
            $insert = [
                "o58_coddot" => $linha->o58_coddot,
                "o58_orgao" => $linha->o58_orgao,
                "o58_unidade" => $linha->o58_unidade,
                "o58_funcao" => $linha->o58_funcao,
                "o58_subfuncao" => $linha->o58_subfuncao,
                "o58_programa" => $linha->o58_programa,
                "o58_projativ" => $linha->o58_projativ,
                "o58_elemento" => $linha->o58_elemento,
                "o58_codigo" => $linha->o58_codigo,
                "recurso" => $fonteRecurso,
                "o58_complemento" => $idComplemento,
                "dot_ini" => $dotacaoInicial,
                "reduzido_acumulado" => $linha->reduzido_acumulado,
                "empenhado" => $linha->empenhado,
                "anulado" => $linha->anulado,
                "liquidado" => $linha->liquidado,
                "pago" => $linha->pago,
                "valor_suplementado_recurso" => $valorSuplementadoRecurso,
                "valor_reduzido_recurso" => $valorReduzidoRecurso,
                "transferencia" => $linha->transferencia,
                "transposicao" => $linha->transposicao,
                "remanejamento" => $linha->remanejamento,
            ];

            pg_insert($conn, 'w_baldesp', $insert);
        }

        $sqlBalDesp = "
            select *
              from w_baldesp
             order by o58_orgao, o58_unidade, o58_funcao, o58_subfuncao, o58_programa, o58_projativ, o58_elemento,
                      recurso, o58_complemento
        ";

        $result = db_query($sqlBalDesp);

       // db_criatabela($result); die();

        db_query("rollback");
        $dotacoes = "";
        for ($i = 0; $i < pg_numrows($result); $i++) {
            db_fieldsmemory($result, $i);

            if ($o58_coddot > 0 and $o58_codigo <= 0) {
                $dotacoes .= $o58_coddot . " - <br>";
            }

        }
        if ($dotacoes != "") {
            echo "<font color='red'><br><b>DOTACOES COM RECURSO ZERADO:</b><br>$dotacoes<br></font>";
        }

        $totalzao = 0;
        $totalsup = 0;
        $totalcre = 0;
        $totalesp = 0;
        for ($i = 0; $i < pg_numrows($result); $i++) {
            db_fieldsmemory($result, $i);

            $dados = db_utils::fieldsMemory($result, $i);

            if ($o58_codigo > 0) {
                $o58_codigo = $o58_especificacao;
                $line = formatar($o58_orgao, 2, 'n');
                $line .= formatar($o58_unidade, 2, 'n');
                $line .= formatar($o58_funcao, 2, 'n');
                $line .= formatar($o58_subfuncao, 3, 'n');
                $line .= formatar($o58_programa, 4, 'n');
                $line .= formatar(0, 3, 'n'); // subprograma
                $line .= formatar($o58_projativ, 5, 'n');
                $line .= substr($o58_elemento, 1, 6);
                $line .= $dados->recurso;
                $line .= formatar($dot_ini, 13, 'v'); // dotacao inicial
                $line .= formatar(0, 13, 'v'); // atualizacao monetaria
                $sup = 0;
                $cre = 0;
                $esp = 0;

                // leandro contador pediu para passar teste do coddoc 71 de cre (credito especial) para sup (credito suplementar)
                // pois estava dando problema em sapiranga
                // 2007-03-27_15:30

                if (!empty($o58_coddot)) {
                    $sql = "
                      select sum(case when c71_coddoc in (7,52,53,54,55,65,71) then c70_valor else 0 end ) as sup,
                             sum(case when c71_coddoc in (56,58,59,60,61,62,64) then c70_valor else 0 end ) -
                             sum(case when c71_coddoc in (10) then c70_valor else 0 end ) as cre,
                             sum(case when c71_coddoc in (63,74,75,76,77) then c70_valor else 0 end ) -
                             sum(case when c71_coddoc in (14) then c70_valor else 0 end ) as esp
                        from conlancamdoc
                           inner join conlancam on c70_codlan = c71_codlan
                           inner join conlancamdot on c73_codlan = c71_codlan
                           inner join conlancamsup on c79_codlan = c71_codlan
                           inner join orcsuplem on orcsuplem.o46_codsup = conlancamsup.c79_codsup
                           where c71_coddoc in (7,10,14,52,53,54,55,56,58,59,60,61,62,63,64,65,71,74,75,76,77)
                             and o46_tiposup not between 1014 and 1016
                             and c71_data between '$data_ini' and '$data_fim'
                             and c73_coddot = $o58_coddot
                             and c73_anousu = {$anousu}";


                    $sql_desdobramento = "
                       select sum(case when c71_coddoc in (7,52,53,54,55,65) then c70_valor else 0 end ) as sup ,
                              sum(case when c71_coddoc in (56,58,59,60,61,64) then c70_valor else 0 end ) as cre,
                              sum(case when c71_coddoc in (62,63) then c70_valor else 0 end ) as esp
                         from conlancamdoc
                        inner join conlancam on c70_codlan = c71_codlan
                        inner join conlancamdot on c73_codlan = c71_codlan
                        inner join conlancamsup on c79_codlan = c71_codlan
                        inner join orcsuplem on orcsuplem.o46_codsup = conlancamsup.c79_codsup
                        inner join orcdotacao  on c73_coddot = o58_coddot
                              and o58_anousu = {$anousu}
                              and o58_orgao = $o58_orgao
                              and o58_unidade = $o58_unidade
                              and o58_funcao = $o58_funcao
                              and o58_subfuncao = $o58_subfuncao
                              and o58_programa = $o58_programa
                              and o58_projativ = $o58_projativ
                              and o58_codigo = $o58_codigo
                        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu
                        where c71_coddoc in (7,52,53,54,55,56,58,59,60,61,62,63,64,65)
                          and o46_tiposup not between 1014 and 1016
                          and c71_data between '$data_ini' and '$data_fim'
                          and substr(o56_elemento,1,7)='" . substr($o58_elemento, 0, 7) . "'
                          and c73_anousu = {$anousu}";

                    if ($subelemento == "sim") {
                        $resultsup = db_query($sql_desdobramento);
                    } else {
                        $resultsup = db_query($sql);
                    }

                    if (pg_numrows($resultsup) > 0) {
                        $sup = pg_result($resultsup, 0, 0) + 0;
                        $cre = pg_result($resultsup, 0, 1) + 0;
                        $esp = pg_result($resultsup, 0, 2) + 0;
                    }
                }

                $totalzao += $sup + $cre + $esp;
                $totalsup += $sup;
                $totalcre += $cre;
                $totalesp += $esp;

                $line .= formatar(round($sup, 2), 13, 'v'); // creditos suple
                $line .= formatar(round($cre, 2), 13, 'v'); // creditos especial
                $line .= formatar(round($esp, 2), 13, 'v'); // creditos extraordinarios

                $line .= formatar(abs(round($reduzido_acumulado, 2)), 13, 'v'); // reducoes
                $line .= formatar($valor_suplementado_recurso, 13, 'v'); // suple recurso vinculado
                $line .= formatar($valor_reduzido_recurso, 13, 'v'); // reducao recurso vinculado
                $line .= formatar(abs(round($empenhado - $anulado, 2)), 13, 'v');
                $line .= formatar(abs(round($liquidado, 2)), 13, 'v'); // liquidado
                $line .= formatar(abs(round($pago, 2)), 13, 'v'); // pago
                $line .= formatar(0, 13, 'v'); // limitado
                $line .= formatar(0, 13, 'v'); // recomposicao
                $line .= formatar(0, 13, 'v'); // previsao

                if ($anousu >= 2020) {
                    $complementoFonteRecurso = $o58_complemento;
                    $line .= str_pad($complementoFonteRecurso, 4, '0', STR_PAD_LEFT);
                }

                if ($anousu >= 2021) {

                    $transferencia = dbround_php_52($dados->transferencia, 2);
                    $transposicao = dbround_php_52($dados->transposicao, 2);
                    $remanejamento = dbround_php_52($dados->remanejamento, 2);

                    if ($transferencia < 0){
                        $line .= '-'.formatar(($transferencia*-1), 12, 'v');
                    }else{
                       $line .= formatar($transferencia, 13, 'v');
                    }

                    if ($transposicao < 0){
                        $line .= '-'.formatar(($transposicao*-1), 12, 'v');
                    }else{
                       $line .= formatar($transposicao, 13, 'v');
                    }

                    if ($remanejamento < 0){
                        $line .= '-'.formatar(($remanejamento*-1), 12, 'v');
                    }else{
                       $line .= formatar($remanejamento, 13, 'v');
                    }

                }

                $contador++;

                if (db_getsession("DB_anousu") >= 2022) {
                    $line .= "00000000";
                }
                fputs($this->arq, $line);
                fputs($this->arq, "\r\n");
            }
        }
        //  trailer
        $contador = espaco(10 - (strlen($contador)), '0') . $contador;
        $line = "FINALIZADOR" . $contador;
        fputs($this->arq, $line);
        fputs($this->arq, "\r\n");

        fclose($this->arq);

        $teste = "true";
        return $teste;
    }
}
