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
    <script rel="script" type="text/javascript" src="scripts/classes/educacao/escola/ListaEscola.classe.js"></script>
    <script rel="script" type="text/javascript"
            src="scripts/classes/educacao/escola/ListaCalendario.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/educacao/escola/ListaEtapa.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
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
            </table>
        </fieldset>
        <input type="button" id="btnEmitirRelatorio" value="Emitir relatório">
    </form>
</div>

<?php db_menu(); ?>
<script>
    const urlRpc = 'edu1_confirmacao_rematricula.RPC.php';
    const colunaEscola = document.querySelector('#listaEscola');
    const colunaCalendario = document.querySelector('#listaCalendario');
    const comboEscola = new DBViewFormularioEducacao.ListaEscola();
    const comboCalendario = new DBViewFormularioEducacao.ListaCalendario();
    const btnEmitirRelatorio = document.querySelector('#btnEmitirRelatorio');

    const onLoadEscola = () => {
        const escolaSelecionada = comboEscola.getSelecionados();

        if (escolaSelecionada.codigo_escola != '') {
            comboCalendario.setEscola(escolaSelecionada.codigo_escola);
            comboCalendario.getCalendarios();
        }
    };

    const onChangeEscola = () => {
        const escolaSelecionada = comboEscola.getSelecionados();

        comboCalendario.limpar();

        if (escolaSelecionada.codigo_escola == '') {
            return false;
        }

        comboCalendario.setEscola(escolaSelecionada.codigo_escola);
        comboCalendario.getCalendarios();
    };

    const onLoadCalendario = () => {
        if (comboCalendario.oElement.options.length == 2) {
            comboCalendario.oElement.value = comboCalendario.oElement.options[1].value;
        }
    };

    const onChangeCalendario = () => {
        const calendarioSelecionado = comboCalendario.getSelecionados();

        if (calendarioSelecionado.iCalendario == '') {
            return false;
        }
    };

    comboEscola.setCallBackLoad(onLoadEscola);
    comboEscola.setCallbackOnChange(onChangeEscola);
    comboEscola.show(colunaEscola);

    comboCalendario.setCallBackLoad(onLoadCalendario);
    comboCalendario.setOnChangeCallBack(onChangeCalendario);
    comboCalendario.show(colunaCalendario);

    btnEmitirRelatorio.addEventListener('click', () => {
        const escola = document.querySelector('select#cboEscola');
        const calendario = document.querySelector('select#cboCalendario');

        if (!escola.value) {
            alert('É necessário selecionar uma escola para realizar a pesquisa.');
            return false;
        }

        if (!calendario.value) {
            alert('É necessário selecionar um calendário para realizar a pesquisa.');
            return false;
        }

        const formData = new FormData();
        formData.append('escola', escola.value);
        formData.append('calendario', calendario.value);
        formData.append('acao', 'emitirRelatorio');

        HttpClient.post(urlRpc, {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return false;
            }

            js_arquivo_abrir(response.arquivo);
        });
    });
</script>

</body>
</html>
