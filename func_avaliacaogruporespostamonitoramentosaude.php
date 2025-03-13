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
$rotulo->label("eso37_cpf");

$dao = new cl_avaliacaogruporespostamonitoramentosaude();

// busca o formulário de acordo com a versão configurada
$configuracao = new Configuracao();
$formularioId = $configuracao->getFormulario(Tipo::MONITORIAMENTO_SAUDE);

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
                        <td><label for='eso37_cpf'><?=$Leso37_cpf?></label></td>
                        <td>
                            <?php
                            db_input("eso37_cpf", 10, $Ieso37_cpf, true, "text", 4, "", "chave_eso37_cpf");
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
            "distinct eso37_avaliacaogruporesposta as db_preenchimento",
            "eso37_empregador",
            "eso37_matricula",
            "z01_nome as nome",
            "eso37_cpf"
        );
        if (!isset($pesquisa_chave)) {
            if (isset($chave_eso37_cpf) && (trim($chave_eso37_cpf) != "")) {
                $where[] = " eso37_cpf = '{$chave_eso37_cpf}' ";
            }

            $order = array('eso37_avaliacaogruporesposta', 'eso37_cpf');
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
            const inputCpf = document.getElementById('chave_eso37_cpf');
            inputCpf.addEventListener('change', function() {
                inputCpf.value = inputCpf.value.replace(/[^0-9]/g, '');
            })
        </script>
    </body>
</html>
