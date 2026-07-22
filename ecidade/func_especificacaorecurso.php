<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
$uf = InstituicaoRepository::getInstituicaoPrefeitura()->getUf();
$where = [
    "(o205_estado = '' or o205_estado = '$uf')"
];

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link type="text/css" rel="stylesheet" href="estilos.css">
    <link type="text/css" rel="stylesheet" href="estilos/DBFormularios.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
</head>
<body>
<form name="form2" method="post" action="" class="container">
    <fieldset class="container">
        <legend>Filtros:</legend>
        <table class="form-container">
            <tr>
                <td><label for="codigoEspecificacao">Código Especificação:</label></td>
                <td>
                    <input type="text" id="chave_codigo" name="chave_codigo" class="field-size2">
                </td>
            </tr>
            <tr>
                <td><label for="codigoEspecificacao">Descrição Especificação:</label></td>
                <td>
                    <input type="text" id="chave_descricao" name="chave_descricao" class="field-size8" >
                </td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar"
           onClick="parent.db_iframe_especificacao.hide();">
</form>
<?php

echo '<div class="container">';
echo '  <fieldset>';
echo '    <legend>Resultado da Pesquisa</legend>';

$repassa = array();

if (!isset($pesquisa_chave)) {
    if (!empty($_POST['chave_codigo'])) {
        $where[] = "o205_codigo ilike '{$_POST['chave_codigo']}%'";
    }
    if (!empty($_POST['chave_descricao'])) {
        $where[] = "o205_descricao ilike '{$_POST['chave_descricao']}%'";
    }
    $dao = new cl_recursoespecificacao();
    $sql = $dao->sql_query_file(null, "*", '2', implode(' and ', $where));

    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    echo "<script>" . $funcao_js . "('',false);</script>";
}
