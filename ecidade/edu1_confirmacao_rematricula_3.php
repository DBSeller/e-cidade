<?php

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/educacao/escola/ListaEscola.classe.js"></script>
    <script rel="script" type="text/javascript"
            src="scripts/classes/educacao/escola/ListaCalendario.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/educacao/escola/ListaEtapa.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/educacao/escola/ListaTurma.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>

<div class="container">
    <form>
        <fieldset style="min-width: 400px;">
            <legend>Filtros</legend>
            <table class="form-container">
                <tr>
                    <td nowrap="nowrap" class="bold field-size3">Escola:</td>
                    <td nowrap="nowrap" id="listaEscola"></td>
                </tr>
                <tr>
                    <td nowrap="nowrap" class="bold field-size3">Calendário:</td>
                    <td nowrap="nowrap" id="listaCalendario"></td>
                </tr>
                <tr>
                    <td nowrap="nowrap" class="bold field-size3">Turma:</td>
                    <td nowrap="nowrap" id="listaTurma"></td>
                </tr>
            </table>
        </fieldset>
        <input type="button" id="btnPesquisarAlunos" value="Pesquisar Alunos">
    </form>
    <div id="containerAlunos" style="display: none;">
        <fieldset>
            <legend>Alunos</legend>
            <div id="gridAlunos"></div>
        </fieldset>
        <div class="btn-wrapper">
            <input type="button" id="btnCancelarConfirmacaoRematricula" value="Cancelar Confirmação de Rematrícula">
        </div>
    </div>
</div>

<?php db_menu(); ?>
<script>
    const urlRpc = 'edu1_confirmacao_rematricula.RPC.php';
    const colunaEscola = document.querySelector('#listaEscola');
    const colunaCalendario = document.querySelector('#listaCalendario');
    const colunaTurma = document.querySelector('#listaTurma');
    const comboEscola = new DBViewFormularioEducacao.ListaEscola();
    const comboCalendario = new DBViewFormularioEducacao.ListaCalendario();
    const comboTurma = new DBViewFormularioEducacao.ListaTurma();
    const btnPesquisarAlunos = document.querySelector('#btnPesquisarAlunos');
    const btnCancelarConfirmacaoRematricula = document.querySelector('#btnCancelarConfirmacaoRematricula');
    const gridAlunos = document.querySelector('#gridAlunos');
    const containerAlunos = document.querySelector('#containerAlunos');
    var dataGridCollectionAlunos;
    var collectionAlunos;

    const limpaGrid = () => {
        containerAlunos.style.display = 'none';
        collectionAlunos.clear();
        dataGridCollectionAlunos.reload();
    };

    const mostraGrid = () => {
        containerAlunos.style.display = 'block';
    };

    const montaGrid = () => {
        collectionAlunos = new Collection();
        collectionAlunos.setId('sequencial');

        dataGridCollectionAlunos = new DatagridCollection(collectionAlunos, 'dataGridCollectionAlunos');
        dataGridCollectionAlunos.configure('height', '350px');
        dataGridCollectionAlunos.addColumn('sequencial');
        dataGridCollectionAlunos.addColumn('codigo');
        dataGridCollectionAlunos.addColumn('nome', {
            'label': 'Nome',
            'width': '90%'
        }).transform((nome, aluno) => aluno.codigo + ' - ' + nome);
        dataGridCollectionAlunos.grid.setCheckbox(0);
        dataGridCollectionAlunos.hideColumns([1, 2]);
        dataGridCollectionAlunos.show(gridAlunos);
    };

    const onLoadEscola = () => {
        const escolaSelecionada = comboEscola.getSelecionados();

        if (escolaSelecionada.codigo_escola != '') {
            comboCalendario.setEscola(escolaSelecionada.codigo_escola);
            comboCalendario.getCalendarios();
        }
    };

    const onChangeEscola = () => {
        limpaGrid();

        const escolaSelecionada = comboEscola.getSelecionados();

        comboCalendario.limpar();
        comboTurma.limpar();

        if (escolaSelecionada.codigo_escola == '') {
            return false;
        }

        comboCalendario.setEscola(escolaSelecionada.codigo_escola);
        comboCalendario.getCalendarios();
        comboTurma.setEscola(escolaSelecionada.codigo_escola);
    };

    const onLoadCalendario = () => {
        if (comboCalendario.oElement.options.length == 2) {
            comboCalendario.oElement.value = comboCalendario.oElement.options[1].value;
            comboTurma.setCalendario(comboCalendario.oElement.options[1].value);
            comboTurma.getTurmas();
        }
    };

    const onChangeCalendario = () => {
        limpaGrid();
        const calendarioSelecionado = comboCalendario.getSelecionados();
        comboTurma.limpar();

        if (calendarioSelecionado.iCalendario == '') {
            return false;
        }

        const listaCalendario = [calendarioSelecionado.iCalendario];
        comboTurma.setCalendario(listaCalendario.implode(', '));
        comboTurma.getTurmas();
    };

    const onChangeTurma = () => {
        limpaGrid();
    };

    comboEscola.setCallBackLoad(onLoadEscola);
    comboEscola.setCallbackOnChange(onChangeEscola);

    comboCalendario.setCallBackLoad(onLoadCalendario);
    comboCalendario.setOnChangeCallBack(onChangeCalendario);

    comboTurma.setCallbackOnChange(onChangeTurma);

    comboEscola.show(colunaEscola);
    comboCalendario.show(colunaCalendario);
    comboTurma.show(colunaTurma);

    btnPesquisarAlunos.addEventListener('click', () => {
        const escola = document.querySelector('select#cboEscola');
        const calendario = document.querySelector('select#cboCalendario');
        const turma = document.querySelector('select#cboTurma');

        if (!escola.value) {
            alert('É necessário selecionar uma escola para realizar a pesquisa.');
            return false;
        }

        if (!calendario.value) {
            alert('É necessário selecionar um calendário para realizar a pesquisa.');
            return false;
        }

        if (!turma.value) {
            alert('É necessário selecionar uma turma para realizar a pesquisa.');
            return false;
        }

        const formData = new FormData();
        formData.append('escola', escola.value);
        formData.append('calendario', calendario.value);
        formData.append('turma', turma.value);
        formData.append('acao', 'buscarConfirmados');

        HttpClient.post(urlRpc, {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            collectionAlunos.clear();

            if (response.alunos) {
                mostraGrid();

                response.alunos.forEach(aluno => {
                    collectionAlunos.add(aluno);
                });
            }

            dataGridCollectionAlunos.reload();
        });
    });

    btnCancelarConfirmacaoRematricula.addEventListener('click', () => {
        const alunosSelecionadosGrid = dataGridCollectionAlunos.getGrid().getSelection();

        if (alunosSelecionadosGrid.size() === 0) {
            alert('É necessário escolher ao menos um aluno para confirmar a rematrícula.');
            return false;
        }

        const escola = document.querySelector('select#cboEscola');
        const calendario = document.querySelector('select#cboCalendario');
        const turma = document.querySelector('select#cboTurma');

        const formData = new FormData();
        formData.append('acao', 'desconfirmarRematricula');
        formData.append('escola', escola.value);
        formData.append('calendario', calendario.value);
        formData.append('turma', turma.value);

        alunosSelecionadosGrid.forEach(alunoSelecionado => {
            formData.append('alunos[]', alunoSelecionado[0]);
        });

        HttpClient.post(urlRpc, {body: formData}).then(response => {
            alert(response.mensagem);

            if (response.erro) {
                return false;
            }

            alunosSelecionadosGrid.forEach(alunoSelecionado => {
                console.log(alunoSelecionado);
                collectionAlunos.remove(alunoSelecionado[0]);
            });

            dataGridCollectionAlunos.reload();
            collectionAlunos.count() === 0 ? limpaGrid() : true;
        });

    });

    montaGrid();
</script>

</body>
</html>
