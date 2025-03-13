<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
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

require_once(modification('libs/db_conn.php'));
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/JSON.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('dbforms/db_funcoes.php'));

$oJson = json::create();

$oParam = $oJson->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->message = '';
$oRetorno->erro = false;

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case "salvar":
            if (empty($oParam->oDados->j150_percentual)) {
                Throw new \Exception("Percentual de multa não informado.");
            }

            if (empty($oParam->oDados->j150_receita)) {
                Throw new \Exception("Receita da multa não informada.");
            }

            if (empty($oParam->oDados->j150_data)) {
                Throw new \Exception("Data de lançamento não informada.");
            }

            $oDaoProcessoforoMulta = new cl_processoforomulta();
            $data = new DBDate($oParam->oDados->j150_data);
            $oDaoProcessoforoMulta->j150_processoforo = $oParam->oDados->v70_sequencial;
            $oDaoProcessoforoMulta->j150_data = $data->getDate();
            $oDaoProcessoforoMulta->j150_percentual = $oParam->oDados->j150_percentual;
            $oDaoProcessoforoMulta->j150_receita = $oParam->oDados->j150_receita;
            $oDaoProcessoforoMulta->j150_valortotal = 0;
            $oDaoProcessoforoMulta->incluir();
            $codigoDadosMulta = $oDaoProcessoforoMulta->j150_sequencial;

            if ($oDaoProcessoforoMulta->erro_status == '0') {
                Throw new \Exception("Ocorreu algo inesperado ao incluir multa para o processo: {$oDaoProcessoforoMulta->erro_msg}");
            }

            $sqlMultasdoProcesso = $oDaoProcessoforoMulta->sql_query(null, "*", "j150_data", "     j150_processoforo={$oParam->oDados->v70_sequencial} 
                                                                                               and j150_receita={$oParam->oDados->j150_receita}");
            $rsMultasDoProcesso = db_query($sqlMultasdoProcesso);
            if (!$rsMultasDoProcesso) {
                Throw new \Exception("Ocorreu algo inesperado ao incluir multa para o processo.");
            }

            $itensParaIncluir = array();
            $totalProcessos = pg_num_rows($rsMultasDoProcesso);

            $deleteReceitaDeMulta = <<<DELETE
                   delete from arrecad
                    where exists(select 1 
                                   from inicialnumpre
                                        inner join processoforoinicial pi on pi.v71_inicial = v59_inicial 
                                  where v59_numpre = k00_numpre
                                    and v71_processoforo = {$oParam->oDados->v70_sequencial} ) 
                                    and k00_receit =  {$oParam->oDados->j150_receita}
DELETE;
            $rsDeleteMultas = db_query($deleteReceitaDeMulta);
            if (!$rsDeleteMultas) {
                throw new Exception("Ocorreu algo inesperado ao atualizar valor da multa do processo.");
            }

            for ($i = 0; $i < $totalProcessos; $i++) {
                $dadosMulta = db_utils::fieldsMemory($rsMultasDoProcesso, $i);
                $dataCalculo = new DBDate($dadosMulta->j150_data);
                $valorPercentual = $dadosMulta->j150_percentual / 100;

                $sSqlDebitos = <<<SQL
        select k00_numpre, 
               k00_numpar, 
               k00_numcgm,               
               k00_hist,
               k00_tipo,
               k00_numtot,                                       
               case when round(total * {$valorPercentual}, 2) <> 0.00 then round(total * {$valorPercentual}, 2) else 0.01 end as total
        from (select k00_numpre,
                     k00_numpar,
                     k00_receit,
                     k00_numcgm,
                     k00_tipo,
                     min(k00_hist) as  k00_hist,
                     max(k00_numtot) as k00_numtot,
                     sum((substr(fc_calcula,15,13)::float8+
                          substr(fc_calcula,28,13)::float8+
                          substr(fc_calcula,41,13)::float8-
                          substr(fc_calcula,54,13)::float8))::numeric as total
                from ( select arrecad.k00_numpre,
                              arrecad.k00_numpar,
                              k00_receit,
                              k00_numcgm,
                              k00_tipo,
                              k00_hist,
                              k00_numtot,
                              fc_calcula(arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit, '{$dataCalculo->getDate()}', '{$dataCalculo->getDate()}', {$dataCalculo->getAno()})
                         from arrecad
                        where exists(select 1 
                                       from inicialnumpre
                                            inner join processoforoinicial pi on pi.v71_inicial = v59_inicial 
                                      where v59_numpre = k00_numpre 
                                        and v71_processoforo = {$oParam->oDados->v70_sequencial} ) 
                         and k00_receit not in ( select j150_receita 
                                                   from processoforomulta 
                                                  where j150_processoforo = {$oParam->oDados->v70_sequencial} )                
                      )  as x
                group by k00_numpre,
                         k00_numpar,
                         k00_receit,
                         k00_numcgm,
                         k00_tipo
                order by k00_numpre,
                         k00_numpar 
             ) as t;
SQL;

                $rsDebitos = db_query($sSqlDebitos);
                if (!$rsDebitos) {
                    throw new \Exception("Ocorreu algo inesperado. Não foi possível encontrar os débitos do processo: {$oParam->oDados->v70_sequencial}");
                }

                $iTotalLinhas = pg_num_rows($rsDebitos);
                $daoArrecad = new cl_arrecad;

                for ($j = 0; $j < $iTotalLinhas; $j++) {
                    $oDebito = db_utils::fieldsMemory($rsDebitos, $j);
                    $hashNumpre = "{$oDebito->k00_numpre}#{$oDebito->k00_numpar}#{$oDebito->k00_numpar}";

                    if (empty($itensParaIncluir[$hashNumpre])) {
                        $registroMulta = new \stdClass();
                        $registroMulta->k00_numpre = $oDebito->k00_numpre;
                        $registroMulta->k00_numpar = $oDebito->k00_numpar;
                        $registroMulta->k00_numcgm = $oDebito->k00_numcgm;
                        $registroMulta->k00_dtoper = $dataCalculo->getDate();
                        $registroMulta->k00_receit = $oParam->oDados->j150_receita;
                        $registroMulta->k00_hist = $oDebito->k00_hist;
                        $registroMulta->k00_valor = 0;
                        $registroMulta->k00_dtvenc = $dataCalculo->getDate();
                        $registroMulta->k00_numtot = $oDebito->k00_numtot;
                        $registroMulta->k00_numdig = 1;
                        $registroMulta->k00_tipo = $oDebito->k00_tipo;
                        $registroMulta->k00_tipojm = 1;
                        $itensParaIncluir[$hashNumpre] = $registroMulta;
                    }

                    $itensParaIncluir[$hashNumpre]->k00_valor += $oDebito->total;
                    $itensParaIncluir[$hashNumpre]->k00_dtvenc = $dataCalculo->getDate();
                    $itensParaIncluir[$hashNumpre]->k00_dtoper = $dataCalculo->getDate();
                }
            }

            $valorTotalEmMulta = 0;
            foreach ($itensParaIncluir as $oDebito) {
                $daoArrecad->k00_numpre = $oDebito->k00_numpre;
                $daoArrecad->k00_numpar = $oDebito->k00_numpar;
                $daoArrecad->k00_numcgm = $oDebito->k00_numcgm;
                $daoArrecad->k00_dtoper = $oDebito->k00_dtoper;
                $daoArrecad->k00_receit = $oDebito->k00_receit;
                $daoArrecad->k00_hist = $oDebito->k00_hist;
                $daoArrecad->k00_valor = $oDebito->k00_valor;
                $daoArrecad->k00_dtvenc = $oDebito->k00_dtvenc;
                $daoArrecad->k00_numtot = $oDebito->k00_numtot;
                $daoArrecad->k00_numdig = $oDebito->k00_numdig;
                $daoArrecad->k00_tipo = $oDebito->k00_tipo;
                $daoArrecad->k00_tipojm = $oDebito->k00_tipojm;
                $daoArrecad->incluir();

                if ($daoArrecad->erro_status == 0) {
                    throw new \Exception("Ocorreu algo inesperado. Não foi possível salvar dados da multa.\n" . $daoArrecad->erro_msg);
                }

                $valorTotalEmMulta += $oDebito->k00_valor;
            }

            $oDaoProcessoforoMulta = new cl_processoforomulta();
            $oDaoProcessoforoMulta->j150_sequencial = $codigoDadosMulta;
            $oDaoProcessoforoMulta->j150_processoforo = $oParam->oDados->v70_sequencial;
            $oDaoProcessoforoMulta->j150_data = $data->getDate();
            $oDaoProcessoforoMulta->j150_percentual = $oParam->oDados->j150_percentual;
            $oDaoProcessoforoMulta->j150_receita = $oParam->oDados->j150_receita;
            $oDaoProcessoforoMulta->j150_valortotal = $valorTotalEmMulta;
            $oDaoProcessoforoMulta->alterar($oDaoProcessoforoMulta->j150_sequencial);

            if ($oDaoProcessoforoMulta->erro_status === '0') {
                throw new Exception("Ocorreu um erro ao alterar o valor total em multas. " . pg_last_error());
            }

            $oRetorno->status = 1;
            $oRetorno->message = "Valores adicionais acrescentados ao processo.";
            break;

        case 'buscarMultasDoProcesso' :
            $oDaoProcessoforoMulta = new cl_processoforomulta();
            $sqlMultas = $oDaoProcessoforoMulta->sql_query(null, "*", "j150_data",
              "j150_processoforo = {$oParam->dados->processo}");
            $rsProcesso = db_query($sqlMultas);

            $oRetorno->multas = db_utils::makeCollectionFromRecord($rsProcesso, function ($dados) {
                $oRetorno = new \stdClass();
                $oRetorno->id = $dados->j150_sequencial;
                $oRetorno->codigo = $dados->j150_receita;
                $oRetorno->receita = $dados->k02_descr;
                $oRetorno->data = db_formatar($dados->j150_data, 'd');
                $oRetorno->percentual = $dados->j150_percentual;

                return $oRetorno;
            });
            break;

        case 'excluirMultasDoProcesso':
            if (empty($oParam->dados->processo)) {
                Throw new \Exception("Processo não informado.");
            }

            $sSqlDebitosExcluir = <<<SQL
            select distinct 
                   k00_numpre,
                   k00_numpar,
                   arrecad.k00_receit 
              from processoforomulta 
                   inner join processoforoinicial pi on pi.v71_processoforo = j150_processoforo 
                   inner join inicialnumpre ip       on ip.v59_inicial = pi.v71_inicial 
                   inner join arrecad                on arrecad.k00_numpre = ip.v59_numpre 
                                                    and processoforomulta.j150_receita = k00_receit 
            where j150_processoforo = {$oParam->dados->processo}
SQL;

            $rsDebitosExcluir = db_query($sSqlDebitosExcluir);
            if (!$rsDebitosExcluir) {
                throw new \Exception("Não foi possível encontrar os débitos da multa do processo.");
            }

            $oDaoArrecad = new cl_arrecad();

            db_utils::makeCollectionFromRecord($rsDebitosExcluir, function ($dados) use ($oDaoArrecad) {
                $where = "    k00_numpre = {$dados->k00_numpre} ";
                $where .= "and k00_numpar = {$dados->k00_numpar} ";
                $where .= "and k00_receit = {$dados->k00_receit} ";
                $oDaoArrecad->excluir(null, $where);
                if ($oDaoArrecad->erro_status == '0') {
                    throw new Exception("Não foi possível excluir os débitos da multa.");
                }
            });

            $oDaoProcessoforoMulta = new cl_processoforomulta();
            $oDaoProcessoforoMulta->excluir(null, "j150_processoforo = {$oParam->dados->processo} ");

            if ($oDaoProcessoforoMulta->erro_status == "0") {
                throw new Exception("Não foi possível excluir a multa para o processo");
            }

            $oRetorno->status = 1;
            $oRetorno->message = "Multa para o processo excluída com sucesso.";
            break;

        case 'consultaParcelamento':
            $possuiParcelamento = false;

            if (empty($oParam->codigoProcessoForo)) {
                throw new \Exception("Campo Código do Processo do Foro é de preenchimento obrigatório.");
            }

            $sql = "
              SELECT DISTINCT v71_processoforo
              FROM processoforoinicial
                INNER JOIN termoini ON v71_inicial = inicial
                INNER JOIN termo ON parcel = v07_parcel
              WHERE v71_processoforo = {$oParam->codigoProcessoForo}
                    AND v07_situacao = 1;
            ";

            $resultado = db_query($sql);

            if (!$resultado) {
                throw new DBException("Ocorreu um erro ao consultar a existência de parcelamentos para o processo informado.");
            }

            if (pg_num_rows($resultado) > 0) {
                $possuiParcelamento = true;
            }

            $oRetorno->possuiParcelamento = $possuiParcelamento;
            break;

    }

    db_fim_transacao(false);
} catch (\Exception $e) {
    db_fim_transacao(true);

    $oRetorno->status = 2;
    $oRetorno->message = $e->getMessage();
    $oRetorno->erro = true;
}

echo $oJson->stringify($oRetorno);
