<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_servicosprestadores_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clservicosprestadores = new cl_servicosprestadores();
$clservicosprestadores->rotulo->label('fm08_codigo');
$clservicosprestadores->rotulo->label('fm08_prestador');

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
                <td><label for="chave_fm08_codigo"><?=$Lfm08_codigo?></label></td>
                <td><?php db_input("fm08_codigo",10, $Ifm08_codigo, true, "text", 4, "", "chave_fm08_codigo"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_fm08_prestador"><?=$Lfm08_prestador?></label></td>
                <td><?php db_input("fm08_prestador",10, $Ifm08_prestador, true, "text", 4, "", "chave_fm08_prestador"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_servicosprestadores.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_servicosprestadores.php") === true) {
            include(modification("funcoes/db_func_servicosprestadores.php"));
        } else {
            $campos = "servicosprestadores.*";
        }
    }
        if(isset($chave_fm08_codigo) && (trim($chave_fm08_codigo)!="") ){
	         $sql = $clservicosprestadores->sql_query($chave_fm08_codigo,$campos,"fm08_codigo");
        }else if(isset($chave_fm08_prestador) && (trim($chave_fm08_prestador)!="") ){
	         $sql = $clservicosprestadores->sql_query("",$campos,"fm08_prestador"," fm08_prestador like '$chave_fm08_prestador%' ");
        }else{
           $sql = $clservicosprestadores->sql_query("",$campos,"fm08_codigo","");
        }
        $repassa = array();
        if(isset($chave_fm08_prestador)){
          $repassa = array("chave_fm08_codigo"=>$chave_fm08_codigo,"chave_fm08_prestador"=>$chave_fm08_prestador);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clservicosprestadores->sql_record($clservicosprestadores->sql_query($pesquisa_chave));
          if($clservicosprestadores->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$fm08_prestador',false);</script>";
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
    js_tabulacaoforms("form2","chave_fm08_prestador",true,1,"chave_fm08_prestador",true);
</script>
