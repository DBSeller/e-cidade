 /*
  * @fileoverview Esse arquivo cria um componente com um campo de codigo de barras
  * 
  * será possivel passar o label, limite de caracteres e a mensagem.
  *
  * var oDBCodigoBarra = new DBCodigoBarra("Instancia do OBJ");
	    oDBCodigoBarra.setLabel("Lable do campo:"); 
	    oDBCodigoBarra.setMaximoDigito(tamanhodo padrao do codigo de barra);
	    oDBCodigoBarra.setMensagemLeitura('msgm de aguardadndo leitura');
	    oDBCodigoBarra.criaComponentes();
	    oDBCodigoBarra.show('container a ser exibido');
  *
  * @author Rafael Lopes rafael.lopes@dbseller.com.br
  * @author Bruno silva bruno.silva@dbseller.com.br
  * @version 1.0 $
  */

DBCodigoBarra = function (sNomeCampo, sNameInstance) {
	
  this.sLabel 		         = "Código de Barras:";
  this.sMensagemLeitura    = "Aguardando Leitura do Código de Barras";
  this.sNameInstance       = sNameInstance;
  this.sAtributosBotao     = "";
  this.iTamanhoCampo       = 50;
  this.iMaximoDigito       = 44;
  this.sEstiloCssLeitura   = "background-color: rgb(222, 184, 135); color: black";
  this.sNomeCampo          = sNomeCampo;
  this.fCallbackAposLeitura = function () {};
  this.fCallbackInicioLeitura = function() {};
  this.setMensagemLeitura = function(sMensagemLeitura) {
	  this.sMensagemLeitura = sMensagemLeitura;
  };
  
  this.setMaximoDigito = function(iMaximoDigito){
	  this.iMaximoDigito = iMaximoDigito;
  };
  
  this.setLabel = function(sLabel){
	  this.sLabel = sLabel;
  };
  
  this.setTamanhoCampo = function(iTamanhoCampo){
	  this.iTamanhoCampo = iTamanhoCampo;
  };
  
  this.setAtributosBota = function(sAtributos)  {
	  this.sAtributosBotao = sAtributos;  
  };

  
  this.criaComponentes = function() {
	  
	  sConteudo  = "<td><label id='ctnLabelCodigoBarra'><strong>" + this.sLabel + "</strong></label></td>";
	  sConteudo += "<td><label id='ctnCodigoBarra' ></label>";
	  sConteudo += "<input id='btnCodigoBarra' type='button' value='Cadastrar' onclick = '"+ this.sNameInstance+".liberarCodigoDeBarra();' "+ this.sAtributosBotao +" />";
	  sConteudo += "</td>";
	  this.sConteudo = sConteudo;
  };
  
  
  this.show = function (sContainer) {
	  
	  $(sContainer).innerHTML    = this.sConteudo;
	  oTxtCodigoBarra            = new DBTextField(this.sNomeCampo,'oTxtCodigoBarra', null, this.iTamanhoCampo);
	  oTxtCodigoBarra.addEvent("onKeyPress", "return js_mask(event,\"0-9\")");
	  oTxtCodigoBarra.setReadOnly(true);
	  oTxtCodigoBarra.addEvent("onKeyUp", this.sNameInstance+".lerCodigo(event)");
	  oTxtCodigoBarra.addEvent("onKeyDown", this.sNameInstance+".bloquearTab(event)");
	  
	  oTxtCodigoBarra.show($('ctnCodigoBarra'));
  };
  
  /**
   * Libera o input do código de barra para que seja inserido valores
   */
  this.liberarCodigoDeBarra = function() {
	
    this.fCallbackInicioLeitura();
    oTxtCodigoBarra.setValue('');
	  oTxtCodigoBarra.setReadOnly(false);
	  $(this.sNomeCampo).setAttribute("style", this.sEstiloCssLeitura);
	  
    var sNomeCampo = this.sNomeCampo;
	  $(sNomeCampo).focus();
    js_divCarregando(this.sMensagemLeitura, 'msgBox');

    /**
     * Ao clicar na div criada pela funcao js_divCarregando retorna o focu para o campo
     */
    $('msgBoxmodal').onclick = function(event) { 
      $(sNomeCampo).focus();
      return false; 
    }
  };
  
  /**  
   * Bloqueia a tecla tab para que, após o usuário clicar no botão, não permita sair do foco do campo input
   */
  this.bloquearTab = function(event) {
    
    if (event.which == 9) {
      
      event.preventDefault();
      event.stopPropagation();
      return false;
    };
  };
  
  this.lerCodigo = function(event) {
    
    var oSelf = this;
    
	  if (event.keyCode == 27) { 
	  	
	  	js_removeObj("msgBox");
	  	$(this.sNomeCampo).value = '';
	  	oTxtCodigoBarra.setReadOnly(true);
	  }
	  
	  var iTotalCaractereCodigoBarra = $('txtCodigoBarra').value.length;
	  
     if (iTotalCaractereCodigoBarra == this.iMaximoDigito) {
	  
	    if (event.keyCode == 13) { 
	    	
	    	oTxtCodigoBarra.setReadOnly(true);
	  	  js_removeObj("msgBox");
	  	  oSelf.fCallbackAposLeitura(oSelf.processarCodigoDeBarra());
	    }
	  } 
	  if (event.keyCode == 13 && iTotalCaractereCodigoBarra > this.iMaximoDigito ) {
	  	
	  	$(this.sNomeCampo).value = '';
	  	js_removeObj("msgBox");
	  	js_divCarregando("Codigo de barra invalido, tente novamente ou pressione ESC para sair.", 'msgBox');
	  	oTxtCodigoBarra.setReadOnly(false);
	  	$(this.sNomeCampo).setAttribute("style", this.sEstiloCssLeitura);
	  }
  };
};

/**
 * Função de callback para o componente executar após ler o código de barras com sucesso
 * @param fFunction
 */
DBCodigoBarra.prototype.setCallBackAposLeitura = function(fFunction) {
  this.fCallbackAposLeitura = fFunction;
};

DBCodigoBarra.prototype.setCallBackInicioLeitura = function(fFunction) {
  this.fCallbackInicioLeitura = fFunction;
};

DBCodigoBarra.prototype.processarCodigoDeBarra = function() {

   var sCodigoBarra = oTxtCodigoBarra.getValue();
   var iTipoBarra   = sCodigoBarra.substr(0,1) == '8' ? 2 : 1;
   var sData        = '';
   var sValor       = new Number(sCodigoBarra.substr(4, 11)) / 100 ;
   if (iTipoBarra == 1) {

     sValor = new Number(sCodigoBarra.substr(9, 10)) / 100 ; 
     /**
      * Data base para somar com os dias que vem no codigo de barras
      * - 07/10/1997, no javascript os meses comecao em 0
      */
     var oDataInicial = new Date(1997, 9, 7);
     var iNumeroDias  = new Number(sCodigoBarra.substr(5, 4));
     
     oDataInicial.setDate(oDataInicial.getDate() + iNumeroDias);
     var sDia = js_strLeftPad(oDataInicial.getDate(), 2, '0');
     var sMes = js_strLeftPad(oDataInicial.getMonth() + 1, 2, '0');
     sData = oDataInicial.getFullYear()+"-"+ sMes +"-"+ sDia;  
   } 

   var oRetorno = {
     tipo : iTipoBarra,
     data_pagamento: sData,
     valor: sValor 
   }

   return oRetorno;
}                            
