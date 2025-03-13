<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_obrasender_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clobrasender = new cl_obrasender();
$clobrasender->rotulo->label('ob07_codconstr');
$clobrasender->rotulo->label('ob07_codobra');

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
                <td><label for="chave_ob07_codconstr"><?=$Lob07_codconstr?></label></td>
                <td><?php db_input("ob07_codconstr",10, $Iob07_codconstr, true, "text", 4, "", "chave_ob07_codconstr"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_ob07_codobra"><?=$Lob07_codobra?></label></td>
                <td><?php db_input("ob07_codobra",10, $Iob07_codobra, true, "text", 4, "", "chave_ob07_codobra"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_obrasender.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_obrasender.php") === true) {
            include(modification("funcoes/db_func_obrasender.php"));
        } else {
            $campos = "obrasender.*";
        }
    }
        if(isset($chave_ob07_codconstr) && (trim($chave_ob07_codconstr)!="") ){
	         $sql = $clobrasender->sql_query($chave_ob07_codconstr,$campos,"ob07_codconstr");
        }else if(isset($chave_ob07_codobra) && (trim($chave_ob07_codobra)!="") ){
	         $sql = $clobrasender->sql_query("",$campos,"ob07_codobra"," ob07_codobra like '$chave_ob07_codobra%' ");
        }else{
           $sql = $clobrasender->sql_query("",$campos,"ob07_codconstr","");
        }
        $repassa = array();
        if(isset($chave_ob07_codobra)){
          $repassa = array("chave_ob07_codconstr"=>$chave_ob07_codconstr,"chave_ob07_codobra"=>$chave_ob07_codobra);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clobrasender->sql_record($clobrasender->sql_query($pesquisa_chave));
          if($clobrasender->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ob07_codobra',false);</script>";
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
    js_tabulacaoforms("form2","chave_ob07_codobra",true,1,"chave_ob07_codobra",true);
</script>
