<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_avaliacaogruporespostaremuneracaorgps_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING']);
$clavaliacaogruporespostaremuneracaorgps = new cl_avaliacaogruporespostaremuneracaorgps();
$clavaliacaogruporespostaremuneracaorgps->rotulo->label('eso28_sequencial');
$clavaliacaogruporespostaremuneracaorgps->rotulo->label('eso28_sequencial');

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
                <td><label for="chave_eso28_sequencial"><?=$Leso28_sequencial?></label></td>
                <td><?php db_input("eso28_sequencial",10, $Ieso28_sequencial, true, "text", 4, "", "chave_eso28_sequencial"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_eso28_sequencial"><?=$Leso28_sequencial?></label></td>
                <td><?php db_input("eso28_sequencial",10, $Ieso28_sequencial, true, "text", 4, "", "chave_eso28_sequencial"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_avaliacaogruporespostaremuneracaorgps.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_avaliacaogruporespostaremuneracaorgps.php") === true) {
            include(modification("funcoes/db_func_avaliacaogruporespostaremuneracaorgps.php"));
        } else {
            $campos = "avaliacaogruporespostaremuneracaorgps.*";
        }
    }
        if(isset($chave_eso28_sequencial) && (trim($chave_eso28_sequencial)!="") ){
	         $sql = $clavaliacaogruporespostaremuneracaorgps->sql_query($chave_eso28_sequencial,$campos,"eso28_sequencial");
        }else if(isset($chave_eso28_sequencial) && (trim($chave_eso28_sequencial)!="") ){
	         $sql = $clavaliacaogruporespostaremuneracaorgps->sql_query("",$campos,"eso28_sequencial"," eso28_sequencial like '$chave_eso28_sequencial%' ");
        }else{
           $sql = $clavaliacaogruporespostaremuneracaorgps->sql_query("",$campos,"eso28_sequencial","");
        }
        $repassa = array();
        if(isset($chave_eso28_sequencial)){
          $repassa = array("chave_eso28_sequencial"=>$chave_eso28_sequencial,"chave_eso28_sequencial"=>$chave_eso28_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clavaliacaogruporespostaremuneracaorgps->sql_record($clavaliacaogruporespostaremuneracaorgps->sql_query($pesquisa_chave));
          if($clavaliacaogruporespostaremuneracaorgps->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$eso28_sequencial',false);</script>";
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
    js_tabulacaoforms("form2","chave_eso28_sequencial",true,1,"chave_eso28_sequencial",true);
</script>
