<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_termotaxaparc_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$oDaoTermotaxaparc = new cl_termotaxaparc;
$db_opcao    = 1;

if (isset($opcao)) {
    switch ($opcao) {
        case "alterar":
            $db_opcao = 2;
            break;
        case "excluir":
            $db_opcao = 3;
            break;
    }
}

$db_botao    = true;
$sPosScripts = "";

if (isset($incluir)) {
    db_inicio_transacao();

    $oDaoTermotaxaparc->ar29_instit = db_getsession("DB_instit");
    $oDaoTermotaxaparc->ar29_numpar = $ar29_numpar;
    $oDaoTermotaxaparc->ar29_taxa = $ar29_taxa;
    $oDaoTermotaxaparc->incluir(null);

    db_fim_transacao();

    $sPosScripts .= 'alert("' . $oDaoTermotaxaparc->erro_msg . '");' . "\n";

    if ($oDaoTermotaxaparc->erro_status == "0") {

        $db_botao = true;
        $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

        if ($oDaoTermotaxaparc->erro_campo != "") {
            $sPosScripts .= "document.form1.{$oDaoTermotaxaparc->erro_campo}.classList.add('form-error');";
            $sPosScripts .= "document.form1.{$oDaoTermotaxaparc->erro_campo}.focus();";
        }
    } else {
        $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }

} elseif (isset($alterar)) {
    db_inicio_transacao();
    $db_opcao = 2;
    $oDaoTermotaxaparc->alterar($ar29_sequencial);
    db_fim_transacao();

    $sPosScripts .= 'alert("' . $oDaoTermotaxaparc->erro_msg . '");' . "\n";

    if ($oDaoTermotaxaparc->erro_status == "0") {

        $db_botao = true;
        $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

        if ($oDaoTermotaxaparc->erro_campo != "") {
            $sPosScripts .= "document.form1.{$oDaoTermotaxaparc->erro_campo}.classList.add('form-error');";
            $sPosScripts .= "document.form1.{$oDaoTermotaxaparc->erro_campo}.focus();";
        }
    } else {
        $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }
} elseif (isset($excluir)) {

    db_inicio_transacao();
    $db_opcao = 3;
    $oDaoTermotaxaparc->excluir($ar29_sequencial);
    db_fim_transacao();

    $sPosScripts .= 'alert("' . $oDaoTermotaxaparc->erro_msg . '");' . "\n";

    if ($oDaoTermotaxaparc->erro_status != "0") {
        $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }

}

if (isset($opcao)) {

    try {

        $oDaoTermotaxaparc->ar29_sequencial = $ar29_sequencial;
        $sSqlTermoTaxa = $oDaoTermotaxaparc->sql_query($ar29_sequencial);
        $rsTermoTaxa = pg_query($sSqlTermoTaxa);

        if (!$rsTermoTaxa) {
            throw new Exception("Erro ao buscar o vinculo de parcela com a taxa informada.");
        }

        db_fieldsmemory($rsTermoTaxa, 0);

    } catch (Exception $exception) {
        db_fim_transacao(true);
    }
}



include(modification("forms/db_frmtermotaxaparc.php"));
?>
