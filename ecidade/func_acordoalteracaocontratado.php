<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_acordoalteracaocontratado_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clacordoalteracaocontratado = new cl_acordoalteracaocontratado();
$clacordoalteracaocontratado->rotulo->label('ac60_sequencial');
$clacordoalteracaocontratado->rotulo->label('ac60_sequencial');

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
                <td><label for="chave_ac60_sequencial"><?=$Lac60_sequencial?></label></td>
                <td><?php db_input("ac60_sequencial",10, $Iac60_sequencial, true, "text", 4, "", "chave_ac60_sequencial"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_ac60_sequencial"><?=$Lac60_sequencial?></label></td>
                <td><?php db_input("ac60_sequencial",10, $Iac60_sequencial, true, "text", 4, "", "chave_ac60_sequencial"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_acordoalteracaocontratado.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
            $campos = "acordoalteracaocontratado.*";
    }
        if(isset($chave_ac60_sequencial) && (trim($chave_ac60_sequencial)!="") ){
	         $sql = $clacordoalteracaocontratado->sql_query($chave_ac60_sequencial,$campos,"ac60_sequencial");
        }else if(isset($chave_ac60_sequencial) && (trim($chave_ac60_sequencial)!="") ){
	         $sql = $clacordoalteracaocontratado->sql_query("",$campos,"ac60_sequencial"," ac60_sequencial like '$chave_ac60_sequencial%' ");
        }else{
           $sql = $clacordoalteracaocontratado->sql_query("",$campos,"ac60_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ac60_sequencial)){
          $repassa = array("chave_ac60_sequencial"=>$chave_ac60_sequencial,"chave_ac60_sequencial"=>$chave_ac60_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clacordoalteracaocontratado->sql_record($clacordoalteracaocontratado->sql_query($pesquisa_chave));
          if($clacordoalteracaocontratado->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ac60_sequencial',false);</script>";
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
    js_tabulacaoforms("form2","chave_ac60_sequencial",true,1,"chave_ac60_sequencial",true);
</script>
