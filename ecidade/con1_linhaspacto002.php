<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_linhaspacto_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoLinhaspacto = new cl_linhaspacto;
$db_opcao = 22;
$db_botao = false;
$sPosScripts = "";

if (isset($alterar)) {

    db_inicio_transacao();
    $db_opcao = 2;
    $oDaoLinhaspacto->alterar($c07_sequencial);
    db_fim_transacao();

    $sPosScripts .= 'alert("' . $oDaoLinhaspacto->erro_msg . '");' . "\n";

    if ($oDaoLinhaspacto->erro_status == "0") {

        $db_botao = true;
        $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

        if ($oDaoLinhaspacto->erro_campo != "") {
            $sPosScripts .= "document.form1.{$oDaoLinhaspacto->erro_campo}.classList.add('form-error');";
            $sPosScripts .= "document.form1.{$oDaoLinhaspacto->erro_campo}.focus();";
        }
    } else {
        $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }
} else if (isset($chavepesquisa)) {

    $db_opcao = 2;
    $db_botao = true;
    $result = $oDaoLinhaspacto->sql_record($oDaoLinhaspacto->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
}

if ($db_opcao == 22) {
    $sPosScripts .= "document.form1.pesquisar.click();\n";
}

$sPosScripts .= 'js_tabulacaoforms("form1", "c07_titulo", true, 1, "c07_titulo", true);';

include(modification("forms/db_frmlinhaspacto.php"));
?>
