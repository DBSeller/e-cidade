const PRE_NATAL = '12';

/**
 * Classe Responsável por montar uma view para cadastro e/ou visualização dos problemas/condições de saúde do Paciente.
 * 
 * @see forms/db_frmfichaatendproced.php
 * 
 * Dependências:
 * <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
 * <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
 * <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css" rel="stylesheet"/>
 * <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css" rel="stylesheet"/>
 * <script type="text/javascript" src="scripts/scripts.js"></script>
 * <script type="text/javascript" src="scripts/strings.js"></script>
 * <script type="text/javascript" src="scripts/classes/http/http.js"></script>
 * <script type="text/javascript" src="scripts/session.js"></script>
 * <script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
 * <script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
 * <script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
 * <script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
 */
class ViewProblemasPaciente {
    /**
     * @param {HTMLElement} divProblemas 
     * @param {boolean} permiteManutencao 
     */
    constructor(divProblemas, permiteManutencao = false, openInModal = true) {
        this.routes = {
            salvar: 'saude/ambulatorial/procedimento/problemaspaciente/salvar',
            apagar: 'saude/ambulatorial/procedimento/problemaspaciente/apagar',
            byPaciente: 'saude/ambulatorial/consulta/problemaspaciente/by-paciente',
            getAll: 'saude/ambulatorial/consulta/problemas',
            vinculaProntuario: 'saude/ambulatorial/procedimento/prontuario-problemaspaciente/vincular'
        };
        this.inputs = {};
        this._div = divProblemas;
        this._permiteManutencao = permiteManutencao;
        this._openInModal = openInModal;
        this._montaHtml();

        if (openInModal) {
            this._addDivMask(divProblemas.parentNode);
            this._montaWindowAux();
        }

        if (permiteManutencao) {
            this._addEventos();
        }
    }

    /**
     * Mostra a tabela com os problemas do paciente
     * @param {integer} idPaciente 
     */
    async show(idPaciente) {
        if (this._openInModal) {
            return;
        }

        this._montaTabelaProblemas();
        await this._buscarProblemasPaciente(idPaciente);
    }

    /**
     * Abre a window para visualização e/ou cadastro
     * @param {integer} idPaciente 
     * @param {string} nomePaciente 
     */
    async open(idPaciente, nomePaciente, idProntuario = '') {
        if (!this._openInModal) {
            return;
        }

        this.inputs.paciente.value = idPaciente;
        document.getElementById('nome-paciente').value = nomePaciente;
        this.inputs.prontuario.value = idProntuario;
        this._divMask.hidden = false;
        this._window.show(0, 0);
        this._montaTabelaProblemas();
        await this._buscarProblemasPaciente(idPaciente);

        if (this._permiteManutencao) {
            this._limparCampos();
            await this._buscarProblemas();
        }
    }

    /**
     * Monta uma div para mascara para garantir a funcionalidade da bootstrape table, pois ao usar a classe DBMask
     * presente na estrutura da windowAux, o componente constroi a mascara no body principal da estrutura HTML,
     * o que não é viável na rotina de atendimento.
     * @param {HTMLElement} div 
     */
    _addDivMask(div) {
        let divMask = document.createElement('div');
        divMask.style.position = 'fixed';
        divMask.style.top = '0px';
        divMask.style.left = '0px';
        divMask.style.right = '0px';
        divMask.style.bottom = '0px';
        divMask.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
        divMask.hidden = true;
        div.appendChild(divMask);

        this._divMask = divMask;
    }
    
    /**
     * Monta a estrutura da view
     */
    _montaHtml() {
        if (this._openInModal) {
            this._montaInputs();
            this._div.appendChild(document.createElement('br'));
        }
        
        this._montaTabela();
    }

    /**
     * Retorna a url da API do laravel
     * @returns {Promise | string}
     */
    async _getUrlApi() {
        if (PHPSession.requestApi === undefined) {
            await PHPSession.loadData();
        }
    
        return PHPSession.requestApi;
    }

    /**
     * Busca os problemas/condições cadastrados é monta o select
     */
    async _buscarProblemas() {
        HttpClient.get(`${await this._getUrlApi()}/${this.routes.getAll}`).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
        
            this.inputs.problema.options.length = 0;
            this.inputs.problema.add(new Option('Selecione...', ''));
            for (let dado of response.data) {
                this.inputs.problema.add(new Option(dado.descricao, dado.id));
            }
        });
    }

    /**
     * Busca os problemas do paciente e carrega na tabela
     * @param {integer} idPaciente 
     */
    async _buscarProblemasPaciente(idPaciente) {
        HttpClient.get(`${await this._getUrlApi()}/${this.routes.byPaciente}/${idPaciente}`).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
        
            this.table.bootstrapTable('load', response.data);
        });
    }

    /**
     * Adiciona os eventos dos inputs
     */
    _addEventos() {
        let labelDataInicio = document.getElementById('inicio-dum');
        let labelDataFim = document.getElementById('fim-ddp');
        let dataFim = this.inputs.dataFim.getElements();

        this.inputs.problema.addEventListener('change', () => {
            labelDataInicio.innerHTML = 'Inicio: ';
            this.inputs.ativo.disabled = false;
            this.inputs.ativo.checked = true;
            labelDataFim.hidden = false;
            dataFim.inputText.hidden = false;
            dataFim.inputButton.hidden = false;
            this.inputs.dataInicio.setValue('');
            this.inputs.dataFim.setValue('');
            // Modifica o label das datas e oculta a data de fim quando o problema for pré-natal
            if (this.inputs.problema.value == PRE_NATAL) {
                labelDataInicio.innerHTML = 'DUM: ';
                labelDataFim.hidden = true;
                dataFim.inputText.hidden = true;
                dataFim.inputButton.hidden = true;
            }
        });

        /**
         * controla a exibição da data de fim mostrando ela caso o problema seja pré-natal
         */
        this.inputs.resolvido.addEventListener('click', () => {
            if (this.inputs.problema.value == PRE_NATAL) {  
                labelDataFim.hidden = false;
                dataFim.inputText.hidden = false;
                dataFim.inputButton.hidden = false;
            }
        });

        /**
         * controla a exibição da data de fim ocultando ela caso o problema seja pré-natal e limpa o campo data fim
         */
        this.inputs.ativo.addEventListener('click', () => {
            if (this.inputs.problema.value == PRE_NATAL) {
                this.inputs.dataFim.setValue('');
                labelDataFim.hidden = true;
                dataFim.inputText.hidden = true;
                dataFim.inputButton.hidden = true;
            }
        });

        /**
         * Controla o campo ativo e resolvido, marcado o campo resolvido e desabilitando o campo ativo
         * quando for informado uma data fim 
         */
        dataFim.inputText.observe('blur', () => {
            this.inputs.ativo.disabled = false;
            if (this.inputs.problema.value != PRE_NATAL) {
                this.inputs.ativo.checked = true;
            }

            if (this.inputs.dataFim.__toLocaleDateString() != null) {
                this.inputs.ativo.disabled = true;
                this.inputs.resolvido.checked = true;
            }
        });
    }

    /**
     * Define as propriedades da windowAux
     */
    _montaWindowAux() {
        let height = 600;
        if (this._permiteManutencao) {
            height = 700;
        }

        this._window = new windowAux('wndProblemas', 'Problemas/Condições de Saúde do Paciente', 1250, height);
        this._window.setContent(this._div);
        this._window.setShutDownFunction(() => {
            this._divMask.hidden = true;
            this._window.hide();
        });
    }

    /**
     * Retorna o valor do input da data de inicio
     * @returns {string}
     */
    _getDataInicio() {
        if (!this.inputs.dataInicio.getValue()) {
            return '';
        }

        return js_formatar(this.inputs.dataInicio.__toLocaleDateString(), 'd');
    }

    /**
     * Retorna o valor do input da data de fim
     * @returns {string}
     */
    _getDataFim() {
        if (!this.inputs.dataFim.getValue()) {
            return '';
        }

        return js_formatar(this.inputs.dataFim.__toLocaleDateString(), 'd');
    }

    /**
     * Valida se os dados à serem salvos são válidos
     * @returns {boolean}
     */
    _isValido() {
        if (this.inputs.problema.value == '') {
            alert('Informe um problema!');
            return false;
        }
    
        if (this.inputs.problema.value == PRE_NATAL && this._getDataInicio() == '') {
            alert('Informe a DUM!');
            return false;
        }

        if (this.inputs.problema.value == PRE_NATAL && this.inputs.resolvido.checked && this._getDataFim() == '') {
            alert('Informe a data fim da gestação!');
            return false;
        }
        
        return true;
    }

    /**
     * Realiza um post para a API salvando os dados do formulário
     */
    async _adicionarProblema() {
        const formData = new FormData;

        if (!this._isValido()) {
            return;
        }
        
        if (this.inputs.id.value !== '') {
            formData.append('id', this.inputs.id.value);
        }

        formData.append('prontuario', this.inputs.prontuario.value);
        formData.append('problema', this.inputs.problema.value);
        formData.append('paciente', this.inputs.paciente.value);
        formData.append('dataInicio', this._getDataInicio());
        formData.append('dataFim', this._getDataFim());
        formData.append('ativo', document.querySelector('input[name="situacao"]:checked').value);

        PHPSession.appendFormData(formData);

        HttpClient.post(`${await this._getUrlApi()}/${this.routes.salvar}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.error) {
                return;
            }

            this._limparCampos();
            this._buscarProblemasPaciente(this.inputs.paciente.value);
        });
    }

    /**
     * Limpa os campos
     */
    _limparCampos() {
        let labelDataInicio = document.getElementById('inicio-dum');
        let labelDataFim = document.getElementById('fim-ddp');
        let dataFim = this.inputs.dataFim.getElements();

        this.inputs.alert.hidden = true;
        this.inputs.id.value = '';
        this.inputs.problema.value = '';
        this.inputs.problema.classList.remove('readonly');
        this.inputs.problema.disabled = false;
        this.inputs.ativo.disabled = false;
        this.inputs.ativo.checked = true;
        labelDataInicio.innerHTML = 'Inicio: ';
        this.inputs.dataInicio.setValue('');
        labelDataFim.hidden = false;
        this.inputs.dataFim.setValue('');
        dataFim.inputText.hidden = false;
        dataFim.inputButton.hidden = false;
    }

    /**
     * Apaga o problema passado por parametro
     * @param {integer} id 
     */
    async _apagarProblema(id) {
        const formData = new FormData;
                  
        formData.append('id', id);
        
        PHPSession.appendFormData(formData);
        
        HttpClient.post(`${await this._getUrlApi()}/${this.routes.apagar}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.error) {
                return;
            }

            this._limparCampos();
            this._buscarProblemasPaciente(this.inputs.paciente.value);
        });
    }

    /**
     * Retorna um objeto com as ações de alterar e apagar para uso na bootstrapTable
     * @returns {Object}
     */
    _getAcoes() {
        return {
            'click .alterar': (e, d, data) => {
                this.inputs.id.value = data.id;
                this.inputs.problema.value = data.idProblema;
                this.inputs.problema.addClassName('readonly');
                this.inputs.problema.disabled = true;
                this.inputs.dataInicio.setValue(data.dataInicio);
                this.inputs.dataFim.setValue(data.dataFim);
                this.inputs.resolvido.checked = true;
                if (data.ativo) {
                    this.inputs.ativo.checked = true;
                }
              
                if (this.inputs.alert.firstChild) {
                    this.inputs.alert.removeChild(this.inputs.alert.firstChild);
                }
              
                let p = document.createElement('p');
                p.innerHTML = `Editando registro de código ${data.id}`;
                this.inputs.alert.appendChild(p);
                this.inputs.alert.hidden = false;
            },
            'click .apagar': (e, d, data) => {
                alertify.confirm('Confirma a exclusão do registro?', confirma => {
                    if (confirma) {
                        this._apagarProblema(data.id);
                    }
                });
            }
        };
    }

    /**
     * Retorna um array de objetos com as colunas a serem exibidas na bootstrapTable
     * @returns {Array}
     */
    _getColunas() {
        let colunas = [
            {
                field: 'id',
                title: 'Código',
                halign: 'center',
                align: 'center',
                width: 80
            },
            {
                field: 'problema',
                title: 'Problema/Condição',
                halign: 'center',
                align: 'left',
                width: 320
            },
            {
                field: 'data',
                title: 'Data',
                halign: 'center',
                align: 'center',
                width: 100
            },
            {
                field: 'profissional',
                title: 'Profissional',
                halign: 'center',
                align: 'left',
                width: 320
            },
            {
                field: 'situacao',
                title: 'Situação',
                halign: 'center',
                align: 'center',
                width: 100
            },
            {
                field: 'dataInicio',
                title: 'Inicio',
                halign: 'center',
                align: 'center',
                width: 100
            },
            {
                field: 'dataFim',
                title: 'Fim',
                halign: 'center',
                align: 'center',
                width: 100
            }
        ];

        if (this._permiteManutencao) {
            let acoes = this._getAcoes();
            colunas.push({
                field: 'actions',
                title: 'Ações',
                halign: 'center',
                align: 'Center',
                width: 80,
                formatter: () => {
                    let btnAlterar = '<a class="alterar" title="Alterar"><i class="fas fa-edit"></i></i></a>';
                    let btnApagar = '<a class="apagar" title="Apagar"><i class="fas fa-trash-alt"></i></a>';
                    return `${btnAlterar}&nbsp;${btnApagar}`
                },
                events: acoes
            });
        }

        return colunas
    }

    /**
     * Monta a bootstrapTable
     */
    _montaTabelaProblemas() {
        this.table = jQuery('#data-table-problemas');

        let colunas = this._getColunas();

        this.table.bootstrapTable({
            height: 400,
            search: true,
            detailView: true,
            columns: colunas,
            detailFormatter: (i, row, detail) => {
                let tabela = detail.html('<table></table>').find('table');
                let colunas = [
                    {
                        field: 'prontuario',
                        title: 'Atendimento',
                        halign: 'center',
                        align: 'center',
                    },
                    {
                        field: 'data',
                        title: 'Data',
                        halign: 'center',
                        align: 'center'
                    }
                ];
        
                /**
                 * Se o problema for pré-natal é necessário mostrar a idade gestacional na data do atendimento
                 */
                if (row.idProblema == 12) {
                    colunas.push({
                        field: 'idadeGestacional',
                        title: 'Idade Gestacional na Data do Atendimento',
                        halign: 'center',
                        align: 'center',
                        formatter: (a, data) => {
                            let dataDum = js_formatar(row.dataInicio, 'd');
                            let dataConsulta = js_formatar(data.data, 'd');
                            let diferenca = js_diferenca_datas(dataDum, dataConsulta, 'd');
                
                            let semanas = parseInt(diferenca / 7);
                            let dias = parseInt(diferenca % 7);

                            let detalhe = '';

                            if (data.prontuario == row.consultas[0].prontuario) {
                                // Calcula a data do provavel do parto, somando 294 dias a data DUM
                                let timeDum = (new Date(dataDum)).getTime();
                                let dataParto = new Date(timeDum + 294 * 86400000);

                                detalhe = `&nbsp&nbsp<b>DPP</b>: ${dataParto.toLocaleDateString('pt-BR')}`;
                            }
                
                            return `${semanas} semanas e ${dias} dias.${detalhe}`;
                        }
                    });
            
                    if (
                        row.ativo 
                        && (row.consultas.length == 0 || row.consultas[0].prontuario !== '<b>Atual</b>') 
                        && this._openInModal
                    ) {
                        row.consultas.unshift({
                            prontuario: '<b>Atual</b>',
                            data: (new Date()).toLocaleDateString('pt-BR'),
                            dataInicio: row.dataInicio
                        });
                    }
                }
            
                tabela.bootstrapTable({
                    columns: colunas,
                    data: row.consultas
                });
            }
        });
    }

    /**
     * Monta os inputs a serem exibidos na tela
     */
    _montaInputs() {
        let div = document.createElement('div');
        div.setAttribute('id', 'alert-problema');
        div.setAttribute('role', 'alert');
        div.classList.add('alert-info');
        div.style.textAlign = 'center';
        div.hidden = true;
        this._div.appendChild(div);
        this.inputs.alert = div;

        div = document.createElement('div');
        div.classList.add('container');
        this._div.appendChild(div);

        let fieldset = document.createElement('fieldset');
        div.appendChild(fieldset);
        
        let legend = document.createElement('legend');
        legend.innerHTML = 'Dados do Paciente';
        fieldset.appendChild(legend);

        let table = document.createElement('table');
        table.classList.add('form-container');
        table.style.textAlign = 'left';
        fieldset.appendChild(table);

        /** 
         * <tr>
         *   <td><label>Paciente:</label></td>
         *   <td colspan="3">
         *     <input type="hidden" id="id-problemapaciente">
         *     <input type="hidden" id="id-faa">
         *     <input type="text" id="id-paciente" class="field-size2 readonly" readonly>
         *     <input type="text" id="nome-paciente" class="field-size8 readonly" readonly>
         *   </td>
         * </tr> 
         */
        let tr = document.createElement('tr');
        table.appendChild(tr);
        let td = document.createElement('td');
        tr.appendChild(td);
        let label = document.createElement('label');
        label.innerHTML = 'Paciente:';
        td.appendChild(label);
        td = document.createElement('td');
        tr.appendChild(td);
        /** id da tabela ProblemasPaciente */
        let input = document.createElement('input');
        input.setAttribute('id', 'id-problemapaciente');
        input.hidden = true;
        td.appendChild(input);
        this.inputs.id = input;
        /** id do prontuario */
        input = document.createElement('input');
        input.setAttribute('id', 'id-prontuario');
        input.hidden = true;
        td.appendChild(input);
        this.inputs.prontuario = input;
        /** id do paciente em atendimento */
        input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('id', 'id-paciente');
        input.classList.add('field-size2');
        input.classList.add('readonly');
        input.readOnly = true;
        td.appendChild(input);
        this.inputs.paciente = input;
        /** nome do paciente em atendimento */
        input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('id', 'nome-paciente');
        input.classList.add('field-size8');
        input.classList.add('readonly');
        input.readOnly = true;
        td.appendChild(input);
        
        if (this._permiteManutencao) {
            legend.innerHTML = 'Adicionar problema/condição de saúde';

            /**
             * <tr>
             *   <td><label for="select-problema">Problema/Condição:</label></td>
             *   <td colspan="3">
             *     <select id="select-problema">
             *       <option value="">Selecione...</option>
             *     </select>
             *   </td>
             * </tr>
             */
            tr = document.createElement('tr');
            table.appendChild(tr);
            td = document.createElement('td');
            tr.appendChild(td);
            label = document.createElement('label');
            label.setAttribute('for', 'select-problema');
            label.innerHTML = 'Problema/Condição:';
            td.appendChild(label);
            td = document.createElement('td');
            tr.appendChild(td);
            /** select com os problemas à adicionar */
            input = document.createElement('select');
            input.setAttribute('id', 'select-problema');
            input.add(new Option('Selecione...', ''));
            td.appendChild(input);
            this.inputs.problema = input;

            /**
             * <tr>
             *   <td><label>Situação:</label></td>
             *   <td>
             *     <input type="radio" name="situacao" id="radio-ativo" value='1' checked>
             *     <label for="radio-ativo" style="vertical-align: top;">Ativo</label>&nbsp;&nbsp;
             *     <input type="radio" name="situacao" id="radio-resolvido" value='0'>
             *     <label for="radio-resolvido" style="vertical-align: top;">Resolvido</label>
             *   </td>
             * </tr>
             */
            tr = document.createElement('tr');
            table.appendChild(tr);
            td = document.createElement('td');
            tr.appendChild(td);
            label = document.createElement('label');
            label.innerHTML = 'Situação:';
            td.appendChild(label);
            td = document.createElement('td');
            tr.appendChild(td);
            input = document.createElement('input');
            input.setAttribute('type', 'radio');
            input.setAttribute('name', 'situacao');
            input.setAttribute('id', 'radio-ativo');
            input.setAttribute('value', '1');
            input.checked = true;
            td.appendChild(input);
            this.inputs.ativo = input;
            label = document.createElement('label');
            label.setAttribute('for', 'radio-ativo');
            label.style.verticalAlign = 'top';
            label.innerHTML = ' Ativo ';
            td.appendChild(label);
            td.append('\xa0\xa0');
            input = document.createElement('input');
            input.setAttribute('type', 'radio');
            input.setAttribute('name', 'situacao');
            input.setAttribute('id', 'radio-resolvido');
            input.setAttribute('value', '0');
            td.appendChild(input);
            this.inputs.resolvido = input;
            label = document.createElement('label');
            label.setAttribute('for', 'radio-resolvido');
            label.style.verticalAlign = 'top';
            label.innerHTML = ' Resolvido';
            td.appendChild(label);

            /**
             * <tr>
             *   <td><label id="inicio-dum">Inicio:</label></td>
             *   <td>
             *     <input id="data-inicio"></td>&nbsp;&nbsp;
             *     <label>Fim:</label>
             *     <input id="data-fim">
             *   </td>
             * </tr>
             */
            tr = document.createElement('tr');
            table.appendChild(tr);
            td = document.createElement('td');
            tr.appendChild(td);
            label = document.createElement('label');
            label.setAttribute('for', 'data-inicio');
            label.setAttribute('id', 'inicio-dum');
            label.innerHTML = 'Inicio:';
            td.appendChild(label);
            td = document.createElement('td');
            tr.appendChild(td);
            input = document.createElement('input');
            input.setAttribute('id', 'data-inicio');
            td.appendChild(input);
            td.append('\xa0\xa0');
            this.inputs.dataInicio = new DBInputDate(input);
            label = document.createElement('label');
            label.setAttribute('for', 'data-fim');
            label.setAttribute('id', 'fim-ddp');
            label.innerHTML = 'Fim:';
            td.appendChild(label);
            input = document.createElement('input');
            input.setAttribute('id', 'data-fim');
            td.appendChild(input);
            this.inputs.dataFim = new DBInputDate(input);

            let button = document.createElement('button');
            button.onclick = () => { this._adicionarProblema(); };
            button.innerHTML = '<i class="fas fa-save"></i> Salvar';
            div.appendChild(button);
            
            button = document.createElement('button');
            button.onclick = () => { this._limparCampos(); };
            button.innerHTML = '<i class="fas fa-eraser"></i> Limpar';
            div.appendChild(button);
        }
    }

    /**
     * Monta a area da tabela que a bootstrapTable irá usar
     */
    _montaTabela() {
        let div = document.createElement('div');
        div.classList.add('subcontainer');
        div.style.width = '1200px';

        this._div.appendChild(div);

        let fieldset = document.createElement('fieldset');
        
        div.appendChild(fieldset);

        let legend = document.createElement('legend');
        legend.innerHTML = 'Problemas/Condições de Saúde do Paciente';

        fieldset.appendChild(legend);

        let table = document.createElement('table');
        table.setAttribute('id', 'data-table-problemas');
        table.classList.add('table');
        table.classList.add('table-sm');
        
        fieldset.appendChild(table);
    }
}