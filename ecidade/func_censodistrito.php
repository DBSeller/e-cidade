<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_censodistrito_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clcensodistrito = new cl_censodistrito();
$clcensodistrito->rotulo->label('ed262_i_codigo');
$clcensodistrito->rotulo->label('ed262_c_nome');

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
                <td>
                    <label for="chave_ed262_i_codigo"><?= $Led262_i_codigo ?></label>
                </td>
                <td>
                    <?php
                    db_input("ed262_i_codigo", 20, $Ied262_i_codigo, true, "text", 4, "", "chave_ed262_i_codigo");
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="chave_ed262_c_nome"><?= $Led262_c_nome ?></label>
                </td>
                <td>
                    <?php
                    db_input("ed262_c_nome", 20, $Ied262_c_nome, true, "text", 4, "", "chave_ed262_c_nome");
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_censodistrito.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_censodistrito.php") === true) {
            include(modification("funcoes/db_func_censodistrito.php"));
        } else {
            $campos = "ed262_i_codigo, ed262_c_nome, ed262_i_censomunic, ed262_i_coddistrito";
        }
    }
    if (isset($chave_ed262_i_codigo) && (trim($chave_ed262_i_codigo) != "")) {
        $sql = $clcensodistrito->sql_query($chave_ed262_i_codigo, $campos, "ed262_i_codigo");
    } elseif (isset($chave_ed262_c_nome) && (trim($chave_ed262_c_nome) != "")) {
        $sql = $clcensodistrito->sql_query("", $campos, "ed262_c_nome", " ed262_c_nome like '$chave_ed262_c_nome%' ");
    } else {
        $sql = $clcensodistrito->sql_query("", $campos, "ed262_i_codigo", "");
    }
    $repassa = [];
    if (isset($chave_ed262_i_codigo)) {
        $repassa["chave_ed262_i_codigo"] = $chave_ed262_i_codigo;
    }
    if (isset($chave_ed262_c_nome)) {
        $repassa["chave_ed262_c_nome"] = $chave_ed262_c_nome;
    }

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    if ($pesquisa_chave != null && $pesquisa_chave != "") {
        $result = $clcensodistrito->sql_record($clcensodistrito->sql_query($pesquisa_chave));
        if ($clcensodistrito->numrows != 0) {
            db_fieldsmemory($result, 0);
            echo "<script>" . $funcao_js . "('$ed262_c_nome',false);</script>";
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
    js_tabulacaoforms("form2", "chave_ed262_i_codigo", true, 1, "chave_ed262_i_codigo", true);
</script>
