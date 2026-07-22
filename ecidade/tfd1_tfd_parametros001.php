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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_tfd_parametros_classe.php"));
include(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($HTTP_POST_VARS);
$cltfd_parametros = new cl_tfd_parametros;
$db_opcao         = 1;
$db_botao         = true;

$post = db_utils::postMemory($_POST);

if (isset($incluir)) {
    db_inicio_transacao();

    $cltfd_parametros->incluir($tf11_i_codigo);

    db_fim_transacao();
}

if (isset($alterar)) {
    db_inicio_transacao();

    try {
        $cltfd_parametros->tf11_especmedico = $post->tf11_especmedico;
        $cltfd_parametros->alterar($tf11_i_codigo);
    } catch (Exception $e) {
        $cltfd_parametros->erro_msg = $e->getMessage();
    }
    db_fim_transacao();
}
$sSql = $cltfd_parametros->sql_query_geral("", "tfd_parametros.*,rh70_estrutural,rh70_descr,sd02_i_codigo,descrdepto,".
    "sd03_i_codigo,z01_nome", "", ""
);

$rs   = $cltfd_parametros->sql_record($sSql);
if ($cltfd_parametros->numrows == 0) {
    $db_opcao = 1;
} else {
    $db_opcao = 2;
    db_fieldsmemory($rs, 0);
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
    db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
    db_app::load(" grid.style.css");
    ?>
</head>
<body onLoad="a=1" >
<div class="container" style="width: 790px; margin-top: 0;">
    <?php include(modification("forms/db_frmtfd_parametros.php")); ?>
</div>
<?php
db_menu();
?>
</body>
</html>
<script>
  js_tabulacaoforms("form1","tf11_i_utilizagradehorario",true,1,"tf11_i_utilizagradehorario",true);
</script>
<?php
if (isset($incluir) || isset($alterar)) {
    if ($cltfd_parametros->erro_status == "0") {
        $cltfd_parametros->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($cltfd_parametros->erro_campo!="") {
            echo "<script> document.form1.".$cltfd_parametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1.".$cltfd_parametros->erro_campo.".focus();</script>";
        }
    } else {
        $cltfd_parametros->erro(true, true);
    }
}
?>
