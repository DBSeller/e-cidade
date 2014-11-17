
/**
 * Classe responsavel por instanciar os comportamentos padrão para Ancora informada como parametro
 * @param Object oAncora         Ancora para lookUp
 * @param Object oInputID        Input com a informação do ID
 * @param Object oInputDescricao Input com a informação da Descrição
 * @param Object oParametros     Parametros opcionais
 */
var DBLookUp = function (oAncora, oInputID, oInputDescricao, oParametros) {
  
  /**
   * Define valor padrão
   */
 
  oParametros = oParametros || {};
  
  this.iReferencia     = DBLookUp.repository.addInstance(this);

  this.oAncora         = oAncora;
  this.oInputID        = oInputID;
  this.oInputDescricao = oInputDescricao;
  this.oParametros     = {
    "sArquivo"           : oParametros.sArquivo          || null,
    "sLabel"             : oParametros.sLabel            || "Pesquisar",
    "sQueryString"       : oParametros.sQueryString      || "",
    "sDestinoLookUp"     : oParametros.sDestinoLookUp    || "",
    "sObjetoLookUp"      : oParametros.sObjetoLookUp     || "db_pesquisa",
    "aCamposAdicionais"  : oParametros.aCamposAdicionais || [],
    "sCallBack"          : oParametros.sCallBack         || null 
  };

this.oCallback  = {
  onChange         : function(lErro) {},
  onClick          : function() {},
};
  this.__init();
};


/**
 * Função __init para alterar os elementos necessários
 * para o comportamento da LookUp
 */
DBLookUp.prototype.__init = function() {

  /**
   * Modifica a ancora
   */
   this.oAncora.className         += "DBAncora bold";
   this.oAncora.href               = "javascript:void(0)";

   /**
    * Modifica os Inputs
    */
   this.oInputID.className        += "field-size2"; 
   this.oInputID.oInstancia        = this;
   this.oInputDescricao.className += "field-size8";
   this.oInputDescricao.className += " readOnly";
   this.oInputDescricao.readOnly   = true;
   this.oInputDescricao.oInstancia = this;

   this.oAncora .addEventListener('click', this.eventFunctions.click.bind(this));
   this.oInputID.onchange = this.eventFunctions.change.bind(this);
};

DBLookUp.prototype.eventFunctions =

/**
 * Realiza o tratamento dos eventos 
 * Click e Change
 */
DBLookUp.prototype.eventFunctions = { 

  click: function(click){
    this.abrirJanela(true);
  },
  change: function(change){
    
    if ( this.oInputID.value == "" ) {
      this.oInputDescricao.value = "";
      change.preventDefault();
      return false;
    }
    this.abrirJanela(false);
  }
};

/**
 * Monta a QueryString para quando é 
 * feito o click na ancora
 * @return String QueryString
 */
DBLookUp.prototype.getQueryStringClick = function() {

  var sQuery  = "";
      sQuery += this.oParametros.sArquivo;
      sQuery += "?";
      sQuery += "funcao_js=parent.DBLookUp.repository.getInstance("+this.iReferencia+").callBackClick";
      sQuery += "|" + this.oInputID.lang;
      sQuery += "|" + this.oInputDescricao.lang;
  
  var sCampos = this.oParametros.aCamposAdicionais.join("|");

  if ( sCampos ) {
    sQuery += "|" + sCampos;
  } 

  sQuery += this.oParametros.sQueryString ? "|" + this.oParametros.sQueryString : "";

  return sQuery; 
}

/**
 * Monta a QueryString para quando é executado 
 * o Change no objeto oInputID
 * @return String QueryString
 */
DBLookUp.prototype.getQueryStringChange = function() {

  var sQuery  = "";
      sQuery += this.oParametros.sArquivo;
      sQuery += "?";
      sQuery += "pesquisa_chave=" + this.oInputID.value;
      sQuery += "&";
      sQuery += "funcao_js=parent.DBLookUp.repository.getInstance("+this.iReferencia+").callBackChange";
  
  var sCampos = this.oParametros.aCamposAdicionais.join("|");

  if ( sCampos ) {
    sQuery += "|" + sCampos;
  } 

  sQuery += this.oParametros.sQueryString;

  return sQuery; 
}

/**
 * Trata o retorno do click na Ancora
 * @param  integer iCodigo
 * @param  string sDescricao
 */
DBLookUp.prototype.callBackClick = function(iCodigo, sDescricao) {

  this.oInputID.value        = iCodigo;
  this.oInputDescricao.value = sDescricao;
  var oObjetoLookUp          = eval(this.oParametros.sObjetoLookUp);
  oObjetoLookUp.hide();
   
  this.oCallback.onClick();
  return;
}

/**
 * Trata o retorno do change no objeto oInputID.
 * Percorre todos os arguments recebido pela função, pois não temos um padrão
 * de retorno dos dados, verificando qual deles é responsavel por informar 
 * a ocorrencia de erro e qual realmente é a string que deverá ser a descrição.
 */
DBLookUp.prototype.callBackChange  = function() {

  var aArgumentos = arguments,
      lErro       = null,
      sDescricao  = null;

  for (var iArgumento = 0; iArgumento < aArgumentos.length; iArgumento++) {

    if (typeof(aArgumentos[iArgumento]) == "boolean") {  
      lErro = aArgumentos[iArgumento];
    };

    if (typeof(aArgumentos[iArgumento]) == 'string' && sDescricao == null ) {
      sDescricao = aArgumentos[iArgumento];
    };
  };

  if (lErro) {

    this.oInputID.value        = '';
    this.oInputDescricao.value = sDescricao;
  } else {
    this.oInputDescricao.value = sDescricao;
  };

  this.oCallback.onChange(lErro);

  return;
};

/**
 * Função responsável pela abertura da janela de pesquisa.
 * @param  boolean lAbre
 */
DBLookUp.prototype.abrirJanela = function(lAbre){
  
  var sQueryString = '';

  if ( !this.oParametros.sArquivo ) {
    throw "Arquivo não pode ser vazio.";
  };

  if (lAbre) {
    sQueryString = this.getQueryStringClick();
  } else {
    sQueryString = this.getQueryStringChange();
  };

  var oJanela = js_OpenJanelaIframe(

    this.oParametros.sDestinoLookUp,
    this.oParametros.sObjetoLookUp,
    sQueryString,
    this.oParametros.sLabel,
    lAbre
  );

  if ( oJanela ) {
    oJanela.setAltura("calc(100% - 10px)");
    oJanela.setLargura("calc(100% - 10px)");
    oJanela.setPosY("0");
  };
};


/**
 * Seta o arquivo responsável pela pesquisa
 * @param String sArquivo
 */
DBLookUp.prototype.setArquivo = function(sArquivo){
  this.oParametros.sArquivo = sArquivo;
};

/**
 * Seta o nome do Label que será utilizado como 
 * titulo da janela de pesquisa
 * @param String sLabel
 */
DBLookUp.prototype.setLabel = function(sLabel){
  this.oParametros.sLabel = sLabel;
};

/**
 * Seta a query string que deverá ser utilizada 
 * na função de pesquisa.
 * @param String sQueryString
 */
DBLookUp.prototype.setQueryString = function(sQueryString){
  this.oParametros.sQueryString = sQueryString;
};

/**
 * Seta o Destino da LookUp
 * @param String sDestinoLookUp
 */
DBLookUp.prototype.setDestinoLookUp = function(sDestinoLookUp){
  this.oParametros.sDestinoLookUp = sDestinoLookUp;
};

/**
 * Seta o nome do objeto que será utilizado na tela de pesquisa.
 * @param String sObjetoLookUp
 */
DBLookUp.prototype.setObjetoLookUp = function(sObjetoLookUp){
  this.oParametros.sObjetoLookUp = sObjetoLookUp;
};

/**
 * Seta os campos adicionais
 * @param array aCamposAdicionais
 */
DBLookUp.prototype.setCamposAdicionais = function(aCamposAdicionais){
  this.oParametros.aCamposAdicionais = aCamposAdicionais;
};



/**
 * Seta função de callBack.
 * @param string sEvento tipo do evendo (onChange ou onClick)
 * @param string fFuncao
 */
DBLookUp.prototype.setCallBack = function(sEvento, fFuncao){
  this.oCallback[sEvento] = fFuncao;
}

DBLookUp.repository = DBLookUp.repository || {};
DBLookUp.repository = {

  "oInstances"  : DBLookUp.repository.oInstances || {},

  "iCounter"    : DBLookUp.repository.iCounter   || 0,
  
  "addInstance" : function( oDBLookUp ) { 
  
    if ( !( oDBLookUp instanceof DBLookUp ) ) {
      throw('Objeto Inválido');
    }
    var iNumeroInstancia = DBLookUp.repository.iCounter++;
    DBLookUp.repository.oInstances['DBLookUp'+ iNumeroInstancia] = oDBLookUp;
    return iNumeroInstancia;
  },
  "getInstance" : function( iCodigo ) { 
    return DBLookUp.repository.oInstances["DBLookUp" + iCodigo];
  }
}
