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
include(modification("classes/db_orctiporec_classe.php"));
include(modification("classes/db_conplanoexe_classe.php"));

require_once(modification("src/Financeiro/Orcamento/Recurso/Especificacao.php"));
require_once(modification("src/Financeiro/Orcamento/Recurso/TipoDetalhamento.php"));
require_once(modification("src/Financeiro/Orcamento/Recurso/IdentificadorUso.php"));
require_once(modification("src/Financeiro/Orcamento/Recurso/Grupo.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$chave_o15_descr = isset($chave_o15_descr) ? stripslashes($chave_o15_descr) : '';
$dataSessao = date('Y-m-d', db_getsession('DB_datausu'));
$oPost = db_utils::postMemory($_POST, 0);
$oGet = db_utils::postMemory($_GET, 0);

$clconplanoexe = new cl_conplanoexe;
$clorctiporec = new cl_orctiporec;
$clorctiporec->rotulo->label("o15_codigo");
$clorctiporec->rotulo->label("o15_descr");

if (isset($pesquisa_conta)) {
    // temos um reduzido do plano de contas
    // descobrimos o recurso e configuramos esta consulta para apresentar aquele recurso
    $result = $clconplanoexe->sql_record(
        $clconplanoexe->sql_descr(db_getsession("DB_anousu"), $pesquisa_conta, "c61_codigo")
    );

    if ($clconplanoexe->numrows > 0) {
        db_fieldsmemory($result, 0);
        $pesquisa_chave = $c61_codigo;
    }
}

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
                    <td title="<?= $To15_codigo ?>">
                        <strong>Código:</strong>
                    </td>
                    <td>
                        <?php
                        db_input("o15_codigo", 4, $Io15_codigo, true, "text", 4, "", "chave_o15_codigo");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td  title="<?= $To15_descr ?>">
                        <?= $Lo15_descr ?>
                    </td>
                    <td>
                        <?php
                        $chave_o15_descr = preg_replace("/[\'\"]/", "", $chave_o15_descr);
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

$chave_o15_descr = addslashes($chave_o15_descr);

$where = [];
$dbwhere = "";
if (isset($sem_recurso) && trim($sem_recurso) != "") {
    $where[] = "o15_codigo not in (" . $sem_recurso . ")";
}
if (isset($sFiltroTipo) && $sFiltroTipo != '') {
    $where[] = "o15_tipo = {$sFiltroTipo}";
}

if (!isset($ativo) || (isset($ativo) && $ativo == 0)) {
    $where[] = "(o15_datalimite is null or o15_datalimite > '{$dataSessao}')";
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

if (!isset($pesquisa_chave)) {
    if (isset($campos) == false) {
        if (file_exists("funcoes/db_func_orctiporec.php") == true) {
            include(modification("funcoes/db_func_orctiporec.php"));
        } else {
            $campos = "orctiporec.*";
        }
    }

    if (isset($chave_o15_codigo)) {
        if (!DBNumber::isInteger($chave_o15_codigo)) {
            $chave_o15_codigo = '';
        }
    }
    $campos .= " , o15_codigosiconfi ";
    $orderBy = 'o15_recurso::int, o15_codigo';

    if (isset($chave_o15_codigo) && (trim($chave_o15_codigo) != "")) {
        $where[] = "o15_codigo = {$chave_o15_codigo}";
    } else if (isset($chave_o15_descr) && (trim($chave_o15_descr) != "")) {
        $where[] = "o15_descr like '$chave_o15_descr%'";
    }

    $sql = $clorctiporec->sql_query("", $campos, $orderBy, implode(' and ', $where));

    if (isset($chave_o15_descr)) {
        $chave_o15_descr = str_replace("\\", "", $chave_o15_descr);
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js);
    echo '  </fieldset> ';
    echo '</div> ';
} else {
    if (isset($pesquisa_conta)) {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
            $where[] = "o15_codigo = {$pesquisa_chave}";
            $sql1 = $clorctiporec->sql_query(null, " * ", "", implode(' and ', $where));
            $result = $clorctiporec->sql_record($sql1);

            if ($clorctiporec->numrows != 0) {
                db_fieldsmemory($result, 0);
                echo "<script>" . $funcao_js . "('$pesquisa_chave','$o15_descr');</script>";
            }
        }

    } else {
        // aqui continua a funçao normalmente
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
            $where[] = "o15_codigo = {$pesquisa_chave}";
            $sql2 = $clorctiporec->sql_query(null, " * ", "", implode(' and ', $where));
            $result = $clorctiporec->sql_record($sql2);
            if ($clorctiporec->numrows != 0) {
                db_fieldsmemory($result, 0);
                echo "<script>" . $funcao_js . "('$o15_descr',false);</script>";
            } else {
                echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
            }
        } else {
            echo "<script>" . $funcao_js . "('',false);</script>";
        }
    }
}
?>
</body>
</html>
<?php
if (!isset($pesquisa_chave)) {
    ?>
    <script>

        (function () {

            if (document.getElementById('chave_o15_codigo').value != '') {
                var oRegex = /^[0-9]+$/;
                if (!oRegex.test(document.getElementById('chave_o15_codigo').value)) {
                    alert('Recurso deve ser preenchido somente com números!');
                    document.getElementById('chave_o15_codigo').value = '';
                    return false;
                }
            }
        })();
    </script>
    <?php
}
?>
<script type="text/javascript">

    (function () {
        var query = frameElement.getAttribute('name').replace('IF', ''),
            input = document.querySelector('input[value="Fechar"]');
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();
</script>
