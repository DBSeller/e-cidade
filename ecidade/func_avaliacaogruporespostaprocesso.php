<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_avaliacaogruporespostalotacao_classe.php");

use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$rotulo = new rotulocampo;
$rotulo->label("z01_nome");

$dao = new cl_avaliacaogruporespostaprocesso;
$dao->rotulo->label("eso05_cmg");
$dao->rotulo->label("eso05_processo");

// busca o formulário de acordo com a versão configurada
$configuracao = new Configuracao();
$formularioId = $configuracao->getFormulario(Tipo::PROCESSOS);

// filtro da instituicao
$instituicao = null;
if (!empty($_GET["instituicao"])) {
    $instituicao = $_GET["instituicao"];
}
$where = array();
$where[] = " db102_avaliacao = {$formularioId} ";

?>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    <link href='estilos.css' rel='stylesheet' type='text/css'>
    <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
    <form name="form2" method="post" action="" class="container">
        <fieldset>
            <legend>Dados para Pesquisa</legend>
            <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
                <tr title='Informe o cgm do empregador ou pesquise pelo nome.'>
                    <td><label for='eso05_cgm'>Empregador:</label></td>
                    <td >
                        <?php
                        db_input("eso05_cgm", 10, $Ieso05_cgm, true, "text", 4, "", "chave_eso05_cgm");
                        db_input("z01_nome", 30, $Iz01_nome, true, "text", 4, "", "chave_z01_nome");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><label for='eso05_processo'><?=$Leso05_processo?></label></td>
                    <td>
                        <?php
                        db_input("eso05_processo", 10, $Ieso05_processo, true, "text", 4, "", "chave_eso05_processo");
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_avaliacaogruporespostaprocesso.hide();">
    </form>
    <?php

    $tipoProcesso = "case when eso05_tipoprocesso = 1 then 'Administrativo' else 'Judicial' end::varchar as dl_tipo_processo";

    $campos = array(
        "distinct eso05_avaliacaogruporesposta as db_preenchimento",
        "eso05_cgm",
        "z01_nome as dl_empregador",
        "eso05_processo",
        "eso05_tipoprocesso as db_tipoprocesso",
        $tipoProcesso
    );
    if (!isset($pesquisa_chave)) {
        if (isset($chave_eso05_cgm) && (trim($chave_eso05_cgm) != "")) {
            $where[] = " eso05_cgm = {$chave_eso05_cgm} ";
        } else if (isset($chave_z01_nome) && (trim($chave_z01_nome) != "")) {
            $where[] = " z01_nome like '{$chave_z01_nome}%' ";
        } else if (isset($chave_eso05_processo) && (trim($chave_eso05_processo) != "")) {
            $where[] = " eso05_processo ilike '{$chave_eso05_processo}%' ";
        }

        $order = array('eso05_avaliacaogruporesposta', 'eso05_processo');
        $sql = $dao->buscaPreenchimento($campos, $where, $order, $instituicao);
        $repassa = array();
        if (isset($chave_z01_nome)) {
            $repassa = array(
                "chave_eso05_cgm" => $chave_eso05_cgm,
                "chave_z01_nome" => $chave_z01_nome,
                "chave_eso05_processo" => $chave_eso05_processo
            );
        }

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        echo '  </fieldset>';
        echo '</div>';
    }
?>
</body>
</html>
