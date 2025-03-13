<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_certdiv_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcertdiv = new cl_termoinscrreg;
$clcertdiv->rotulo->label("v93_termo");
$clcertdiv->rotulo->label("v93_coddiv");
$clcertdiv->rotulo->label("v93_coddiv");
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
            <td width="4%" align="right" nowrap title="<?=$Tv93_termo?>">
              <?=$Lv93_termo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v93_termo",4,$Iv93_termo,true,"text",4,"","chave_v93_termo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tv93_coddiv?>">
              <?=$Lv93_coddiv?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v93_coddiv",4,$Iv93_coddiv,true,"text",4,"","chave_v93_coddiv");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        $campos = "distinct(v93_termo), z01_numcgm, z01_nome, v92_dtinsc";
        if(isset($chave_v93_termo) && (trim($chave_v93_termo)!="") ){
	         $sql = $clcertdiv->sql_query_deb($chave_v93_termo,$chave_v93_coddiv,$campos,"v93_termo","v01_instit = ".db_getsession('DB_instit')." and v92_instit =  ".db_getsession('DB_instit'));
        }else if(isset($chave_v93_coddiv) && (trim($chave_v93_coddiv)!="") ){
	         $sql = $clcertdiv->sql_query_deb("","",$campos,"v93_termo"," v93_coddiv like '$chave_v93_coddiv%' and v01_instit = ".db_getsession('DB_instit')." and v92_instit =  ".db_getsession('DB_instit'));
        }else{
           $sql = $clcertdiv->sql_query_deb("","",$campos,"v93_termo"," v01_instit = ".db_getsession('DB_instit')." and v92_instit =  ".db_getsession('DB_instit'));
        }
				db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        $result = $clcertdiv->sql_record($clcertdiv->sql_query($pesquisa_chave),"","",""," v01_instit = ".db_getsession('DB_instit')." and v92_instit =  ".db_getsession('DB_instit'));
        if($clcertdiv->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$v93_coddiv',false);</script>";
        }else{
	       echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
document.form2.chave_v93_termo.focus();
document.form2.chave_v93_termo.select();
  </script>
  <?
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
