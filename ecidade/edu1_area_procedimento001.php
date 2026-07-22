<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div id='ctnAbas'></div>

<div id='ctnCriarProcedimetno' class='subcontainer'>
    <fieldset>
        <legend>Avaliação da Área de Conhecimento</legend>
        <table class="form-container">
            <tr>
                <td><label for="procedimento">Vincular ao Procedimento:</label></td>
                <td>
                    <select id="procedimento">
                        <option value="">Selecione um procedimento</option>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
    <button type="button" id="btnProsseguir" disabled>
        <i class="fas fa-hand-point-right"></i>
        Continue para criar procedimento
    </button>
    <button type="button" id="btnExcluirProcedimentoArea" disabled>
        <i class="fas fa-trash"></i>
        Excluir Avaliação da Área de Conhecimento
    </button>
</div>
<div id='ctnAbaElementosAvaliacao' class='subcontainer'>
    <div class="container">
        <form id="frmElementoAvaliacao">
            <fieldset>
                <legend>Elementos de avaliação</legend>
                <table class="form-container">
                    <tr>
                        <td><label for="codigoPeriodoAvaliacao">
                                <a href="#" id="ancoraPeriodoAvaliacao">Período de Avaliação:</a></label>
                        </td>
                        <td>
                            <input id="codigoPeriodoAvaliacao" name="codigoPeriodoAvaliacao"
                                   lang="ed09_i_codigo" class="field-size2">
                            <input id="descricaoPeriodoAvaliacao" name="descricaoPeriodoAvaliacao" lang="ed09_c_descr"
                                   class="field-size8 readonly" disabled>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="ordemElementoProcedimento">Elemento do Procedimento:</label>
                        </td>
                        <td>
                            <select id="ordemElementoProcedimento" name="ordemElementoProcedimento"></select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="formaObtencao">Forma de Obtenção:</label></td>
                        <td>
                            <select id="formaObtencao" name="formaObtencao"></select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="codigoFormaAvaliacao">
                                <a href="#" id="ancoraFormaAvaliacao">Forma de Avaliação:</a></label>
                        </td>
                        <td>
                            <input id="codigoFormaAvaliacao" name="codigoFormaAvaliacao"
                                   lang="ed37_i_codigo" class="field-size2">
                            <input id="descricaoFormaAvaliacao" name="descricaoFormaAvaliacao" lang="ed37_c_descr"
                                   class="field-size8 readonly" disabled>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <input type="hidden" id="codigoAvaliacao" name="codigoAvaliacao">
            <button type="button" id="btnSalvarElementoAvalicao">
                <i class="fas fa-save"></i>
                Salvar
            </button>
            <button type="button" id="btnOrdenarElementoAvalicao">
                <i class="fas fa-sort-numeric-up"></i>
                Ordenar
            </button>
        </form>
    </div>
    <div class="subcontainer">
        <fieldset>
            <legend>Elementos configurados</legend>
            <div id="ctnElementosAvaliacao" style="width: 1300px;">
            </div>
        </fieldset>
    </div>
</div>
<div id='ctnAbaResultado' class='subcontainer'>
    <form id="formResultado">

        <fieldset>
            <legend>Configurar Resultado</legend>
            <table class="form-container">
                <tr>
                    <td><label for="codigoTipoResultado">
                            <a href="#" id="ancoraTipoResultado">Tipo de Resultado:</a></label>
                    </td>
                    <td>
                        <input id="codigoTipoResultado" name="codigoTipoResultado"
                               lang="ed42_i_codigo" class="field-size2">
                        <input id="descricaoTipoResultado" name="descricaoTipoResultado" lang="ed42_c_descr"
                               class="field-size8 readonly" disabled>
                    </td>
                </tr>
                <tr>
                    <td><label for="formaObtencaoResultado">Forma de Obtenção:</label></td>
                    <td><select name="formaObtencaoResultado" id="formaObtencaoResultado"></select></td>
                </tr>
                <tr>
                    <td><label for="codigoFormaAvaliacaoResultado">
                            <a href="#" id="ancoraFormaAvaliacaoResultado">Forma de Avaliação:</a></label>
                    </td>
                    <td>
                        <input id="codigoFormaAvaliacaoResultado" name="codigoFormaAvaliacaoResultado"
                               lang="ed37_i_codigo" class="field-size2">
                        <input id="descricaoFormaAvaliacaoResultado" name="descricaoFormaAvaliacaoResultado"
                               lang="ed37_c_descr" class="field-size8 readonly" disabled>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="hidden" id="codigoResultado" name="codigoResultado">
        <button type="button" id="btnSalvarResultado">
            <i class="fas fa-save"></i>
            Salvar
        </button>
    </form>
</div>

<!-- Modal -->
<div id="modalOrdenarElementos" class="subcontainer" style="display: block">
    <fieldset style="width: 350px; height: 180px;">
        <legend id="modal_legend">
            Elementos de Avaliação
        </legend>
        <div id="ctnOrdenar" style="float:left">
            <select id="cbOrdenarElementos" class="field-size4" size="6" multiple>

            </select>
        </div>
        <div id="btnsOrdenar" style="float:left; display: flex; flex-direction: column">
            <button type="button" id="orderUp">
                <i class="fas fa-sort-amount-up"></i>
            </button>
            <button type="button" id="orderDown">
                <i class="fas fa-sort-amount-down"></i>
            </button>
        </div>
    </fieldset>
    <button id="btnSalvarOrdemElementos">
        <i class="fa fa-save"></i>
        Salvar Ordem
    </button>
</div>

</body>
<script type="text/javascript">
    const containerAbas = document.getElementById('ctnAbas');
    const containerCriarProcedimento = document.getElementById('ctnCriarProcedimetno');
    const containerElementosAvaliacao = document.getElementById('ctnAbaElementosAvaliacao');
    const containerResultado = document.getElementById('ctnAbaResultado');

    const procedimentosEscola = [];
    var procedimentoSelecionado = {};
    var procedimentoArea = {};
    var turmasEncerradas = false;

    // elementos aba Criar Procedimento
    const selectProcedimento = document.getElementById('procedimento');
    const btnProsseguir = document.getElementById('btnProsseguir');
    const btnExcluirProcedimentoArea = document.getElementById('btnExcluirProcedimentoArea');

    // elementos aba Elementos de Avaliação
    const formularioElementoAvaliacao = document.getElementById('frmElementoAvaliacao');
    const ancoraPeriodoAvaliacao = document.getElementById('ancoraPeriodoAvaliacao');
    const inputCodigoPeriodoAvaliacao = document.getElementById('codigoPeriodoAvaliacao');
    const inputDescricaoPeriodoAvaliacao = document.getElementById('descricaoPeriodoAvaliacao');
    const selectOrdemElementoProcedimento = document.getElementById('ordemElementoProcedimento');
    const selectFormaObtencao = document.getElementById('formaObtencao');
    const ancoraFormaAvaliacao = document.getElementById('ancoraFormaAvaliacao');
    const inputCodigoFormaAvaliacao = document.getElementById('codigoFormaAvaliacao');
    const inputDescricaoFormaAvaliacao = document.getElementById('descricaoFormaAvaliacao');
    const btnSalvarElementoAvalicao = document.getElementById('btnSalvarElementoAvalicao');
    const btnOrdenarElementoAvalicao = document.getElementById('btnOrdenarElementoAvalicao');
    const inputCodigoAvaliacao = document.getElementById('codigoAvaliacao');

    const collectionElementosProcedimentoArea = new Collection().setId('codigo');
    const gridElementosProcedimentoArea = new DatagridCollection(collectionElementosProcedimentoArea).configure({
        order: false,
        height: 200
    });

    gridElementosProcedimentoArea.addColumn('elemento', {label: "Elemento", 'width': '20%'});
    gridElementosProcedimentoArea.addColumn('elementoProcedimento', {
        label: "Elemento do Procedimento",
        'width': '20%'
    });
    gridElementosProcedimentoArea.addColumn('formaObtencaoNome', {label: "Forma de Obtenção", 'width': '20%'});
    gridElementosProcedimentoArea.addColumn('formaAvaliacaoDescricao', {label: "Forma de Avaliação", 'width': '20%'});
    gridElementosProcedimentoArea.addAction('Editar', 'Editar', (event, linha) => {
        if (turmasEncerradas) {
            alert("Não é possível alterar Procedimentos de Avaliação que tenham turmas encerradas.");
            return;
        }
        inputCodigoPeriodoAvaliacao.value = linha.periodoAvaliacao.codigo;
        inputDescricaoPeriodoAvaliacao.value = linha.periodoAvaliacao.descricao;
        selectOrdemElementoProcedimento.value = linha.ordem_elemento;
        selectFormaObtencao.value = linha.formaObtencao;
        inputCodigoFormaAvaliacao.value = linha.formaAvaliacao.codigo;
        inputDescricaoFormaAvaliacao.value = linha.formaAvaliacao.descricao;
        inputCodigoAvaliacao.value = linha.codigo;
    }, true, 'fa-edit');
    gridElementosProcedimentoArea.addAction('Excluir', 'Excluir', (event, linha) => {
        if (turmasEncerradas) {
            alert("Não é possível excluir Procedimentos de Avaliação que tenham turmas encerradas.");
            return;
        }
        if (!confirm(`Se houver avaliação calculada para a área de conhecimento vinvulada ao Elemento (${linha.periodoAvaliacao.descricao}), as mesmas serão excluídas.\nDeseja continuar?`)) {
            return;
        }

        const formData = new FormData();
        formData.append('acao', 'excluirElementoAvaliacao');
        formData.append('codigoElemento', linha.codigo);
        formData.append('procedimentoVinculado', selectProcedimento.value);
        HttpClient.post('edu4_area_procedimento.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }

            procedimentoArea = response.procedimentoArea;
            if (procedimentoArea.avaliacoes.length == 0) {
                liberarCadastro(false, true);
            }
            limparAbaElementoAvaliacao();
            atualizarFormularios();
        });

        return;
    }, true, 'fa-trash');


    gridElementosProcedimentoArea.show(document.getElementById('ctnElementosAvaliacao'));

    new DBLookUp(ancoraPeriodoAvaliacao, inputCodigoPeriodoAvaliacao, inputDescricaoPeriodoAvaliacao, {
        'sArquivo': 'func_periodoavaliacao.php',
        'sLabel': 'Pesquisar Períodos de Avaliação',
        'sObjetoLookUp': "db_iframe_periodoavaliacao"
    });

    const lookupFormaAvaliacao = new DBLookUp(
        ancoraFormaAvaliacao,
        inputCodigoFormaAvaliacao,
        inputDescricaoFormaAvaliacao, {
            'sArquivo': 'func_formaavaliacao.php',
            'sLabel': 'Pesquisar Forma de Avaliação',
            'sObjetoLookUp': "db_iframe_formaavaliacao"
        });

    // elementos aba Resultado
    const formResultado = document.getElementById('formResultado');
    const ancoraTipoResultado = document.getElementById('ancoraTipoResultado');
    const inputCodigoTipoResultado = document.getElementById('codigoTipoResultado');
    const inputDescricaoTipoResultado = document.getElementById('descricaoTipoResultado');
    const selectFormaObtencaoResultado = document.getElementById('formaObtencaoResultado');
    const ancoraFormaAvaliacaoResultado = document.getElementById('ancoraFormaAvaliacaoResultado');
    const inputCodigoFormaAvaliacaoResultado = document.getElementById('codigoFormaAvaliacaoResultado');
    const inputDescricaoFormaAvaliacaoResultado = document.getElementById('descricaoFormaAvaliacaoResultado');
    const inputCodigoResultado = document.getElementById('codigoResultado');
    const btnSalvarResultado = document.getElementById('btnSalvarResultado');

    const lookupElementoResultado = new DBLookUp(
        ancoraTipoResultado,
        inputCodigoTipoResultado,
        inputDescricaoTipoResultado, {
            'sArquivo': 'func_resultado.php',
            'sLabel': 'Pesquisar Elemento de Resultado',
            'sObjetoLookUp': "db_iframe_resultado"
        });

    const lookupFormaAvaliacaoResultado = new DBLookUp(
        ancoraFormaAvaliacaoResultado,
        inputCodigoFormaAvaliacaoResultado,
        inputDescricaoFormaAvaliacaoResultado, {
            'sArquivo': 'func_formaavaliacao.php',
            'sLabel': 'Pesquisar Forma de Avaliação',
            'sObjetoLookUp': "db_iframe_formaavaliacao"
        });

    // Modal ORdenar elementos de avaliação da Area
    const modalOrdenarElementos = document.getElementById('modalOrdenarElementos');
    const cboElementos = document.getElementById('cbOrdenarElementos');
    const btnOrderUp = document.getElementById('orderUp');
    const btnOrderDown = document.getElementById('orderDown');
    const btnSalvarOrdemElementos = document.getElementById('btnSalvarOrdemElementos');

    btnOrderUp.addEventListener('click', () => {
        if (cboElementos.selectedIndex != -1 && cboElementos.selectedIndex > 0) {
            var SI2 = cboElementos.selectedIndex - 1;
            var auxText2 = cboElementos.options[SI2].text;
            var auxValue2 = cboElementos.options[SI2].value;
            cboElementos.options[SI2] = new Option(cboElementos.options[SI2 + 1].text, cboElementos.options[SI2 + 1].value);
            cboElementos.options[SI2 + 1] = new Option(auxText2, auxValue2);
            cboElementos.options[SI2].selected = true;
        }
    });

    btnOrderDown.addEventListener('click', () => {
        if (cboElementos.selectedIndex != -1 && cboElementos.selectedIndex < (cboElementos.length - 1)) {
            var SI2 = cboElementos.selectedIndex + 1;
            var auxText2 = cboElementos.options[SI2].text;
            var auxValue2 = cboElementos.options[SI2].value;
            cboElementos.options[SI2] = new Option(cboElementos.options[SI2 - 1].text, cboElementos.options[SI2 - 1].value);
            cboElementos.options[SI2 - 1] = new Option(auxText2, auxValue2);
            cboElementos.options[SI2].selected = true;
        }
    });

    /**
     * @type {windowAux}
     */
    var windowOrdenar = new windowAux('windowOrdenar', 'Ordenar Elementos de Avaliação', 400, 300);
    windowOrdenar.setContent(modalOrdenarElementos);
    windowOrdenar.allowCloseWithEsc(true);
    windowOrdenar.setShutDownFunction(function () {
        hideWindowOrdenar();
    });

    const hideWindowOrdenar = () => {
        if (!!windowOrdenar.oDBMask) {
            windowOrdenar.oDBMask.destroy();
        }
        windowOrdenar.hide();
    };

    btnOrdenarElementoAvalicao.addEventListener('click', () => {
        cboElementos.options.length = 0;
        collectionElementosProcedimentoArea.itens.map((item) => {
            cboElementos.add(new Option(item.elemento, item.codigo));
        });
        windowOrdenar.show(0, 0, true);
    });

    btnSalvarOrdemElementos.addEventListener('click', () => {
        const qtdIndex = cboElementos.options.length;
        const ordens = [];
        for (x = 0; x < qtdIndex; x++) {
            ordens.push({
                "codigo": cboElementos.options[x].value,
                "novaOrdem": cboElementos.options[x].index + 1
            });
        }

        const formData = new FormData();
        formData.append('acao', 'salvarOrdemElementos');
        formData.append('procedimentoVinculado', selectProcedimento.value);
        formData.append('ordens', JSON.stringify(ordens));
        HttpClient.post('edu4_area_procedimento.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }

            procedimentoArea = response.procedimentoArea;
            limparAbaElementoAvaliacao();
            atualizarFormularios();
            hideWindowOrdenar();
        });
    });

    // Objetos para controle das Abas
    const dBAba = new DBAbas($('ctnAbas'));
    const abaProcedimento = dBAba.adicionarAba("Criar Procedimento", containerCriarProcedimento);
    const abaElementosAvaliacao = dBAba.adicionarAba("Elementos de Avaliação", containerElementosAvaliacao);
    const abaResultado = dBAba.adicionarAba("Resultado", containerResultado);

    const reiniciaCadastro = () => {
        turmasEncerradas = false;
        abaElementosAvaliacao.lBloqueada = true;
        abaResultado.lBloqueada = true;
        btnProsseguir.disabled = true;
        btnExcluirProcedimentoArea.disabled = true;
    };

    const liberarCadastro = (desabilitaAvaliacoes, desabilitaResultado) => {
        abaElementosAvaliacao.lBloqueada = desabilitaAvaliacoes;
        abaResultado.lBloqueada = desabilitaResultado;
        btnProsseguir.disabled = false;
    };


    (function () {
        reiniciaCadastro();

        const formData = new FormData();
        formData.append('acao', 'buscarProcedimentos');
        HttpClient.post('edu4_area_procedimento.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            response.procedimentos.forEach(function (procedimento) {
                selectProcedimento.add(new Option(procedimento.descricao, procedimento.codigo));
                procedimentosEscola.push(procedimento);
            });
        });
    })();

    const configurarAbaElementosAvaliacao = () => {
        limparSelectOrdemElementoProcediemento();
        limparSelectFormaObtencao(selectFormaObtencao);

        procedimentoSelecionado.elementos.map((elemento) => {
            if (elemento.tipo == "A") {
                var option = new Option(elemento.periodoAvaliacao.descricao, elemento.ordem);
            } else {
                var option = new Option(elemento.periodoResultado.descricao, elemento.ordem);
            }
            option.setAttribute('tipo', elemento.tipo);
            selectOrdemElementoProcedimento.add(option);

        });

        switch (procedimentoSelecionado.formaAvaliacao.tipo) {
            case 'NOTA':
                selectFormaObtencao.add(new Option('Soma', 'SO'));
                selectFormaObtencao.add(new Option('Média Aritmética', 'ME'));
                selectFormaObtencaoResultado.add(new Option('Soma', 'SO'));
                selectFormaObtencaoResultado.add(new Option('Média Aritmética', 'ME'));
                selectFormaObtencaoResultado.add(new Option('Aprovação por Período', 'AP'));
                lookupFormaAvaliacao.oParametros.aParametrosAdicionais = ['forma=NOTA'];
                lookupFormaAvaliacaoResultado.oParametros.aParametrosAdicionais = ['forma=NOTA'];
                break;
            case 'NIVEL':
                selectFormaObtencao.add(new Option('Maior Nível', 'MC'));
                selectFormaObtencaoResultado.add(new Option('Maior Nível', 'MC'));
                lookupFormaAvaliacao.oParametros.aParametrosAdicionais = ['forma=NIVEL'];
                lookupFormaAvaliacaoResultado.oParametros.aParametrosAdicionais = ['forma=NIVEL'];
                break;
            case 'PARECER':
                selectFormaObtencao.add(new Option('Atribuído', 'AT'));
                selectFormaObtencaoResultado.add(new Option('Atribuído', 'AT'));
                lookupFormaAvaliacao.oParametros.aParametrosAdicionais = ['forma=PARECER'];
                lookupFormaAvaliacaoResultado.oParametros.aParametrosAdicionais = ['forma=PARECER'];
                break;
        }
    };

    const limparSelect = (select, label) => {
        select.options.length = 0;
        select.add(new Option(label, ''));
    };

    const limparSelectFormaObtencao = (select) => {
        limparSelect(select, 'Informe a fórmula de cálculo');
    };

    const limparSelectOrdemElementoProcediemento = () => {
        limparSelect(selectOrdemElementoProcedimento, 'Selecione o elemento base para cálculo');
    };

    const limparAbaElementoAvaliacao = () => {
        inputCodigoAvaliacao.value = '';
        selectOrdemElementoProcedimento.value = '';
        selectFormaObtencao.value = '';
        inputCodigoPeriodoAvaliacao.value = '';
        inputDescricaoPeriodoAvaliacao.value = '';
        inputCodigoFormaAvaliacao.value = '';
        inputDescricaoFormaAvaliacao.value = '';
    };
    const limparAbas = (abaElementos, abaResultado) => {
        if (abaElementos) {
            collectionElementosProcedimentoArea.clear();
            gridElementosProcedimentoArea.clear();
            limparSelectOrdemElementoProcediemento();
            limparSelectFormaObtencao(selectFormaObtencao);
            limparAbaElementoAvaliacao();
        }
        if (abaResultado) {
            limparSelectFormaObtencao(selectFormaObtencaoResultado);
            inputCodigoResultado.value = '';
            inputCodigoTipoResultado.value = '';
            inputDescricaoTipoResultado.value = '';
            inputCodigoFormaAvaliacaoResultado.value = '';
            inputDescricaoFormaAvaliacaoResultado.value = '';
        }
    };

    const elementoProcedimentoPorOrdem = (ordem) => {
        return procedimentoSelecionado.elementos.filter((elemento) => {
            if (elemento.ordem == ordem) {
                return elemento;
            }
        }).shift();
    };

    const atualizarFormularios = () => {

        gridElementosProcedimentoArea.clear();
        procedimentoArea.avaliacoes.map((elementoAvaliacao) => {
            const elementoProcedimento = elementoProcedimentoPorOrdem(elementoAvaliacao.ordem_elemento);

            if (elementoProcedimento.tipo === 'A') {
                var elemento = elementoProcedimento.periodoAvaliacao;
            } else {
                elemento = elementoProcedimento.periodoResultado;
            }

            elementoAvaliacao.elemento = elementoAvaliacao.periodoAvaliacao.descricao;
            elementoAvaliacao.elementoProcedimento = elemento.descricao;
            elementoAvaliacao.formaAvaliacaoDescricao = elementoAvaliacao.formaAvaliacao.descricao;
            collectionElementosProcedimentoArea.add(elementoAvaliacao);
        });

        gridElementosProcedimentoArea.reload();

        const resultado = procedimentoArea.resultado;
        if (!empty(resultado)) {
            inputCodigoResultado.value = resultado.codigo;
            inputCodigoTipoResultado.value = resultado.tipoResultado.codigo;
            inputDescricaoTipoResultado.value = resultado.tipoResultado.descricao;
            selectFormaObtencaoResultado.value = resultado.formaObtencao;
            inputCodigoFormaAvaliacaoResultado.value = resultado.formaAvaliacao.codigo;
            inputDescricaoFormaAvaliacaoResultado.value = resultado.formaAvaliacao.descricao;
        }
    };

    selectProcedimento.addEventListener('change', (e) => {
        if (e.target.value === '') {
            reiniciaCadastro();
            return;
        }

        limparAbas(true, true);

        procedimentosEscola.map((procedimentoEscola) => {
            if (procedimentoEscola.codigo == e.target.value) {
                procedimentoSelecionado = procedimentoEscola;
            }
        });

        configurarAbaElementosAvaliacao();
        liberarCadastro(false, false);
        const formData = new FormData();
        formData.append('acao', 'buscarProcedimentoArea');
        formData.append('codigoProcedimento', e.target.value);
        HttpClient.post('edu4_area_procedimento.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                alert(mensagem);
                return;
            }

            if (empty(response.procedimentoArea)) {
                liberarCadastro(false, true);
                return;
            }
            procedimentoArea = response.procedimentoArea;
            turmasEncerradas = response.turmasEncerradas;
            btnExcluirProcedimentoArea.disabled = turmasEncerradas;
            if (procedimentoArea.avaliacoes.length == 0) {
                liberarCadastro(false, true);
            }
            atualizarFormularios();
        });

        liberarCadastro();
    });

    btnProsseguir.addEventListener('click', () => {
        if (selectProcedimento.value === '') {
            return;
        }
        abaProcedimento.setVisibilidade(false);
        abaElementosAvaliacao.setVisibilidade(true);
    });


    const getOrdemElementoAvaliacao = () => {
        if (empty(procedimentoArea)) {
            return 1;
        }

        if (empty(codigoAvaliacao.value)) {
            return procedimentoArea.avaliacoes.length + 1;
        } else {
            return procedimentoArea.avaliacoes.filter(elemento => {
                if (elemento.codigo == codigoAvaliacao.value) {
                    return elemento.ordem;
                }
            }).shift().ordem;
        }
    };

    btnSalvarElementoAvalicao.addEventListener('click', () => {
        const formData = new FormData(formularioElementoAvaliacao);

        formData.append('acao', 'salvarAvaliacao');
        formData.append('procedimentoArea', procedimentoArea.codigo);
        formData.append('procedimentoVinculado', selectProcedimento.value);
        formData.append('ordem', getOrdemElementoAvaliacao());

        selectedOrdem = selectOrdemElementoProcedimento.selectedIndex;
        formData.append('tipo', selectOrdemElementoProcedimento.options[selectedOrdem].getAttribute('tipo'));
        // formData.append('procedimentoArea', procedimentoArea);
        HttpClient.post('edu4_area_procedimento.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }

            procedimentoArea = response.procedimentoArea;
            limparAbaElementoAvaliacao();
            atualizarFormularios();
            liberarCadastro(false, false);
        });
    });

    btnSalvarResultado.addEventListener('click', () => {
        if (empty(codigoTipoResultado.value)) {
            alert("Informe o Tipo de resultado.");
            return;
        }
        if (empty(formaObtencaoResultado.value)) {
            alert("Informe a Forma de Obtenção da Nota.");
            return;
        }
        if (empty(codigoFormaAvaliacaoResultado.value)) {
            alert("Informe o Forma de avaliação.");
            return;
        }

        const formData = new FormData(formResultado);
        formData.append('acao', 'salvarResultado');
        formData.append('procedimentoVinculado', selectProcedimento.value);
        HttpClient.post('edu4_area_procedimento.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }
            inputCodigoResultado.value = response.procedimentoArea.resultado.codigo;
        });
    });

    btnExcluirProcedimentoArea.addEventListener('click', () => {
        if (confirm(`ATENÇÃO: Ao excluir o procedimento da Área de Conhecimento será excluido todas as Avaliações do Diário.`)) {
            const formData = new FormData();
            formData.append('acao', 'excluirProcedimentoArea');
            formData.append('procedimentoVinculado', selectProcedimento.value);
            HttpClient.post('edu4_area_procedimento.RPC.php', {body: formData}).then(response => {
                alert(response.mensagem);
                if (response.erro) {
                    return;
                }
                selectProcedimento.value = "";
                reiniciaCadastro();
                limparAbas(true, true);
            });
        }
    });
</script>
