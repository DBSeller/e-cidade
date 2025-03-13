<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
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

use ECidade\Financeiro\Orcamento\Recurso\Complemento;
use ECidade\Financeiro\Orcamento\Recurso\Especificacao;
use ECidade\Financeiro\Orcamento\Recurso\Grupo;
use ECidade\Financeiro\Orcamento\Recurso\IdentificadorUso;
use ECidade\Financeiro\Orcamento\Recurso\TipoDetalhamento;

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clconplanoexe = new cl_conplanoexe;
$clorctiporec = new cl_orctiporec;
$clorctiporec->rotulo->label("o15_codigo");
$clorctiporec->rotulo->label("o15_descr");

$dataSessao = date('Y-m-d', db_getsession('DB_datausu'));
$where = [];

if (!empty($codigo) && empty($_POST)) {
    $where = ["o15_codigo = {$codigo}"];
}

if (!empty($fonteRecurso) && empty($_POST)) {
    $where = ["o15_recurso = '{$fonteRecurso}'"];
}

// lista de campos
$campos = [
    "distinct o15_codigo as db_codigo",
    "o15_recurso",
];


if (InstituicaoRepository::usaFonteRecursoUniao()) {
    $campos[] = "orctiporec.o15_loaidentificadoruso";
    $campos[] = "orctiporec.o15_loatipo";
    $campos[] = "orctiporec.o15_loagrupo";
}
$campos[] = "orctiporec.o15_loaespecificacao";
$campos[] = "o15_descr ";
$campos[] = "o200_descricao ";
$campos[] = "orctiporec.o15_codtri ";
$campos[] = "
    case when orctiporec.o15_tipo = 1 then 'Recurso Livre'
         else 'Recurso Vinculado'
    end::varchar o15_tipo
";

$campos[] = "orctiporec.o15_complemento as db_complemento ";
$campos[] = "o15_codigosiconfi";
$campos = implode(', ', $campos);

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body>
<div class="container">
    <form name="form2" method="post" action="">
        <fieldset>
            <legend>Filtros</legend>
            <table class="form-container">
                <tr>
                    <td title="<?= $To15_descr ?>"><?= $Lo15_descr ?></td>
                    <td>
                        <?php
                        db_input("o15_descr", 30, $Io15_descr, true, "text", 4, "", "chave_o15_descr");
                        ?>
                    </td>
                </tr>

                <?php if (InstituicaoRepository::usaFonteRecursoUniao()) : ?>
                    <tr>
                        <td class="bold" nowrap="nowrap"><label for="identificadorUso">Identificador de Uso:</label>
                        </td>
                        <td>
                            <?php
                            $identificadorUso = array("" => 'Selecione');
                            foreach (IdentificadorUso::getAll() as $indice => $valor) {
                                $identificadorUso[$indice] = $valor;
                            }

                            echo "<select id='o15_loaidentificadoruso' name='o15_loaidentificadoruso'>";
                            foreach ($identificadorUso as $value => $label) {
                                echo "<option value='{$value}' >{$label}</option>";
                            }
                            echo "</select>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="tipoDetalhamento">
                                <strong>Tipo de Detalhamento:</strong>
                            </label>
                        </td>
                        <td>
                            <?php
                            $tipoDetalhamento = array("" => 'Selecione');
                            foreach (TipoDetalhamento::getAll() as $indice => $valor) {
                                $tipoDetalhamento[$indice] = $valor;
                            }

                            echo "<select id='o15_loatipo' name='o15_loatipo'>";
                            foreach ($tipoDetalhamento as $value => $label) {
                                echo "<option value='{$value}' >{$label}</option>";
                            }
                            echo "</select>";
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="grupoFonteRecurso">
                                <strong>Grupo:</strong>
                            </label>
                        </td>
                        <td>

                            <?php
                            $grupoFonteRecurso = array("" => 'Selecione');
                            foreach (Grupo::getAll() as $indice => $valor) {
                                $grupoFonteRecurso[$indice] = $valor;
                            }

                            echo "<select id='o15_loagrupo' name='o15_loagrupo'>";
                            foreach ($grupoFonteRecurso as $value => $label) {
                                echo "<option value='{$value}' >{$label}</option>";
                            }
                            echo "</select>";
                            ?>
                        </td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td>
                        <label for="especificacaoFonte">
                            <strong>Especificação:</strong>
                        </label>
                    </td>
                    <td>
                        <?php
                        $especificacaoFonte = array("" => 'Selecione');
                        foreach (Especificacao::getAll() as $indice => $valor) {
                            $especificacaoFonte[$indice] = $valor;
                        }

                        echo "<select id='o15_loaespecificacao' name='o15_loaespecificacao'>";
                        foreach ($especificacaoFonte as $value => $label) {
                            echo "<option value='{$value}' >{$label}</option>";
                        }
                        echo "</select>";
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="complementoRecurso">
                            <strong>Complemento:</strong>
                        </label>
                    </td>
                    <td>
                        <?php
                        $complementoRecurso = array("" => 'Selecione');
                        foreach (\ECidade\Financeiro\Orcamento\Recurso\Complemento::getAll() as $indice => $valor) {
                            $complementoRecurso[$indice] = $valor;
                        }
                        echo "<select id='o15_complemento' name='o15_complemento'>";
                        foreach ($complementoRecurso as $value => $label) {
                            echo "<option value='{$value}' >{$label}</option>";
                        }
                        echo "</select>";
                        ?>
                    </td>
                </tr>
            </table>

        </fieldset>
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar">
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orctiporec.hide();">
    </form>
</div>

<?php

if (isset($sem_recurso) && trim($sem_recurso) != "") {
    $where[] = "o15_codigo not in ({$sem_recurso})";
}
if (isset($sFiltroTipo) && $sFiltroTipo != '') {
    $where[] = "o15_tipo = {$sFiltroTipo}";
}

if (isset($o15_loaidentificadoruso) && $o15_loaidentificadoruso !== '') {
    $where[] = "o15_loaidentificadoruso = '{$o15_loaidentificadoruso}'";
}

if (isset($o15_loatipo) && $o15_loatipo !== '' ) {
    $where[] = "o15_loatipo = '{$o15_loatipo}'";
}

if (isset($o15_loagrupo) && $o15_loagrupo !== '') {
    $where[] = "o15_loagrupo = '{$o15_loagrupo}'";
}

if (isset($o15_loaespecificacao) && $o15_loaespecificacao !== '') {
    $where[] = "o15_loaespecificacao = '{$o15_loaespecificacao}'";
}

if (isset($o15_complemento) && $o15_complemento !== '') {
    $where[] = "o15_complemento = {$o15_complemento}";
}

if (!isset($ativo) || (isset($ativo) && $ativo == 0)) {
    $where[] = "(o15_datalimite is null or o15_datalimite > '{$dataSessao}')";
}

if (!isset($pesquisa_chave)) {
    $orderBy = 'o15_recurso';
    if (isset($chave_o15_descr) && (trim($chave_o15_descr) != "")) {
        $where[] = "o15_descr like '{$chave_o15_descr}%'";
    }

    $sql = $clorctiporec->sql_queryComplemento("", $campos, $orderBy, implode(' and ', $where));

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js);
    echo '  </fieldset> ';
    echo '</div> ';
} elseif (!empty($pesquisa_chave)) {
    $where[] = "o15_codigo = {$pesquisa_chave}";
    $where = implode(' and ', $where);

    $sql = $clorctiporec->sql_queryComplemento(null, $campos, "", $where);
    $result = db_query($sql);
    if (pg_num_rows($result) != 0) {
        db_fieldsmemory($result, 0);
        echo "<script>" . $funcao_js . "('$o15_descr',false, '$db_codigo', '$o15_recurso', '$o200_descricao');</script>";
    } else {
        echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
    }
} else {
    echo "<script>" . $funcao_js . "('',false);</script>";
}

?>
</body>
</html>
<script type="text/javascript">
    (function () {
        var query = frameElement.getAttribute('name').replace('IF', ''),
            input = document.querySelector('input[value="Fechar"]');
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();
</script>
