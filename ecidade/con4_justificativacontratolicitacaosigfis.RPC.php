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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson                  = new services_json();
$oParam                 = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

try {

    db_inicio_transacao();

    switch ($oParam->exec) {


        case "getEmpenhos":


            $e60_vlrempInicial = $oParam->e60_vlrempInicial;
            $e60_vlrempFinal   = $oParam->e60_vlrempFinal;
            $aWhere = array();
            $sWhereSub = "";

            if ( $oParam->dtInicial != "" && $oParam->dtFinal != "" ) {

                $dDataInicial      = DBDate::converter( $oParam->dtInicial);
                $dDataFinal        = DBDate::converter( $oParam->dtFinal);

                $aWhere [] = " e60_emiss between '{$dDataInicial}' and '{$dDataFinal}' ";
                $sWhereSub .= " and e60_emiss between '{$dDataInicial}' and '{$dDataFinal}' ";
            }

            if ( $oParam->e60_vlrempInicial != "" && $oParam->e60_vlrempFinal != "" ) {

                $aWhere [] = " e60_vlremp between {$e60_vlrempInicial} and {$e60_vlrempFinal} ";
                $sWhereSub .= " and e60_vlremp between {$e60_vlrempInicial} and {$e60_vlrempFinal} ";
            }

            $aWhere[] = "
                            
                             (
                               e60_numemp in (
                            
                                         select distinct e60_numemp
                                           from empempenho
                                                inner join empempaut on e60_numemp = e61_numemp
                                                inner join empautoriza on e61_autori = e54_autori
                                                inner join empautitem on e55_autori = e54_autori
                                                left join empautitempcprocitem on e73_autori = e55_autori and e73_sequen = e55_sequen
                                                left join pcprocitem on e73_pcprocitem = pc81_codprocitem
                                                left join liclicitem on l21_codpcprocitem = pc81_codprocitem
                                                left join liclicita on l21_codliclicita = l20_codigo
                                         where l20_codigo is null
                                          {$sWhereSub}
                            
                                              )
                                 and e60_numemp in (
                                         
                                               select e60_numemp
                                                 from empempenho
                                                      left join empempenhocontrato on e100_numemp = e60_numemp
                                                where e100_acordo is null
                                                {$sWhereSub}
                                )
                            
                             )
                            and e60_instit = ".db_getsession("DB_instit");


            $sCampos = "
                         e60_numemp,
                         e60_codemp,
                         e60_anousu,
                         z01_nome,
                         e60_vlremp
                      ";

            $sWhere         = implode( " and ",  $aWhere);

            $oDaoEmpempenho = db_utils::getDao('empempenho');
            $sSqlEmpempenho = $oDaoEmpempenho->sql_query_empnome ( null, $sCampos, "e60_numemp", $sWhere);

            //echo $sSqlEmpempenho; die();


            $rsEmpempenho   = $oDaoEmpempenho->sql_record($sSqlEmpempenho);

            if ( $oDaoEmpempenho->numrows > 500 ) {

                throw new Exception("Muitos Registros para o Intervalo de Data Selecionado. \n Diminua para manter um Bom Desempenho do Procedimento.");
            }

            $aEmpenhosRetorno = array();
            if ( $oDaoEmpempenho->numrows > 0 ) {

               for ( $iEmpenho = 0; $iEmpenho <  $oDaoEmpempenho->numrows; $iEmpenho++) {

                   $oConsulta = db_utils::fieldsMemory($rsEmpempenho, $iEmpenho );
                   $oDadosRetorno = new stdClass();
                   $oDadosRetorno->e60_numemp  = $oConsulta->e60_numemp;
                   $oDadosRetorno->e60_codemp  = $oConsulta->e60_codemp;
                   $oDadosRetorno->e60_anousu  = $oConsulta->e60_anousu;
                   $oDadosRetorno->z01_nome    = urlencode_all($oConsulta->z01_nome);
                   $oDadosRetorno->e60_vlremp  = db_formatar($oConsulta->e60_vlremp, "f");
                   $aEmpenhosRetorno[] = $oDadosRetorno;
               }

            }

            $oRetorno->aDados = $aEmpenhosRetorno;

        break;





        case "salvar":

            if (count($oParam->aEmpenhos) < 1) {
                throw new BusinessException("Nenhum empenho foi informado.");
            }

            foreach ($oParam->aEmpenhos as $codEmpenho) {
                $oDaoEmpenhoJustificativaContratoLicitacao = new cl_empenhojustificativacontratolicitacao();
                $oDaoEmpenhoJustificativaContratoLicitacao->e08_empempenho = $codEmpenho;
                $oDaoEmpenhoJustificativaContratoLicitacao->e08_tipojustificativalicitacao = $oParam->tipoJustificativaLicitacao;
                $oDaoEmpenhoJustificativaContratoLicitacao->e08_tipojustificativacontrato  = $oParam->tipoJustificativaContrato;
                $oDaoEmpenhoJustificativaContratoLicitacao->e08_descricaojustificativalicitacao = $oParam->descricaoJustificativaLicitacao;
                $oDaoEmpenhoJustificativaContratoLicitacao->e08_descricaojustificativacontrato = $oParam->descricaoJustificativaContrato;

                $sWhere = " e08_empempenho = $codEmpenho";

                $sSql = $oDaoEmpenhoJustificativaContratoLicitacao->sql_query_file(null, '*', null, $sWhere);
                $rsResult = db_query($sSql);

                if($rsResult === false) {
                    throw new DBException("Não foi possivel encontrar as justificativas do empenho.");
                }

                if (pg_num_rows($rsResult) > 0) {
                    $sequencial = db_utils::fieldsmemory($rsResult, 0)->e08_sequencial;
                    $oDaoEmpenhoJustificativaContratoLicitacao->alterar( $sequencial);
                } else {
                    $oDaoEmpenhoJustificativaContratoLicitacao->incluir();
                }

                if($oDaoEmpenhoJustificativaContratoLicitacao->erro_status == 0) {
                    throw new DBException('Não foi possível salvar as justificativas do empenho.');
                }

                $oRetorno->sMessage = "Justificativas salvas com sucesso.";
            }
           break;

        case "excluir":

            if (count($oParam->aEmpenhos) < 1) {
                throw new BusinessException("Nenhum empenho foi informado.");
            }

            $empenhos =  implode ( ',' , $oParam->aEmpenhos );

            $oDaoEmpenhoJustificativaContratoLicitacao = new cl_empenhojustificativacontratolicitacao();
            $sWhere = " e08_empempenho in ($empenhos)";

            $oDaoEmpenhoJustificativaContratoLicitacao->excluir('e08_sequencial', $sWhere);

            if($oDaoEmpenhoJustificativaContratoLicitacao->erro_status == 0) {
                throw new DBException('Não foi possível remover as justificativas do empenho.');
            }

            $oRetorno->sMessage = "Justificativas excluídas com sucesso.";
            break;


        case "buscarJustificativaEmpenho":

            if (empty($oParam->codigoEmpenho)) {
                throw new BusinessException("Nenhum empenho foi informado.");
            }

            $daoJustificava = new cl_empenhojustificativacontratolicitacao();
            $buscaEmpenho = $daoJustificava->sql_query_file(null, "*", null, "e08_empempenho = {$oParam->codigoEmpenho}");
            $buscaEmpenho = db_query($buscaEmpenho);
            if (!$buscaEmpenho) {
                throw new DBException("Não foi possível buscar o empenho.");
            }
            $oRetorno->dadosEmpenho = db_utils::fieldsMemory($buscaEmpenho, 0);

            break;
   }

  db_fim_transacao(false);

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);


