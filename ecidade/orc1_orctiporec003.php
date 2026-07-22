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
require_once(modification("libs/db_libdicionario.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oPost = db_utils::postMemory($_POST);
$oGet = db_utils::postMemory($_GET);

$db_botao = false;
$db_opcao = 3;
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js, dbautocomplete.widget.js");
    db_app::load("dbmessageBoard.widget.js, prototype.js, prototype.maskedinput.js, dbtextField.widget.js");
    db_app::load("dbcomboBox.widget.js, estilos.css, grid.style.css");
    ?>
    <style>
        #sMascara, #o15_codtri, #o15_descr, #iTipo, #o15_tipo {
            width: 100%;
        }

        #iTipo_select_descr, #o15_tipo_select_descr {
            width: 100%;
        }

        #o15_datalimite {
            background-color: rgb(222, 184, 135);
        }
    </style>
</head>
<body onLoad="js_buscaDadosMascara();">
<?php
    include(modification("forms/db_frmorctiporec.php"));
    db_menu();
?>
</body>
</html>
<script>
    js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>
<?php
if ($db_opcao == 3) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
