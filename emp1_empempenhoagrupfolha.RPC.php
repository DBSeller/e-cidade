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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$oRetorno = new stdClass();
$oRetorno->erro = false;
$aDadosRetorno = [];
try {
    db_inicio_transacao();
    switch ($parametros->acao) {
        /**
         * case para obter empenhos
         */
        case "getEmpenhoFiltroManutencao":
            $aWhere = [];
            $instit = db_getsession("DB_instit");
            $sSqlAgrupamentoPagamento = "select e60_numemp, e60_codemp, e60_anousu, e50_codord, c70_valor, e50_data, e172_dados->>'codigo_agrupamento' as e172_dados, e172_pagordem ";
            $sSqlAgrupamentoPagamento .= "from empenho.empempenho as E ";
            $sSqlAgrupamentoPagamento .= "inner join empelemento as EELE on  EElE.e64_numemp = E.e60_numemp ";
            $sSqlAgrupamentoPagamento .= "inner join orcelemento as OELE on  OELE.o56_codele = EELE.e64_codele
            and OELE.o56_anousu = E.e60_anousu ";
            $sSqlAgrupamentoPagamento .= "inner join empenho.pagordem as P on P.e50_numemp = E.e60_numemp ";
            $sSqlAgrupamentoPagamento .= "inner join contabilidade.conlancamord as CLO on CLO.c80_codord = P.e50_codord ";
            $sSqlAgrupamentoPagamento .= "inner join contabilidade.conlancam as CL on CL.c70_codlan = CLO.c80_codlan ";
            $sSqlAgrupamentoPagamento .= "inner join contabilidade.conlancamdoc as CLD on CLD.c71_codlan = CL.c70_codlan ";
            $sSqlAgrupamentoPagamento .= "inner join contabilidade.conhistdoc as CHD on CHD.c53_coddoc = CLD.c71_coddoc ";
            $sSqlAgrupamentoPagamento .= "inner join empenho.pagordemoutrosdados as POD on POD.e172_pagordem = P.e50_codord ";
            $aWhere[] = "substr(o56_elemento,3,1)::Integer = 1";
            $aWhere[] = "(substr(o56_elemento,6,2) in ('01','03','04','05','11','16','34') or
            (substr(o56_elemento,6,2) = '08' and substr(o56_elemento,8,1) = '0'))";
            $aWhere[] = "e60_instit = $instit";
            $aWhere[] = "c53_tipo = 20";
            if (!empty($parametros->numeroCgm)) {
                $aWhere[] = "e60_numcgm= {$parametros->numeroCgm}";
            }
            if (!empty($parametros->dataInicial) and !empty($parametros->dataFinal)) {
                $aWhere[] = "e50_data between '{$parametros->dataInicial}' and '{$parametros->dataFinal}'";
            }
            if ((!empty($parametros->empenhoInicial) or ($parametros->empenhoInicial) === "0") and (!empty($parametros->empenhoFinal) or ($parametros->empenhoInicial === "0"))) {
                $aWhere[] = "e60_codemp::Integer >= {$parametros->empenhoInicial} and e60_codemp::Integer <= {$parametros->empenhoFinal}";
            }
            if (!empty($aWhere)) {
               $sSqlAgrupamentoPagamento .= "where " . implode(" and ", $aWhere);
            }
            $sSqlAgrupamentoPagamento .= " order by e60_codemp;";
            $empenhos = array();
            $rsBuscaAgrupamentoPagamento = db_query($sSqlAgrupamentoPagamento);
            $numRowsAgrupamentoPagamento = pg_numrows($rsBuscaAgrupamentoPagamento);
            if ($numRowsAgrupamentoPagamento > 0) {
                for ($iRow = 0; $iRow < $numRowsAgrupamentoPagamento; $iRow++) {
                    $empenhos[] = db_utils::fieldsMemory($rsBuscaAgrupamentoPagamento, $iRow);
                }
            }
            $oRetorno->empenhos = $empenhos;
            break;

        case "updateEmpenhoFiltroManutencao":
            $oEmpenhoOutrosDados = new cl_pagordemoutrosdados();
            $oEmpenhoOutrosDados->e172_dados = $parametros->dados;
            $oEmpenhoOutrosDados->alterar(null, $parametros->codOrd);
            break;
    }
} catch (Exception $eErro) {
    $oRetorno->erro = true;
    $oRetorno->menssagem = $eErro->getMessage();
}
db_fim_transacao($oRetorno->erro);
echo JSON::create()->stringify($oRetorno);
