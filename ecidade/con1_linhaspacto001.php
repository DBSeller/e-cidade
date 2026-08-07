<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_linhaspacto_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$oDaoLinhaspacto = new cl_linhaspacto;
$db_opcao = 1;
$db_botao = true;
$sPosScripts = "";

if (isset($incluir)) {

    db_inicio_transacao();
    $oDaoLinhaspacto->incluir(null);
    db_fim_transacao();

    $sPosScripts .= 'alert("' . $oDaoLinhaspacto->erro_msg . '");' . "\n";

    if ($oDaoLinhaspacto->erro_status == '0') {

        $db_botao = true;
        $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

        if ($oDaoLinhaspacto->erro_campo != "") {
            $sPosScripts .= "document.form1.{$oDaoLinhaspacto->erro_campo}.classList.add('form-error');\n";
            $sPosScripts .= "document.form1.{$oDaoLinhaspacto->erro_campo}.focus();\n";
        }
    } else {
        $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }
}

$sPosScripts .= 'js_tabulacaoforms("form1", "c07_titulo", true, 1, "c07_titulo", true);';

include(modification("forms/db_frmlinhaspacto.php"));
?>
