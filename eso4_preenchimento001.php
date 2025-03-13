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

$rh01_regist = '';
$matricula = null;
$lTrazerSugestoes = false;

db_postmemory($_GET);
db_postmemory($_POST);

$nome = empty($z01_nome) ? '' : $z01_nome;

if (!empty($rh01_regist)) {
    $matricula = $rh01_regist;
}

if (!isset($semVinculo)) {
    $semVinculo = false;
}

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DBSeller Informática Ltda</title>
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
    <script src="scripts/awesomplete.js"></script>
    <script src="scripts/classes/avaliacao/DBAutoComplete.js"></script>
    <script src="scripts/classes/eSocial/DBAutoCompleteEsocial.js"></script>
    <script src="scripts/classes/avaliacao/DBViewRespostaNula.classe.js"></script>
    <script src="scripts/AjaxRequest.js"></script>
    <script src="scripts/widgets/DBLookUp.widget.js"></script>
    <script src="scripts/classes/http/http.js" rel="script" type="text/javascript"></script>
    <style>
        .controle {
            width: 80px;
        }

        .w-100 {
            width: 100% !important;
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
    <?php
    if (empty($matricula)) {
        $lTrazerSugestoes = true;
        ?>
        <fieldset>
            <legend>
                <label for="matricula">Escolha sua Matrícula</label>
            </legend>
            <select id="matricula" style="width:100%" onchange="buscarAvaliacao(Event)"></select>
        </fieldset>
        <?php
    } else {
        $lTrazerSugestoes = true;
        ?>
        <input type="hidden" id="matricula" name="matricula" value="<?= $matricula ?>"/>
        <?php
    }
    ?>
    <fieldset id="fieldsetEmpregador">
        <legend>Empregador</legend>
        <table style="width: 100%">
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
        <legend>Servidor(a)</legend>
        <table class="w-100">
            <tbody>
            <tr>
                <td>
                    <input type="text" id="nome" name="nome" class="w-100" value="<?php echo $nome; ?>" disabled>
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset>
        <legend>Formulário de Cadastro para o eSocial</legend>
        <div id="questionario"></div>
    </fieldset>
    <input type="button" id="anterior" name="anterior" value="Anterior" class="controle"/>
    <input type="button" id="limpar" name="limpar" value="Limpar" class="controle"/>
    <input type="button" id="salvar" name="salvar" value="Salvar" class="controle"/>
    <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" class="controle"/>
    <input type="button" id="proximo" name="proximo" value="Próximo" class="controle"/>
    <input type="hidden" id="sem_vinculo" name="sem_vinculo" value="<?php echo $semVinculo ?>"/>
</form>
<?php

if (!isset($iframe) || !$iframe) {
    db_menu();
}

?>

<script>
    const selectEmpregador = document.getElementById('empregador');
    const inputPesquisar = document.getElementById('pesquisar');
    const inputMatricula = document.getElementById('matricula');
    const inputNome = document.getElementById('nome');

    const dbLookUp = new DBLookUp(
        document.createElement('a'),
        document.createElement('input'),
        document.createElement('input'),
        {
            'sArquivo': 'func_rhpessoal.php',
            'oObjetoLookUp': 'func_nome',
            'aParametrosAdicionais': [
                'vinculados=true'
            ]
        }
    );
    dbLookUp.setCamposAdicionais(['rh01_regist','z01_nome']);
    dbLookUp.setCallBack('onClick', argumentos => {
        inputMatricula.value = argumentos[0];
        inputNome.value = argumentos[3];
        buscarAvaliacao();
    });

    const pesquisar = () => {
        dbLookUp.abrirJanela(true);
    };

    inputPesquisar.addEventListener('click', pesquisar);

    const inicializar = () => {
        const formData = new FormData();
        formData.append('acao', 'inicializar');
        formData.append('integracao', '2');

        HttpClient.post('sped02_preenchimento.RPC.php', {
            body: formData
        }).then(response => {
            if (response.erro) {
                throw response.mensagem;
            }

            response.empregadores.map(empregadorOption => {
                selectEmpregador.add(new Option(empregadorOption.nome, empregadorOption.cgm));
            });
        }).catch(mensagem => alert(mensagem));
    };

    inicializar();

    var viewAvaliacao;
    var iMatriculaAnterior = '';

    (() => {
        try {
            buscarMatriculas();
        }
        catch (e) {
            alert(e);
        }
    })();

    function buscarHorarios() {
        Array.from(document.querySelectorAll('input[identificador*=codHorContrat]:not([aria-owns])')).
            forEach(elemento => {
                new DBAutoComplete(elemento, 'eso4_autocompleteesocial.RPC.php?exec=buscarHorarios', true);
            });
    }

    function buscarAvaliacao(e = null) {
        if (e) {
            if (!confirmaSaida(
                'Se você alterar a matrícula os dados que não foram salvos serão perdidos.\nTem certeza que deseja trocar de matrícula?')) {
                $('matricula').value = iMatriculaAnterior;
                return false;
            }
        }

        removeEventoBotoes();

        iMatriculaAnterior = $F('matricula');
        $('questionario').innerHTML = '';

        var iMatricula = $F('matricula');
        var oDados = {};
        oDados.exec = 'buscarAvaliacao';
        oDados.trazerSugestoes = true;
        oDados.semVinculo = $F('sem_vinculo');

        if (!empty(iMatricula)) {
            oDados.iMatricula = iMatricula;
        }

        var oAjaxRequest = new AjaxRequest('eso4_preenchimento.RPC.php', oDados, montarAvaliacao);
        oAjaxRequest.setMessage('Buscando dados da avaliação...');
        oAjaxRequest.execute();
    }

    function montarAvaliacao(oResponse, lErro) {
        if (lErro) {
            alert(oResponse.mensagem);
        }

        inputNome.value = oResponse.servidor.cgm.nome;

        viewAvaliacao = DBViewFormulario.makeFromObject(oResponse.oFormulario).
            setEvent('changeStep', controlarBotoes).
            setSugestao(oResponse.sugestao).
            show($('questionario'));

        DBViewRespostaNula.adicionaRespostaNula(viewAvaliacao);

        $('proximo').observe('click', function() {
            this.blur();
            viewAvaliacao.avancarGrupo();
        });

        $('anterior').observe('click', function() {
            viewAvaliacao.recurarGrupo();
        });

        $('salvar').observe('click', function() {
            salvarQuestionario(viewAvaliacao);
        });

        $('limpar').observe('click', function() {
            if (viewAvaliacao.getStatus().grupoAtual) {
                viewAvaliacao.getStatus().grupoAtual.limparRespostas();
            }
        });
    }

    function salvarQuestionario(viewAvaliacao, iCodigoGrupo) {
        if (!viewAvaliacao.getStatus().grupoAtual.isValido()) {
            alert('Há informações obrigatórias inconsistentes.\nVerifique.');
            return false;
        }

        var lRetorno = true;
        var oAjaxRequest = new AjaxRequest(
            'eso4_preenchimento.RPC.php',
            {
                exec: 'salvarAvaliacao',
                iMatricula: iMatriculaAnterior,
                iCodigoAvaliacao: viewAvaliacao.codigo,
                iCodigoGrupoPerguntas: iCodigoGrupo,
                lSemVinculo: $F('sem_vinculo'),
                aPerguntasRespostas: viewAvaliacao.getDados(iCodigoGrupo),
                empregador: selectEmpregador.value
            },

            function(oResponse, lErro) {
                if (!iCodigoGrupo || lErro) {
                    alert(oResponse.mensagem);
                }
                if (lErro) {
                    lRetorno = false;
                    return;
                }

                viewAvaliacao.avancarGrupo();
            }
        );

        oAjaxRequest.setMessage('Salvando dados da avaliação...');
        oAjaxRequest.execute();

        return lRetorno;
    }

    var controlarBotoes = function() {
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

    function buscarMatriculas() {
        if (!empty($F('matricula'))) {
            buscarAvaliacao();
            return;
        }

        var oDados = {};
        oDados.exec = 'getMatriculas';

        var oAjaxRequest = new AjaxRequest('eso4_preenchimento.RPC.php', oDados, function(oResponse, lErro) {
            var oComboMatriculas = $('matricula');
            oComboMatriculas.options.lenght = 0;

            if (lErro) {
                alert(oResponse.mensagem);
                return false;
            }

            for (oMatricula of oResponse.matriculas) {
                var oOption = new Option(oMatricula.matricula + ' - ' + oMatricula.nome, oMatricula.matricula);
                oComboMatriculas.add(oOption);
            }

            buscarAvaliacao();
        });
        oAjaxRequest.setMessage('Buscando dados da avaliação...');
        oAjaxRequest.execute();

    }

    function removeEventoBotoes() {
        $('salvar').stopObserving('click');
        $('proximo').stopObserving('click');
        $('anterior').stopObserving('click');
    }

    function confirmaSaida(sMensagem) {
        if (typeof sMensagem == 'undefined' || sMensagem == null || sMensagem == false) {
            sMensagem = 'Você está saindo do cadastro do eSocial.\nAntes de sair, salve seus dados.';
        }

        return confirm(sMensagem);
    }

    if (parent.windowLiberacao) {
        parent.windowLiberacao.setShutDownFunction(function() {
            if (!confirmaSaida()) {
                return false;
            }

            parent.windowLiberacao.destroy();
        });
    }
</script>
</body>
</html>
