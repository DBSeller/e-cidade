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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clprofissionais = new cl_profissionais();
$clprofissionais->rotulo->label('fm15_codigo');
$clprofissionais->rotulo->label('fm15_nome');

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="estilos.css">
    <script src="scripts/scripts.js"></script>
</head>
<body>
<form name="form2" method="post" class="container">
    <fieldset>
        <legend>Dados para Pesquisa</legend>
        <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
            <tr>
                <td><label for="chave_fm15_codigo"><?=$Lfm15_codigo?></label></td>
                <td><?php db_input("fm15_codigo",10, $Ifm15_codigo, true, "text", 4, "", "chave_fm15_codigo"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_fm15_nome"><?=$Lfm15_nome?></label></td>
                <td><?php db_input("fm15_nome",115, $Ifm15_nome, true, "text", 4, "", "chave_fm15_nome"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_profissionais.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_profissionais.php") === true) {
            include(modification("funcoes/db_func_profissionais.php"));
        } else {
            $campos = "profissionais.*";
        }
    }
    if (isset($chave_fm15_codigo) && (trim($chave_fm15_codigo) != "")) {
       $sql = $clprofissionais->sql_query($chave_fm15_codigo,$campos,"fm15_codigo");
    } else if (isset($chave_fm15_nome) && (trim($chave_fm15_nome) != "")) {
       $sql = $clprofissionais->sql_query("",$campos,"fm15_nome"," fm15_nome like '$chave_fm15_nome%' ");
    } else {
       $sql = $clprofissionais->sql_query("",$campos,"fm15_codigo","");
    }
    $repassa = array();
    if (isset($chave_fm15_nome)) {
       $repassa = array("chave_fm15_codigo"=>$chave_fm15_codigo,"chave_fm15_nome"=>$chave_fm15_nome);
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    if ($pesquisa_chave != null && $pesquisa_chave != "") {
       $result = $clprofissionais->sql_record($clprofissionais->sql_query($pesquisa_chave));
       if ($clprofissionais->numrows != 0) {
          db_fieldsmemory($result,0);
          $retorno = "<script>".$funcao_js."('$fm15_nome','$fm15_cpf','$fm15_cbo','$rh70_descr',";
          $retorno .= "'$fm15_regprof','$fm15_orgaoemissor','$sd51_v_descricao',false);</script>";
          echo "$retorno";
       } else {
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
       }
    } else {
       echo "<script>{$funcao_js}('', false);</script>";
    }
}
?>
</body>
</html>
<?php if (isset($pesquisa_chave) === false) { ?>
    <script rel="script" type="text/javascript">
    </script>
<?php } ?>
<script rel="script" type="text/javascript">
    js_tabulacaoforms("form2","chave_fm15_nome",true,1,"chave_fm15_nome",true);
</script>
