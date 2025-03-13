<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_sagresarquivogerado_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clsagresarquivogerado = new cl_sagresarquivogerado();
$clsagresarquivogerado->rotulo->label('c141_sequencial');
$clsagresarquivogerado->rotulo->label('c141_json');

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
                <td><label for="chave_c141_sequencial"><?=$Lc141_sequencial?></label></td>
                <td><?php db_input("c141_sequencial",10, $Ic141_sequencial, true, "text", 4, "", "chave_c141_sequencial"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_c141_json"><?=$Lc141_json?></label></td>
                <td><?php db_input("c141_json",10, $Ic141_json, true, "text", 4, "", "chave_c141_json"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_sagresarquivogerado.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_sagresarquivogerado.php") === true) {
            include(modification("funcoes/db_func_sagresarquivogerado.php"));
        } else {
            $campos = "sagresarquivogerado.*";
        }
    }
        if(isset($chave_c141_sequencial) && (trim($chave_c141_sequencial)!="") ){
	         $sql = $clsagresarquivogerado->sql_query($chave_c141_sequencial,$campos,"c141_sequencial");
        }else if(isset($chave_c141_json) && (trim($chave_c141_json)!="") ){
	         $sql = $clsagresarquivogerado->sql_query("",$campos,"c141_json"," c141_json like '$chave_c141_json%' ");
        }else{
           $sql = $clsagresarquivogerado->sql_query("",$campos,"c141_sequencial","");
        }
        $repassa = array();
        if(isset($chave_c141_json)){
          $repassa = array("chave_c141_sequencial"=>$chave_c141_sequencial,"chave_c141_json"=>$chave_c141_json);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clsagresarquivogerado->sql_record($clsagresarquivogerado->sql_query($pesquisa_chave));
          if($clsagresarquivogerado->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c141_json',false);</script>";
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
    js_tabulacaoforms("form2","chave_c141_json",true,1,"chave_c141_json",true);
</script>
