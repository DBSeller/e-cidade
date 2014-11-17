<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_conhistdoc_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

require_once("classes/db_empage_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empagemov_classe.php");
require_once("classes/db_empord_classe.php");
require_once("classes/db_empagepag_classe.php");
require_once("classes/db_empageslip_classe.php");
require_once("classes/db_empagemovforma_classe.php");
require_once("classes/db_empagegera_classe.php");
require_once("classes/db_empageconf_classe.php");
require_once("classes/db_empageconfgera_classe.php");
require_once("classes/db_conplanoconta_classe.php");
require_once("classes/db_empagemod_classe.php");
require_once("classes/db_db_bancos_classe.php");

db_postmemory($HTTP_POST_VARS);
$clempagegera     = new cl_empagegera;
$clempageconf     = new cl_empageconf;
$clempageconfgera = new cl_empageconfgera;
$clrotulo         = new rotulocampo;
$clempagetipo     = new cl_empagetipo;
$clempagegera->rotulo->label();
$clempagetipo->rotulo->label();

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("DBLancador.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("classes/dbViewAvaliacoes.classe.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");
//c60_descr
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script src="scripts/widgets/DBAncora.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


<center>

<form name="form1" method="post" action="">

	<fieldset style="margin-top: 50px; width: 700px;">

	<legend><strong>Regerar Arquivo OBN</strong></legend>

		<table border='0' align='left'>
		  <tr> 
		    <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
		    <td align="left" nowrap>
				  <?
				     db_input("e87_codgera",10,$Ie87_codgera,true,"text",1,"onchange='js_pesquisa_gera(false);'"); 
				     db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
				  ?>
		    </td>
		  </tr>
		  <tr>
		    <td align='left'>
		      <b>Data geração:</b>
		    </td>
		    <td>
		      <?
		      if(!isset($dtin_dia)){
		        $dtin_dia = date('d',db_getsession('DB_datausu'));
		      }
		      if(!isset($dtin_mes)){
		        $dtin_mes = date('m',db_getsession('DB_datausu'));
		      }
		      if(!isset($dtin_ano)){
		        $dtin_ano = date('Y',db_getsession('DB_datausu'));
		      }
		      db_inputdata('dtin',@$dtin_dia,@$dtin_mes,@$dtin_ano,true,'text',1);
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td align='left'>
		      <b>Autoriza pgto.:</b>
		    </td>
		    <td>
		      <?
		        db_inputdata('deposito',@$deposito_dia,@$deposito_mes,@$deposito_ano,true,'text',1);
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center"><br>
		    </td>
		  </tr>
		</table>

</fieldset>

<div style="margin-top: 10px;">
  <input name="imprimir" type="button" id="imprimir" value="Imprimir Arquivo" onclick="js_regerarArquivo();">
</div>

</form>

</center>


<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
var sUrlRPC = "cai4_arquivoBanco004.RPC.php";

function js_regerarArquivo(){

  var iCodGera   = $F("e87_codgera");
  var dtGeracao  = $F("dtin");
  var dtAutoriza = $F("deposito");


  if ( iCodGera == '' ) {

	  alert('Selecione um arquivo.');
	  return false;
	} 
  
	var oParametros        = new Object();
	var msgDiv             = "Regerando arquivo.<br>Aguarde ...";
	oParametros.exec       = 'regerarArquivoObn';
	oParametros.iCodGera   = iCodGera  ;
	oParametros.dtGeracao  = js_formatar(dtGeracao , 'd') ;
	oParametros.dtAutoriza = js_formatar(dtAutoriza, 'd');

	js_divCarregando(msgDiv,'msgBox');

	new Ajax.Request(sUrlRPC,
	                        {method: "post",
	                         parameters:'json='+Object.toJSON(oParametros),
	                         onComplete: js_retornoRegerarArquivo
	                        });
}
function js_retornoRegerarArquivo(oAjax) {

	  js_removeObj('msgBox');
	  var oRetorno = eval("("+oAjax.responseText+")");

	  if (oRetorno.iStatus == '1') {
		  
		  var pArquivo = oRetorno.sArquivo+"# Download do Arquivo - "+ oRetorno.sArquivo;
	    js_montarlista(pArquivo, 'form1'); 
		} else {
	    alert(oRetorno.sMessage.urlDecode());
		}
	  
}



	

//======================================pesquisa arquivo
function js_pesquisa_gera(mostra){
	  if(mostra==true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa de Arquivos Gerados',true);
	  }else{
	     if(document.form1.e87_codgera.value != ''){ 
	        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
	     }else{
	       document.form1.e87_descgera.value = ''; 
	     }
	  }
	}
	function js_mostragera(chave,erro){
	  document.form1.e87_descgera.value = chave; 
	  if(erro==true){ 
	    document.form1.e87_codgera.focus(); 
	    document.form1.e87_codgera.value = ''; 
	  }
	}
	function js_mostragera1(chave1,chave2){
	  document.form1.e87_codgera.value = chave1;
	  document.form1.e87_descgera.value = chave2;
	  db_iframe_empagegera.hide();
	}
	//--------------------------------

</script>