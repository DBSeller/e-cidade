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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

/** *******************************************************
 * Essa função só traz BENS ativos da instituição logada. *
 ******************************************************** */

$instituicao = db_getsession("DB_instit");
$daoDepartamento = new cl_db_depart();
$camposDpto = "coddepto, descrdepto";
$whereDpto = "instit = $instituicao";
$rsDepartamentos = db_query($daoDepartamento->sql_query_file(null, $camposDpto, "2", $whereDpto));
$departamentos = array();
db_utils::makeCollectionFromRecord($rsDepartamentos, function ($dado) use (&$departamentos) {
    $departamentos[$dado->coddepto] = $dado->descrdepto;
});

db_postmemory($_POST);
$get = db_utils::postMemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clrotulo->label('t52_bem');
$clrotulo->label('t52_ident');
$clrotulo->label('t52_descr');
$clrotulo->label('descrdepto');


$campos = "distinct bens.t52_bem, bens.t52_codcla, bens.t52_valaqu, bens.t52_dtaqu, bens.t52_ident, bens.t52_descr, 
bens.t52_obs, bens.t52_depart, bens.t52_instit";

$where = array(
    "not exists (select 1 from bensbaix where bensbaix.t55_codbem = t52_bem)",
    "t52_instit = {$instituicao}"
);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<div class="container">
    <form name="form2" id="form_filtros" method="post" action="">
        <fieldset style="width: 500px;">
            <legend class="bold">Filtros</legend>
            <table class="form-container">
                <tr>
                    <td title="<?= $Tt52_ident ?>">
                        <label for="chave_t52_ident"><?= $Lt52_ident ?> </label>
                    </td>
                    <td>
                        <?php db_input("t52_ident", 20, $It52_ident, true, "text", 4, "", "chave_t52_ident"); ?>
                    </td>
                </tr>
                <tr>
                    <td title="<?= $Tt52_bem ?>">
                        <label for="chave_t52_bem"><?= $Lt52_bem ?></label>
                    </td>
                    <td>
                        <?php db_input("t52_bem", 8, $It52_bem, true, "text", 4, "", "chave_t52_bem"); ?>
                    </td>
                </tr>

                <tr>
                    <td title="<?= $Tt52_descr ?>">
                        <label for="chave_t52_descr"><?= $Lt52_descr ?></label>
                    </td>
                    <td>
                        <?php db_input("t52_descr", 40, $It52_descr, true, "text", 4, "", "chave_t52_descr"); ?>
                    </td>
                </tr>
                <tr>
                    <td title="<?= $Tdescrdepto ?>">
                        <label for="dpto">Departamento:</label>
                    </td>
                    <td>
                        <?php db_select('dpto', $departamentos,true, 1); ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="button" id="limpar" value="Limpar" onclick="document.getElementById('form_filtros').reset();">
        <input name="Fechar" type="button" id="fechar" value="Fechar">
    </form>
</div>

<div class="container">
<?php
if (!empty($chave_t52_ident)) {
    $where[] = "t52_ident = '{$chave_t52_ident}'";
}
if (!empty($chave_t52_bem)) {
    $where[] = "t52_bem = {$chave_t52_bem}";
}
if (!empty($chave_t52_descr)) {
    $where[] = "t52_descr ilike '{$chave_t52_descr}%'";
}
if (!empty($dpto)) {
    $where[] = "coddepto = {$dpto}";
}

if (!isset($pesquisa_chave)) {
    $repassa = array();
    if (isset($chave_t52_ident)) {
        $repassa = array(
            "chave_t52_ident" => $chave_t52_ident,
            "chave_t52_bem" => $chave_t52_bem,
            "chave_t52_descr" => $chave_t52_descr,
            "dpto" => $dpto,
        );
    }

    $daoBem = new cl_bens();
    $sql = $daoBem->sql_query_func_pesquisa(null, $campos, null, implode(" and ", $where));

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
} else if ($pesquisa_chave != null && $pesquisa_chave != "") {

    $where[] = "t52_bem = {$pesquisa_chave}";
    $daoBem = new cl_bens();
    $sql = $daoBem->sql_query_func_pesquisa(null, $campos, null, implode(" and ", $where));
    $rs = db_query($sql);
    if (pg_num_rows($rs) == 1) {
        db_fieldsmemory($rs, 0);
        echo "<script>" . $funcao_js . "('{$t52_descr}', false, '{$t52_ident}');</script>";
    } else {
        echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
    }
} else {
    echo "<script>" . $funcao_js . "('',false);</script>";
}


?>
</div>
</body>
</html>
<script type="text/javascript">
(function () {
    var query = frameElement.getAttribute('name').replace('IF', ''),
        input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();

</script>
