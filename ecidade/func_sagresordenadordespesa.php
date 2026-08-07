<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_sagresordenadordespesa_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clsagresordenadordespesa = new cl_sagresordenadordespesa();
$clsagresordenadordespesa->rotulo->label('c139_sequencial');
$clsagresordenadordespesa->rotulo->label('c139_cgm');

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
                <td><label for="chave_c139_sequencial"><?=$Lc139_sequencial?></label></td>
                <td><?php db_input("c139_sequencial",10, $Ic139_sequencial, true, "text", 4, "", "chave_c139_sequencial"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_c139_cgm"><?=$Lc139_cgm?></label></td>
                <td><?php db_input("c139_cgm",10, $Ic139_cgm, true, "text", 4, "", "chave_c139_cgm"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_sagresordenadordespesa.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_sagresordenadordespesa.php") === true) {
            include(modification("funcoes/db_func_sagresordenadordespesa.php"));
        } else {
            $campos = "sagresordenadordespesa.*";
        }
    }
        if(isset($chave_c139_sequencial) && (trim($chave_c139_sequencial)!="") ){
	         $sql = $clsagresordenadordespesa->sql_query($chave_c139_sequencial,$campos,"c139_sequencial");
        }else if(isset($chave_c139_cgm) && (trim($chave_c139_cgm)!="") ){
	         $sql = $clsagresordenadordespesa->sql_query("",$campos,"c139_cgm"," c139_cgm like '$chave_c139_cgm%' ");
        }else{
           $sql = $clsagresordenadordespesa->sql_query("",$campos,"c139_sequencial","");
        }
        $repassa = array();
        if(isset($chave_c139_cgm)){
          $repassa = array("chave_c139_sequencial"=>$chave_c139_sequencial,"chave_c139_cgm"=>$chave_c139_cgm);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clsagresordenadordespesa->sql_record($clsagresordenadordespesa->sql_query($pesquisa_chave));
          if($clsagresordenadordespesa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c139_cgm',false);</script>";
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
    js_tabulacaoforms("form2","chave_c139_cgm",true,1,"chave_c139_cgm",true);
</script>
