<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_orcparamseqcoluna_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clorcparamseqcoluna = new cl_orcparamseqcoluna();
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
                <td>
                    <label for="chave_relatorio">Código do relatório:</label>
                </td>
                <td>
                    <input type="text" name="chave_relatorio" id="chave_relatorio" class="field-size2">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="chave_o115_sequencial">Código da coluna:</label>
                </td>
                <td>
                    <input type="text" name="chave_o115_sequencial" id="chave_o115_sequencial" class="field-size2">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="chave_o115_descricao">Descrição:</label>
                </td>
                <td>
                    <input type="text" name="chave_o115_descricao" id="chave_o115_descricao" class="field-size7">
                </td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_orcparamseqcoluna.hide();">
</form>

<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_orcparamseqcoluna.php") === true) {
            include(modification("funcoes/db_func_orcparamseqcoluna.php"));
        } else {
            $campos = "orcparamseqcoluna.*";
        }
    }

    $where = array();
    $order = array();

    if (!empty($chave_o115_sequencial)) {
        $where[] = "o115_sequencial = {$chave_o115_sequencial}";
        $order[] = "o115_sequencial";
    }

    if (!empty($chave_o115_descricao)) {
        $where[] = "o115_descricao ilike '{$chave_o115_descricao}'";
        $order[] = "o115_descricao";
    }

    if (!empty($chave_relatorio)) {
        $where[] = "o115_relatorio = {$chave_relatorio}";
        $order[] = "o115_relatorio";
    }

    $where = implode(" AND ", $where);
    $order = !empty($order) ? implode(" , ", $order) : "o115_sequencial";
    $sql = $clorcparamseqcoluna->sql_query_relatorio("", $campos, $order, $where);

    $repassa = array();
    if (isset($chave_o115_descricao)) {
        $repassa = array(
            "chave_o115_sequencial" => $chave_o115_sequencial,
            "chave_o115_descricao" => $chave_o115_descricao
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
        $result = $clorcparamseqcoluna->sql_record($clorcparamseqcoluna->sql_query_relatorio($pesquisa_chave));
        if ($clorcparamseqcoluna->numrows != 0) {
            db_fieldsmemory($result, 0);
            echo "<script>" . $funcao_js . "('$o115_descricao',false);</script>";
        } else {
            echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
        }
    } else {
        echo "<script>{$funcao_js}('', false);</script>";
    }
}
?>
<script>
    (() => {
        document.getElementById('chave_o115_sequencial').focus();
    })();
</script>
</body>
</html>
