<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_conplanoorcamento_classe.php');

//db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clconplanoorcamento = new cl_conplanoorcamento();

$clconplanoorcamento->rotulo->label('c60_codcon');
$clconplanoorcamento->rotulo->label('c60_anousu');
$clconplanoorcamento->rotulo->label('c60_descr');
$clconplanoorcamento->rotulo->label('c60_estrut');

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
        <table class="form-container">
            <tr>
                <td><label for="chave_c60_codcon"><?= $Lc60_codcon ?></label></td>
                <td><?php db_input("c60_codcon", 10, $Ic60_codcon, true, "text", 4, "", "chave_c60_codcon"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_c60_descr">Estrutural</label></td>
                <td><?php db_input("c60_estrut", 15, $Ic60_estrut, true, "text", 4, "", "chave_c60_estrut"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_c60_descr"><?= $Lc60_descr ?></label></td>
                <td><?php db_input("c60_descr", 60, $Ic60_descr, true, "text", 4, "", "chave_c60_descr"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_conplanoorcamento.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    $campos = 'conplanoorcamento.*';
    $filtros = ['c60_anousu = ' . db_getsession('DB_anousu')];
    if (isset($_GET['filtraDespesa'])) {
        $filtros[] = "c60_estrut like '3%'";
    }

    if (!empty($_POST['chave_c60_codcon'])) {
        $filtros[] = "c60_codcon = {$_POST['chave_c60_codcon']}";
    }
    if (!empty($_POST['chave_c60_estrut'])) {
        $filtros[] = "c60_estrut like '{$_POST['chave_c60_estrut']}%'";
    }
    if (!empty($_POST['chave_c60_descr'])) {
        $filtros[] = "c60_descr ilike '{$_POST['chave_c60_descr']}'";
    }
    $sql = $clconplanoorcamento->sql_query_geral('', '', $campos, 'c60_estrut', implode(' AND ', $filtros));
    $repassa = [];
    if (isset($chave_c60_descr)) {
        $repassa = ["chave_c60_codcon" => $_POST['chave_c60_codcon'], "chave_c60_descr" => $_POST['chave_c60_descr']];
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    if ($pesquisa_chave != null && $pesquisa_chave != "") {
        $result = db_query($clconplanoorcamento->sql_query_geral($pesquisa_chave, db_getsession('DB_anousu')));
        if ($clconplanoorcamento->numrows != 0) {
            db_fieldsmemory($result, 0);
            echo "<script>" . $funcao_js . "('$c60_descr',false, '$c60_estrut');</script>";
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
    js_tabulacaoforms("form2", "chave_c60_descr", true, 1, "chave_c60_descr", true);
</script>
