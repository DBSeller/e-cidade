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

/**
 * Busca todos empenhos com bens pendente de incorporação
 */

$instituicao = db_getsession("DB_instit");

$campos = " distinct e60_numemp, e60_codemp ||'/'|| e60_anousu::varchar as dl_Empenho,  e60_vlremp ";
$where = array();
$order = "e60_numemp desc";
$dao = new cl_bempendenteincorporacao;

db_postmemory($_POST);
$get = db_utils::postMemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clrotulo->label('e60_numemp');
$clrotulo->label('e60_codemp');

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
                    <td title="<?= $Te60_numemp ?>">
                        <label for="chave_e60_numemp"><?= $Le60_numemp ?> </label>
                    </td>
                    <td>
                        <?php db_input("e60_numemp", 10, $Ie60_numemp, true, "text", 4, "", "chave_e60_numemp", "", "width: 100px;"); ?>
                    </td>
                </tr>
                <tr>
                    <td title="Número do empenho">
                        <label for="chave_numeroEmpenho">Empenho:</label>
                    </td>
                    <td>
                        <?php db_input("numeroEmpenho", 8, $Ie60_codemp, true, "text", 4, "", "chave_numeroEmpenho", "", "width: 100px;"); ?>
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
if (!empty($chave_e60_numemp)) {
    $where[] = "e60_numemp = {$chave_e60_numemp}";
}
if (!empty($chave_numeroEmpenho)) {
    if (strpos($chave_numeroEmpenho, '/') === false) {
        $chave_numeroEmpenho .= "/" . db_getsession("DB_anousu");
    }

    $numeroEmpenho = explode('/', $chave_numeroEmpenho);
    $where[] = "e60_codemp = '{$numeroEmpenho[0]}'";
    $where[] = "e60_anousu = {$numeroEmpenho[1]}";
}

if (!isset($pesquisa_chave)) {
    $sql = $dao->sql_origem($campos, $order, implode(' and ', $where));

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", array());
    echo '  </fieldset>';
    echo '</div>';
} else if ($pesquisa_chave != null && $pesquisa_chave != "") {
    if (strpos($pesquisa_chave, '/') === false) {
        $pesquisa_chave .= "/" . db_getsession("DB_anousu");
    }
    $numeroEmpenho = explode('/', $pesquisa_chave);
    $where[] = "e60_codemp = '{$numeroEmpenho[0]}'";
    $where[] = "e60_anousu = {$numeroEmpenho[1]}";

    $sql = $dao->sql_origem($campos, $order, implode(' and ', $where));
    $rs = db_query($sql);
    if (pg_num_rows($rs) == 1) {
        db_fieldsmemory($rs, 0);
        echo "<script>" . $funcao_js . "('{$e60_numemp}', false, '{$dl_empenho}');</script>";
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
