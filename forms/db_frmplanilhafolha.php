<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

$clrotulo  = new rotulocampo;
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");

?>
<center>
	<form name="form1" method="post" action="">
  <input type="hidden" value="<?= isset($DB_COMPLEMENTAR) ? "1" : "0"; ?>" id="db_complementar" name = "db_complementar" >
	<table>
	  <tr>
	  <td>
	  <fieldset>
	    <legend align="left">
	      <b>Gerar Planilha</b>
	    </legend>
	    <table align="center">
			  <tr>
			    <td align="left" nowrap>
			      <b>Ano / Mês :</b>
			    </td>
			    <td>
			      <?
			        $anofolha = db_anofolha();
			        db_input('anofolha',4,$IDBtxt23,true,'text',2,"onkeyup='js_ValidaCampos(this,1,\"Ano/Mês\",\"\",\"\",event);'");
			      ?>
			      &nbsp;/&nbsp;
			      <?
			        $mesfolha = db_mesfolha();
			        db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onkeyup='js_ValidaCampos(this,1,\"Ano/Mês\",\"\",\"\",event);'");
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td>
			      <b>Ponto:</b>
			    </td>
			    <td>
			     <?
			     
			       $aSigla = array( "r14"=>"Salário",
					                    "r48"=>"Complementar",
					                    "r35"=>"13o. Salário",
					                    "r20"=>"Rescisão",
					                    "r22"=>"Adiantamento");
			       
			       db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto()'; style='width:109px';");
			     ?>
			    </td>
		    </tr>
		    <tr id='linhaComplementar' style='display:none'>
		    </tr>
        <tr>
          <td nowrap title="<?=@$Tz01_numcgm?>">
            <?
            db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",1);
            ?>        
          </td>
          <td  colspan='3'> 
            <?
             db_input('z01_numcgm',12,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);' onkeyup='js_ValidaCampos(this,1,\"Credor\",\"\",\"\",event);'");
             db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');
            ?>
          </td>
        </tr>
		  </table> 
	  </fieldset>
	  </td>
	  </tr>
	  </table>
	  <table>  
		  <tr>
		    <td align = "center"> 
		      <input name="gerar" id="gerar" type="button" value="Gerar" onClick="js_gerarPlanilha();">
		    </td>
		  </tr>
	  </table>
	</form>
	<div style='width:50%; display: none' id='linhaRescisoes'>
          <fieldset>
            <legend>Rescisões</legend>
            <div id='ctnGridRescisoes'> 
          </fieldset>
        </div>
</center>	
<script>

var sCaminhoMensagem = 'recursoshumanos.pessoal.db_frmplanilhafolha.';

/*
 Valida para não deixar colar letras nos campos numéricos
 */

$('mesfolha').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

$('anofolha').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

$('z01_numcgm').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

			     
$('mesfolha').maxLength = 2;
$('anofolha').maxLength = 4;

 var sUrl = 'pes1_rhempenhofolhaRPC.php';
  
 function js_consultaPontoComplementar(){
 
   js_divCarregando('Consultando ponto complementar...','msgBox');
   js_bloqueiaTela(true);
 

   if ($F("db_complementar") == "1"){
    var sQuery  = 'sMethod=consultaComplementaresFechadas';
   } else {
    var sQuery  = 'sMethod=consultaPontoComplementar';
        sQuery += '&sSigla='+$F('ponto');
   }
   sQuery +='&iAnoFolha=' + $F('anofolha');
   sQuery +='&iMesFolha=' + $F('mesfolha');
 
    var oAjax   = new Ajax.Request( sUrl, {
                                             method: 'post',
                                             parameters: sQuery,
                                             onComplete: js_retornoPontoComplementar
                                           }
                                   );
 
 }

 function js_retornoPontoComplementar(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
   
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
    
  
   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   }

   var sLinha          = "";
   var iLinhasSemestre = aRetorno.aSemestre.length;
   
   if ( iLinhasSemestre > 0 ) {
   
   
     sLinha += " <td align='left' title='Nro. Complementar'> ";
     sLinha += "   <strong>Nro. Complementar:</strong>       ";
     sLinha += " </td>                                       ";
     sLinha += " <td>                                        ";
     sLinha += "   <select id='semestre' name='semestre'>    ";
     
     for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {

      var oSemestre = aRetorno.aSemestre[iInd];
      
      if ($F("db_complementar") == "1"){
        sLinha += " <option value = '"+oSemestre.rh141_codigo+"'>"+oSemestre.rh141_codigo+"</option>";
      } else {
        sLinha += " <option value = '"+oSemestre.semestre+"'>"+oSemestre.semestre+"</option>";
      }
     }
     
     sLinha += " </td>                                       ";
   
   } else {
   
     sLinha += " <td colspan='2' align='center'>                                ";
     sLinha += "   <font color='red'>Sem complementar para este período.</font> ";
     sLinha += " </td>                                                          ";
   
   }
   
   $('linhaComplementar').innerHTML     = sLinha;
   $('linhaComplementar').style.display = '';

 }

 function js_validaTipoPonto(){
 
   if ( $F('ponto') == 'r48') {
   
     $('linhaRescisoes').style.display = 'none';
     js_consultaPontoComplementar();     
   } else if ($F('ponto') == 'r20') {
	   $('linhaComplementar').style.display = 'none';
     js_getRescisoes();   
   } else {
     
     $('linhaRescisoes').style.display = 'none';
     $('linhaComplementar').style.display = 'none';
   }
   
 }
 
 
 function js_gerarPlanilha(){

   if ($F('mesfolha') == '') {
    alert(_M( sCaminhoMensagem + 'campo_obrigatorio', {sCampo: 'Ano/Mês'}));
    $('mesfolha').focus();
    $('mesfolha').value = '';
    return false;
  }

  if ($F('anofolha') == '') {
    alert(_M( sCaminhoMensagem + 'campo_obrigatorio', {sCampo: 'Ano/Mês'}));
    $('anofolha').focus();
    $('anofolha').value = '';
    return false;
  }

	 if ($F('ponto') == 'r48') {
		 if (!$('semestre') || $F('semestre') == "0") {
			 alert(_M( sCaminhoMensagem + 'complementar_aberto' ));
			 return false;
		 } 
	 }

   if ($F('anofolha') == 0000) {
     
     alert(_M( sCaminhoMensagem + 'ano_invalido' ));
     $('anofolha').focus();
     $('anofolha').value = '';
     return false;
   }

   if ($F('mesfolha') > 12) {
     
     alert(_M( sCaminhoMensagem + 'mes_invalido' ));
     $('mesfolha').focus();
     $('mesfolha').value = '';
     return false;
   }
   
   if ($F('ponto') == 'r20') {
     
     var sListarescisoes = ""; 
     var aRescisoes      = oGridrescisoes.getSelection("object")
     var sVirgula        = "";
     if (oGridrescisoes.getSelection().length == 0) {
   
       alert( _M( sCaminhoMensagem + 'rescisao' ));
       return false;
      }
    }

    if ($F('ponto') == 'r14' && $F("db_complementar") == "1"){
      
      require_once('scripts/classes/DBViewFormularioFolha/ValidarFolhaPagamento.js');
      var iMesFolha = $F('mesfolha'); 
      var iAnoFolha = $F('anofolha');
      
      var oFolhaComplementar = new DBViewFormularioFolha.ValidarFolhaPagamento();
      var lFolhaComplementar = oFolhaComplementar.verificarFolhaPagamentoAberta(oFolhaComplementar.TIPO_FOLHA_SALARIO, iAnoFolha, iMesFolha);
      
      if (lFolhaComplementar == true){
      
        alert( _M( sCaminhoMensagem + 'folha_salario_fechada'));
        return false;
      }
    }

    js_divCarregando(' Aguarde ...','msgBox');
    js_bloqueiaTela(true); 
          
    var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: js_getQueryTela('geraPlanilha'), 
                                            onComplete: js_retornoGerarPlanilha
                                          }
                                  );         
 
 } 
 
 function js_retornoGerarPlanilha(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);
  
   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');
  
   alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
   
   if ( aRetorno.lErro ) {
     return false;
   } else {
     var sQuery  = 'sListaPla='+aRetorno.sListaPla;
     jan = window.open('cai2_emiteplanilha002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');   
   }

 } 

 
 function js_bloqueiaTela(lBloq){
 
   if ( lBloq ) {
     $('anofolha').disabled = true;         
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;
     $('gerar').disabled    = true;
     
     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = true;
       } 
     }     
     
   } else {
     $('anofolha').disabled = false;         
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;
     $('gerar').disabled    = false;
     
     if ($F('ponto') == 'r48') {
       if ($('semestre')) {
         $('semestre').disabled = false;
       }
     }
        
   }
 
 }
 
 function js_getQueryTela(sMethod){
 
   var sQuery  = 'sMethod='+sMethod;
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sSigla='+$F('ponto');
       sQuery += '&iCgm='+$F('z01_numcgm');
        
   if ( $F('ponto') == 'r48' ) {
     if ($('semestre')) {
       sQuery += '&sSemestre='+$F('semestre');
     }
   }
   if ($F('ponto') == 'r20') {
     
       var sListarescisoes = ""; 
       var aRescisoes      = oGridrescisoes.getSelection("object")
       var sVirgula        = "";
       if (oGridrescisoes.getSelection().length == 0) {
     
         alert(_M( sCaminhoMensagem + 'rescisao' ));
         return false;
     } else {
       aRescisoes.each(function(oRescisao, id) {
       
         sListarescisoes += sVirgula+oRescisao.aCells[0].getValue();
         sVirgula  = ",";
       });
     }
     sQuery += "&sRescisoes="+sListarescisoes;       
   }          
   return sQuery;    
 
 }
 
  function js_geraSlip() {
   
   js_OpenJanelaIframe('top.corpo',
                       'db_iframe_geraslip',
                       'pes1_rhgeralistaslip001.php?'+js_getQueryTela(''),
                       'Gera SLIP - '+$F('mesfolha')+'/'+$F('anofolha'),
                       true,
                       25,
                       (document.width-(document.width-200))/2,
                       document.width-200,
                       600
                       );
                       
  }
   function js_getRescisoes() {
   
   $('linhaRescisoes').style.display = '';
   js_divCarregando('Pesquisando Rescisoes','msgBox');
   js_bloqueiaTela(true); 
   var sQuery  = 'sMethod=getRescisoesPlanilhas';
       sQuery += '&iAnoFolha='+$F('anofolha');
       sQuery += '&iMesFolha='+$F('mesfolha');
       sQuery += '&sSigla='+$F('ponto');  
       sQuery += '&iCgm='+$F('z01_numcgm');  
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoGetRescisoes
                                          }
                                  );    
 
 }
 
 function js_retornoGetRescisoes(oAjax) {
 
   js_removeObj('msgBox');
   js_bloqueiaTela(false);
   oGridrescisoes.clearAll(true);
   var oRetorno = eval("("+oAjax.responseText+")");
   oRetorno.sListaRescisoes.each(function (oRescisao, id) {
   
      var aLinha = new Array();
      aLinha[0]  = oRescisao.seqpes;
      aLinha[1]  = oRescisao.matricula;
      aLinha[2]  = oRescisao.nome.urlDecode();  
      aLinha[3]  = js_formatar(oRescisao.datarescisao,'d');
      oGridrescisoes.addRow(aLinha);  
   });
   oGridrescisoes.renderRows();
 }
 function js_montaGrid() {
 
   oGridrescisoes     = new DBGrid('gridRescisoes');
   oGridrescisoes.nameInstance = "oGridrescisoes";
   oGridrescisoes.setCheckbox(0);
   oGridrescisoes.setCellAlign(new Array("right","right","Left","center"));
   oGridrescisoes.setCellWidth(new Array("4%","20%","66%","20%"));
   oGridrescisoes.setHeader(new Array("Seq","Mátricula","Nome","Data"));
   oGridrescisoes.show($('ctnGridRescisoes'));
 }
 js_montaGrid(); 
 
 function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if($('z01_numcgm').value != ''){ 
        js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+$('z01_numcgm').value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       $('z01_nome').value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  $('z01_nome').value = chave; 
  if(erro==true){ 
    $('z01_numcgm').focus(); 
    $('z01_numcgm').value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  $('z01_numcgm').value = chave1;
  $('z01_nome').value = chave2;
  func_nome.hide();
}
 
</script>