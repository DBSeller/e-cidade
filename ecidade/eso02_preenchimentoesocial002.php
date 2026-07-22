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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$parametros = JSON::requestParameters();

$nome = '';
if (isset($_POST['z01_nome'])) {
    $nome = $_POST['z01_nome'];
}
?>
<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="estilos/grid.style.css">
    <link rel="stylesheet" href="estilos/avaliacao.css">
    <link rel="stylesheet" href="estilos/awesomplete.css">
    <script src="scripts/scripts.js"></script>
    <script src="scripts/prototype.js"></script>
    <script src="scripts/object.js"></script>
    <script src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script src="scripts/widgets/DBInputHora.widget.js"></script>
    <script src="scripts/widgets/Input/DBInputCep.widget.js"></script>
    <script src="scripts/widgets/Input/DBInputCNPJ.js"></script>
    <script src="scripts/widgets/Input/DBInputCpf.widget.js"></script>
    <script src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <script src="scripts/widgets/Input/DBInputTelefone.widget.js"></script>
    <script src="scripts/widgets/Input/DBInputValor.widget.js"></script>
    <script src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js"></script>
    <script src="scripts/widgets/Input/DBCheckBox.widget.js"></script>
    <script src="scripts/widgets/Input/DBRadio.widget.js"></script>
    <script src="scripts/widgets/Collection.widget.js"></script>
    <script src="scripts/classes/avaliacao/DBViewFormulario.classe.js"></script>
    <script src="scripts/classes/avaliacao/DBViewGrupoPerguntas.classe.js"></script>
    <script src="scripts/classes/avaliacao/DBViewPergunta.classe.js"></script>
    <script src="scripts/classes/avaliacao/DBViewResposta.classe.js"></script>
    <script src="scripts/classes/avaliacao/DBViewResposta.classe.js"></script>
    <script src="scripts/awesomplete.js"></script>
    <script src="scripts/classes/avaliacao/DBAutoComplete.js"></script>
    <script src="scripts/classes/eSocial/DBAutoCompleteEsocial.js"></script>
    <script src="scripts/classes/avaliacao/DBViewRespostaNula.classe.js"></script>
    <script src="scripts/AjaxRequest.js"></script>
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
    <fieldset>
        <legend><label for="cgmEmpregador">Escolha o Empregador</label></legend>
        <select name="cgmEmpregador" id="cgmEmpregador" style="width:100%">
            <option value="">Selecione o empregador</option>
        </select>
    </fieldset>
    <fieldset>
        <legend>Trabalhador</legend>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td>
                    <input type="text" name="nome" id="nome" class="readonly" title="Nome" value="<?=$nome?>" disabled>
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset>
        <legend>Formulário eSocial</legend>
        <div id="questionario"></div>
    </fieldset>
    <input type="button" id="anterior" name="anterior" value="Anterior" class="controle"/>
    <input type="button" id="limpar" name="limpar" value="Limpar" class="controle"/>
    <input type="button" id="salvar" name="salvar" value="Salvar" class="controle"/>
    <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" class="controle"/>
    <input type="button" id="proximo" name="proximo" value="Próximo" class="controle"/>
    <input type="hidden" id="matricula" name="matricula" value="<?= $parametros->rh01_regist ?>"/>
    <input type="hidden" id="formularioTipo" name="formularioTipo" value="<?= $parametros->formularioTipo ?>"/>

    <form>

        <?php
        if (!isset($iframe) || !$iframe) {
            db_menu();
        }
        ?>

        <script>
            const urlRpc = "eso2_preenchimentoesocial.RPC.php";
            var viewAvaliacao = '';
            var instituicao = <?php echo db_getsession("DB_instit")?>;

            (function() {

                const parametros = {'exec': 'getEmpregadores', 'instituicao': instituicao};

                new AjaxRequest('eso4_esocialapi.RPC.php', parametros, function(retorno) {
                    if (retorno.erro) {
                        alert('Desculpe, não encontramos nenhum Empregador vinculado na instituição.\nContate o suporte.');
                        return;
                    }

                    const cgm = document.querySelector("select[name=cgmEmpregador]");

                    cgm.options.length = 0;
                    cgm.add(new Option('Selecione o empregador', ''));
                    for (var empregador of retorno.empregadores) {
                        const nome = empregador.documento + ' - ' + empregador.nome;
                        cgm.add(new Option(nome, empregador.cgm));
                    }

                    if (retorno.empregadores.length == 1) {
                        cgm.value = retorno.empregadores[0].cgm;
                    }

                    buscarAvaliacao();
                }).setMessage('Buscando empregadores.').execute();
            })();

            function buscarAvaliacao() {

                removeEventoBotoes();

                $('questionario').innerHTML = '';

                const params = {
                    exec: 'buscarAvaliacao',
                    trazerSugestoes: true,
                    matricula: document.querySelector('input[name=matricula]').value,
                    formularioTipo: document.querySelector('input[name=formularioTipo]').value,
                    cgmEmpregador: document.querySelector("select[name=cgmEmpregador]").value
                };

                const ajaxRequest = new AjaxRequest(urlRpc, params, montarAvaliacao);
                ajaxRequest.setMessage('Buscando dados da avaliação...');
                ajaxRequest.execute();
            }

            function buscarHorarios() {

                document.querySelectorAll("input[identificador*=codHorContrat]:not([aria-owns])").forEach(function(elemento){
                    new DBAutoComplete(elemento, "eso4_autocompleteesocial.RPC.php?exec=buscarHorarios", true);
                });
            }

            function montarAvaliacao(response, erro) {

                if (erro) {
                    alert(response.mensagem);
                }

                const sugestao = response.sugestao ? response.sugestao : null;
                viewAvaliacao = DBViewFormulario.makeFromObject(response.formulario)
                    .setEvent('changeStep', controlarBotoes)
                    .setSugestao(sugestao)
                    .show($('questionario'));

                DBViewRespostaNula.adicionaRespostaNula(viewAvaliacao);

                $('proximo').observe('click', function () {
                    viewAvaliacao.avancarGrupo();
                });

                $('anterior').observe('click', function () {
                    viewAvaliacao.recurarGrupo();
                });

                $('salvar').observe('click', function () {
                    salvarQuestionario(viewAvaliacao);
                });
                $('limpar').observe('click', function () {

                    if (viewAvaliacao.getStatus().grupoAtual) {
                        viewAvaliacao.getStatus().grupoAtual.limparRespostas();
                    }
                });
            }

            function salvarQuestionario(viewAvaliacao, codigoGrupo) {

                if (!viewAvaliacao.getStatus().grupoAtual.isValido()) {
                    alert("Há informações obrigatórias inconsistentes.\nVerifique.");
                    return false;
                }
                var retorno = true;
                const ajaxRequest = new AjaxRequest(
                    urlRpc,
                    {
                        exec: 'salvarAvaliacao',
                        matricula: document.querySelector('input[name=matricula]').value,
                        formularioTipo: document.querySelector('input[name=formularioTipo]').value,
                        codigoAvaliacao: viewAvaliacao.codigo,
                        codigoGrupoPerguntas: codigoGrupo,
                        perguntasRespostas: viewAvaliacao.getDados(codigoGrupo),
                        cgmEmpregador: document.querySelector("select[name=cgmEmpregador]").value
                    },

                    function (response, erro) {

                        if (!codigoGrupo || erro) {
                            alert(response.mensagem);
                        }
                        if (erro) {
                            retorno = false;
                            return;
                        }

                        viewAvaliacao.avancarGrupo();
                    }
                );

                ajaxRequest.setMessage('Salvando dados da avaliação...');
                ajaxRequest.execute();
                return retorno;
            }

            var controlarBotoes = function (event) {
                DBAutoCompleteEsocial.gerarAutoComplete();
                DBViewRespostaNula.adicionaRespostaNula(viewAvaliacao);
                buscarHorarios();
                var status = this.getStatus();

                $('proximo').disabled = true;
                $('anterior').disabled = true;
                $('salvar').disabled = true;

                if (status.grupoPosterior) {
                    $('proximo').disabled = false;
                }

                if (status.grupoAnterior) {
                    $('anterior').disabled = false;
                }

                if (status.grupoAtual) {
                    $('salvar').disabled = false;
                }
            };

            function removeEventoBotoes() {

                $('salvar').stopObserving('click');
                $('proximo').stopObserving('click');
                $('anterior').stopObserving('click');
            }

            $('pesquisar').addEventListener('click', function () {

                if (confirm('Ao sair da tela sem salvar suas alterações serão perdidas. Deseja sair?')) {
                    location.href = 'eso02_preenchimentoesocial001.php?formularioTipo='+document.querySelector('input[name=formularioTipo]').value;
                }
            });
        </script>
</body>
</html>
