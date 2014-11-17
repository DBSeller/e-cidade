<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhfundamentacaolegal_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhfundamentacaolegal = new cl_rhfundamentacaolegal;
$clrhfundamentacaolegal->rotulo->label("rh137_numero");
$clrhfundamentacaolegal->rotulo->label("rh137_descricao");
$clrhfundamentacaolegal->rotulo->label("rh137_sequencial");

if ( isset($chave_rh137_sequencial) && !DBNumber::isInteger($chave_rh137_sequencial) ) {
  $chave_rh137_sequencial = '';
}

if ( isset($chave_rh137_numero) && !DBNumber::isInteger($chave_rh137_numero) ) {
  $chave_rh137_numero = '';
}

$chave_rh137_descricao = isset($chave_rh137_descricao) ? stripslashes($chave_rh137_descricao) : '';

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Trh137_sequencial?>">
              <?=$Lrh137_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
           db_input("rh137_sequencial",11,$Irh137_sequencial,true,"text",4,"","chave_rh137_sequencial");
           ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Trh137_numero?>">
              <?=$Lrh137_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("rh137_numero",11,$Irh137_numero,true,"text",4,"","chave_rh137_numero");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Trh137_descricao?>">
              <?=$Lrh137_descricao?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("rh137_descricao",50,$Irh137_descricao,true,"text",4,"","chave_rh137_descricao");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhfundamentacaolegal.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $chave_rh137_descricao = addslashes($chave_rh137_descricao);

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhfundamentacaolegal.php")==true){
             include("funcoes/db_func_rhfundamentacaolegal.php");
           }else{
           $campos = "rhfundamentacaolegal.*";
           }
        }

        if( isset($chave_rh137_sequencial) ){
          if (  !DBNumber::isInteger($chave_rh137_sequencial) ) {
            $chave_rh137_sequencial = '';
          }
        }

        if( isset($chave_rh137_numero) ){
          if (  !DBNumber::isInteger($chave_rh137_numero) ) {
            $chave_rh137_numero = '';
          }
        }

        $sAliasDocumentos  =" CASE rh137_tipodocumentacao        ";
        $sAliasDocumentos .="WHEN 1 THEN 'Decreto'              ";
        $sAliasDocumentos .="WHEN 2 THEN 'Decreto Lei'          ";
        $sAliasDocumentos .="WHEN 3 THEN 'Emenda Constitucional'";
        $sAliasDocumentos .="WHEN 4 THEN 'Instrução Normativa'  ";
        $sAliasDocumentos .="WHEN 5 THEN 'Lei'                  ";
        $sAliasDocumentos .="WHEN 6 THEN 'Medida Provisória'    ";
        $sAliasDocumentos .="WHEN 7 THEN 'Nota'                 ";
        $sAliasDocumentos .="WHEN 8 THEN 'Ordem de Serviço'     ";
        $sAliasDocumentos .="WHEN 9 THEN 'Portaria'             ";
        $sAliasDocumentos .="WHEN 10 THEN 'Resolução'           ";
        $sAliasDocumentos .="END as rh137_tipodocumentacao      ";

        if (isset($chave_rh137_sequencial) && (trim($chave_rh137_sequencial)!="" && DBNumber::isInteger($chave_rh137_sequencial) ) ){
           $sql = $clrhfundamentacaolegal->sql_query($chave_rh137_sequencial,$campos.$sAliasDocumentos,"rh137_sequencial","rh137_sequencial = $chave_rh137_sequencial");
        }else if(isset($chave_rh137_numero) && (trim($chave_rh137_numero)!=""  && DBNumber::isInteger($chave_rh137_numero)) ){
           $sql = $clrhfundamentacaolegal->sql_query("",$campos.$sAliasDocumentos,"rh137_numero","rh137_numero = $chave_rh137_numero");
        }else if(isset($chave_rh137_descricao) && (trim($chave_rh137_descricao)!="") ){
	         $sql = $clrhfundamentacaolegal->sql_query("",$campos.$sAliasDocumentos,"rh137_descricao"," rh137_descricao ilike '$chave_rh137_descricao%' ");
        }else{
           $sql = $clrhfundamentacaolegal->sql_query("",$campos.$sAliasDocumentos,"rh137_sequencial","");
        }

        if( isset($chave_rh137_descricao) ){
          $chave_rh137_descricao = str_replace("\\", "", $chave_rh137_descricao);
        }
        $repassa = array();
        if(isset($chave_rh137_sequencial)){
          $repassa = array("chave_rh137_sequencial"=>$chave_rh137_sequencial,
                           "chave_rh137_numero"=>$chave_rh137_numero,
                           "chave_rh137_descricao"=>$chave_rh137_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhfundamentacaolegal->sql_record($clrhfundamentacaolegal->sql_query($pesquisa_chave));
          if($clrhfundamentacaolegal->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh137_numero','$rh137_descricao',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
   (function(){

       if( document.getElementById('$chave_rh137_sequencial').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('$chave_rh137_sequencial').value ) ) {
          alert('Código Fundamentação Legal deve ser preenchido somente com números!');
          document.getElementById('$chave_rh137_sequencial').value = '';
          return false;
        }
      }

      if( document.getElementById('$chave_rh137_numero').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('$chave_rh137_numero').value ) ) {
          alert('Campo Número deve ser preenchido somente com números!');
          document.getElementById('$chave_rh137_numero').value = '';
          return false;
        }
      }

    })();
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_rh137_sequencial",true,1,"chave_rh137_sequencial",true);
</script>
