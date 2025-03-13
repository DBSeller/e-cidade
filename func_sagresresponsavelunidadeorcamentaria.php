<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_sagresresponsavelunidadeorcamentaria_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clsagresresponsavelunidadeorcamentaria = new cl_sagresresponsavelunidadeorcamentaria();
$clsagresresponsavelunidadeorcamentaria->rotulo->label('c140_sequencial');
$clsagresresponsavelunidadeorcamentaria->rotulo->label('c140_cgm');

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
                <td><label for="chave_c140_sequencial"><?=$Lc140_sequencial?></label></td>
                <td><?php db_input("c140_sequencial",10, $Ic140_sequencial, true, "text", 4, "", "chave_c140_sequencial"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_c140_cgm"><?=$Lc140_cgm?></label></td>
                <td><?php db_input("c140_cgm",10, $Ic140_cgm, true, "text", 4, "", "chave_c140_cgm"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_sagresresponsavelunidadeorcamentaria.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_sagresresponsavelunidadeorcamentaria.php") === true) {
            include(modification("funcoes/db_func_sagresresponsavelunidadeorcamentaria.php"));
        } else {
            $campos = "sagresresponsavelunidadeorcamentaria.*";
        }
    }
        if(isset($chave_c140_sequencial) && (trim($chave_c140_sequencial)!="") ){
	         $sql = $clsagresresponsavelunidadeorcamentaria->sql_query($chave_c140_sequencial,$campos,"c140_sequencial");
        }else if(isset($chave_c140_cgm) && (trim($chave_c140_cgm)!="") ){
	         $sql = $clsagresresponsavelunidadeorcamentaria->sql_query("",$campos,"c140_cgm"," c140_cgm like '$chave_c140_cgm%' ");
        }else{
           $sql = $clsagresresponsavelunidadeorcamentaria->sql_query("",$campos,"c140_sequencial","");
        }
        $repassa = array();
        if(isset($chave_c140_cgm)){
          $repassa = array("chave_c140_sequencial"=>$chave_c140_sequencial,"chave_c140_cgm"=>$chave_c140_cgm);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clsagresresponsavelunidadeorcamentaria->sql_record($clsagresresponsavelunidadeorcamentaria->sql_query($pesquisa_chave));
          if($clsagresresponsavelunidadeorcamentaria->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c140_cgm',false);</script>";
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
    js_tabulacaoforms("form2","chave_c140_cgm",true,1,"chave_c140_cgm",true);
</script>
