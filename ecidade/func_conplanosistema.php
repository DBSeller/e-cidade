<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_conplanosistema_classe.php');

$get = db_utils::postMemory($_GET);
db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING']);
$clconplanosistema = new cl_conplanosistema();
$clconplanosistema->rotulo->label('c122_sequencial');
$clconplanosistema->rotulo->label('c122_descricao');

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
                <td><label for="chave_c122_sequencial"><?=$Lc122_sequencial?></label></td>
                <td><?php db_input("c122_sequencial",10, $Ic122_sequencial, true, "text", 4, "", "chave_c122_sequencial"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_c122_descricao"><?=$Lc122_descricao?></label></td>
                <td><?php db_input("c122_descricao",50, $Ic122_descricao, true, "text", 4, "", "chave_c122_descricao"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_conplanosistema.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {


    $campos = "conplanosistema.c122_sequencial, conplanosistema.c122_descricao";
    $where = array();
    if (!empty($get->tipo)) {
        $where[] = "c122_tipo = {$get->tipo}";
    }

    if(isset($chave_c122_sequencial) && (trim($chave_c122_sequencial)!="") ){

        $where[] = " c122_sequencial = {$chave_c122_sequencial} ";
        $sql = $clconplanosistema->sql_query("",$campos,"c122_sequencial", implode(' and ', $where));

    } else if(isset($chave_c122_descricao) && (trim($chave_c122_descricao)!="") ){

        $where[] = " c122_descricao ilike '{$chave_c122_descricao}%' ";
        $sql = $clconplanosistema->sql_query("",$campos,"c122_sequencial", implode(' and ', $where));
    }
    else{
        $sql = $clconplanosistema->sql_query("",$campos,"c122_sequencial", implode(' and ', $where));
    }
    $repassa = array();
    if(isset($chave_c122_sequencial)){
        $repassa = array("chave_c122_sequencial"=>$chave_c122_sequencial,"chave_c122_descricao"=>$chave_c122_descricao);
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    echo '  </fieldset>';
    echo '</div>';
} else {
    if ($pesquisa_chave != null && $pesquisa_chave != "") {

        $where = array("c122_sequencial = {$pesquisa_chave}");
        if (!empty($get->tipo)) {
            $where[] = "c122_tipo = {$get->tipo}";
        }
        $result = $clconplanosistema->sql_record($clconplanosistema->sql_query(null, "*", null, implode(' and ', $where)));
        if($clconplanosistema->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c122_descricao',false);</script>";
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
  js_tabulacaoforms("form2","chave_c122_sequencial",true,1,"chave_c122_sequencial",true);
</script>
