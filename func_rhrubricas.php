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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_rhrubricas_classe.php');

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clrhrubricas = new cl_rhrubricas;
$clrhrubricas->rotulo->label("rh27_rubric");
$clrhrubricas->rotulo->label("rh27_instit");
$clrhrubricas->rotulo->label("rh27_descr");

$oGet = db_utils::postMemory($_GET);
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

if (isset($oGet->naoFiltraUsuario) && $oGet->naoFiltraUsuario == 'true') {
    $dao = new cl_rhrubricas();
    $where = array();
}

$where[] = "rh27_instit = {$instituicao->getCodigo()}";

$lUtilizaVinculoLotacaoRubrica = "f";

$clcfpess = new cl_cfpess;
$rsCfPess = $clcfpess->sql_record($clcfpess->sql_query_file(DBPessoal::getAnoFolha(),
                                                            DBPessoal::getMesFolha(),
                                                            db_getsession("DB_instit"), 
                                                            "r11_utilizarhlotavincrubrica"));
$lUtilizaVinculoLotacaoRubrica = db_utils::fieldsMemory($rsCfPess, 0)->r11_utilizarhlotavincrubrica;

/*
 * Lista do codigo dos itens de menu que nao devem utilizar o filtro de vinculo lotacao e rubrica.
 */
$aItensMenuLiberados = array();
$aItensMenuLiberados[] = 4376;   //Pessoal > Cadastros > Tabelas > Rubricas / Códigos > Inclusão
$aItensMenuLiberados[] = 4377;   //Pessoal > Cadastros > Tabelas > Rubricas / Códigos > Alteração
$aItensMenuLiberados[] = 4378;   //Pessoal > Cadastros > Tabelas > Rubricas / Códigos > Exclusão
$aItensMenuLiberados[] = 228681; //Pessoal > Cadastros > Tabelas > Lotações > Vinculo Lotação/Rubrica
$aItensMenuLiberados[] = 10488;  //eSocial > Procedimentos > Envio de eventos para o eSocial
$aItensMenuLiberados[] = 228373; //eSocial > Procedimentos > Re-enviar Eventos para o eSocial

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <style rel="stylesheet" type="text/css">
        #chave_rh27_rubric, #opcao {
            width: 80px;
        }
    </style>
</head>
<body>
<form name="form2" method="post" class="container">
    <fieldset>
        <legend>Dados para Pesquisa</legend>
        <table class="form-container">
            <tr>
                <td>
                    <label for="chave_rh27_rubric"><?= $Lrh27_rubric ?></label>
                </td>
                <td>
                    <?php db_input("rh27_rubric", 4, $Irh27_rubric, true, "text", 4, "", "chave_rh27_rubric"); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="opcao">Seleção por:</label>
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
                    <label for="chave_rh27_descr"><?= $Lrh27_descr ?></label>
                </td>
                <td>
                    <?php db_input("rh27_descr", 30, $Irh27_descr, true, "text", 4, "", "chave_rh27_descr"); ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="button" id="limpar" value="Limpar" onclick="window.location.reload(true)"/>
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_rhrubricas.hide()"/>
</form>
<?php

if (isset($opcao) && trim($opcao) != "i") {
    $where[] = "rh27_ativo = '{$opcao}' ";
}

if (isset($datlimit)) {
    if ($datlimit == 'true') {
        $where[] = "rh27_limdat is true";
    } elseif ($datlimit == 'false') {
        $where[] = "rh27_limdat is false";
    }
}

if (isset($fixas)) {
    if ($fixas == 'true') {
        $where[] = "rh27_tipo = 1";
    } else {
        if ($fixas == 'false') {
            $where[] = "rh27_tipo = 2";
        }
    }
}

if (isset($tipo_rubrica) && $tipo_rubrica != '') {
    $where[] = "rh27_pd = {$tipo_rubrica}";
}

if (!empty($oGet->somentcomformula)) {
    $where[] = " (trim(rh27_form) <> '' or trim(rh27_form2) <> '' or trim(rh27_form3) <> '') ";
}

if ($lUtilizaVinculoLotacaoRubrica == "t") {
    
  if (!in_array(db_getsession("DB_itemmenu_acessado"), $aItensMenuLiberados)) {
    $where[] = " rhrubricas.rh27_rubric in ( select rh239_rhrubricas
                                               from rhlotavincrubrica 
                                              where rh239_instituicao = ".db_getsession("DB_instit")."
                                                and rh239_rhlota in (select distinct rhlotasecretarias.r70_codigo 
                                                                       from db_usuariosrhlota 
                                                                            inner join rhlota on rh157_lotacao = r70_codigo 
                                                                            inner join rhlota as rhlotasecretarias on substr(rhlotasecretarias.r70_estrut,1,2) = substr(rhlota.r70_estrut,1,2) 
                                                                      where rh157_usuario = ".db_getsession("DB_id_usuario")."))";        
  }
  
}
 
if (!isset($pesquisa_chave)) {
    if (isset($campos) == false) {
        if (file_exists("funcoes/db_func_rhrubricas.php") == true) {
            include(modification("funcoes/db_func_rhrubricas.php"));
        } else {
            $campos = "rhrubricas.*";
        }
    }
    if (isset($chave_rh27_rubric) && (trim($chave_rh27_rubric) != "")) {
        $where[] = "rh27_rubric = '{$chave_rh27_rubric}'";
    } elseif (isset($chave_rh27_descr) && (trim($chave_rh27_descr) != "")) {
        $where[] = "rh27_descr like '{$chave_rh27_descr}%' ";
    }

    $sql = $dao->sqlRubricas($campos, $where, array('rh27_rubric'));

    echo "<div class='container'>";
    echo "  <fieldset>";
    echo "    <legend>Resultado da Pesquisa</legend>";
    db_lovrot($sql, 15, "()", "", $funcao_js);
    echo "  </fieldset>";
    echo "</div>";
} else {
    if ($pesquisa_chave != null && $pesquisa_chave != "") {
        $where[] = "rh27_rubric = '{$pesquisa_chave}'";

        $sql = $dao->sqlRubricas('*', $where);
        $result = $dao->sql_record($sql);

        if ($dao->numrows != 0) {
            db_fieldsmemory($result, 0);

            $sRetorno = "<script>" . $funcao_js . "('$rh27_descr',false);</script>";

            if (isset($oGet->campos_adicionais)) {

                $rh27_obs = preg_replace('/[\n\r]+/', ' ', $rh27_obs);
                $sParametros = "{ 'rh27_descr'            : '$rh27_descr',";
                $sParametros .= "  'rh27_limdat'           : '$rh27_limdat',";
                $sParametros .= "  'rh27_presta'           : '$rh27_presta',";
                $sParametros .= "  'rh27_obs'              : '$rh27_obs',";
                $sParametros .= "  'rh27_valorlimite'      :  $rh27_valorlimite,";
                $sParametros .= "  'rh27_quantidadelimite' :  $rh27_quantidadelimite,";
                $sParametros .= "  'rh27_tipobloqueio'     : '$rh27_tipobloqueio'}";

                $sRetorno = "<script>{$funcao_js}({$sParametros} ,false)</script>";
            }

            echo $sRetorno;
        } else {
            echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
        }
    } else {
        echo "<script>" . $funcao_js . "('',false);</script>";
    }
}
?>
<script rel="script" type="text/javascript">
    js_tabulacaoforms('form2', 'chave_rh27_descr', true, 1, 'chave_rh27_descr', true);
</script>
</body>
</html>
