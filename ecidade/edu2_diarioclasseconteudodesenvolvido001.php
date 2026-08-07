<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_app.utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_aluno");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js,
                  prototype.js,
                  strings.js,
                  arrays.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbmessageBoard.widget.js,
                  dbcomboBox.widget.js,
                  dbtextField.widget.js,
                  webseller.js,
                  DBVisualizadorImpressaoTexto.js");
    db_app::load('widgets/DBToggleList.widget.js');
    db_app::load('classes/educacao/escola/ListaDisciplinas.classe.js');
    db_app::load("estilos.css,
                  grid.style.css,
                  dbVisualizadorImpressaoTexto.style.css");
    ?>
    <style type="text/css">
        .tabela tr td:FIRST-CHILD {
            width: 150px;
        }

        .prePagina {
            font-family: monospace;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body style='padding-top: 25px' bgcolor="#cccccc">
<div class="container">
    <form name="form1" id='frmDiarioClasse' method="post">
        <div style='display:table;' id='ctnForm'>
            <fieldset>
                <legend style="font-weight: bold">Diário de Classe - Registro de Aula</legend>
                <table class="tabela" border='0' width="100%">
                    <tr>
                        <td nowrap title="">
                            <b>Escola : </b>
                        </td>
                        <td nowrap id="ctnCboEscola">
                        </td>
                    </tr>
                    <tr>
                        <td nowrap title="">
                            <b>Calendário : </b>
                        </td>
                        <td nowrap id="ctnCboCalendario">
                        </td>
                    </tr>
                    <tr>
                        <td nowrap title="">
                            <b>Turma : </b>
                        </td>
                        <td nowrap id="ctnCboTurma">
                        </td>
                    </tr>
                    <tr>
                        <td nowrap title="">
                            <b>Período: </b>
                        </td>
                        <td nowrap id="ctnCboPeriodo">
                        </td>
                    </tr>
                </table>

                <fieldset id="fieldsetDisciplinas" class='separator'>
                    <legend>Disciplinas</legend>
                    <div id='listaDisciplinas'></div>
                </fieldset>

                <fieldset id="fieldsetEmissao" class='separator'>
                    <legend>Emissão</legend>
                </fieldset>

                <table class="tabela">
                    <tr>
                        <td class="bold">
                            <label>Número de páginas</label>
                        </td>
                        <td>
                            <select id='numeroPaginas'>
                                <option value='1' selected="selected">1</option>
                                <option value='2'>2</option>
                                <option value='3'>3</option>
                                <option value='4'>4</option>
                                <option value='5'>5</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="ctnMostrarHabilidades">
                        <td colspan="2">
                            <input type="checkbox" id="cboExibirTodasDisciplinas">
                            <label for="cboExibirTodasDisciplinas">Exibir Todas Disciplinas</label>
                            <span style="color: red; font-size: 9px">*Marque este modelo em caso de turmas de anos iniciais/currículo</span>
                            </br>
                            <input type="checkbox" id="cboMostrarHabilidades">
                            <label for="cboMostrarHabilidades">Exibir Habilidades Lançadas</label>
                            </br>
                            <input type="checkbox" id="cboExibirAnexo" disabled>
                            <label for="cboExibirAnexo">Emitir Relatório Descritivo das Habilidades</label>
                            </br>
                            <input type="checkbox" id="cboDatasVigencias">
                            <label for="cboDatasVigencias">Emitir Todos os Professores da Turma</label>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap="nowrap" class="bold">
                            <label>Preenchimento:</label>
                        </td>
                        <td nowrap="nowrap" class="bold">
                            <input type="radio" value='manual' name='preenchimento' id='manualPreenchimento'
                                   checked="checked"/>
                            <label for='manualPreenchimento'>Registro Manual</label>
                            <input type="radio" value='diario' name='preenchimento' id='diarioPreenchimento'/>
                            <label for='diarioPreenchimento'>Registro de Conteúdo</label>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <input name="btnImprimir" id="btnImprimir" type="button" value="Imprimir">
        <input type="hidden" id='sSessionNome'>
    </form>
</div>
</body>
<?php
db_menu();
?>
<script type="text/javascript">

    var anoCalendario = ''; // oCboCalendario.getAttributeOptionSelected('data-ano')
    var sUrlRpc = "edu4_escola.RPC.php";
    var sRpcBase = "edu_educacaobase.RPC.php";

    var oDisciplina = new DBViewFormularioEducacao.ListaDisciplinas();
    oDisciplina.show($('listaDisciplinas'));

    oCboEscola = new DBComboBox("cboEscola", "oCboEscola", null, "100%");
    oCboEscola.addItem("", "Selecione");
    oCboEscola.addEvent("onChange", "js_pesquisarCalendario()");
    oCboEscola.show($('ctnCboEscola'));

    oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "100%");
    oCboCalendario.addItem("", "Selecione");
    oCboCalendario.addEvent("onChange", "js_pesquisarTurmas()");
    oCboCalendario.show($('ctnCboCalendario'));

    oCboTurma = new DBComboBox("cboTurma", "oCboTurma", null, "100%");
    oCboTurma.addEvent("onChange", "js_pesquisarDisciplinas()");
    oCboTurma.addItem("", "Selecione");
    oCboTurma.show($('ctnCboTurma'));

    oCboPeriodo = new DBComboBox("cboPeriodo", "oCboPeriodo", null, "100%");
    oCboPeriodo.show($('ctnCboPeriodo'));

    function init() {

        carredaDadosEscola();
    }

    function carredaDadosEscola() {

        var oParametros = new Object();
        oParametros.exec = 'getEscola';
        oParametros.filtraModulo = true;

        js_divCarregando('Aguarde, pesquisando Escolas...<br>Esse procedimento pode levar algum tempo.', 'msgBox');
        var oAjax = new Ajax.Request(sUrlRpc,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: js_retornoPreencheEscolas
            }
        );

    }

    function js_retornoPreencheEscolas(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oAjax.responseText);
        oCboEscola.clearItens();
        oCboEscola.addItem("", "Selecione");
        oRetorno.itens.each(function (oEscola, iSeq) {
            oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
        });
        if (oRetorno.itens.length == 1) {
            oCboEscola.setValue(oRetorno.itens[0].codigo_escola);
            oCboEscola.lDisabled = true;
            oCboEscola.setDisable();
            js_pesquisarCalendario();
        }
    }

    function js_pesquisarCalendario() {

        oDisciplina.clear();
        oCboCalendario.clearItens();
        oCboCalendario.addItem("", "Selecione");
        oCboTurma.clearItens();
        oCboTurma.addItem("", "Selecione");
        oCboPeriodo.clearItens();
        oCboPeriodo.addItem("", "Selecione");

        if (oCboEscola.getValue() == "") {
            return false;
        }
        js_divCarregando('Aguarde, pesquisando calendario', 'msgBox');

        var oParametros = new Object();
        oParametros.exec = "PesquisaCalendario";
        oParametros.escola = oCboEscola.getValue();

        var oAjax = new Ajax.Request(sUrlRpc, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParametros),
            onComplete: js_retornoPesquesarCalendario
        });


    }

    function js_retornoPesquesarCalendario(oResponse) {

        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oResponse.responseText);

        oRetorno.aResult.each(function (oCalendario, iSeq) {
            var ano = new Object();
            ano.nome = 'data-ano';
            ano.valor = oCalendario.ed52_i_ano;
            oCboCalendario.addItem(
                oCalendario.ed52_i_codigo,
                oCalendario.ed52_c_descr.urlDecode(),
                null,
                new Array(ano)
            );
        });

        if (oRetorno.aResult.length == 1) {

            oCboCalendario.setValue(oCalendario.ed52_i_codigo);
            js_pesquisarTurmas();
        }
    }

    function js_pesquisarTurmas() {
        oDisciplina.clear();
        oCboTurma.clearItens();
        oCboTurma.addItem("", "Selecione");
        oCboPeriodo.clearItens();
        oCboPeriodo.addItem("", "Selecione");

        if (oCboCalendario.getValue() == "") {
            return false;
        }

        anoCalendario = oCboCalendario.getAttributeOptionSelected('data-ano');

        if (anoCalendario < 2020) {
            $('ctnMostrarHabilidades').hide();
        } else {
            $('ctnMostrarHabilidades').show();
        }

        js_divCarregando('Aguarde, pesquisando turmas', 'msgBox');

        var oParametros = new Object();
        oParametros.exec = "buscaTurmasPorCalendarioEscola";
        oParametros.iEscola = oCboEscola.getValue();
        oParametros.iCalendario = oCboCalendario.getValue();

        var oAjax = new Ajax.Request('edu4_turmas.RPC.php',
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: js_retornoGetTurmas
            });
    }

    function js_retornoGetTurmas(oResponse) {

        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oResponse.responseText);

        oRetorno.aTurmas.each(function (oTurma, iSeq) {
            const nome = oTurma.sTurma.urlDecode() + ' - ' + oTurma.sEtapa.urlDecode();
            oCboTurma.addItem(oTurma.iTurma, nome, null, [{nome: 'etapa', valor: oTurma.iEtapa}]);
        });

        if (oRetorno.aTurmas.length == 1) {
            oCboTurma.setValue(oRetorno.aTurmas[0].codigo_turma);
            js_pesquisarPeriodos();
            js_pesquisarDisciplinas();
        } else {
            js_pesquisarPeriodos();
        }
    }

    function js_pesquisarPeriodos() {

        js_divCarregando('Aguarde, pesquisando Periodos', 'msgBox');

        var oParametros = new Object();
        oParametros.exec = "getPeriodosAvaliacaoEscola";
        oParametros.iCalendario = oCboCalendario.getValue();

        var oAjax = new Ajax.Request(sUrlRpc,
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParametros),
                onComplete: js_retornoGetPeriodos
            });
    }

    function js_retornoGetPeriodos(oResponse) {

        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oResponse.responseText);

        oRetorno.aPeriodos.each(function (oPeriodo, iSeq) {
            oCboPeriodo.addItem(oPeriodo.codigo_periodo, oPeriodo.descricao_periodo.urlDecode());
        });
    }

    function js_pesquisarDisciplinas() {

        oDisciplina.getDisciplinas(oCboTurma.getValue(), oCboTurma.getAttributeOptionSelected('etapa'), false);
        return;
    }


    function moveSelected(oComboOrigin, oComboDestiny) {

        if (oComboOrigin.getValue() != null) {

            var aItens = oComboOrigin.getValue();
            aItens.each(function (oItem, iSeq) {

                oItem = oComboOrigin.aItens[oItem];
                oComboDestiny.addItem(oItem.id, oItem.descricao);
                oComboOrigin.removeItem(oItem.id);
            });
        }

    }

    function moveAll(oComboOrigin, oComboDestiny) {

        oComboOrigin.aItens.each(function (oItem, iSeq) {

            oComboDestiny.addItem(oItem.id, oItem.descricao);
            oComboOrigin.removeItem(oItem.id);
        });
    }

    init();

    const radioManualPreenchimento = document.getElementById('manualPreenchimento');
    const radioDiarioPreenchimento = document.getElementById('diarioPreenchimento');
    const cboMostrarHabilidades = document.getElementById('cboMostrarHabilidades');
    const cboExibirTodasDisciplinas = document.getElementById('cboExibirTodasDisciplinas');
    const cboDatasVigencias = document.getElementById('cboDatasVigencias');
    const cboExibirAnexo = document.getElementById('cboExibirAnexo');

    const controlaCboExibirAnexo = () => {
        if (cboMostrarHabilidades.checked && radioDiarioPreenchimento.checked) {
            cboExibirAnexo.disabled = false;
        } else {
            cboExibirAnexo.disabled = true;
            cboExibirAnexo.checked = false;
        }
    };
    cboExibirTodasDisciplinas.addEventListener('change', () => {
        if (cboExibirTodasDisciplinas.checked) {
            radioDiarioPreenchimento.disabled = true
        } else {
            radioDiarioPreenchimento.disabled = false
        }
    })
    cboMostrarHabilidades.addEventListener('change', () => {
        controlaCboExibirAnexo();
    });
    radioManualPreenchimento.addEventListener('change', () => {
        controlaCboExibirAnexo();
    });
    radioDiarioPreenchimento.addEventListener('change', () => {
        controlaCboExibirAnexo();
    });

    $('btnImprimir').observe('click', function () {

        var aDisciplinaSelecionadas = oDisciplina.getSelecionados();
        if (aDisciplinaSelecionadas.length == 0) {

            alert('Selecione ao menos uma disciplina para impressão do relatório');
            return false;
        }

        var aDisciplinas = new Array();
        aDisciplinaSelecionadas.each(function (oDisciplina, id) {
            aDisciplinas.push(oDisciplina.iRegencia);
        });

        var sPreenchimento = 'manual';

        if ($('diarioPreenchimento').checked) {
            sPreenchimento = 'diario';
        }

        fonteEmissao = 'edu2_diarioclasseconteudodesenvolvido002.php';
        if (anoCalendario < 2020) {
            fonteEmissao = 'edu2_diarioclasseconteudodesenvolvido002_2019.php';
        }

        var sUrlRelatorio = fonteEmissao;
        sUrlRelatorio += '?escola=' + oCboEscola.getValue();
        sUrlRelatorio += '&calendario=' + oCboCalendario.getValue();
        sUrlRelatorio += '&turma=' + oCboTurma.getValue();
        sUrlRelatorio += '&etapa=' + oCboTurma.getAttributeOptionSelected('etapa');
        sUrlRelatorio += '&periodo=' + oCboPeriodo.getValue();
        sUrlRelatorio += '&disciplinas=' + aDisciplinas;
        sUrlRelatorio += '&paginas=' + $F('numeroPaginas');
        sUrlRelatorio += '&preenchimento=' + sPreenchimento;
        sUrlRelatorio += '&mostrarHabilidades=' + cboMostrarHabilidades.checked;
        sUrlRelatorio += '&mostraTodasDisciplinas=' + cboExibirTodasDisciplinas.checked;
        sUrlRelatorio += '&exibirAnexo=' + cboExibirAnexo.checked;
        sUrlRelatorio += '&emitirVigencia=' + cboDatasVigencias.checked;
        sUrlRelatorio += '&lRegistroOcorrencia=false';

        if (cboExibirAnexo.checked) {
            confirm("Algumas disciplinas podem não possuir habilidades lançadas no período selecionado, não apresentando portanto descrição das habilidades.");
        }

        jan = window.open(sUrlRelatorio, '',
            'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0');
        jan.moveTo(0, 0);
    });

</script>
</html>
