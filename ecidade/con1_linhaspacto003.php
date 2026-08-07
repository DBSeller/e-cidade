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
$daoLinhasPactoVinculo = new cl_previsaodespesalinhaspacto();
$db_botao = false;
$db_opcao = 33;
$sPosScripts = "";

if (isset($excluir)) {

    db_inicio_transacao();
    $db_opcao = 3;

    $whereVinculo = " c41_linhaspacto = {$c07_sequencial} ";
    $sqlLinhasPactoVinculo = $daoLinhasPactoVinculo->sql_query_file(null, '1', null, $whereVinculo);
    $rsLinhasPactoVinculo = db_query($sqlLinhasPactoVinculo);

    if (pg_num_rows($rsLinhasPactoVinculo) > 0) {
        $sPosScripts .= 'alert("Não foi possível excluir a linha de pacto '.$c07_titulo.', pois a mesma já possuí vínculo com uma previsão de despesa.\n");';
    } else {
        $oDaoLinhaspacto->excluir($c07_sequencial);


        $sPosScripts .= 'alert("' . $oDaoLinhaspacto->erro_msg . '");' . "\n";

        if ($oDaoLinhaspacto->erro_status != "0") {
            $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
        }
    }

    db_fim_transacao();
} else if (isset($chavepesquisa)) {

    $db_opcao = 3;
    $db_botao = true;
    $result = $oDaoLinhaspacto->sql_record($oDaoLinhaspacto->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
}

if ($db_opcao == 33) {
    $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .= 'js_tabulacaoforms("form1", "c07_titulo", true, 1, "c07_titulo", true);';

include(modification("forms/db_frmlinhaspacto.php"));
?>
