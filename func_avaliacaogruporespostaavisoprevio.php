<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_avaliacaogruporespostaavisoprevio_classe.php");

use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$rotulo = new rotulocampo;
$rotulo->label("z01_nome");

$dao = new cl_avaliacaogruporespostaavisoprevio;
$dao->rotulo->label("eso07_empregador");
$dao->rotulo->label("eso07_regist");

// busca o formulário de acordo com a versão configurada
$configuracao = new Configuracao();
$formularioId = $configuracao->getFormulario(Tipo::AVISO_PREVIO);

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
                    <td><label for='eso07_empregador'>Empregador:</label></td>
                    <td >
                        <?php
                        db_input("eso07_empregador", 10, $Ieso07_empregador, true, "text", 4, "", "chave_eso07_empregador");
                        db_input("z01_nome", 30, $Iz01_nome, true, "text", 4, "", "chave_z01_nome");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><label for='eso07_regist'><?=$Leso07_regist?></label></td>
                    <td>
                        <?php
                        db_input("eso07_regist", 10, $Ieso07_regist, true, "text", 4, "", "chave_eso07_regist");
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
        <input name="limpar" type="reset" id="limpar" value="Limpar" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_avaliacaogruporespostaavisoprevio.hide();">
    </form>
    <?php
    $campos = array(
        "distinct eso07_avaliacaogruporesposta as db_preenchimento",
        "eso07_empregador",
        "z01_nome as dl_empregador",
        "eso07_regist"
    );
    if (!isset($pesquisa_chave)) {
        if (isset($chave_eso07_empregador) && (trim($chave_eso07_empregador) != "")) {
            $where[] = " eso07_empregador = {$chave_eso07_empregador} ";
        } else if (isset($chave_z01_nome) && (trim($chave_z01_nome) != "")) {
            $where[] = " z01_nome like '{$chave_z01_nome}%' ";
        } else if (isset($chave_eso07_regist) && (trim($chave_eso07_regist) != "")) {
            $where[] = " eso07_regist ilike '{$chave_eso07_regist}%' ";
        }

        $order = array('eso07_avaliacaogruporesposta', 'eso07_regist');
        $sql = $dao->buscaPreenchimento($campos, $where, $order, $instituicao);
        $repassa = array();
        if (isset($chave_z01_nome)) {
            $repassa = array(
                "chave_eso07_empregador" => $chave_eso07_empregador,
                "chave_z01_nome" => $chave_z01_nome,
                "chave_eso07_regist" => $chave_eso07_regist
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
