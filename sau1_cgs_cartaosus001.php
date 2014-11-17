<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

db_postmemory( $_POST );
$oPost = db_utils::postMemory( $_POST );

$clcgs_cartaosus = new cl_cgs_cartaosus;
$db_opcao        = 1;
$db_botao        = true;

function validaCNS( $oPost ) {

  if( empty( $oPost->s115_c_cartaosus ) ) {

    db_msgbox( 'Informe o número do cartão do SUS.' );
    return false;
  }

  if( !validaCnsDefinitivo( $oPost->s115_c_cartaosus ) && !validaCnsProvisorio( $oPost->s115_c_cartaosus ) ) {

    db_msgbox( 'Número do cartão do SUS inválido.' );
    return false;
  }

  return true;
}

//altera exclui inicio
if( isset( $opcao ) ) {

  /////comeca classe alterar excluir
  $campos  = "";
  $result1 = $clcgs_cartaosus->sql_record( $clcgs_cartaosus->sql_query( "", "*", "", "s115_i_codigo = {$s115_i_codigo}" ) );

  if( $clcgs_cartaosus->numrows > 0 ) {
    db_fieldsmemory( $result1, 0 );
  }

  if( $opcao == "alterar" ) {

   $db_opcao  = 2;
   $db_botao1 = true;
  } else {

    if( $opcao == "excluir" || isset( $db_opcao ) && $db_opcao == 3 ) {

      $db_opcao  = 3;
      $db_botao1 = true;
    } else {

      if( isset( $alterar ) ) {

        $db_opcao  = 2;
        $db_botao1 = true;
      }
    }
  }
}

$lErroCNS = false;
if( isset( $incluir ) ) {


  if( !validaCNS( $oPost ) ) {
    db_redireciona( 'sau1_cgs_cartaosus001.php?s115_i_cgs=' . $oPost->s115_i_cgs . '&z01_v_nome=' . $oPost->z01_v_nome );
  }

  $sSqlValidaCartaoExiste = $clcgs_cartaosus->sql_query_file( "", "1", "", "s115_c_cartaosus = '{$s115_c_cartaosus}'" );
  $rsValidaCartaoExiste   = db_query( $sSqlValidaCartaoExiste );
  if ( $rsValidaCartaoExiste && pg_num_rows($rsValidaCartaoExiste) > 0) {
    $lErroCNS = true;
  }

  if (!$lErroCNS) {

    db_inicio_transacao();
    $clcgs_cartaosus->incluir($s115_i_codigo);
    db_fim_transacao();
  }
}

if( isset( $alterar ) ) {

  if( !validaCNS( $oPost ) ) {
    db_redireciona( 'sau1_cgs_cartaosus001.php?s115_i_cgs=' . $oPost->s115_i_cgs . '&z01_v_nome=' . $oPost->z01_v_nome );
  }

  db_inicio_transacao();
  $db_opcao = 2;
  $clcgs_cartaosus->alterar($s115_i_codigo);
  db_fim_transacao();
}

if( isset( $excluir ) ) {

  db_inicio_transacao();
  $db_opcao = 3;
  $clcgs_cartaosus->excluir($s115_i_codigo);
  db_fim_transacao();
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("webseller.js");
  ?>
  <script language='JavaScript' type='text/javascript' src='scripts/strings.js'></script>
  <script language='JavaScript' type='text/javascript' src='scripts/classes/saude/validaCNS.js'></script>
</head>

<body class="body-default">
  <div class="container" >
    <?php
    include("forms/db_frmcgs_cartaosus.php");
    ?>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","s115_i_cgs",true,1,"s115_i_cgs",true);
</script>
<?php
if( ( isset( $incluir ) ) || ( isset( $alterar ) ) || ( isset( $excluir ) ) ) {

  if ( $lErroCNS ) {

    echo "<script> document.form1.s115_c_cartaosus.focus();</script>";
    db_msgBox("Cartão SUS já esta cadastrado no sistema.");
    exit();
  }

	if( $clcgs_cartaosus->erro_status == "0" ) {

    $clcgs_cartaosus->erro( true, false );
	  $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clcgs_cartaosus->erro_campo!="") {

      echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgs_cartaosus->erro_campo.".focus();</script>";
    }
	} else {
    $clcgs_cartaosus->erro(true,false);
    db_redireciona("sau1_cgs_cartaosus001.php?s115_i_cgs=$s115_i_cgs&z01_v_nome=$z01_v_nome");
  }
}