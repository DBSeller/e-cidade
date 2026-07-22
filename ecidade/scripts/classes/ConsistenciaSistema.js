var ConsistenciaSistema = function (propriedade) {

    const RPC = 'con4_consistencia.RPC.php';

    /**
     * Objeto com os dados do JSON cadastrados na base
     * @type {object}
     */
    this.propriedades = propriedade;

    /**
     * Nome da Consistencia
     * @type {string}
     */
    this.nome = propriedade.jsonConsistencia.nome;

    /**
     * Descrição da Consistencia
     * @type {string}
     */
    this.descricao = propriedade.jsonConsistencia.descricao;

    /**
     * Dados do formulario
     * @type {object}
     */
    this.formulario = propriedade.jsonConsistencia.formulario;

    /**
     * Collection
     * @type {Collection}
     */
    this.colecao = null;

    /**
     * DatagridCollection
     * @type {DatagridCollection}
     */
    this.dataGrid = null;

    /**
     * Objeto com as propriedades de sql
     * @type {{}}
     */
    this.sql = propriedade.jsonConsistencia.sql;

    /**
     * Objeto com as opções dos combobox que possuem as opções como consulta SQL.
     *
     * @type {{}}
     */
    this.opcoesSelect = {};

    /**
     * Abre a janela
     */
    this.abrir = function () {
        this.construirJanela();
        this.verificarInconsitencias();
    };


    /**
     * Percorre e envia os registros a serem salvos
     */
    this.salvar = function () {
        var self = this;
        var registros = this.getLinhasParaCorrecao();

        AjaxRequest.create(
            RPC,
            {'exec': 'executarCorrecao', 'id': self.propriedades.id, 'registros': registros},
            function (retorno, erro) {
                alert(retorno.mensagem);
                self.verificarInconsitencias();
            }
        ).setMessage('Aguarde, salvando...').execute();
    };

    this.exportarParaCsv = function() {
        var self = this;
        var filtros = self.getFiltros();
        AjaxRequest.create(
            RPC,
            {'exec': 'exportarParaCsv', 'id': self.propriedades.id, 'filtros': filtros },
            function (retorno, erro) {

                if (erro) {
                    alert(retorno.mensagem);
                }

                var linkDownload = "<a href='"+retorno.arquivo+"' download >"+retorno.nome_arquivo+"</a>";
                document.querySelector("#linkDownloadConsistencia").innerHTML = linkDownload;

            }
        ).setMessage('Aguarde, exportando para CSV...').execute();

    };

    /**
     * Retorna um array de linhas formatadas para a correção.
     *
     * @return {any[]}
     */
    this.getLinhasParaCorrecao = function () {
        var self = this;
        var dadosParaEnvio = new Array();
        var elementosCorrecao = document.getElementsByClassName('correcao');
        var propriedadesCorrecao = self.getPropriedadesParaCorrecao();

        for (var linha of elementosCorrecao) {
            var propriedadesLinhas = new Array();

            propriedadesCorrecao.forEach(function (propriedade) {
                var valor = linha.getElementsByClassName(propriedade)[0].value;

                if (linha.getElementsByClassName(propriedade)[0].tagName === 'SPAN') {
                    valor = linha.getElementsByClassName(propriedade)[0].innerHTML;
                } else {
                    if (linha.getElementsByClassName('nao_nulo').length > 0 && (valor == '' || valor == null)) {
                        return;
                    }
                }

                propriedadesLinhas.push({
                    "propriedade": propriedade,
                    "valor": valor
                });
            });

            if (propriedadesCorrecao.length == propriedadesLinhas.length) {
                dadosParaEnvio.push(propriedadesLinhas);
            }

        }
        return dadosParaEnvio;
    };

    /**
     * Retorna um array com as propriedades a serem enviadas para correção.
     *
     * @return {any[]}
     */
    this.getPropriedadesParaCorrecao = function () {
        var propriedadesCorrecao = new Array();

        for (var campo of this.formulario.campos) {
            if (campo.tipo !== undefined || (campo.chave_primaria !== undefined && campo.chave_primaria)) {
                propriedadesCorrecao.push(campo.propriedade);
            }
        }

        return propriedadesCorrecao;
    };

    /**
     * Executa a consulta e monta os dados na grid
     */
    this.verificarInconsitencias = function () {
        var self = this;

        var filtros = self.getFiltros();
        AjaxRequest.create(
            RPC,
            {'exec': 'executarConsistencia', 'id': propriedade.id, 'filtros': filtros},
            function (retorno, erro) {

                self.dataGrid.clear();
                if (erro) {
                    return alert(retorno.mensagem);
                }
                self.permiteSalvar = retorno.possuiCorrecao;

                if (retorno.ajuda) {
                    document.querySelector('#divAjuda').innerHTML = retorno.ajuda;
                }

                if (retorno.registrosConsistencia.length == 0) {
                    alert('Nenhum registro encontrado.');
                    return false;
                }

                retorno.registrosConsistencia.forEach(
                    function (linha) {
                        self.adicionarLinhaGrid(linha);
                    }
                );

                self.dataGrid.reload();

                for (var campo of self.formulario.campos) {
                    if (campo.tipo !== undefined) {
                        var elementosParaListner = document.getElementsByClassName(campo.propriedade);

                        for (var elemento of elementosParaListner) {
                            elemento.addEventListener('change', function () {
                                var existeClassSalvar = this.closest('tr').classList.contains('correcao');
                                if (!existeClassSalvar) {
                                    this.closest('tr').addClassName('correcao');
                                }
                            });
                        }
                    }
                }
            }
        ).setMessage('Aguarde, executando consulta...').execute();
    };

    /**
     * Adiciona uma linha na Grid montada.
     *
     * @param linha
     */
    this.adicionarLinhaGrid = function (linha) {
        var linhaGrid = {};

        for (var coluna in linha) {
            var campoFormulario = this.getCampoFormularioPorPropriedade(coluna);

            if (campoFormulario) {
                campoFormulario.valorElemento = linha[coluna];

                linhaGrid[coluna] = this.criarElementoHtmlPorTipo(campoFormulario.tipo, campoFormulario);
            }
        }

        if (Object.keys(linhaGrid).length > 0 && linhaGrid[coluna] !== "") {
            this.colecao.add(linhaGrid);
        }
    };

    /**
     * Cria um elemento de acordo com o tipo informado:
     * 'select' - <select>...</select>
     * 'text'   - <input type='text' />
     * default  - <span>...</span>
     *
     * @param tipoElemento
     * @param camposElemento
     *
     * @return elemento
     */
    this.criarElementoHtmlPorTipo = function (tipoElemento, camposElemento) {
        var elemento = "";

        switch (tipoElemento) {
            case "select":
                var select = document.createElement("select");
                var selected = false;
                var domOpcao = document.createElement('option');

                select.setAttribute("class", camposElemento.propriedade + " " + camposElemento.propriedade + "_" + camposElemento.valorElemento);

                if (camposElemento.nulo == false) {
                    select.addClassName('nao_nulo');
                }

                domOpcao.value = '';
                domOpcao.innerHTML = "Selecione...";

                if (!camposElemento.valorElemento) {
                    domOpcao.setAttribute("selected", "selected");
                    selected = true;
                }

                select.appendChild(domOpcao);

                if (Array.isArray(camposElemento.opcoes)) {
                    camposElemento.opcoes.forEach(function (opcao, index) {

                        domOpcao = document.createElement('option');
                        domOpcao.value = opcao.codigo;
                        domOpcao.innerHTML = opcao.descricao;

                        if (!selected && camposElemento.valorElemento !== undefined && opcao.codigo == camposElemento.valorElemento) {
                            domOpcao.setAttribute("selected", "selected");
                            selected = true;
                        }

                        select.appendChild(domOpcao);
                    });
                } else {
                    var opcoes = this.opcoesSelect.hasOwnProperty(camposElemento.propriedade) ? this.opcoesSelect[camposElemento.propriedade] : this.getOpcoesPorConsulta(camposElemento);
                    for (var opcao of opcoes) {
                        domOpcao = document.createElement('option');
                        domOpcao.value = opcao.codigo;
                        domOpcao.innerHTML = opcao.descricao;

                        if (!selected && camposElemento.valorElemento !== undefined && opcao.codigo == camposElemento.valorElemento) {
                            domOpcao.setAttribute('selected', 'selected');
                            selected = true;
                        }

                        select.appendChild(domOpcao);
                    }
                }

                if (!selected) {
                    domOpcao = document.createElement('option');
                    domOpcao.value = camposElemento.valorElemento;
                    domOpcao.innerHTML = 'Código ' + camposElemento.valorElemento + ' sem descrição';
                    domOpcao.setAttribute('selected', 'selected');
                }

                elemento = select.outerHTML;
                break;
            case "input":
                var input = document.createElement('input');
                input.setAttribute('class', camposElemento.propriedade + ' ' + camposElemento.propriedade + '_' + camposElemento.valorElemento);

                for (var inputParametros in camposElemento.configuracoes) {
                    input.setAttribute(inputParametros, camposElemento.configuracoes[inputParametros]);
                }

                elemento = input.outerHTML;
                break;
            default:
                var span = document.createElement('span');
                span.setAttribute('class', camposElemento.propriedade + ' ' + camposElemento.propriedade + '_' + camposElemento.valorElemento);
                span.innerHTML = camposElemento.valorElemento;

                elemento = span.outerHTML;
                break;
        }

        return elemento;
    };

    this.getOpcoesPorConsulta = function (elementoSelect) {
        var self = this;
        var opcoes = new Array();

        AjaxRequest.create(
            RPC,
            {
                'exec': 'getOpcoesSelectPorConsultaSql',
                'id': self.propriedades.id,
                'propriedadeCampo': elementoSelect.propriedade
            },
            function (retorno, erro) {
                if (erro) {
                    return alert(retorno.mensagem);
                }
                opcoes = retorno.opcoes;
            }
        ).asynchronous(false).execute();

        this.opcoesSelect[elementoSelect.propriedade] = opcoes;

        return opcoes;
    }

    /**
     * Busca um objeto no array de campos do formulario
     *
     * @param nomePropriedade
     * @returns {*}
     */
    this.getCampoFormularioPorPropriedade = function (nomePropriedade) {
        for (var campo of this.formulario.campos) {
            if (campo.propriedade == nomePropriedade) {
                return campo;
            }
        }

        return undefined;
    };

    /**
     * Constroi a janela montando o esquelo para adicionarmos os dados com a função adicionarLinhaGrid(linha)
     */
    this.construirJanela = function () {

        var self = this;

        var divContainer = document.createElement('div');
        divContainer.style.width = '99.5%';

        var divContainerMessage = document.createElement('div');
        divContainerMessage.id = 'containerMessageBoard';
        divContainer.appendChild(divContainerMessage);

        var fieldset = document.createElement('fieldset');
        fieldset.style.margin = '10px';
        fieldset.style.width = '96%';
        var legend = document.createElement('legend');
        legend.innerHTML = 'Resultados Encontrados';
        fieldset.appendChild(legend);

        var divAjuda = document.createElement('div');
        divAjuda.id = "divAjuda";
        divAjuda.innerHTML = "";
        fieldset.appendChild(divAjuda);

        var divGrid = document.createElement('div');
        divGrid.id = 'ctnGrid';
        fieldset.appendChild(divGrid);

        divContainer.appendChild(fieldset);

        var divContainerBotao = document.createElement('div');
        divContainerBotao.className = 'container';
        var botaoSalvar = document.createElement('input');
        botaoSalvar.type = 'button';
        botaoSalvar.value = 'Salvar';

        divContainerBotao.appendChild(botaoSalvar);

        var botaoExportar = document.createElement('input');
        botaoExportar.type = 'button';
        botaoExportar.value = 'Exportar para CSV';
        divContainerBotao.appendChild(botaoExportar);

        var divLink = document.createElement("div");
        divLink.id = "linkDownloadConsistencia";
        divContainerBotao.appendChild(divLink);

        divContainer.appendChild(divContainerBotao);

        var larguraJanela = document.body.clientWidth - 100;
        var alturaJanela = document.body.clientHeight - 20;

        var windowConsistencia = new windowAux('windowConsistencia', 'Consistência de Dados', larguraJanela, alturaJanela);
        windowConsistencia.setObjectForContent(divContainer);
        windowConsistencia.setShutDownFunction(function () {
            if (self.getLinhasParaCorrecao().length > 0) {
                if (confirm("Existem alterações que não foram salvas, deseja salva-las antes de sair? ")) {
                    self.salvar();
                }
            }

            windowConsistencia.destroy();
        });
        windowConsistencia.show(10, 10, true);

        var messageBoard = new DBMessageBoard('messageBoardConsistencia', this.nome, this.descricao, divContainerMessage);
        messageBoard.show();

        this.colecao = null;

        for (var row = 0; row < this.formulario.campos.length; row++) {
            if (this.formulario.campos[row].chave_primaria !== undefined && this.formulario.campos[row].chave_primaria === true) {
                this.colecao = new Collection().setId(this.formulario.campos[row].propriedade);
            }
        }

        if (this.colecao === null) {
            alert("Nenhum campo do formulário setado como chave primária.");
        }

        this.dataGrid = DatagridCollection.create(this.colecao).configure("order", false);
        var tamanhoColunas = Number(100 / this.formulario.campos.length).toFixed();


        for (var row = 0; row < this.formulario.campos.length; row++) {
            var campo = this.formulario.campos[row];

            tamanhoColunas = campo.width !== undefined ? campo.width : tamanhoColunas;

            this.dataGrid.addColumn(campo.propriedade, {
                'label': campo.nome,
                "width": tamanhoColunas + "%",
                'align': 'center'
            });
        }

        this.dataGrid.show(divGrid);
        this.dataGrid.reload();

        botaoSalvar.observe('click', function () {
            self.salvar();
        });

        botaoExportar.observe('click', function () {
            self.exportarParaCsv();
        });
    };

    this.getFiltros = function () {


        var filtros = $$('.filtroDinamico');
        var filtrosRetorno = [];
        for (var filtro of filtros) {

            var campo = {
                nome: filtro.id,
                tipo: filtro.tipo,
                valor: filtro.value,
            };
            filtrosRetorno.push(campo);
        }
        return filtrosRetorno;
    };


};
