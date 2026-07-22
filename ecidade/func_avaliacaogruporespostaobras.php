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

$dao = new cl_avaliacaogruporespostaobras;
$dao->rotulo->label("eso35_cmg");
$dao->rotulo->label("eso35_cnpj");

// busca o formulário de acordo com a versão configurada
$configuracao = new Configuracao();
$formularioId = $configuracao->getFormulario(Tipo::OBRAS);

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
                    <tr>
                        <td><label for='eso35_cnpj'><?=$Leso35_cnpj?></label></td>
                        <td>
                            <?php
                            db_input("eso35_cnpj", 10, $Ieso35_cnpj, true, "text", 4, "", "chave_eso35_cnpj");
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_avaliacaogruporespostaobras.hide();">
        </form>
        <?php
        $campos = array(
            "distinct eso35_avaliacaogruporesposta as db_preenchimento",
            "eso35_empregador",
            "z01_nome as dl_empregador",
            "eso35_cnpj"
        );
        if (!isset($pesquisa_chave)) {
             if (isset($chave_eso35_cnpj) && (trim($chave_eso35_cnpj) != "")) {
                $where[] = " eso35_cnpj ilike '{$chave_eso35_cnpj}%' ";
             }

            $order = array('eso35_avaliacaogruporesposta', 'eso35_cnpj');
            $sql = $dao->buscaPreenchimento($campos, $where, $order, $instituicao);
            $repassa = array();
            if (isset($chave_z01_nome)) {
                $repassa = array(
                    "chave_eso35_empregador" => $chave_eso05_empregador,
                    "chave_eso35_cnpj" => $chave_eso05_cpnj
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
        <script>
            const inputCnpj = document.getElementById('chave_eso35_cnpj');
            inputCnpj.addEventListener('change', function() {
                inputCnpj.value = inputCnpj.value.replace(/[^0-9]/g, '');
            })
        </script>
    </body>
</html>
