<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */
require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

$parametros = JSON::requestParameters();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <link href="estilos/avaliacao.css" rel="stylesheet" type="text/css">
    <link href="estilos/awesomplete.css" rel="stylesheet" type="text/css">
    <script src="scripts/scripts.js" rel="script" type="text/javascript"></script>
    <script src="scripts/prototype.js" rel="script" type="text/javascript"></script>
    <script src="scripts/object.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInput.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/DBInputHora.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputCep.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputCNPJ.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputCpf.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputDate.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputInteger.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputTelefone.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputValor.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBCheckBox.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBRadio.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/Collection.widget.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/avaliacao/DBViewFormulario.classe.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/avaliacao/DBViewGrupoPerguntas.classe.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/avaliacao/DBViewPergunta.classe.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/avaliacao/DBViewResposta.classe.js" rel="script" type="text/javascript"></script>
    <script src="scripts/awesomplete.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/avaliacao/DBAutoComplete.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/eSocial/DBAutoCompleteEsocial.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/avaliacao/DBViewRespostaNula.classe.js" rel="script" type="text/javascript"></script>
    <script src="scripts/classes/http/http.js" rel="script" type="text/javascript"></script>
    <style>
        .controle {
            width: 80px;
        }

        #anterior {
            margin-left: 2px;
            float: left;
        }

        #proximo {
            margin-right: 2px;
            float: right;
        }

        .db-tooltip {
            display: none;
        }
    </style>
</head>
<body>
<form class="container" style="width: 800px;">
    <fieldset id="fieldsetContribuinte" style="display: none">
        <legend>Contribuinte</legend>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td>
                    <select id="contribuinte">
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset id="fieldsetEmpregador" style="display: none">
        <legend>Empregador</legend>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td>
                    <select id="empregador" name="empregador" title="Empregador" class="field-size-max"></select>
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset>
        <legend id="legendaFormulario">Formulário EFD-Reinf</legend>
        <div id="questionario"></div>
    </fieldset>
    <input type="button" id="anterior" name="anterior" value="Anterior" class="controle"/>
    <input type="button" id="limpar" name="limpar" value="Limpar" class="controle"/>
    <input type="button" id="salvar" name="salvar" value="Salvar" class="controle"/>
    <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" class="controle" style="display: none"/>
    <input type="button" id="proximo" name="proximo" value="Próximo" class="controle"/>
</form>
<?php db_menu(); ?>
<script rel="stylesheet" type="text/javascript">
    const EFD_REINF = '1';
    const ESOCIAL = '2';
    const rpc = 'sped02_preenchimento.RPC.php';
    const urlParams = new URLSearchParams(window.location.search);
    const integracao = urlParams.get('integracao');
    const selectEmpregador = document.getElementById('empregador');
    const selectContribuinte = document.getElementById('contribuinte');
    const divQuestionario = document.getElementById('questionario');
    const buttonPesquisar = document.getElementById('pesquisar');
    const buttonProximo = document.getElementById('proximo');
    const buttonAnterior = document.getElementById('anterior');
    const buttonSalvar = document.getElementById('salvar');
    const buttonLimpar = document.getElementById('limpar');

    var formulario = null;
    var preenchimento = null;
    var empregadorSelecionado = null;

    const inicializar = () => {
        const formData = new FormData();
        formData.append('acao', 'inicializar');
        formData.append('integracao', integracao);

        HttpClient.post(rpc, {
            body: formData
        }).then(response => {
            if (response.erro) {
                throw response.mensagem;
            }

            if (integracao === EFD_REINF) {
                const contribuintes = response.contribuinte;
                contribuintes.forEach(i => {
                    let option = new Option(i.descricao, i.cgm)
                    selectContribuinte.appendChild(option);
                })
                document.getElementById('fieldsetContribuinte').style.display = '';
            }

            if (integracao === ESOCIAL) {
                response.empregadores.map(empregadorOption => {
                    selectEmpregador.add(new Option(empregadorOption.nome, empregadorOption.cgm));
                });
                document.getElementById('fieldsetEmpregador').style.display = '';
                document.getElementById('legendaFormulario').innerHTML = 'Formulário eSocial'
            }
        }).then(buscar).catch(mensagem => alert(mensagem));
    };

    const salvar = (codigoGrupo = null) => {
        if (!formulario.getStatus().grupoAtual.isValido()) {
            return alert("Há informações obrigatórias inconsistentes.\nVerifique o preenchimento.");
        }

        const formData = new FormData();

        const parametros = JSON.parse('<?php echo JSON::create()->stringify($parametros); ?>');
        Object.keys(parametros).map(key => {
            formData.append(key, parametros[key]);
        });

        formData.append('acao', 'salvar');
        formData.append('formularioTipo', urlParams.get('formularioTipo'));
        formData.append('codigoAvaliacao', formulario.codigo);
        formData.append('perguntasRespostas', JSON.stringify(formulario.getDados(codigoGrupo)));

        formulario.grupos.itens.each(grupo => {
            grupo.perguntas.itens.each(pergunta => {

                if ((pergunta.elemento.down('input').type !== 'radio')) {
                    formData.append(pergunta.identificador_campo, pergunta.elemento.down('input').value);
                } else {
                    pergunta.respostas.itens.forEach(function(item){
                        formData.append(pergunta.identificador_campo, item.codigo);
                    });
                }
            });
        });

        if (integracao === EFD_REINF) {
            formData.append('cgm', selectContribuinte.value);
        }

        if (integracao === ESOCIAL) {
            formData.append('cgm', selectEmpregador.value);
        }

        if (codigoGrupo) {
            formData.append('codigoGrupoPerguntas', codigoGrupo);
        }

        if (preenchimento) {
            formData.append('preenchimento', preenchimento);
        }

        HttpClient.post(rpc, {
            body: formData
        }).then(response => {
            alert(response.mensagem);
            const possuiProximoGrupo = (formulario.comboBox.selectedIndex + 1) < formulario.grupos.get().length;
            if (!response.erro && possuiProximoGrupo) {
                preenchimento = response.preenchimento;
                formulario.avancarGrupo();
            }
        });
    };

    const montar = response => {
        if (response.erro) {
            return alert(response.mensagem);
        }

        const sugestao = response.sugestao ? response.sugestao : null;

        preenchimento = response.hasOwnProperty('preenchimento') ? response.preenchimento : null;

        formulario = DBViewFormulario.makeFromObject(response.formulario).
            setEvent('changeStep', () => { controlarBotoes() } ).
            setSugestao(sugestao).
            show(divQuestionario);

        DBViewRespostaNula.adicionaRespostaNula(formulario);

        if (response.somenteLeitura) {
            formulario.grupos.itens.map(grupo => {
                grupo.perguntas.itens.map(pergunta => {
                    if (response.somenteLeitura.indexOf(pergunta.identificador_campo) >= 0) {
                        pergunta.elemento.disabled = true;
                    }
                });
            });
        }

        buttonProximo.addEventListener('click', () => salvar(formulario.getStatus().grupoAtual.getCodigo()));
        buttonAnterior.addEventListener('click', () => {
            if (confirm("As informações preenchidas poderão ser perdidas.\nTem certeza que deseja voltar?")) {
                formulario.recurarGrupo();
            }
        });

        buttonSalvar.addEventListener('click', () => salvar());
        buttonLimpar.addEventListener('click', () => {
            if (formulario.getStatus().grupoAtual) {
                formulario.getStatus().grupoAtual.limparRespostas();
            }
        });
    };

    const buscar = () => {
        divQuestionario.innerHTML = '';

        const formData = new FormData();
        const parametros = JSON.parse('<?php echo JSON::create()->stringify($parametros); ?>');
        parametros.acao = 'buscar';

        if (integracao === EFD_REINF) {
            parametros.cgm = selectContribuinte.value;
        }

        if (integracao === ESOCIAL) {
            parametros.cgm = selectEmpregador.value;
        }

        Object.keys(parametros).map(key => {
            formData.append(key, parametros[key]);
        });

        HttpClient.post(rpc, {
            body: formData
        }).then(montar);
    };

    const controlarBotoes = () => {
        if (formulario) {
            const status = formulario.getStatus();

            DBAutoCompleteEsocial.gerarAutoComplete();
            DBViewRespostaNula.adicionaRespostaNula(formulario);

            buttonProximo.disabled = true;
            buttonAnterior.disabled = true;
            buttonSalvar.disabled = true;

            if (status.grupoPosterior) {
                buttonProximo.disabled = false;
            }

            if (status.grupoAnterior) {
                buttonAnterior.disabled = false;
            }

            if (status.grupoAtual) {
                buttonSalvar.disabled = false;
            }
        }
    };

    selectEmpregador.addEventListener('focus', () => empregadorSelecionado = selectEmpregador.value);
    selectEmpregador.addEventListener('change', () => {
        if (confirm('As informações não salvas serão perdidas ao selecionar outro empregador. Deseja continuar?')) {
            buscar();
        } else {
            selectEmpregador.value = empregadorSelecionado;
        }
    });

    selectContribuinte.addEventListener('focus', () => contribuinteSelecionado = selectContribuinte.value);
    selectContribuinte.addEventListener('change', () => {
        if (confirm('As informações não salvas serão perdidas ao selecionar outro contribuinte. Deseja continuar?')) {
            buscar();
        } else {
            selectContribuinte.value = contribuinteSelecionado;
        }
    });

    buttonPesquisar.addEventListener('click', () => {
        if (confirm('Ao sair da tela sem salvar suas alterações serão perdidas. Deseja sair?')) {
            window.history.back();
        }
    });

    if (window.history.length > 1) {
        buttonPesquisar.style.display = '';
    }

    inicializar();
</script>
</body>
</html>
