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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/pdf.php"));

$oGet = db_utils::postMemory($_GET);



?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script rel="script" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <script type="text/javascript">
    
    function emitePdf(sUrl) {
      alert("Receitas sem vínculo com orçamento");
      var sListagem = sUrl + "#Download arquivo";
      js_montarlista(sListagem,"form1");
    }

  </script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <form action="" name="form1" method="post">
	      <center>
          <table id="processando" style="visibility:visible" width="100%" border="0" cellspacing="0">
            <tr>
              <td height="45">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="center"><font size="5">Processando Classifica&ccedil;&atilde;o Aguarde...
                </font></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
          <table id="processado" style="visibility:hidden" width="100%" border="0" cellspacing="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="center"><font size="5">Processo Conclu&iacute;do.</font></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>
        </center>
      </form>
	  </td>
  </tr>
</table>
</body>
</html>
<?php

   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

   flush();

   try {


   	db_inicio_transacao();


    $sSqlVinculoReceitas = VinculoReceitas($oGet->codret);

    $rsSqlVinculos = db_query($sSqlVinculoReceitas);

    if( pg_num_rows($rsSqlVinculos) > 0 ){
      
      $oPdf = new PDF();
      $oPdf->Open();
      $oPdf->AliasNbPages();
      $oPdf->setfillcolor(235);

      $oPdf->AddPage('L');
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(20, 4, 'Tipo'   , 1, 0, "C", 1);
      $oPdf->cell(50, 4, 'Receita'       , 1, 1, "C", 1);
      
      $oPdf->setfont('arial','',8);

      $oDadosReceita = db_utils::getCollectionByRecord($rsSqlVinculos);

      foreach ( $oDadosReceita as $oReceita ) {
        $oPdf->cell(20, 4, $oReceita->tipo       , 1, 0, "C", 0);
        $oPdf->cell(50, 4, $oReceita->k00_receit , 1, 1, "C", 0);
      }

      $sCaminhoPdf = "tmp/listareceita".date('Ymd').".pdf";
      $oPdf->Output($sCaminhoPdf);

      echo "<script>emitePdf('$sCaminhoPdf')</script>";

      exit;   
    }

   	if(!isset($oGet->codret) || $oGet->codret == ""){
   		throw new Exception("Código do arquivo ({$oGet->codret}) de Retorno Inválido.");
   	}

   	$sSql = "select * from fc_executa_baixa_banco($oGet->codret,'".date("Y-m-d",db_getsession("DB_datausu"))."')";
   	$rsBaixaBanco = pg_query($sSql);

   	if (!$rsBaixaBanco) {
   		throw new Exception(str_replace("\n","", pg_last_error()));
   	}

    $aRetornoBaixa = db_utils::makeCollectionFromRecord($rsBaixaBanco, function($oRetorno) {

      $oRetorno->processado = $oRetorno->processado == 't';
      return $oRetorno;
    });

    $lSucesso = false;
    $sRetorno = '';
    $aErros   = array();

    foreach ($aRetornoBaixa as $oRetorno) {

      $sMensagemRetorno = (!empty($oRetorno->codigo) ? "{$oRetorno->codigo} - " : '') . $oRetorno->descricao;

      if ($oRetorno->processado) {

        $lSucesso = true;
        $sRetorno = $sMensagemRetorno;
      } else {

        $aErros[] = $sMensagemRetorno;
      }
    }

   	if (!$lSucesso) {
   	  throw new Exception(implode("\n", $aErros));
   	}

    if (!empty($aErros)) {
      $sRetorno .= "\n\nForam encontradas algumas inconsistências no arquivo de retorno:\n";
      $sRetorno .= implode("\n", $aErros);
    }

   	db_msgbox($sRetorno);

   	db_fim_transacao(false);

   } catch (Exception $oErro) {

   	db_fim_transacao(true);
    $sErro = utf8_decode($oErro->getMessage());
   	$sMsgRetorno  = "Erro durante o processamento da Classificação da Baixa de Banco!\\n\\n{$sErro}";
   	db_msgbox($sMsgRetorno);

   }

   db_redireciona("cai4_baixabanco002.php?db_opcao=4&pesquisar=true&k15_codbco={$oGet->k15_codbco}&k15_codage={$oGet->k15_codage}");


function VinculoReceitas( $iCodRetorno ){
  
  $iAnoSessao = db_getsession('DB_anousu');

  $sSql  = " select distinct 1 AS tipo, ";
  $sSql .= "                k00_receit,";
  $sSql .= "         taborc.k02_codigo";
  $sSql .= "        FROM recibopaga";
  $sSql .= "          LEFT JOIN taborc ON taborc.k02_codigo = recibopaga.k00_receit";
  $sSql .= "                          AND taborc.k02_anousu = {$iAnoSessao}";
  $sSql .= "    WHERE k00_numnov IN";
  $sSql .= "                    (SELECT k00_numpre";
  $sSql .= "                                FROM disbanco";
  $sSql .= "                              WHERE dtarq >= '{$iAnoSessao}-01-01'::date";
  $sSql .= "                                    AND classi IS FALSE and codret = {$iCodRetorno} )";
  $sSql .= "          AND taborc.k02_codigo IS NULL  and recibopaga.k00_receit in (select tabrec.k02_codigo from  tabrec where tabrec.k02_codigo  = recibopaga.k00_receit and tabrec.k02_tipo ='O' )  ";
  $sSql .= " UNION ";
  $sSql .= "  select distinct 2 AS tipo,";
  $sSql .= "                k00_receit,";
  $sSql .= "                taborc.k02_codigo";
  $sSql .= "        FROM recibo";
  $sSql .= "          LEFT JOIN taborc ON taborc.k02_codigo = recibo.k00_receit";
  $sSql .= "                          AND taborc.k02_anousu =  {$iAnoSessao}";
  $sSql .= "     WHERE k00_numpre IN";
  $sSql .= "                      (SELECT k00_numpre";
  $sSql .= "                                FROM disbanco";
  $sSql .= "                            WHERE dtarq >= '{$iAnoSessao}-01-01'::date";
  $sSql .= "                                  AND classi IS FALSE and codret = {$iCodRetorno})";
  $sSql .= "           AND taborc.k02_codigo IS NULL and recibo.k00_receit in (select tabrec.k02_codigo from  tabrec where tabrec.k02_codigo  = recibo.k00_receit and tabrec.k02_tipo ='O' )";
  $sSql .= " UNION ";
  $sSql .= " select  distinct 3 AS tipo,";
  $sSql .= "                  k00_receit,";
  $sSql .= "           taborc.k02_codigo";
  $sSql .= "        FROM arrecad";
  $sSql .= "          LEFT JOIN taborc ON taborc.k02_codigo = arrecad.k00_receit";
  $sSql .= "                          AND taborc.k02_anousu =  {$iAnoSessao}";
  $sSql .= "      WHERE k00_numpre IN";
  $sSql .= "                      (SELECT k00_numpre";
  $sSql .= "                                FROM disbanco";
  $sSql .= "                          WHERE dtarq >= '{$iAnoSessao}-01-01'::date";
  $sSql .= "                                AND classi IS FALSE and codret = {$iCodRetorno} )";
  $sSql .= "            AND taborc.k02_codigo IS NULL and  arrecad.k00_receit   in ( select tabrec.k02_codigo from  tabrec where tabrec.k02_codigo  = arrecad.k00_receit and tabrec.k02_tipo ='O' )";

  return $sSql;

}
?>
