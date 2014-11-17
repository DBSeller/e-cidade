<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$oDaoParam    = db_utils::getDao("licitaparam");
$lSelecionaPc = 'f';
$rsParam      = $oDaoParam->sql_record($oDaoParam->sql_query_file(db_getsession("DB_instit")));
if ($oDaoParam->numrows > 0) {

  $oParam    = db_utils::fieldsMemory($rsParam, 0);
  if ($oParam->l12_escolherprocesso == 't') {
    $lSelecionaPc = 't';
  }
  
}
$db_botao  = true;
$clrotulo  = new rotulocampo;
$clrotulo->label("l20_codigo");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<br />
<br />
<center>
<form name="form1" method="post" action="lic4_geraautorizacaoprocessos.php">
  <fieldset style="width: 300px;">
  <legend><b>Gera Autorização</b></legend>
    <table  align="center">
      <tr>
        <td  align="left" nowrap title="<?=$Tl20_codigo?>">
          <b>
            <?db_ancora('Licitação',"js_pesquisa_liclicita(true);",1);?>:
          </b> 
        </td>
        <td align="left" nowrap>
          <?
            db_input("l20_codigo",6,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Escolher Processo de Compras:</b>
        </td>
        <td>
          <?
            $aValores = array('t' => "Sim",'f' => "Não");
            db_select("lSelecionaPc",$aValores, true,1);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <p align="center">
    <input  name="emite2" id="emite2" type="submit" value="Processar" <?=($db_botao == true?"disabled":"")?>>
  </p>
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?lContratos=1&situacao=1&validasaldo=1&funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?lContratos=1&situacao=1&validasaldo=1&pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  if(erro==true){ 
    document.form1.emite2.disabled  = true;
    alert("Licitacao ja julgada,revogada ou com autorizacao ativa.");
    document.form1.l20_codigo.value = ''; 
    document.form1.l20_codigo.focus(); 
  }else{
    document.form1.l20_codigo.value = chave; 
    document.form1.emite2.disabled  = false;

	}
}
function js_mostraliclicita1(chave1){

   document.form1.l20_codigo.value = chave1;  
   document.form1.emite2.disabled  = false;
   db_iframe_liclicita.hide();

}
</script>
<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>