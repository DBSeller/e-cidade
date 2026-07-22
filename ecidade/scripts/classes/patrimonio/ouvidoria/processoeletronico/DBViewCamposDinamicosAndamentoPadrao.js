require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
require_once('scripts/widgets/DBLookUp.widget.js');
require_once('scripts/widgets/Collection.widget.js');
require_once('scripts/widgets/DatagridCollection.widget.js');
require_once('scripts/widgets/FormCollection.widget.js');
require_once('scripts/classes/http/http.js');

DBViewCamposDinamicosAndamentoPadrao = function() {

    this.RPC     = 'pro1_camposdinamicosandamentopadrao.RPC.php';
    this.largura = '100%';
    this.altura  = '100%';

    this.window  = {};
    this.idTipoProcesso = null;
    this.ordem          = null;
    this.lookupCampoDD  = null;
    this.dataGridCampos = null;
    this.campos = Collection.create().setId('codigo');
    this.callBackCloseWindow = function() {
        return true;
    };
    this.baseURL = null;

    const body = new FormData();
    body.append('acao', 'info');

    HttpClient.post(
        'con4_ecidadeinfo.RPC.php',
        { body }
    ).then(response => {
                
        if (response.error === true) {
            throw response;
        }

        if (response.message) {
            alert(response.message);
        }

        this.baseURL = response.url;
    })
    .catch(err => {
        console.error(err)
        err.message ? alert(err.message) : alert(err);
    })
}

DBViewCamposDinamicosAndamentoPadrao.prototype.openView = function() {

    this.largura = document.body.getWidth() / 2;
    this.altura  = document.body.clientHeight / 1.2;

    this.window  = new windowAux('windowCamposDinamicosAndamentoPadrao', 'Vincular campos dinâmicos ao andamento padrão', this.largura, this.altura);
    this.window.setShutDownFunction(function() {
        this.callBackCloseWindow();
        this.window.destroy();
    }.bind(this));

    js_divCarregando('Carregando view para manutenção de campos', 'loading_message');

    this.window.setContent('');
    this.window.show();
    this.window.getContentContainer().load('pro1_camposdinamicosandamentopadrao.view.php', function() {

        this.lookupCampoDD = new DBLookUp(
            $('lbl_campo'),
            $('codcam'),
            $('nomecam'),
            {
                'sLabel'   : 'Pesquisar Campos Dicionário de Dados',
                'zIndex'   : 100000,
                'sArquivo' : 'func_db_syscampo.php'
            }
        );

        this.dataGridCampos = new DatagridCollection(this.campos, 'gridCampos');
        this.dataGridCampos.configure("height", "200");
        this.dataGridCampos.addColumn('codigo', {
            width: '60px',
            align: 'center',
            label: 'ID',
        });

        this.dataGridCampos.addColumn('codcam', {
            width: '60px',
            align: 'center',
            label: 'Código',
        });

        this.dataGridCampos.addColumn('nomecam', {
            width: '240px',
            align: 'left',
            label: 'Nome do Campo',
        });

        this.dataGridCampos.addColumn('obrigatorio', {
            width: '80px',
            align: 'center',
            label: 'Obrigatório',
        }).transform(obrigatorio => (obrigatorio === true || obrigatorio === 'true') ? 'Sim' : 'Não')

        this.formCollection = new FormCollection(this.dataGridCampos, $('formCampo'));
        this.formCollection.makeBehavior($('btnSalvarCampo'),   'save',   this.salvarCampo.bind(this));
        this.formCollection.makeBehavior($('btnExcluirCampo'),  'delete', this.removerCampo.bind(this));

        this.dataGridCampos.show($('gridCampos'));

        js_removeObj('loading_message');

    }.bind(this))

    // new DBMessageBoard(
    //   'msgCamposDinamicosAndamentoPadrao',
    //   'Campos dinâmicos do andamento padrão',
    //   'Informe os campos a serem preenchidos ao dar um despacho no processo',
    // );

    $('btnNovoCampo').observe('click', function() {
        this.limpar();
    }.bind(this));
}

DBViewCamposDinamicosAndamentoPadrao.prototype.buscarCamposTipoProcesso = function() {

    const url = this.getURL(this.idTipoProcesso) + '?ordem='+ this.ordem;
    
    HttpClient.get(url)
    .then(response => {
                
        if (response.error === true) {
            throw response;
        }

        if (response.message) {
            alert(response.message);
        }

        this.campos.clear();
        response.data.forEach(c => this.campos.add({
            codigo      : c.codigo,
            codcam      : c.campo.codcam,
            nomecam     : c.campo.nomecam,
            obrigatorio : c.obrigatorio,
        }))

        this.dataGridCampos.reload();
    })
    .catch(err => {
        console.error(err)
        err.message ? alert(err.message) : alert(err);
    })
}

DBViewCamposDinamicosAndamentoPadrao.prototype.getURL = function(idTipoProcesso) {

    const 
        url = this.baseURL,
        apiUrl = `${url}/v4/api/`,
        path = `patrimonial/protocolo/processo/${idTipoProcesso}/andamento-padrao/campos-dinamicos`;

    return apiUrl + path;
}

DBViewCamposDinamicosAndamentoPadrao.prototype.limpar = function() {

    $('codigo').value = '';
    this.formCollection.clearForm();
}

DBViewCamposDinamicosAndamentoPadrao.prototype.salvarCampo = function(itemCollection) {

    if (itemCollection.codcam == '' || itemCollection.nomecam == '') {
        alert('Escolha um campo para adicionar');
        return false;
    }

    const 
        url = this.getURL(this.idTipoProcesso) + '/'+ this.ordem
        parametrosSalvarCampo = new FormData()
    ;

    parametrosSalvarCampo.append('idCampoDinamico', itemCollection.codigo);
    parametrosSalvarCampo.append('codcam', itemCollection.codcam);
    parametrosSalvarCampo.append('obrigatorio', itemCollection.obrigatorio);


    HttpClient.post(url, {
        reportProgress: true,
        reportMessage: "Salvando campo",
        body: parametrosSalvarCampo,
    })
    .then(response => {

        if (response && response.error) {
            throw response;
        }

        if (response && response.message) {
            alert(response.message)
        }

        itemCollection.codigo      = response.data.codigo;
        itemCollection.nomecam     = $F('nomecam');
        itemCollection.obrigatorio = response.data.obrigatorio;

        this.campos.add(itemCollection);
        this.dataGridCampos.reload();
        this.limpar();
    })
    .catch(err => {
        console.error(err);
        err.message ? alert(err.message) : alert(err);
    })

    return false;
}

DBViewCamposDinamicosAndamentoPadrao.prototype.removerCampo = function(itemCollection) {

    if (!confirm("Tem certeza que deseja excluir este campo?")) {
        return false;
    }

    const url = this.getURL(this.idTipoProcesso) + 
    '?ordem='+ this.ordem +
    '&codigo='+ itemCollection.codigo;
    
    HttpClient.delete(url, {
        reportProgress: true,
        reportMessage: "Excluindo campo"
    })
    .then(response => {

        if (response && response.error) {
            throw response
        }

        if (response && response.message) {
            alert(response.message)
        }

        this.campos.remove(itemCollection.codigo);
        this.dataGridCampos.reload();
    })
    .catch(err => {
        console.error(err);
        err.message ? alert(err.message) : alert(err);
    })

    this.limpar();
    return false;
}

DBViewCamposDinamicosAndamentoPadrao.prototype.setCallbackCloseWindow = function(callback) {
    this.callBackCloseWindow = callback;
}

DBViewCamposDinamicosAndamentoPadrao.prototype.show = function(idTipoProcesso, ordem) {

    this.idTipoProcesso = idTipoProcesso;
    this.ordem          = ordem;

    this.openView();
    this.buscarCamposTipoProcesso();
}
