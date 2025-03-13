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
$rotulo->label("eso36_matricula");

$dao = new cl_esoacidentetrabalho();

// busca o formulário de acordo com a versão configurada
$configuracao = new Configuracao();
$formularioId = $configuracao->getFormulario(Tipo::CAT);

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
                        <td><label for='eso36_matricula'><?=$Leso36_matricula?></label></td>
                        <td>
                            <?php
                            db_input("eso36_matricula", 10, $Ieso36_matricula, true, "text", 4, "", "chave_eso36_matricula");
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_avaliacaogruporespostaocat.hide();">
        </form>
        <?php
        $campos = array(
            "distinct eso36_avaliacaogruporesposta as db_preenchimento",
            "eso36_empregador",
            "eso36_matricula",
            "z01_nome as nome",
            "eso36_data",
            "eso36_cpf"
        );
        if (!isset($pesquisa_chave)) {
            if (isset($chave_eso36_matricula) && (trim($chave_eso36_matricula) != "")) {
                $where[] = " eso36_matricula = {$chave_eso36_matricula} ";
            }

            $order = array('eso36_avaliacaogruporesposta', 'eso36_matricula');
        }
        $sql = $dao->buscaPreenchimento($campos, $where, $order, $instituicao);
        $repassa = [
        ];

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        echo '  </fieldset>';
        echo '</div>';
        ?>
        <script>
            const inputMatricula = document.getElementById('chave_eso36_matricula');
            inputMatricula.addEventListener('change', function() {
                inputMatricula.value = inputMatricula.value.replace(/[^0-9]/g, '');
            })
        </script>
    </body>
</html>
