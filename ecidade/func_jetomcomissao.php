<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_jetomcomissao_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$cljetomcomissao = new cl_jetomcomissao();
$cljetomcomissao->rotulo->label('rh242_sequencial');
$cljetomcomissao->rotulo->label('rh242_sequencial');

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
        <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
            <tr>
                <td><label for="chave_rh242_sequencial"><?= $Lrh242_sequencial ?></label></td>
                <td><?php db_input("rh242_sequencial", 10, $Irh242_sequencial, true, "text", 4, "", "chave_rh242_sequencial"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_jetomcomissao.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_jetomcomissao.php") === true) {
            include(modification("funcoes/db_func_jetomcomissao.php"));
        } else {
            $campos = "jetomcomissao.*";
        }
    }
    if (isset($chave_rh242_sequencial) && (trim($chave_rh242_sequencial) != "")) {
        $sql = $cljetomcomissao->sql_query($chave_rh242_sequencial, $campos, "rh242_sequencial");
    } else if (isset($chave_rh242_sequencial) && (trim($chave_rh242_sequencial) != "")) {
        $sql = $cljetomcomissao->sql_query("", $campos, "rh242_sequencial", " rh242_sequencial like '$chave_rh242_sequencial%' ");
    } else {
        $sql = $cljetomcomissao->sql_query("", $campos, "rh242_sequencial", "");
    }
    $repassa = array();
    if (isset($chave_rh242_sequencial)) {
        $repassa = array("chave_rh242_sequencial" => $chave_rh242_sequencial, "chave_rh242_sequencial" => $chave_rh242_sequencial);
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    if ($pesquisa_chave != null && $pesquisa_chave != "") {
        $result = $cljetomcomissao->sql_record($cljetomcomissao->sql_query($pesquisa_chave));
        if ($cljetomcomissao->numrows != 0) {
            db_fieldsmemory($result, 0);
            echo "<script>" . $funcao_js . "('$rh242_descricao',false);</script>";
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
<?php if (isset($pesquisa_chave) === false) { ?>
    <script rel="script" type="text/javascript">
    </script>
<?php } ?>
<script rel="script" type="text/javascript">
    js_tabulacaoforms("form2", "chave_rh242_sequencial", true, 1, "chave_rh242_sequencial", true);
</script>
