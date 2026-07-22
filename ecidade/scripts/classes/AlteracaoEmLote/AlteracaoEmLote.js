require_once('scripts/classes/AlteracaoEmLote/AlteracaoEmLote.css');

/**
 *
 * Componente que cria uma grid para preenchimento de valores em lote
 *
 * Exemplo de Utilizacao
 * data = [{
 *   'j01_iptubase': i*3,
 *   'j14_codigo': '',
 *   'j14_nome': '',
 *   'numero': '',
 *   'complemento': '',
 *   'j01_data': ''
 * }]
 *
 * var lote = new AlteracaoEmLote('lote', 'matricula', 'Matricula/DadosDaConstrucao.json', $('ctgrid'), data);
 *     lote.start();
 *
 * @param nomeInstancia
 * @param campoChave
 * @param template
 * @param container
 * @param registros
 * @constructor
 */
AlteracaoEmLote = function(nomeInstancia, campoChave, template, container, registros) {

    /**
     * $type {String}
     */
    this.nomeInstancia = nomeInstancia;

    /**
     * Onde o componente vai ser mostrado.
     */
    this.container = container;

    /**
     * String
     */
    this.template = template;

    /**
     * registros que apareceram na grid
     */
    this.registros = registros;

    /**
     * Define o campo que será chave.
     * @type string
     */
    this.campo_chave = campoChave;

    /**
     * Define se deve retornar a chave composta
     * @type {boolean}
     */
    this.possuiChaveComposta = false;

    /**
     * @type array
     */
    this.filtrosAdicionais = [];

    /**
     * Parametros que podem ser utilizados dentro de metodos
     * @type array
     */
    this.parametrosPesquisa = [];

    /**
     * @type DBGrid
     */
    this.grid = null;

    this.lookupParaCriar = [];
    this.comboboxParaCriar = [];
    this.dataParaCriar = [];

    /**
     * Define se possui chave composta
     * @param {boolean} possuiChaveComposta
     */
    this.setChaveComposta = function (possuiChaveComposta) {
        this.possuiChaveComposta = possuiChaveComposta;
    };

    /**
     * Adiciona filtros adicionais de colunas
     * @param filtros
     */
    this.setFiltrosAdicionais = function(filtros) {
        this.filtrosAdicionais = filtros;
    };

    this.setParametrosPesquisa = function(parametros) {
        this.parametrosPesquisa = parametros;
    }

    /**
     * Carrega o arquivo JSON para criação da tela
     */
    this.carregarTemplate = function () {

        self = this;
        AjaxRequest.create(
            'con4_alteracaoEmLote.RPC.php',
            {'exec' : 'getTemplate', 'template' : this.template, 'filtros_adicionais' : this.filtrosAdicionais, 'parametros' : this.parametrosPesquisa},
            function (retorno, erro) {

                if (erro) {
                    alert(retorno.mensagem);
                }
                self.template = retorno.template;
            }
        ).asynchronous(false).execute();
    };

    /**
     * Inicia a construção dos componentes necessários para utilização
     */
    this.start = function() {

        this.carregarTemplate();

        this.grid = new DBGrid("gridAlteracaoEmLote");
        this.grid.nameInstance = this.nomeInstancia + ".grid";
        this.grid.allowSelectColumns(true);
        this.grid.setHeight(280);
        this.grid.setCheckbox(0);
        let headers = [];
        let width = [];
        let align = [];
        let headersParaOcultar = [];
        let headerPercorrido = 1;
        for (const header of this.template) {

            headers.push(header.label);
            width.push(header.width);

            let valorAlign = 'left';
            if (header.align !== undefined && header.align !== '') {
                valorAlign = header.align;
            }

            if (header.display !== undefined && header.display === false) {
                headersParaOcultar.push(headerPercorrido);
            }

            align.push(valorAlign);
            headerPercorrido++;
        }
        this.grid.setCellWidth(width);
        this.grid.setHeader(headers);
        this.grid.setCellAlign(align);
        this.grid.setCallbackSingle(this.atualizarValorCampos);
        for (const index of headersParaOcultar) {
            this.grid.aHeaders[index].lDisplayed = false;
        }
        this.grid.show(this.container);

        this.popularGrid();
        this.carregarLookups();
        this.carregarCamposData();
    };

    this.popularGrid = function () {

        this.grid.clearAll(true);
        this.grid.addRow(this.montarLinha({}, 'linha_0', true));
        for (const linha of this.registros) {
            this.grid.addRow(this.montarLinha(linha, 'linha_' + linha[this.campo_chave]));
        }
        this.grid.renderRows();
    };



    this.montarLinha = function (data, id, callback) {

        if (callback == null) {
            callback = false;
        }
        var linha = [];
        for (var campo of this.template) {

            valorCampo = '';
            if (data[campo.valor] !== undefined) {
                valorCampo = data[campo.valor];
            }
            if (!campo.editavel) {
                linha.push(valorCampo);
                continue;
            }

            switch (campo.tipo) {
                case 'lookup':
                    linha.push(this.criarLookup(campo, id, data, callback));
                    break;
                case 'combo' :
                    linha.push(this.criarComboBox(campo, id, valorCampo, callback));
                    break;
                case 'data' :
                    linha.push(this.criarCampoData(campo, id, valorCampo, callback));
                    break;
                default:
                    linha.push(this.criarCampoPadrao(campo ,id, valorCampo, callback));
                    break;
            }
        }
        return linha;
    }
};

/**
 * Atualiza os valores dos campos selecionados quando selecionado um item único na grid.
 */
AlteracaoEmLote.prototype.atualizarValorCampos = function () {

    let campos = document.querySelectorAll("div .camposDefault");
    for (let campo of campos) {

        if (campo.value === '') {
            continue;
        }
        let event = new Event('change');
        campo.dispatchEvent(event);
    }
};

/**
 * Retorna as linhas marcadas com seus valores
 * @returns {Array}
 */
AlteracaoEmLote.prototype.getLinhas = function () {

    let seletor = '.body-container tr.marcado';
    const linhas = document.querySelectorAll(seletor);
    let retorno = [];
    for (const linha of linhas) {

        let valorChave = linha.childNodes[0].childNodes[0].value;
        let chaveCompostaValor = null;
        if (this.possuiChaveComposta) {
            chaveCompostaValor = linha.childNodes[0].nextElementSibling.nextElementSibling.innerHTML;
        }

        let dadosRetorno = {
            'chave_pk_1' : valorChave,
            'chave_pk_2' : chaveCompostaValor,
            'campos' : []
        };

        for (const campo of this.template) {

            var nomeGrupo = "campos_" + campo.nome;
            const campoTela = linha.querySelector("." + nomeGrupo);
            if (campoTela == null) {
                continue;
            }
            let informacao = {'campo' : campo.nome, 'valor': campoTela.value};
            dadosRetorno.campos.push(informacao);
        }
        retorno.push(dadosRetorno);
    }
    return retorno;
};

/**
 * Preenche os campos selecionados com a informação posta na primeira linha da grid
 *
 * @param campoCodigoBase
 * @param nomeGrupo
 */
AlteracaoEmLote.prototype.preencherCampos = function(campoCodigoBase, nomeGrupo) {

    let seletor = '.body-container tr.marcado';
    const linhas = document.querySelectorAll(seletor);
    for (let linha  of linhas) {

        const camposCodigo = linha.querySelectorAll("." + nomeGrupo);
        for (let campo  of camposCodigo) {
            campo.value = $F(campoCodigoBase);
        }
    }
};

AlteracaoEmLote.prototype.criarCampoPadrao = function(campo, id, valor, callback) {

    let idCampoCodigo = (campo.nome + id);
    const nomeGrupo = 'campos_' + campo.nome;
    let funcaoCallback = '';
    let camposDefault = '';
    if (callback) {
        funcaoCallback = "onchange='"+this.nomeInstancia+".preencherCampos(\""+idCampoCodigo+"\", \""+nomeGrupo+"\")'";
        camposDefault = 'camposDefault';
    }

    var style = "text-color: #000; width: 100%;";
    if (campo.align !== undefined) {
        style += "text-align: " + campo.align + ";";
    }
    var templateTexto = "<input type='text' id='" + idCampoCodigo + "' ";
       templateTexto += "       style='" + style + "' ";
       templateTexto += "       value='" + valor + "' class='" + nomeGrupo + " "+camposDefault+"' "+funcaoCallback+">";
    return templateTexto;
};

/**
 * Cria o campo data para apresentação na grid.
 * @param campo
 * @param id
 * @param valor
 * @param callback
 * @returns {string}
 */
AlteracaoEmLote.prototype.criarCampoData = function (campo, id, valor, callback) {

    let idCampoCodigo = (campo.nome + id);
    let funcaoCallback = '';
    let nomeGrupo = "campos_"+ campo.nome;
    let camposDefault = '';
    if (callback) {
        funcaoCallback  = " onchange='"+this.nomeInstancia+".preencherCampos(\""+idCampoCodigo+"\", \""+nomeGrupo+"\")' ";
        funcaoCallback += " onblur='"+this.nomeInstancia+".preencherCampos(\""+idCampoCodigo+"\", \""+nomeGrupo+"\")' ";
        camposDefault = 'camposDefault';
    }
    this.dataParaCriar.push(idCampoCodigo);
    return "<input type='text' id='" + idCampoCodigo + "' size='10' "+funcaoCallback+" value='" + valor + "' class='"+nomeGrupo+" "+camposDefault+"' >";
};

/**
 * Carrega os campos datas da forma DBInputDate
 */
AlteracaoEmLote.prototype.carregarCamposData = function () {
    for (let campo of this.dataParaCriar) {
        var obj = new DBInputDate($(campo));
        $(campo).setAttribute('campodata', obj);
    }
};

/**
 * Cria o combo box
 * @param campo
 * @param id
 * @param valor
 * @param callback
 * @returns {string}
 */
AlteracaoEmLote.prototype.criarComboBox = function(campo, id, valor, callback) {

    let template = campo;
    let idCampoCodigo = (template.nome + id);
    let funcaoCallback = '';
    let nomeGrupo = "campos_"+ template.nome;
    let camposDefault = '';
    if (callback) {
        funcaoCallback = "onChange='"+this.nomeInstancia+".preencherCampos(\""+idCampoCodigo+"\", \""+nomeGrupo+"\")'";
        camposDefault = 'camposDefault';
    }

    let templateTexto = "<select  type='text' id='" + idCampoCodigo + "' "+funcaoCallback+" class='"+nomeGrupo +" "+ camposDefault + " ' >";
    templateTexto += "<option value= ''> Selecione</option>";
    for (const item of template.lista) {

        var selected = item.codigo == valor ? ' selected ' : '';
        templateTexto += "<option value= '" + item.codigo + "' " + selected + ">" + item.descricao + "</option>";
    }
    return templateTexto;
};

/**
 * Cria os inputs e containers para a lookup
 * @param campo
 * @param id
 * @param valor
 * @param callback
 * @returns {string}
 */
AlteracaoEmLote.prototype.criarLookup = function (campo, id, valor, callback) {

    let idCampoCodigo = (campo.nome + id);
    let label = ('lbl_' + campo.nome + id);
    valorCodigo = '';
    valorDescricao = '';

    if (valor[campo.valor.codigo] !== undefined) {
        valorCodigo = valor[campo.valor.codigo];
    }
    if (valor[campo.valor.descricao] !== undefined) {
        valorDescricao = valor[campo.valor.descricao];
    }
    let camposDefault = '';
    if (callback) {
        camposDefault = 'camposDefault';
    }

    const nomeGrupo = 'campos_' + campo.nome;
    const nomeGrupoDescricao = 'campos_descricao_' + campo.parametros_lookup.campo_descricao;
    let idCampoDescricao = (campo.parametros_lookup.campo_descricao + id);
    let templateTexto = "<label id='" + label + "' for='" + idCampoCodigo + "'>" + campo.label + '&nbsp;:</label>';
    templateTexto += "<input type='text' id='" + idCampoCodigo + "' size='10' data='" + campo.nome + "' value='" + valorCodigo + "' class='" + nomeGrupo + " "+camposDefault+" '  >";
    templateTexto += "<input type='text' id='" + idCampoDescricao + "' size='10'  data='" + campo.parametros_lookup.campo_descricao + "' value='" + valorDescricao + "' class='" + nomeGrupoDescricao + "' >";

    let callbackCampo = function () {};
    if (callback) {
        self = this;
        callbackCampo = function () {
            self.preencherCamposLookup(idCampoCodigo, idCampoDescricao, nomeGrupo, nomeGrupoDescricao);
        };
    }

    let abrirJanela = function () { return []; };
    if (campo.chave_pesquisa) {

        abrirJanela = function () {
            let valor = $F(campo.dependencia + id);
            return [campo.chave_pesquisa+'='+valor]
        };
    }

    var lookup = {
        arquivo: campo.parametros_lookup.arquivo,
        campo_id: idCampoCodigo,
        campo_descricao: idCampoDescricao,
        label: label,
        abrirJanela: abrirJanela,
        callback: callbackCampo
    };
    this.lookupParaCriar.push(lookup);
    return templateTexto;
};

/**
 * Preenche o código / descrição nos campos inputs que estiverem marcados
 *
 * @param campoCodigoBase
 * @param campoDescricaoBase
 * @param nomeGrupo
 * @param nomeGrupoDescricao
 */
AlteracaoEmLote.prototype.preencherCamposLookup = function(campoCodigoBase, campoDescricaoBase, nomeGrupo, nomeGrupoDescricao) {

    let seletor =  '.body-container tr.marcado';
    const linhas = document.querySelectorAll(seletor);
    for (let linha  of linhas) {

        const camposCodigo = linha.querySelectorAll("." + nomeGrupo);
        for (let campo  of camposCodigo) {
            campo.value = $F(campoCodigoBase);
        }

        const camposDescricao = linha.querySelectorAll("." + nomeGrupoDescricao);
        for (let campo  of camposDescricao) {
            campo.value = $F(campoDescricaoBase);
        }
    }
};

/**
 * Carrega em tela as âncoras e campos inputs para tratamento da lookup
 */
AlteracaoEmLote.prototype.carregarLookups = function () {

    for (const lookup of this.lookupParaCriar) {


        new DBLookUp($(lookup.label),
            $(lookup.campo_id),
            $(lookup.campo_descricao),
            {
                sArquivo: lookup.arquivo,
                sObjetoLookUp: 'func_' + lookup.campo_id,
                fCallBack: lookup.callback,
                fnAbrirJanela: lookup.abrirJanela
            }
        );
    }
};
