<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_licencaempreendimento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllicencaempreendimento = new cl_licencaempreendimento;
$cllicencaempreendimento->rotulo->label("am08_sequencial");
$cllicencaempreendimento->rotulo->label("am08_datavencimento");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label><?=$Lam08_sequencial?></label></td>
          <td><? db_input("am08_sequencial",10,$Iam08_sequencial,true,"text",4,"","chave_am08_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lam08_datavencimento?></label></td>
          <td><? db_input("am08_datavencimento",10,$Iam08_datavencimento,true,"text",4,"","chave_am08_datavencimento");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_licencaempreendimento.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_licencaempreendimento.php")==true){
             include("funcoes/db_func_licencaempreendimento.php");
           }else{
           $campos = "licencaempreendimento.*";
           }
        }
        if(isset($chave_am08_sequencial) && (trim($chave_am08_sequencial)!="") ){
	         $sql = $cllicencaempreendimento->sql_query($chave_am08_sequencial,$campos,"am08_sequencial");
        }else if(isset($chave_am08_datavencimento) && (trim($chave_am08_datavencimento)!="") ){
	         $sql = $cllicencaempreendimento->sql_query("",$campos,"am08_datavencimento"," am08_datavencimento like '$chave_am08_datavencimento%' ");
        }else{
           $sql = $cllicencaempreendimento->sql_query("",$campos,"am08_sequencial","");
        }
        $repassa = array();
        if(isset($chave_am08_datavencimento)){
          $repassa = array("chave_am08_sequencial"=>$chave_am08_sequencial,"chave_am08_datavencimento"=>$chave_am08_datavencimento);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllicencaempreendimento->sql_record($cllicencaempreendimento->sql_query($pesquisa_chave));
          if($cllicencaempreendimento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$am08_datavencimento',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_am08_datavencimento",true,1,"chave_am08_datavencimento",true);
</script>
