<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" type="text/css" href="estilos/avaliacao.css">
    <link rel="stylesheet" type="text/css" href="estilos/awesomplete.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputCpf.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBRadio.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewResposta.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewPergunta.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewGrupoPerguntas.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewFormulario.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/eSocial/DBAutoCompleteEsocial.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewRespostaNula.classe.js"></script>
    <script src="scripts/awesomplete.js"></script>
    <script src="scripts/classes/avaliacao/DBAutoComplete.js"></script>
    <title>DBSeller Informática Ltda</title>
    <style>
        #anterior {
            margin-left: 2px;
            float: left;
        }

        #proximo {
            margin-right: 2px;
            float: right;
        }

        .DBJanelaIframe {
            top: 60px;
        }
    </style>
</head>
<body>
<form class="container" id="form" style="width: 800px;">
    <fieldset>
        <legend>Escolha o Empregador</legend>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td>
                    <select id="empregador" name="empregador" class="field-size-max" title="Empregador"></select>
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset>
        <legend>Formulário de Cadastro para o eSocial</legend>
        <div id="formulario"></div>
    </fieldset>
    <input type="button" id="anterior" name="anterior" value="Anterior" class="controle">
    <input type="button" id="novo" name="novo" value="Novo" class="controle" disabled>
    <input type="button" id="salvar" name="salvar" value="Salvar" class="controle" disabled>
    <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" class="controle">
    <input type="button" id="proximo" name="proximo" value="Próximo" class="controle">
</form>
<script type="text/javascript">
    const rpc = 'eso01_preenchimentoexclusaoeventos.RPC.php';
    const formularioContainer = document.getElementById('formulario');
    const anterior = document.querySelector('#anterior');
    const novo = document.querySelector('#novo');
    const salvar = document.querySelector('#salvar');
    const proximo = document.querySelector('#proximo');
    const empregadorSelect = document.querySelector('#empregador');

    var viewAvaliacao;
    var preenchimento;
    var autoCompleteTipoEvento;
    var formulario;

    new DBLookUp($('pesquisar'), document.createElement('input'), document.createElement('input'), {
        'sArquivo': 'func_reciboesocial.php',
        'sObjetoLookUp': 'db_iframe_reciboesocial',
        'sLabel': 'Pesquisar Recibo',
    });

    buscarEmpregador();

    function buscarEmpregador() {
        const formData = new FormData();
        formData.append('acao', 'buscarEmpregador');

        return fetch(rpc, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => response.json()).then(response => {
            if (response.erro) {
                return alert(response.mensagem);
            }

            response.empregadores.forEach(empregador => {
                $('empregador').add(new Option(empregador.empregador, empregador.cgm));
            });
        });
    }

    function preencher(recibo, empregador) {
        formularioContainer.innerHTML = '';
        viewAvaliacao = DBViewFormulario.makeFromObject(formulario).
            setEvent('changeStep', controlarBotoes).
            show(formularioContainer);

        DBAutoCompleteEsocial.gerarAutoComplete();

        viewAvaliacao.grupos.itens.each(grupo => {
            grupo.perguntas.itens.each(pergunta => {
                if (pergunta.identificador_campo === 'tpEvento') {
                    pergunta.elemento.down('input').value = recibo.evento;
                    document.querySelector('input[identificador="tpEvento_descricao"]').value = recibo.evento;
                }
                if (pergunta.identificador_campo === 'nrRecEvt') {
                    pergunta.elemento.down('input').value = recibo.numero;
                }
                if (pergunta.identificador_campo === 'cpfTrab') {
                    pergunta.elemento.down('input').value = recibo.cpf;
                }
                if (pergunta.identificador_campo === 'nisTrab') {
                    pergunta.elemento.down('input').value = recibo.nis;
                }
            });
        });

        empregadorSelect.value = empregador;
    }

    novo.addEventListener('click', carregar);

    carregar();

    function carregar() {
        js_divCarregando('Buscando Formulário', 'loading_message');

        autoCompleteTipoEvento = null;

        const formData = new FormData();
        formData.append('acao', 'buscar');

        return fetch(rpc, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            if (response.erro) {
                return alert(response.mensagem);
            }

            preenchimento = response.preenchimento;
            formularioContainer.innerHTML = '';
            formulario = response.formulario;
            viewAvaliacao = DBViewFormulario.makeFromObject(formulario).
                setEvent('changeStep', controlarBotoes).
                show(formularioContainer);

            DBAutoCompleteEsocial.gerarAutoComplete();

            proximo.addEventListener('click', () => viewAvaliacao.avancarGrupo());
            anterior.addEventListener('click', () => viewAvaliacao.recurarGrupo());
            salvar.addEventListener('click', salvarFormulario);
        });
    }

    $('pesquisar').click();

    function salvarFormulario() {
        if (!viewAvaliacao.isValido()) {
            return alert('Há informações obrigatórias inconsistentes. Verifique o preenchimento do formulário.');
        }

        js_divCarregando('Salvando Formulário', 'loading_message');

        const formData = new FormData();
        formData.append('acao', 'salvar');
        formData.append('perguntasRespostas', JSON.stringify(viewAvaliacao.getDados()));
        formData.append('empregador', empregadorSelect.value);

        viewAvaliacao.grupos.itens.each(grupo => {
            grupo.perguntas.itens.each(pergunta => {
                formData.append(pergunta.identificador_campo, pergunta.elemento.down('input').value);
            });
        });

        return fetch(rpc, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            alert(response.mensagem);

            if (response.erro) {
                return;
            }

            if ((viewAvaliacao.comboBox.selectedIndex + 1) < viewAvaliacao.grupos.get().length) {
                viewAvaliacao.avancarGrupo();
            }
        });
    }

    function controlarBotoes() {
        DBViewRespostaNula.adicionaRespostaNula(viewAvaliacao);

        const status = this.getStatus();

        proximo.disabled = true;
        anterior.disabled = true;
        salvar.disabled = true;
        novo.disabled = true;

        if (status.grupoPosterior) {
            proximo.disabled = false;
        }

        if (status.grupoAnterior) {
            anterior.disabled = false;
        }

        if (status.grupoAtual) {
            salvar.disabled = false;
            novo.disabled = false;
        }
    }
</script>
<?php db_menu(); ?>
</body>
