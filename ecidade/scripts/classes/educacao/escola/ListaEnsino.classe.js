require_once("scripts/arrays.js");
require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
/**
 * Monta um select com uma lista de Ensinos
 * @dependency Utiliza DBViewFormularioEducacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.5 $
 * @package Educacao
 * @subpackage Escola
 * @example
 *         var oEnsino = new DBViewFormularioEducacao.ListaEnsino();
 *
 *         var fFuncaoLoad = function() {
 *           alert('Chamei após carregar');
 *         };
 *
 *         var fFuncaoChange = function () {
 *           alert(JSON.stringify(oEnsino.getSelecionados()));
 *         };
 *
 *         oEnsino.setCallBackLoad(fFuncaoLoad);       // Opcional
 *         oEnsino.setCallbackOnChange(fFuncaoChange); // Opcional
 *         oEnsino.habilitarOpcaoTodas(true);          // Opcional
 *         oEnsino.show($('listaEnsinos'));
 *
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEnsino = function() {

    /**
     * Nome do arquivo RPC para as requisições ajax
     * @var string
     */
    this.sUrlRPC = "edu4_ensino.RPC.php";

    /**
     * Função callback ao carregar os dados
     * @var function
     */
    this.fCallBackLoad = function () {
        return true;
    };

    /**
     * Função callback ao selecionar um option do select
     * @var function
     */
    this.fCallbackOnChange = function () {
        return true;
    };

    /**
     * Se true, adiciona no combobox o option 'Todas'
     * @var boolean
     */
    this.lHabilitaOpcaoTodas = false;

    /**
     * Código da escola que devemos buscar as ensinos
     * OBS.: Se 0, busca de todas as escolas
     * @var integer
     */
    this.iEscola = 0;

    /**
     * Código do calendário que devemos buscar as ensinos
     * OBS.: Se 0, busca de todos os calendários
     * @var integer
     */
    this.iCalendario = 0;

    /**
     * Elemento select das ensinos
     * @var HTMLElement
     */
    this.oCboEnsino  = document.createElement("select");
    this.oCboEnsino.style.width = "100%";
    this.oCboEnsino.add( new Option( 'Selecione o Ensino', '' ) );
};

/**
 * Define uma função para ser executada após o carregamento dos dados
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.setCallBackLoad = function (fFunction) {
    this.fCallBackLoad = fFunction;
};

/**
 * Define uma função para ser executada ao mudar a seleção no combobox
 * @param function fFunction
 * @return {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.setCallbackOnChange = function (fFunction) {

    this.fCallbackOnChange = fFunction;
    this.oCboEnsino.stopObserving('change');
    this.oCboEnsino.observe('change', function() {
        fFunction();
    });
};

/**
 * Método que define se será exibido a opção 'Todas'
 * @param boolean lHabilta
 */
DBViewFormularioEducacao.ListaEnsino.prototype.habilitarOpcaoTodas = function (lHabilta) {
    this.lHabilitaOpcaoTodas = lHabilta;
};

/**
 * Retorna o option selecionado no comboBox
 * @returns Object
 */
DBViewFormularioEducacao.ListaEnsino.prototype.getSelecionados = function () {
    var oRetorno          = {};
    oRetorno.codigo_ensino = this.oCboEnsino.value;
    oRetorno.ensino        = this.oCboEnsino.options[this.oCboEnsino.selectedIndex].innerHTML;
    return oRetorno;
};

/**
 * Define uma escola para ser buscada as ensinos
 * @param  {integer} iEscola Código da escola
 * @return {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.setEscola = function (iEscola) {
    this.iEscola = iEscola;
};

/**
 * Define o calendário que sera buscado as ensinos
 * @param  {integer} iCalendario código do calendário
 * @return {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.setCalendario = function (iCalendario) {
    this.iCalendario = iCalendario;
};


/**
 * Realiza a pesquisa das ensinos cadastrada no sistema para os filtros informados
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.pesquisaEnsinos = function () {

    var oSelf = this;

    var oParametro = new Object();
    oParametro.sExecucao = 'pesquisaEnsinos';
    oParametro.iEscola = this.iEscola;
    oParametro.iCalendario = this.iCalendario;

    js_divCarregando("Aguarde, pesquisando ensinos.", "msgBoxE");

    var oObjeto        = {};
    oObjeto.method     = 'post';
    oObjeto.parameters = 'json='+Object.toJSON(oParametro);
    oObjeto.onComplete = function(oAjax) {
        oSelf.retornoEnsinos(oAjax);
    };

    new Ajax.Request(oSelf.sUrlRPC, oObjeto);
};

/**
 * Trata o retorno do metodo pesquisaEnsinos montando o comboBox com os parâmetros configurados
 * @param Object oAjax
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.retornoEnsinos = function (oAjax) {

    var oSelf = this;
    js_removeObj('msgBoxE');
    var oRetorno = JSON.parse(oAjax.responseText);

    if (oRetorno.iStatus != 1) {
        alert(oRetorno.sMensagem.urlDecode());
        return false;
    }

    oSelf.limpar();

    var iContadorEnsino = oRetorno.dados.length;

    if (oSelf.lHabilitaOpcaoTodas && iContadorEnsino > 1) {
        oSelf.oCboEnsino.add(new Option('Todas', '0'));
    }
    oRetorno.dados.each( function (oDado) {
        var sLabel = oDado.ensino.urlDecode();
        oSelf.oCboEnsino.add(new Option(sLabel, oDado.codigo));
    });

    if (iContadorEnsino == 1) {
        oSelf.oCboEnsino.value = oRetorno.dados[0].codigo;
    }

    oSelf.fCallBackLoad();
};

/**
 * Renderiza o comboBox com a lista de ensinos.
 * @param oElement id onde será renderizado o comboBox
 * @returns {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.show = function(oElement) {
    oElement.appendChild(this.oCboEnsino);
};

/**
 * Remove os options do comboBox das Ensinos
 * @return {void}
 */
DBViewFormularioEducacao.ListaEnsino.prototype.limpar = function() {
    this.oCboEnsino.options.length = 0;
    this.oCboEnsino.add( new Option( 'Selecione um Ensino', '' ) );
};
