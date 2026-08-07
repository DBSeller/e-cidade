require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');

/**
 *
 * @param instance
 * @constructor
 */
AtributosLancamento = function(instance) {

    this._instance = instance;

    /**
     * RPC
     * @type {string}
     * @private
     */
    this._rpc = 'con4_manutencaoatributoslancamento.RPC.php';

    /**
     * Contas Debito
     * @type {Array}
     * @private
     */
    this._contasDebito= [];

    /**
     * Conta Credito
     * @type {Array}
     * @private
     */
    this._contasCredito= [];

    /**
     *
     * @type {undefined}
     * @private
     */
    this._grid = undefined;

    /**
     * Dados configurados
     * @type {Array}
     * @private
     */
    this._retorno = [];

    /**
     *
     * @type {boolean}
     * @private
     */
    this._possuiAtributos = false;

    /**
     * function
     * @private
     */
    this._callback = function () {};

    /**
     *
     * @type {string}
     * @private
     */
    this._html  = "<div id='messageBoardWindow'></div> ";
    this._html += "<fieldset>";
    this._html += "  <legend class='bold'>Atributos para lançamento contábil</legend>";
    this._html += "  <p style='text-align: center; padding: 10px; background-color: #fff984; border: 1px solid black '>";
    this._html += "Atenção: o atributo <b><i>FR - Fonte de Recurso</i></b> deve ser preenchido com o código sequencial do E-cidade,";
    this._html += " devidamente cadastrado em <i><b>Orçamento > Cadastro > Tipos de Recursos</i></b></p> ";
    this._html += "  <div id='ctnContas'></div>";
    this._html += "</fieldset>";
    this._html += "<p style='text-align:center;'>";
    this._html += "  <input type='button' id='btnSalvar' value='Salvar' onclick='"+this._instance+".salvarInformacoes()' />";
    this._html += "  <input type='button' id='btnLimpar' value='Limpar Campos' onclick='"+this._instance+".limpar()' />";
    this._html += "</p>";

};

/**
 * Armazena os dados informados pelo usuário em uma propriedade
 * @returns {boolean}
 */
AtributosLancamento.prototype.salvarInformacoes = function () {

    let permiteSalvar = true;

    this._retorno = [];
    let self = this;
    this._grid.aRows.forEach(
        function (row, indice) {

            let dadosConta = row.aCells[0].getValue().split(' - ');
            let dadosContaCorrente = row.aCells[2].getValue().split(' - ');
            let dadosAtributo = row.aCells[4].getValue().split(' - ');

            if (self._retorno[dadosConta[0]] === undefined) {

                self._retorno[dadosConta[0]] = {
                    'sinal' : row.aCells[1].getValue(),
                    'conta_contabil' : {
                        'codigo' : dadosConta[0],
                        'reduzido' : dadosConta[1],
                        'estrutural' : dadosConta[2],
                        'descricao' : dadosConta[3]
                    },
                    'conta_corrente' : []
                };
            }

            if (self._retorno[dadosConta[0]].conta_corrente[dadosContaCorrente[0]] === undefined) {

                self._retorno[dadosConta[0]].conta_corrente[dadosContaCorrente[0]] = {
                    'codigo': dadosContaCorrente[0],
                    'descricao': dadosContaCorrente[1],
                    'atributos': []
                };
            }

            self._retorno[dadosConta[0]].conta_corrente[dadosContaCorrente[0]].atributos.push({
                'codigo' : dadosAtributo[0],
                'descricao' :  dadosAtributo[1],
                'sigla' :  row.aCells[3].getValue(),
                'valor' : row.aCells[5].getValue()
            });

            if (row.aCells[5].getValue().trim() === '') {
                permiteSalvar = false;
            }
        }
    );

    if (!permiteSalvar) {
        alert("Todos os atributos devem ser preenchidos.");
        return false;
    }
    this._callback();
};

/**
 * Retorna as linhas configuradas
 * @returns {Array}
 */
AtributosLancamento.prototype.getAtributosPorSinal = function (sinal) {

    let registros = [];
    for (let linha in this._retorno) {
        if (this._retorno[linha].sinal === sinal) {
            registros.push(this._retorno[linha]);
        }
    }
    return registros;
};


AtributosLancamento.prototype.show = function () {
    this._carregarAtributos();
};

/**
 * Limpa todos os campos da grid.
 */
AtributosLancamento.prototype.limpar = function () {
    this._grid.aRows.forEach(
        function (row, indice) {
            document.querySelector('#input_'+indice).value = '';
        }
    );
};
/**
 *
 * @param resposta
 * @private
 */
AtributosLancamento.prototype._construirTela = function (resposta) {

    let window =  new windowAux( 'window', 'Atributos do Lançamento', 1200, 500);
    window.setShutDownFunction(function () {
       window.destroy();
    });
    window.setContent(this._html);

    let tituloMessageBoard = "Atributos do lançamento";
    let ajudaMessageBoard  = "As contas selecionadas possuem atributos vinculados. Todos os atributos devem ser preenchidos.";
    window.show();
    let messageBoard = new DBMessageBoard('messageBoard', tituloMessageBoard, ajudaMessageBoard, window.getContentContainer());
    messageBoard.show();

    this._grid = new DBGrid('gridAtributos');
    this._grid.nameInstance = this._instance+'._grid';
    this._grid.setHeader(['Conta Contábil', 'Natureza', 'Conta Corrente', 'Sigla', 'Atributo', 'Valor']);
    this._grid.setCellWidth(['40%', '5%', '20%', '5%', '20%', '10%']);
    this._grid.setCellAlign(['left', 'center', 'left', 'center', 'left', 'left']);
    this._grid.setHeight(280);
    this._grid.show($('ctnContas'));

    let rowGrid = 0;
    for (let dadosSistema of resposta.atributos) {

        this._grid.addRow([
            dadosSistema.codigo_conta +" - "+dadosSistema.codigo_reduzido+" - "+dadosSistema.estrutural_conta+" - "+dadosSistema.descricao_conta,
            dadosSistema.sinal_conta,
            Number(dadosSistema.codigo_sistema) === 1 ? '1 - MSC' : dadosSistema.codigo_sistema +' - '+dadosSistema.descricao_sistema,
            dadosSistema.sigla_atributo,
            dadosSistema.codigo_atributo +" - "+dadosSistema.descricao_atributo,
            "<input type='text' id='input_"+rowGrid+"' style='width:100%; border:1 solid grey;' onchange='"+this._instance+".atualizarValores("+rowGrid+")' value='"+dadosSistema.valor+"'/>"
        ]);
        rowGrid++;
    }
    this._grid.renderRows();
};

/**
 * Atualiza os valores.
 * @param linhaGrid
 */
AtributosLancamento.prototype.atualizarValores = function (linhaGrid) {

    let valor = this._grid.aRows[linhaGrid].aCells[5].getValue();
    let sigla = this._grid.aRows[linhaGrid].aCells[3].getValue();
    let sinal = this._grid.aRows[linhaGrid].aCells[1].getValue();
    this._grid.aRows.forEach(
        function (row, indice) {
            let valorDestino = document.querySelector('#input_'+indice).value;
            if (row.aCells[3].getValue() === sigla && valorDestino === '' && row.aCells[1].getValue() === sinal) {
                document.querySelector('#input_'+indice).value = valor;
            }
        }
    );
};

/**
 * Carrega os atributos das contas.
 * @private
 */
AtributosLancamento.prototype._carregarAtributos = function () {

    let parametros = {
        'exec' : 'getInformacoes',
        'contas_debito' : this._contasDebito,
        'contas_credito' : this._contasCredito
    };

    let self = this;
    AjaxRequest.create(
        this._rpc,
        parametros,
        function (resposta, erro) {

            if (erro) {
                alert(resposta.mensagem);
                return false;
            }
            self._possuiAtributos = (resposta.atributos.length > 0);
            if (!self._possuiAtributos) {
                return;
            }
            self._construirTela(resposta);
        }
    ).setMessage('Aguarde, carregando informações de atributos...').execute();
};

/**
 * @param {array} contas
 */
AtributosLancamento.prototype.setContasCredito = function (contas) {
    this._contasCredito = contas;
};

/**
 * @param {array} contas
 */
AtributosLancamento.prototype.setContasDebito = function (contas) {
    this._contasDebito = contas;
};

/**
 * Função a ser chamada quando o usuário clicar em salvar.
 * @param callback
 */
AtributosLancamento.prototype.setCallback = function (callback) {
    this._callback = callback
};
