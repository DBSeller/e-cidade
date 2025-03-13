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

$dao = new cl_rhlotacaotributaria;
$dao->rotulo->label("rh268_numcgm");
$dao->rotulo->label("rh268_codigolotacao");


// busca o formulário de acordo com a versão configurada
$configuracao = new Configuracao();
$formularioId = $configuracao->getFormulario(Tipo::LOTACAO_TRIBUTARIA);

// filtro da instituicao
$instituicao = db_getsession("DB_instit");

if (!empty($_GET["instituicao"])) {
    $instituicao = $_GET["instituicao"];
}
$where = [];
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
                        <td><label for='rh268_codigolotacao'><?=$Lrh268_codigolotacao?></label></td>
                        <td>
                            <?php
                            db_input("rh268_codigolotacao", 10, $Irh268_codigolotacao, true, "text", 4, "", "chave_rh268_codigolotacao");
                            ?>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_avaliacaogruporespostalotacaotributaria.hide();">
        </form>
        <?php
        $campos = [
            "distinct on (eso04_cgm, rh268_codigolotacao) 
            max(eso04_avaliacaogruporesposta) over (partition by eso04_cgm, rh268_codigolotacao) as db_preenchimento",
            "eso04_cgm",
            "z01_nome as dl_empregador",
            "rh268_codigolotacao as dl_Codigo_Lotacao"
        ];
        if (!isset($pesquisa_chave)) {
             if (isset($chave_rh268_codigolotacao) && (trim($chave_rh268_codigolotacao) != "")) {
                $where[] = " rh268_codigolotacao ilike '{$chave_rh268_codigolotacao}%' ";
             }

            $order = [];
            
            $sql = $dao->buscaPreenchimento($campos, $where, $order, $instituicao);
            $repassa = [];
            if (isset($chave_z01_nome)) {
                $repassa = [
                    "chave_eso04_cgm" => $chave_eso04_cgm,
                    "chave_rh268_codigolotacao" => $chave_rh268_codigolotacao
                ];
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
            const inputCodigoLotacao = document.getElementById('chave_rh268_codigolotacao');
            inputCodigoLotacao.addEventListener('change', function() {
                inputCodigoLotacao.value = inputCodigoLotacao.value;
            })
        </script>
    </body>
</html>
