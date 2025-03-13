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
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$clrhfuncao = new cl_rhfuncao;
$clrhinstrucao = new cl_rhinstrucao;
$clrhfuncaocomplementar = new cl_rhfuncaooutrosdados;

$db_opcao = 22;
$db_botao = false;

if (isset($alterar)) {
    db_inicio_transacao();
    $db_opcao = 2;
    $clrhfuncao->rh37_instit = db_getsession("DB_instit");

    if (isParaiba()) {

        $jsonb = '{"tipoCargo": ' . '"'. "{$rh267_dados}" . '"}';
        $clrhfuncaocomplementar->rh267_dados = $jsonb;
        $clrhfuncaocomplementar->rh267_rhfuncao = $rh37_funcao;
        $clrhfuncaocomplementar->rh267_instit = $clrhfuncao->rh37_instit;

        $resultFuncaocompl = $clrhfuncaocomplementar->sql_record($clrhfuncaocomplementar->sql_query(null,'rh267_codigo', null, "rh267_rhfuncao = {$rh37_funcao} and rh267_instit = {$clrhfuncao->rh37_instit}"));
        if ($resultFuncaocompl) {
            db_fieldsmemory($resultFuncaocompl, 0);
        }

        if (isset($rh267_codigo)) {
            $clrhfuncaocomplementar->alterar($rh37_funcao, $clrhfuncao->rh37_instit);
        } else {
            $clrhfuncaocomplementar->incluir($rh37_funcao);
        }
    }

    $clrhfuncao->alterar($rh37_funcao, db_getsession("DB_instit"));
    db_fim_transacao();
} else if (isset($chavepesquisa)) {

    $db_opcao = 2;
    $db_botao = true;
    $instit = db_getsession("DB_instit");
    $result = $clrhfuncao->sql_record($clrhfuncao->sql_query($chavepesquisa, $instit));
    db_fieldsmemory($result, 0);

    if (isParaiba()) {
        $rsfuncaooutrosdados = $clrhfuncaocomplementar->sql_record(
            $clrhfuncaocomplementar->sql_query_file(
                null, 
                "rh267_dados->>'tipoCargo' as rh267_dados, rh267_codigo", 
                null,
                "rh267_rhfuncao = {$rh37_funcao} and rh267_instit = {$instit}"
                )
            );
        if ($rsfuncaooutrosdados) {
            db_fieldsmemory($rsfuncaooutrosdados, 0);
        } else {
            $rh267_dados = "";
        }
    }

    $rh37_funcaogrupodescr = $rh100_descricao;
}
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
  include(modification("forms/db_frmrhfuncao.php"));
  db_menu();
?>
</body>
</html>
<?php
if (isset($alterar)) {
    if ($clrhfuncao->erro_status == "0") {
        $clrhfuncao->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clrhfuncao->erro_campo != "") {
            echo "<script> document.form1." . $clrhfuncao->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clrhfuncao->erro_campo . ".focus();</script>";
        }
    } else {
        $clrhfuncao->erro(true, true);
    }
}
if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>