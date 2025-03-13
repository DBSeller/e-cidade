<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_tiposerviconotafiscal_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING']);
$cltiposerviconotafiscal = new cl_tiposerviconotafiscal();
$cltiposerviconotafiscal->rotulo->label('e18_sequencial');
$cltiposerviconotafiscal->rotulo->label('e18_descricao');

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<form name="form2" method="post" class="container">
    <fieldset>
        <legend>Dados para Pesquisa</legend>
        <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
            <tr>
                <td><label for="chave_e18_sequencial"><?=$Le18_sequencial?></label></td>
                <td><?php db_input("e18_sequencial",10, $Ie18_sequencial, true, "text", 4, "", "chave_e18_sequencial"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_e18_descricao"><?=$Le18_descricao?></label></td>
                <td><?php db_input("e18_descricao",10, $Ie18_descricao, true, "text", 4, "", "chave_e18_descricao"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_tiposerviconotafiscal.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_tiposerviconotafiscal.php") === true) {
            include(modification("funcoes/db_func_tiposerviconotafiscal.php"));
        } else {
            $campos = "tiposerviconotafiscal.*";
        }
    }
        if(isset($chave_e18_sequencial) && (trim($chave_e18_sequencial)!="") ){
	         $sql = $cltiposerviconotafiscal->sql_query($chave_e18_sequencial,$campos,"e18_sequencial");
        }else if(isset($chave_e18_descricao) && (trim($chave_e18_descricao)!="") ){
	         $sql = $cltiposerviconotafiscal->sql_query("",$campos,"e18_descricao"," e18_descricao like '$chave_e18_descricao%' ");
        }else{
           $sql = $cltiposerviconotafiscal->sql_query("",$campos,"e18_sequencial","");
        }
        $repassa = array();
        if(isset($chave_e18_descricao)){
          $repassa = array("chave_e18_sequencial"=>$chave_e18_sequencial,"chave_e18_descricao"=>$chave_e18_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $cltiposerviconotafiscal->sql_record($cltiposerviconotafiscal->sql_query($pesquisa_chave));
          if($cltiposerviconotafiscal->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e18_descricao',false);</script>";
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
    js_tabulacaoforms("form2","chave_e18_descricao",true,1,"chave_e18_descricao",true);
</script>
