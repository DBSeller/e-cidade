<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_orcsubfuncao_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clorcsubfuncao = new cl_orcsubfuncao();
$clorcsubfuncao->rotulo->label('o53_subfuncao');
$clorcsubfuncao->rotulo->label('o53_descr');

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
                <td><label for="chave_o53_subfuncao"><?=$Lo53_subfuncao?></label></td>
                <td><?php db_input("o53_subfuncao",3, $Io53_subfuncao, true, "text", 4, "", "chave_o53_subfuncao"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_o53_descr"><?=$Lo53_descr?></label></td>
                <td><?php db_input("o53_descr",40, $Io53_descr, true, "text", 4, "", "chave_o53_descr"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_orcsubfuncao.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_orcsubfuncao.php") === true) {
            include(modification("funcoes/db_func_orcsubfuncao.php"));
        } else {
            $campos = "orcsubfuncao.*";
        }
    }
        if(isset($chave_o53_subfuncao) && (trim($chave_o53_subfuncao)!="") ){
	         $sql = $clorcsubfuncao->sql_query($chave_o53_subfuncao,$campos,"o53_subfuncao");
        }else if(isset($chave_o53_descr) && (trim($chave_o53_descr)!="") ){
	         $sql = $clorcsubfuncao->sql_query("",$campos,"o53_descr"," o53_descr like '$chave_o53_descr%' ");
        }else{
           $sql = $clorcsubfuncao->sql_query("",$campos,"o53_subfuncao","");
        }
        $repassa = array();
        if(isset($chave_o53_descr)){
          $repassa = array("chave_o53_subfuncao"=>$chave_o53_subfuncao,"chave_o53_descr"=>$chave_o53_descr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clorcsubfuncao->sql_record($clorcsubfuncao->sql_query($pesquisa_chave));
          if($clorcsubfuncao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o53_descr',false);</script>";
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
    js_tabulacaoforms("form2","chave_o53_descr",true,1,"chave_o53_descr",true);
</script>
