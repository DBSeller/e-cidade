<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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
require_once(modification("dbforms/db_funcoes.php"));

parse_str($_SERVER['QUERY_STRING']);

if (!isset($abas)) {
    echo "<script>location.href='pro1_obras005.php?db_opcao=1'</script>";
    exit;
}

db_postmemory($_POST);

$clobras = new cl_obras;
$clobrasresp = new cl_obrasresp;
$clobrastec = new cl_obrastec;
$clobrastecnicos = new cl_obrastecnicos;
$clobraspropri = new cl_obraspropri;
$clobrastiporesp = new cl_obrastiporesp;
$clobraslote = new cl_obraslote;
$clobraslotei = new cl_obraslotei;
$clobrasender = new cl_obrasender;
$clobrasprotprocesso = new cl_obrasprotprocesso;
$clobrasiptubase = new cl_obrasiptubase;

$db_opcao = 1;
$db_botao = true;
$sqlerro = false;
$erro = "";

if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] == "Incluir") {
    db_inicio_transacao();

    $clobras->ob01_responsavelprojeto = $_POST["ob15_sequencial"];

    $clobras->incluir($ob01_codobra);

    if ($clobras->erro_status == "0") {
        $erro = $clobras->erro_msg;
        $sqlerro = true;
    } else {
        $ok = $clobras->erro_msg;
    }

    $ob01_codobra = $clobras->ob01_codobra;

    if ($sqlerro == false) {
        $clobraspropri->incluir($ob01_codobra);

        if ($clobraspropri->erro_status == "0") {
            $erro = $clobraspropri->erro_msg;
            $sqlerro = true;
        }
    }

    if ($sqlerro == false) {
        if (!isset($ob10_numcgm)) {
            $clobrasresp->ob10_numcgm = $ob03_numcgm;
            $clobrasresp->ob10_codobra = $ob01_codobra;
            $clobrasresp->incluir($ob01_codobra);
        } else {
            $clobrasresp->ob10_codobra = $ob01_codobra;
            $clobrasresp->incluir($ob01_codobra);
        }

        if ($clobrasresp->erro_status == "0") {
            $erro = $clobrasresp->erro_msg;
            $sqlerro = true;
        }
    }

    if ($j01_matric != "" && $sqlerro == false) {
        $clobrasiptubase->ob24_obras = $ob01_codobra;
        $clobrasiptubase->ob24_iptubase = $j01_matric;
        $clobrasiptubase->incluir(null);

        if ($clobrasiptubase->erro_status == "0") {
            $erro = $clobrasiptubase->erro_msg;
            $sqlerro = true;
        }
    }

    db_fim_transacao($sqlerro);
}

if (isset($pri)) {
    include(modification("pro1_obras004.php"));
    exit;
}
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC>
  <?php
  include(modification("forms/db_frmobras.php"));
  ?>
  </body>
  </html>
<?php
if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] == "Incluir") {
    if ($sqlerro == true) {
        db_msgbox($erro);

        if ($clobras->erro_campo != "") {
            echo "<script> document.form1." . $clobras->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clobras->erro_campo . ".focus();</script>";
        };
    } else {
        db_msgbox($ok);
        echo "
         <script>
         function js_src() {
           parent.iframe_obras.location.href='pro1_obras002.php?chavepesquisa=" . $clobras->ob01_codobra . "&abas=1';\n
           parent.iframe_constr.location.href='pro1_obrasconstr001.php?ob08_codobra=" . $clobras->ob01_codobra . "&abas=1';\n
           parent.mo_camada('constr');
	         parent.document.formaba.constr.disabled=false;
	         parent.document.formaba.areas.disabled=false;
         }

         js_src();
         </script>
       ";
    };
};
