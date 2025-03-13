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

use ECidade\RecursosHumanos\Pessoal\Service\RubricasUsuarioService;

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhrubricas_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhrubricas = new cl_rhrubricas;
$clrhrubricas->rotulo->label("rh27_rubric");
$clrhrubricas->rotulo->label("rh27_descr");

$service = new RubricasUsuarioService();

$usuario = UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario'));
$instituicao = InstituicaoRepository::getInstituicaoSessao();

$dao = new cl_rhrubricas();
$where = array();
// agora por default devemos validar as rubricas configuradas por usuário se houver.
// Se não devemos buscar todas as rubricas
if ($service->possuiConfiguracao($usuario, $instituicao)) {
    $dao = new cl_rubricasusuario();
    $where = array(
        "rh219_usuario = {$usuario->getCodigo()}",
        "rh219_instituicao = {$instituicao->getCodigo()}"
    );
}

if (isset($_GET['naoFiltraUsuario']) && $_GET['naoFiltraUsuario '] == 'true') {
    $dao = new cl_rhrubricas();
    $where = array();
}

$where[] = "rh27_instit = {$instituicao->getCodigo()}";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<style type="text/css">
    #chave_rh27_rubric, #opcao {
        width: 80px;
    }

</style>
<form name="form2" class="container" method="post" action="">
    <fieldset>
        <legend>Pesquisa de Rubricas</legend>
        <table class="form-container" width="35%" border="0" align="center" cellspacing="3">
            <tr>
                <td>
                    <label>
                        <?= $Lrh27_rubric ?>
                    </label>
                </td>
                <td>
                    <?php db_input("rh27_rubric", 4, $Irh27_rubric, true, "text", 4, "", "chave_rh27_rubric"); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        Seleção por:
                    </label>
                </td>
                <td>
                    <?php
                    if (!isset($opcao)) {
                        $opcao = "t";
                    }

                    if (!isset($opcao_bloq)) {
                        $opcao_bloq = 1;
                    }

                    $arr_opcao = array(
                        "i" => "Todos",
                        "t" => "Ativos",
                        "f" => "Inativos"
                    );

                    db_select('opcao', $arr_opcao, true, $opcao_bloq);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label>
                        <?= $Lrh27_descr ?>
                    </label>
                </td>
                <td>
                    <?php db_input("rh27_descr", 30, $Irh27_descr, true, "text", 4, "", "chave_rh27_descr"); ?>
                </td>
            </tr>
        </table>

    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhrubricas.hide();">
</form>
<?php
if (isset($opcao) && !empty($opcao)) {
    $where[] = "rh27_ativo='{$opcao}'";
}

if (!isset($pesquisa_chave)) {

    if (!isset($campos)) {

        if (file_exists("funcoes/db_func_rhrubricas.php")) {
            include(modification("funcoes/db_func_rhrubricas.php"));
        } else {
            $campos = "rhrubricas.*";
        }
    }

    if (isset($chave_rh27_rubric) && !empty($chave_rh27_rubric)) {
        $where[] = " rh27_rubric = '{$chave_rh27_rubric}' ";
    } elseif (isset($chave_rh27_descr) && !empty($chave_rh27_descr)) {
        $where[] = " rh27_descr like '{$chave_rh27_descr}%' ";
    }
    $sql = $dao->sqlRubricas($campos, $where, array('rh27_rubric'));

    echo "<div class='container'>";
    echo "  <fieldset>";
    echo "    <legend>Resultado da Pesquisa</legend>";

    db_lovrot($sql, 15, "()", "", $funcao_js);

    echo "  </fieldset>";
    echo "</div>";

} else {
    if (!is_null($pesquisa_chave) && !empty($pesquisa_chave)) {
        $where[] = " rh27_rubric = '$pesquisa_chave' ";
        $sql = $dao->sqlRubricas("*,case when trim(rh27_form)='' then 'f' else 't' end as formula ", $where);
        $result = $clrhrubricas->sql_record($sql);

        if ($clrhrubricas->numrows) {
            db_fieldsmemory($result, 0);
            $rh27_obs = str_replace(array("\n", "\r"), ' ', $rh27_obs);

            if (!isset($ret)) {
                echo "<script>" . $funcao_js . "('$rh27_descr','$rh27_limdat','$formula','$rh27_obs','$rh27_presta',false, '$rh27_periodolancamento');</script>";

            } else {
                echo "<script>" . $funcao_js . "('$rh27_descr','$rh27_limdat','$formula','$rh27_obs','$rh27_pd','$rh27_presta',false, '$rh27_periodolancamento');</script>";
            }
        } else {
            if (!isset($ret)) {
                echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true,true,true,true,true);</script>";

            } else {
                echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true,true,true,true,true,true);</script>";
            }
        }
    } else {
        if (!isset($ret)) {
            echo "<script>" . $funcao_js . "('',true,true,true,false);</script>";

        } else {
            echo "<script>" . $funcao_js . "('',true,true,true,true,false);</script>";
        }
    }
}
?>
</body>
</html>
<script type="text/javascript">
    (function() {
        var query = frameElement.getAttribute('name').replace('IF', ''),
            input = document.querySelector('input[value="Fechar"]');
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();
</script>
