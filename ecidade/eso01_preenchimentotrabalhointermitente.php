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
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputCep.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBRadio.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewFormulario.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewGrupoPerguntas.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewPergunta.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewResposta.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBViewRespostaNula.classe.js"></script>
    <script rel="script" type="text/javascript" src="scripts/awesomplete.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/avaliacao/DBAutoComplete.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/eSocial/DBAutoCompleteEsocial.js"></script>
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
        <legend>Trabalhador</legend>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td>
                    <input type="text" name="nome" id="nome" class="readonly" title="Nome" disabled>
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
    const rpc = 'eso01_preenchimentotrabalhointermitente.RPC.php';
    const formularioContainer = document.getElementById('formulario');
    const anterior = document.querySelector('#anterior');
    const novo = document.querySelector('#novo');
    const salvar = document.querySelector('#salvar');
    const proximo = document.querySelector('#proximo');
    const pesquisar = document.querySelector('#pesquisar');
    const empregadorSelect = document.querySelector('#empregador');

    var viewAvaliacao;
    var autoCompleteTipoEvento;

    new DBLookUp(pesquisar, document.createElement('input'), document.createElement('input'), {
        'sArquivo': 'func_trabalhointermitente.php',
        'sObjetoLookUp': 'db_iframe_trabalhointermitente',
        'sLabel': 'Pesquisar Trabalho Intermitente',
    });

    anterior.addEventListener('click', () => viewAvaliacao.recurarGrupo());
    proximo.addEventListener('click', () => viewAvaliacao.avancarGrupo());
    salvar.addEventListener('click', salvarFormulario);
    novo.addEventListener('click', () => {
        carregar();
        pesquisar.click();
    });
    pesquisar.click();

    buscarEmpregador();
    carregar();

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
                empregadorSelect.add(new Option(empregador.empregador, empregador.cgm));
            });
        });
    }

    function carregar(preenchimento = null) {
        js_divCarregando('Buscando Formulário', 'loading_message');

        autoCompleteTipoEvento = null;

        const formData = new FormData();
        formData.append('acao', 'buscar');

        if (preenchimento) {
            formData.append('preenchimento', preenchimento);
        }

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

            formularioContainer.innerHTML = '';
            viewAvaliacao = DBViewFormulario.makeFromObject(response.formulario).
                setEvent('changeStep', controlarBotoes).
                show(formularioContainer);
            DBAutoCompleteEsocial.gerarAutoComplete();
        });
    }

    function jaExisteConvocacao(codConv, matricula) {
        const formData = new FormData();
        formData.append('acao', 'verificarSeExisteConvocacao');
        formData.append('codConv', codConv);
        formData.append('matricula', matricula);

        return fetch(rpc, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => response.json()).then(response => {
            if (response.erro) {
                return alert(response.mensagem);
            }

            return response;
        });
    }

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

        const codConv = formData.get('codConv');
        const matricula = formData.get('matricula');

        jaExisteConvocacao(codConv, matricula).then(response => {
            if (response.existe && confirm(response.mensagem) === false) {
                return js_removeObj('loading_message');
            }

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
        });
    }

    function controlarBotoes() {
        DBViewRespostaNula.adicionaRespostaNula(viewAvaliacao);
        DBAutoCompleteEsocial.gerarAutoComplete();

        var elemento = document.querySelector('input[identificador=uf]');
        if (elemento) {
            var caminhoJSON = 'arquivos/esocial/tabelas/uf.json';
            new DBAutoComplete(elemento, caminhoJSON, true);
        }

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

    function preencherSugestoes(servidor) {
        document.querySelector('#nome').value = servidor.nome;

        carregar().then(() => {
            viewAvaliacao.grupos.itens.each(grupo => {
                grupo.perguntas.itens.each(pergunta => {
                    if (pergunta.identificador_campo === 'matricula') {
                        pergunta.elemento.down('input').value = servidor.matricula;
                    }
                    if (pergunta.identificador_campo === 'nisTrab') {
                        pergunta.elemento.down('input').value = servidor.nis;
                    }
                    if (pergunta.identificador_campo === 'cpfTrab') {
                        pergunta.elemento.down('input').value = servidor.cpf;
                    }
                });
            });
        });
    }

    function preencherRespostas(preenchimento) {
        carregar(preenchimento.preenchimento);
    }
</script>
<?php db_menu(); ?>
</body>
