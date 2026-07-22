<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_rhsindicato_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$oPost = db_utils::postMemory($_POST);

$clrhsindicato = new cl_rhsindicato;
$clrhsindicato->rotulo->label("rh116_sequencial");
$clrhsindicato->rotulo->label("rh116_codigo");
$clrhsindicato->rotulo->label("rh116_cnpj");
$clrhsindicato->rotulo->label("rh116_descricao");
$clrhsindicato->rotulo->label("rh116_mesdatabase");

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="estilos.css">
    <script src="scripts/scripts.js"></script>
</head>
<body>
<form name="form2" method="post" class="container">
    <fieldset>
        <legend>Dados para Pesquisa</legend>
        <table width="35%" border="0" class="text-left" cellspacing="0">
            <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Trh116_sequencial; ?>">
                    <?php echo $Lrh116_sequencial; ?>
                </td>
                <td width="96%" align="left" nowrap>
                    <?php db_input("rh116_sequencial", 10, $Irh116_sequencial, true, "text", 4, "",
                        "chave_rh116_sequencial"); ?>
                </td>
            </tr>
            <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Trh116_codigo; ?>">
                    <?php echo $Lrh116_codigo; ?>
                </td>
                <td width="96%" align="left" nowrap>
                    <?php db_input("rh116_codigo", 10, $Irh116_codigo, true, "text", 4, "",
                        "chave_rh116_codigo"); ?>
                </td>
            </tr>
            <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Trh116_cnpj; ?>">
                    <?php echo $Lrh116_cnpj; ?>
                </td>
                <td width="96%" align="left" nowrap>
                    <?php db_input("rh116_cnpj", 10, $Irh116_cnpj, true, "text", 4, "", "chave_rh116_cnpj"); ?>
                </td>
            </tr>
            <tr>
                <td width="4%" align="right" nowrap title="<?php echo $Trh116_descricao; ?>">
                    <?php echo $Lrh116_descricao; ?>
                </td>
                <td width="96%" align="left" nowrap>
                    <?php db_input("rh116_descricao", 10, $Irh116_descricao, true, "text", 4, "",
                        "chave_rh116_descricao"); ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <div class="text-center">
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar">
        <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_rhsindicato.hide();">
    </div>
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_rhsindicato.php") === true) {
            require_once modification('funcoes/db_func_rhsindicato.php');
        } else {
            $campos = "rhsindicato.*";
        }
    }
    $sWhere = null;
    $aWhere = array();

    if (!empty($oPost->chave_rh116_codigo)) {
        $aWhere[] = "rh116_codigo ilike '%{$oPost->chave_rh116_codigo}%'";
    }

    if (!empty($oPost->chave_rh116_cnpj)) {
        $aWhere[] = "rh116_cnpj ilike '%{$oPost->chave_rh116_cnpj}%'";
    }

    if (!empty($oPost->chave_rh116_descricao)) {
        $aWhere[] = "rh116_descricao ilike '%{$oPost->chave_rh116_descricao}%'";
    }

    $sWhere = implode(' and ', $aWhere);
    if (isset($chave_rh116_sequencial) && (trim($chave_rh116_sequencial) != "")) {
        $sql = $clrhsindicato->sql_query($chave_rh116_sequencial, $campos, "rh116_sequencial");
    } else {
        if (!empty($sWhere)) {
            $sql = $clrhsindicato->sql_query("", $campos, "rh116_sequencial", $sWhere);
        } else {
            $sql = $clrhsindicato->sql_query("", $campos, "rh116_sequencial", "");
        }
    }
    $repassa = array();
    if (isset($chave_rh116_sequencial)) {
        $repassa = array(
            "chave_rh116_sequencial" => $chave_rh116_sequencial
        );
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    if ($pesquisa_chave != null && $pesquisa_chave != "") {
        $result = $clrhsindicato->sql_record($clrhsindicato->sql_query($pesquisa_chave));
        if ($clrhsindicato->numrows != 0) {
            db_fieldsmemory($result, 0);
            echo "<script>" . $funcao_js . "('$rh116_sequencial',false);</script>";
        } else {
            echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
        }
    } else {
        echo "<script>{$funcao_js}('', false);</script>";
    }
}
?>
</body>
</html>
<script rel="script" type="text/javascript">
    js_tabulacaoforms('form2', 'chave_rh116_sequencial', true, 1, 'chave_rh116_sequencial', true);
</script>
