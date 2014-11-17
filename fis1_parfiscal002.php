<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_parfiscal_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clparfiscal = new cl_parfiscal;
$db_opcao    = 22;
$db_botao    = false;
$lSqlErro    = false;
$y32_instit  = db_getsession('DB_instit');

if (isset($alterar)) {

  $y32_instit = db_getsession('DB_instit');

  if( $utilizadocpadrao == 0 ){
    $HTTP_POST_VARS['y32_templateautoinfracao'] = "null";
    $y32_templateautoinfracao                   = "null";
  }

  if ($y32_modalvara != 3) {

    $HTTP_POST_VARS['y32_templatealvarasanitarioprovisorio'] = "null";
    $HTTP_POST_VARS['y32_templatealvarasanitariopermanente'] = "null";
    $y32_templatealvarasanitarioprovisorio                   = "null";
    $y32_templatealvarasanitariopermanente                   = "null";
  } else if (empty($y32_templatealvarasanitarioprovisorio)) {

    $clparfiscal->erro_msg    = "Campo Template Padrão Alvará Sanitário Provisório não informado.";
    $clparfiscal->erro_status = "0";
    $clparfiscal->erro_campo  = "y32_templatealvarasanitarioprovisorio";
  }else if (empty($y32_templatealvarasanitariopermanente)) {

    $clparfiscal->erro_msg    = "Campo Template Padrão Alvará Sanitário Permanente não informado.";
    $clparfiscal->erro_status = "0";
    $clparfiscal->erro_campo  = "y32_templatealvarasanitariopermanente";
  }

  if ($clparfiscal->erro_status != "0") {

    db_inicio_transacao();

    $result = $clparfiscal->sql_record($clparfiscal->sql_query_param());
    if($result == false || $clparfiscal->numrows == 0) {

      $clparfiscal->incluir($y32_instit);
      if ($clparfiscal->erro_status == '0') {
        $lSqlErro = true;
      }
    } else {

      $clparfiscal->alterar($y32_instit);

      if ($clparfiscal->erro_status == '0') {
        $lSqlErro = true;
      }
    }

    db_fim_transacao($lSqlErro);
  }
} else {

  $result   = $clparfiscal->sql_record($clparfiscal->sql_query_param($y32_instit,"*",null,""));

  if ($result != false && $clparfiscal->numrows > 0) {
    db_fieldsmemory($result,0);
  }
}

$db_opcao = 2;
$db_botao = true;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <?php
    	  include("forms/db_frmparfiscal.php");
    	?>
    </div>
    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>
  </body>
</html>
<?php

if (isset($alterar)) {

  if ($clparfiscal->erro_status == "0") {

    $clparfiscal->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clparfiscal->erro_campo != "") {

      echo "<script> document.form1.".$clparfiscal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clparfiscal->erro_campo.".focus();</script>";
    }
  } else {
    $clparfiscal->erro(true,true);
  }
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>