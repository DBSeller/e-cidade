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
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    
    <script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
    <script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>

    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>

    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>

    <script src="scripts/widgets/DBLancador.widget.js" rel="script" type="text/javascript"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>

    <style>
   .aviso {
            margin: 15px auto 0 auto;
            background-color: #FFF;
            border-radius: 4px;
            font-weight: bold;
            padding: 15px;
            width: 500px;
            text-align: justify;
        }
    </style>
</head>
<body>
<div id="ctnAbas">
    <div id="abaProcesso">
        <?php require_once modification('forms/db_fromlancarprocessojudicial.php'); ?>
    </div>
    <div id="abaServidor">
        <?php require_once modification('forms/db_fromprocessojudicailvinculaservidor.php'); ?>
    </div>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript">
    const dbAbas = new DBAbas(document.querySelector('#ctnAbas'));
    const abaProcesso = dbAbas.adicionarAba('Dados do Processo', document.querySelector('#abaProcesso'));
    const abaServidor = dbAbas.adicionarAba('Vinculação Funcionário(s)', document.querySelector('#abaServidor'));
    const controlarAbaServidor = bloqueia => {
        abaServidor.lBloqueada = bloqueia;
    };

    const formProcessoJudicial = document.querySelector('#idFormProcessoJudicial');
    const divMensagemProcesso = formProcessoJudicial.querySelector('#idMensagem');
    const fildsetPrincipalProcesso = formProcessoJudicial.querySelector('#idFieldsetPrincipalProcesso');
    const fildsetComplemetarProcesso = formProcessoJudicial.querySelector('#idFieldsetComplemetarProcesso');
    const fildsetCppProcesso = formProcessoJudicial.querySelector('#idFieldsetcppProcesso');

    const inputSequencialProcesso = formProcessoJudicial.querySelector('#sequencial');
    const inputOrigemProcesso = formProcessoJudicial.querySelector('#idOrigem');
    const inputNumeroProcesso = formProcessoJudicial.querySelector('#idNumeroProcesso');
    const inputObservacaoProcesso = formProcessoJudicial.querySelector('#idObservacao');
    const inputDataSentencaProcesso = formProcessoJudicial.querySelector('#idDataSentenca');
    const inputUFVaraProcesso = formProcessoJudicial.querySelector('#idUFVara');
    const inputCodigoMunicipioProcesso = formProcessoJudicial.querySelector('#idCodigoMunicipio');
    const inputCodigoVaraProcesso = formProcessoJudicial.querySelector('#idCodigoVara');
    const inputDataAcordoProcesso = formProcessoJudicial.querySelector('#idDataAcordo');
    const inputTipoAcordoProcesso = formProcessoJudicial.querySelector('#idTipoAcordo');
    const inputCnpjCCPProcesso = formProcessoJudicial.querySelector('#idCnpjCCP');
    const inputTipoContratoProcesso = formProcessoJudicial.querySelector('#idTipoContrato');
    const inputReintegracaoContratoProcesso = formProcessoJudicial.querySelector('#idReintegracaoContrato');
    const inputIndicativoAtividadeProcesso = formProcessoJudicial.querySelector('#idIndicativoAtividade');
    const inputIndicativoMotivoProcesso = formProcessoJudicial.querySelector('#idIndicativoMotivo');
    const inputIndicativoUnicidadeProcesso = formProcessoJudicial.querySelector('#idIndicativoUnicidade');
    const inputCodigoCategoriaReconhecidoProcesso = formProcessoJudicial.querySelector('#idCodigoCategoriaReconhecido');
    const inputNaturezaAtividadeReconhecidoProcesso = formProcessoJudicial.querySelector('#idNaturezaAtividadeReconhecido');
    const inputDataMudancaCategoriaProcesso = formProcessoJudicial.querySelector('#idDataMudancaCategoria');
    const btnSalvarProcessoJudicial = formProcessoJudicial.querySelector('#idSalvarProcessoJudicial');

    const corpoVinculaServidor = document.querySelector('#idFuncionarios');
    const divMensagemVinculaServidor = corpoVinculaServidor.querySelector('#idMensagem');

    const formVinculaServidor = document.querySelector('#idFormVinculaServidor');
    const inputSequencialVincula = formVinculaServidor.querySelector('#sequencial');
    const inputSequencialVinculaProcesso = formVinculaServidor.querySelector('#sequencialProcesso');
    const fieldsetPeriodoNaoDeclarado = formVinculaServidor.querySelector('#idFieldsetPeriodoNaoDeclarado');
    const fieldsetContratoTrabalho = formVinculaServidor.querySelector('#idFieldsetContratoTrabalho');
    const fieldsetNovoCodigoCategoria = formVinculaServidor.querySelector('#idFieldsetNovoCodigoCategoria');
    // const fieldsetBaseCalculoGFIP = formVinculaServidor.querySelector('#idFieldsetBaseCalculoGFIP')
    const divFiltroMatriculaSelecao = formVinculaServidor.querySelector("#idFiltroMatriculaSelecao");
    const divFiltroSelecao = formVinculaServidor.querySelector("#idFiltroSelecao");
    const divFiltroMatricula = formVinculaServidor.querySelector("#containerLancadorMatricula");
    const filtroCombo = formVinculaServidor.querySelector("#filtroCombo");
    const idCodigoSelecao = formVinculaServidor.querySelector("#codigoSelecao");
    const idDescricaoSelecao = formVinculaServidor.querySelector("#descricaoSelecao");
    const btnIncluirVinculoServidor = formVinculaServidor.querySelector("#idIncluirVinculoServidor");

    const btnLimparVinculoServidor = formVinculaServidor.querySelector("#idLimparVinculoServidor");
    const divExibeNumeroProcesso = document.querySelector('#idExibeNumeroProcesso');

    const inputTipoContrato = formVinculaServidor.querySelector('#idTipoContrato');
    const inputIndicativoContrato = formVinculaServidor.querySelector('#idIndicativoContrato');
    const inputIndicativoReintegracao = formVinculaServidor.querySelector('#idIndicativoReintegracao');
    const inputIndicativoNovaCategoria = formVinculaServidor.querySelector('#idIndicativoNovaCategoria');
    const inputNovaAtividade = formVinculaServidor.querySelector('#idNovaAtividade');
    const inputMotivoDesligamento = formVinculaServidor.querySelector('#idMotivoDesligamento');
    const inputCategoriaMudanca = formVinculaServidor.querySelector('#idCodigoCategoriaMudanca');
    const inputNaturezaAtividadeMudanca = formVinculaServidor.querySelector('#idNaturezaAtividadeMudanca');
    const inputDataReconhecidoMudanca = formVinculaServidor.querySelector('#idDataReconhecidoMudanca');
    const inputMatriculaUnicidade = formVinculaServidor.querySelector('#idMatriculaUnicidade');
    const inputCodigoCategoriaUnicidade = formVinculaServidor.querySelector('#idCodigoCategoriaUnicidade');
    const inputDataReconhecidoUnicidade = formVinculaServidor.querySelector('#idDataReconhecidoUnicidade');
    const inputMesInicialProcesso = formVinculaServidor.querySelector('#idMesInicialProcesso');
    const inputAnoInicialProcesso = formVinculaServidor.querySelector('#idAnoInicialProcesso');
    const inputMesFinalProcesso = formVinculaServidor.querySelector('#idMesFinalProcesso');
    const inputAnoFinalProcesso = formVinculaServidor.querySelector('#idAnoFinalProcesso');
    const inputIndicativoRepercussao = formVinculaServidor.querySelector('#idIndicativoRepercussao');
    const inputIndicativoIndenizacaoSD = formVinculaServidor.querySelector('#idIdenizacaoSD');
    const inputIndenizacaoAbono = formVinculaServidor.querySelector('#idIndenizacaoAbono');

    const inputMesPeriodoApuracao = formVinculaServidor.querySelector('#idMesPeriodoApuracao');
    const inputAnoPeriodoApuracao = formVinculaServidor.querySelector('#idAnoPeriodoApuracao');
    const inputMensalContribuicao = formVinculaServidor.querySelector('#idMensalContribuicao');
    const inputContribuicao13 = formVinculaServidor.querySelector('#idContribuicao13');
    const inputGrauExposicao = formVinculaServidor.querySelector('#idGrauExposicao');
    const inputCodigoMudancaoCategoria = formVinculaServidor.querySelector('#idCodigoMudancaoCategoria');
    const inputBaseMudancaoCategoria = formVinculaServidor.querySelector('#idBaseMudancaoCategoria');
    const inputLancamentoPrevidenciario = formVinculaServidor.querySelector('#idLancamentoPrevidenciario');

    const inputMesPeriodoApuracaoFGTS = formVinculaServidor.querySelector('#idMesPeriodoApuracaoFGTS');
    const inputAnoPeriodoApuracaoFGTS = formVinculaServidor.querySelector('#idAnoPeriodoApuracaoFGTS');
    const inputValorFGTSSemSEFIP = formVinculaServidor.querySelector('#idValorFGTSSemSEFIP');
    const inputValorFGTSComSEFIP = formVinculaServidor.querySelector('#idValorFGTSComSEFIP');
    const inputValorFGTSAnterior = formVinculaServidor.querySelector('#idValorFGTSAnterior');
    const inputLancamentoFGTS = formVinculaServidor.querySelector('#idLancamentoFGTS');

    const inputLancamentoUnicidade = formVinculaServidor.querySelector('#idLancamentoUnicidade');
    const inputLancamentoAnoAbono =  formVinculaServidor.querySelector('#idLancamentoAnoAbono');
    const inputLancamentoMudancaCategoria =  formVinculaServidor.querySelector('#idLancamentoMudancaCategoria');
    const inputAnoAbono = formVinculaServidor.querySelector('#idAnoAbono');

    const btnLancarAnoAbono = formVinculaServidor.querySelector('#idBtnLancarAnoAbono');
    const btnLancarRegistro = formVinculaServidor.querySelector('#idBtnLancarRegistro');
    const btnLancarFGTS = formVinculaServidor.querySelector('#idBtnLancarFGTS');
    const btnLancarUnicidade = formVinculaServidor.querySelector('#idBtnLancarUnicidade');
    const btnLancarMudancaCategoria = formVinculaServidor.querySelector('#idBtnLancarMudanca');

    const sequencialUnicidadeExcluir = formVinculaServidor.querySelector('#idSequencialUnicidadeExcluir');
    const sequencialAnoAbonoExcluir = formVinculaServidor.querySelector('#idSequencialAnoAbonoExcluir');
    const sequencialMudancaExcluir = formVinculaServidor.querySelector('#idSequencialMudancaCategoriaExcluir');

    const divMensagemMudanca = formVinculaServidor.querySelector('#idMensagemMudanca');
    const divMensagemUnicidade = formVinculaServidor.querySelector('#idMensagemUnicidade');
    const divMensagemAbono = formVinculaServidor.querySelector('#idMensagemAbono');
    const divMensagemBaseCalculo = formVinculaServidor.querySelector('#idMensagemBaseCalculo');
    const divMensagemFGTS = formVinculaServidor.querySelector('#idMensagemFGTS');

    var lancadorRubrica = new DBLancador("lancadorRubrica");

    function validaCNPJ (cnpj) {
        let b = [ 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2 ]
        let c = String(cnpj).replace(/[^\d]/g, '')
        if(c.length !== 14) {
            alert("O número de dígitos do CNPJ tem que ser igual a 14. Favor revisar.");
            return false;
        }
        
        if(/0{14}/.test(c)) {
            alert("Digitar somente números. Favor revisar.");
            return false;
        }
        
        for (var i = 0, n = 0; i < 12; n += c[i] * b[++i]);
        if(c[12] != (((n %= 11) < 2) ? 0 : 11 - n)) {
            alert("Número de CNPJ inválido. Favor revisar.");
            return false;
        }

        
        for (var i = 0, n = 0; i <= 12; n += c[i] * b[i++]);
        if(c[13] != (((n %= 11) < 2) ? 0 : 11 - n)) {
            alert("Número de CNPJ inválido. Favor revisar.");
            return false;
        }
        return true
    }
 
    function formataData ($dataParametro) {
        let dataFormatada = '';
        if ($dataParametro.length > 0) {
            dataFormatada = $dataParametro.split('-').reverse().join('/');
        }
        return dataFormatada;
    }

    function formataDataDado ($dataParametro) {
        let dataFormatada = '';
        if ($dataParametro.length > 0) {
            dataFormatada = $dataParametro.split('/').reverse().join('-');
        }
        return dataFormatada;
    }

 
    let oToogle1 = new DBToogle(fildsetPrincipalProcesso.id, true);
    let oToogle2 = new DBToogle(fildsetComplemetarProcesso.id, false);
    let oToogle3 = new DBToogle(fildsetCppProcesso.id, false);
    let oToogle4 = new DBToogle(fieldsetPeriodoNaoDeclarado.id, false);
    let oToogle7 = new DBToogle(idFieldsetInformacoesAdicionais.id,true);
    let oToogle8 = new DBToogle(idFieldsetNovoCodigoCategoria.id,false);
    let oToogle9 = new DBToogle(idFieldsetUnicidadeContratual.id,false);

    let urlProcesso = 'pes4_processojudicial.RPC.php';
    let tabelaProcessos = jQuery('#dataProcesso-table');
    let colunasProcessos =  [
        {
            title: 'Sequencial',
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Processo',
            field: 'numeroProcesso',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `150px`
        },{
            title: 'Origem',
            field: 'origem',
            checkbox: false,
            sortable: true,
            align: 'center',
            valign: 'middle',
            width: `50px`
        },{
            title: 'Observações',
            field: 'observacaoProcesso',
            align: 'center',
            valign: 'center',
            width: '100px',
            sortable: true
        },{
            title: 'Ações',
            field: 'acao',
            align: 'center',
            valign: 'right',
            clickToSelect: false,
            width: '100px',
            formatter: adicionaAcao,
            forceHide: true
        }
    ];
    
    function adicionaAcaoVinculo(value, row, index, $el) {
            return [
                '<a href="javascript:void(0)" onclick="editarVinculo(\'' + row.sequencial +'\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
                '<a href="javascript:void(0)" onclick="excluirVinculo(\'' + row.sequencial + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
            ].join('');
        }

    let tabelaVinculo = jQuery('#dataVinculo-table');
    let colunasVinculo =  [
        {
            title: 'Sequencial',
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Matricula',
            field: 'matricula',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `50px`
        },{
            title: 'Funcionario',
            field: 'nome',
            checkbox: false,
            sortable: true,
            align: 'center',
            valign: 'middle',
            width: `150px`
        },{
            title: 'Ações',
            field: 'acao',
            align: 'center',
            valign: 'right',
            clickToSelect: false,
            width: '10px',
            formatter: adicionaAcaoVinculo,
            forceHide: true
        }
    ];

    //Início Mudanca Código Categoria
    let tabelaMudanca = jQuery('#dataMudanca-table');

    function editarMudanca(id) {
        let linhaMudanca = tabelaMudanca.bootstrapTable('getRowByUniqueId', id);
        inputCategoriaMudanca.value = linhaMudanca.codigoCategoriaMudanca;
        inputNaturezaAtividadeMudanca.value = linhaMudanca.naturezaAtividadeMudanca;
        inputDataReconhecidoMudanca.value = formataDataDado(linhaMudanca.dataReconhecidoMudanca);

        inputCategoriaMudanca.focus();
        return;
    }

    function excluirMudanca(registro) {
        let linhaMudanca= tabelaMudanca.bootstrapTable('getRowByUniqueId', registro);
         if (!confirm("Confirma excluir o novo código de categoria reconhecido judicialmente?")) {
            return;
        }
        sequencialExcluidoMudanca.push(linhaMudanca.sequencial);
        sequencialMudancaExcluir.value = sequencialExcluidoMudanca;
        tabelaMudanca.bootstrapTable('removeByUniqueId', registro);
        inputLancamentoMudancaCategoria.value = JSON.stringify(tabelaMudanca.bootstrapTable('getData'));
        divMensagemMudanca.removeAttribute("hidden");
        divMensagemMudanca.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
        return;
    }

    function adicionaAcaoMudanca(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarMudanca(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirMudanca(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    let colunasMudanca =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial',
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial Processo Contrato',
            field: 'sequencialProcessoContrato',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Categoria Mudanca',
            field: 'codigoCategoriaMudanca',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Natureza Atividade Mudanca',
            field: 'naturezaAtividadeMudanca',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Data Reconhecimento',
            field: 'dataReconhecidoMudanca',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaAcaoMudanca
        }
    ];

    //Início Unicidade
    let tabelaUnicidade = jQuery('#dataUnicidade-table');
 
    function adicionaAcaoUnicidade(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarUnicidade(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirUnicidade(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    let colunasUnicidade =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial',
            field: 'sequencialUnicidade',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial Processo Contrato',
            field: 'sequencialProcessoContrato',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Matrícula',
            field: 'matriculaUnicidade',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Código Categoria',
            field: 'codigoCategoriaUnicidade',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Data Início Unicidade',
            field: 'dataInicioUnicidade',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaAcaoUnicidade
        }
    ];

    let tabelaAnoAbono = jQuery('#dataAbono-table');
 
    function adicionaAcaoAnoAbono(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarAnoAbono(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirAnoAbono(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    let colunasAnoAbono =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial',
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial Contrato',
            field: 'sequencialContrato',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Ano Abono',
            field: 'anoAbono',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `750px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaAcaoAnoAbono
        }
    ];

    //Início Fins Previdenciário - Base Cálculo
    let tabelaBaseCalculo = jQuery('#dataBaseCalculo-table');
 
    function adicionaAcaoBaseCalculo(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarBaseCalculo(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirBaseCalculo(\'' + index + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    let colunasBaseCalculo =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial',
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Período',
            field: 'periodoApuracao',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `100px`
        },{
            title: 'Previdenciário',
            field: 'mensalContribuicao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `100px`
        },{
            title: 'Previdenciário 13º',
            field: 'contribuicao13',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `100px`
        },{
            title: 'Grau Exposicao',
            field: 'grauExposicao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `100px`
        },{
            title: 'Categoria',
            field: 'codigoMudancaoCategoria',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `100px`
        },{
            title: 'Valor Remuneração',
            field: 'valorBaseMudancaoCategoria',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `100px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `500px`,
            formatter: adicionaAcaoBaseCalculo
        }
    ];

    //Início Fins FGTS - Base Cálculo
    let tabelaFGTS = jQuery('#dataFGTS-table');
 
    function adicionaAcaoFGTS(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarFGTS(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirFGTS(\'' + index + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    let colunasFGTS =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Período Apuração',
            field: 'periodoApuracao',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Base Sem SEFIP',
            field: 'valorFGTSSemSEFIP',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Base Com SEFIP',
            field: 'valorFGTSComSEFIP',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Valor Anterior',
            field: 'valorFGTSAnterior',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaAcaoFGTS
        }
    ];

    var lancadorMatricula = new DBLancador('lancadorMatricula');
    let dadosMatricula = [{
        funcao: 'func_rhpessoal.php'
        }
    ];

    let sequencialExcluidoUnicidade = [];
    let sequencialExcluidoAnoAbono = [];
    let sequencialExcluidoMudanca = [];

    const inicializar = () => {

        desabilitarFormularioProcesso(true);
        controlarAbaServidor(true);

        divMensagemProcesso.removeAttribute("hidden");
        divMensagemProcesso.setAttribute("class", "alert alert-success");
        divMensagemProcesso.innerHTML = "Clique no botão <strong>'Novo'</strong> para incluir um processo " +
            "ou edite clicando no ícone <i class='fa fa-edit' style='font-size:22px'></i> em " +
            "<strong>'Processos Lançados'</strong>."

        //Lista de Processos
        jQuery(document).ready(jQuery => {
            tabelaProcessos.bootstrapTable('destroy').bootstrapTable({
                height: 300,
                columns:colunasProcessos,
                uniqueId :"numeroProcesso",
                locale : 'pt-BR',
                cache : false,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'Todos'],
                search : true,
                class : "table table-sm",
                showExport : true,
                exportTypes : ['json', 'xml', 'csv', 'txt', 'excel']
            });
        });

        tabelaProcessos.on("click-cell.bs.table", function (field, value, row, $el) {
            divExibeNumeroProcesso.innerHTML = ($el.numeroProcesso ? 'Funcionários Vinculados ao Processo Número: ' + $el.numeroProcesso : 'Funcionários Vinculados ao Processo').urlDecode().replace(/\\n/g, '\n');
            editarProcesso($el.sequencial);
        });

        btnSalvarProcessoJudicial.addEventListener('click', salvarProcessoJudicial);
        btnIncluirVinculoServidor.addEventListener('click', vincularServidor);

        formProcessoJudicial.querySelector('#idLimparProcessoJudicial').addEventListener('click', () => {
            divMensagemProcesso.removeAttribute("hidden");
            divMensagemVinculaServidor.removeAttribute("hidden");
            if (confirm('Novo Processo Judicial?') == true) {
                divMensagemProcesso.setAttribute("class", "alert alert-success");
                divMensagemProcesso.innerHTML = 'Novo Processo.'
                divMensagemVinculaServidor.setAttribute("class", "alert alert-success");
                divMensagemVinculaServidor.innerHTML = 'Novo Processo.'
                inputSequencialProcesso.value = "";
                btnSalvarProcessoJudicial.disabled = false;
                formProcessoJudicial.reset();
                desabilitarFormularioProcesso(false);
                controlarAbaServidor(true);
                return;
            }
            divMensagemProcesso.setAttribute("hidden", "hidden");
            divMensagemVinculaServidor.setAttribute("hidden", "hidden");
            return;
        });

        listaProcessos();

        const oLookupServidor = new DBLookUp($("ancoraSelecao"), $("codigoSelecao"), $("descricaoSelecao"), {
            "sArquivo"              : "func_selecao.php",
            "sObjetoLookUp"         : "db_iframe_selecao",
            'sLabel': 'Pesquisar Seleção',
            "aParametrosAdicionais" : ['instit=<?=db_getsession("DB_instit")?>'],
        });

        changeMatricula();
        inicializaFiltro();

        //Lista de Funcionários vinculado a processo
        jQuery(document).ready(jQuery => {
            tabelaVinculo.bootstrapTable('destroy').bootstrapTable({
                height: 300,
                columns:colunasVinculo,
                uniqueId :"sequencial",
                locale : 'pt-BR',
                cache : false,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'Todos'],
                search : true,
                class : "table table-sm",
                showExport : true,
                exportTypes : ['json', 'xml', 'csv', 'txt', 'excel']
            });
        });

        tabelaVinculo.on("click-cell.bs.table", function (field, value, row, $el) {
            if (value != 'acao') {
                editarVinculo($el);
            }
        });

        //Lista de valores Novo Código de Categoria Reconhecido Judicialmente
        jQuery(document).ready(jQuery => {
            tabelaMudanca.bootstrapTable('destroy').bootstrapTable({
                height: 150,
                columns:colunasMudanca,
                locale : 'pt-BR',
                cache : false,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'Todos'],
                search : false,
                showRefresh: false,
                showColumns: false,
                uniqueId: "id",
                reorderableRows: true,
                toolbar: '.toolbar',
                class : "table table-sm",
                exportTypes : ['json', 'xml', 'csv', 'txt', 'excel']
            });
        });

        const incluirMudanca = () => {
            const camposMudanca = new FormData(formVinculaServidor);

            let codigoCategoriaMudanca = camposMudanca.get('codigoCategoriaMudanca');
            let naturezaAtividadeMudanca = camposMudanca.get('naturezaAtividadeMudanca');
            let dataReconhecidoMudanca = formataData(camposMudanca.get('dataReconhecidoMudanca'));
            let sequencialMudanca = 0;
            let sequencialProcessoContrato = camposMudanca.get('sequencialProcessoContrato');
            let idUnico = parseInt(dataReconhecidoMudanca.replace(/[^0-9]/g,''));

            if (isNaN(idUnico)) {
                alert("'Data de Reconhecimento de Nova Categoria' não definida. Favor revisar.");
                inputDataReconhecidoMuadicionaAcaoFGTSdanca.focus();
                return;
            }

            let verificaApuracao = tabelaMudanca.bootstrapTable('getRowByUniqueId', idUnico);

            if (Boolean(verificaApuracao)){
                sequencialMudanca =  verificaApuracao.sequencial;
            }

            let itemMudanca = {
                id: idUnico,
                sequencial: sequencialMudanca,
                sequencialProcessoContrato: sequencialProcessoContrato,
                codigoCategoriaMudanca: codigoCategoriaMudanca,
                naturezaAtividadeMudanca: naturezaAtividadeMudanca,
                dataReconhecidoMudanca: dataReconhecidoMudanca,
            };

            if (verificaApuracao) {
                if (!confirm("Novo Código de Categoria já lançado. Deseja sobrescrever?")) {
                        inputMesPeriodoApuracao.focus();
                        return;
                    }
                    tabelaMudanca.bootstrapTable('updateByUniqueId', {
                        id: itemMudanca.id,
                        row: itemMudanca
                    });
                    inputLancamentoMudancaCategoria.value = JSON.stringify(tabelaMudanca.bootstrapTable('getData'));
                    inputCategoriaMudanca.value = '';
                    inputNaturezaAtividadeMudanca.value = '';
                    inputDataReconhecidoMudanca.value = '';
                    inputCategoriaMudanca.focus();
                    divMensagemMudanca.removeAttribute("hidden");
                    divMensagemMudanca.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
                    return;
            }
            tabelaMudanca.bootstrapTable('append', itemMudanca);

            inputLancamentoMudancaCategoria.value = JSON.stringify(tabelaMudanca.bootstrapTable('getData'));
            inputCategoriaMudanca.value = '';
            inputNaturezaAtividadeMudanca.value = '';
            inputDataReconhecidoMudanca.value = '';
            inputCategoriaMudanca.focus();
            divMensagemMudanca.removeAttribute("hidden");
            divMensagemMudanca.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
            return;
        }

        idBtnLancarMudanca.addEventListener('click', incluirMudanca); 


        //Lista de valores Reconhecimento de Unicidade Contratual
        jQuery(document).ready(jQuery => {
            tabelaUnicidade.bootstrapTable('destroy').bootstrapTable({
                height: 150,
                columns:colunasUnicidade,
                locale : 'pt-BR',
                cache : false,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'Todos'],
                search : false,
                showRefresh: false,
                showColumns: false,
                uniqueId: "id",
                reorderableRows: true,
                toolbar: '.toolbar',
                class : "table table-sm",
                exportTypes : ['json', 'xml', 'csv', 'txt', 'excel']
            });
        });

        const incluirUnicidade = () => {
            const camposUnicidade = new FormData(formVinculaServidor);
            let matricula = camposUnicidade.get('matriculaUnicidade');
            let codigoCategoria = camposUnicidade.get('codigoCategoriaUnicidade');
            let dataUnicidade = formataData(camposUnicidade.get('dataReconhecidoUnicidade'));
            let sequencialUnicidade = 0;
            let sequencialProcessoContrato = camposUnicidade.get('sequencialProcessoContrato');
            let idUnico = parseInt(matricula.replace(/[^0-9]/g,'') + codigoCategoria.replace(/[^0-9]/g,'') + dataUnicidade.replace(/[^0-9]/g,''));
            if (isNaN(idUnico)) {
                alert("Todos os campos referente a <strong>Informações dos Vínculos/Contratos Incorporados por Reconhecimento de Unicidade Contratual </strong> estão vazios. Favor revisar.");
                inputMatriculaUnicidade.focus();
                return;
            }

            let verificaApuracao = tabelaUnicidade.bootstrapTable('getRowByUniqueId', idUnico);

            if (Boolean(verificaApuracao)){
                sequencialUnicidade =  verificaApuracao.sequencialUnicidade;
            }

            let itemUnicidade = {
                id: idUnico,
                sequencialUnicidade: sequencialUnicidade,
                sequencialProcessoContrato: sequencialProcessoContrato,
                matriculaUnicidade: matricula,
                codigoCategoriaUnicidade: codigoCategoria,
                dataInicioUnicidade: dataUnicidade
            };

            if (verificaApuracao) {
                if (!confirm("Unicidade já lançada. Deseja sobrescrever?")) {
                        inputMesPeriodoApuracao.focus();
                        return;
                    }
                    tabelaUnicidade.bootstrapTable('updateByUniqueId', {
                        id: itemUnicidade.id,
                        row: itemUnicidade
                    });
                    inputLancamentoUnicidade.value = JSON.stringify(tabelaUnicidade.bootstrapTable('getData'));
                    divMensagemUnicidade.removeAttribute("hidden");
                    divMensagemUnicidade.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
                    inputMesPeriodoApuracao.focus();
                    inputMatriculaUnicidade.value = '';
                    inputCodigoCategoriaUnicidade.value =  '';
                    inputDataReconhecidoUnicidade.value = '';
                    return;
            }
            tabelaUnicidade.bootstrapTable('append', itemUnicidade);

            inputLancamentoUnicidade.value = JSON.stringify(tabelaUnicidade.bootstrapTable('getData'));
            divMensagemUnicidade.removeAttribute("hidden");
            divMensagemUnicidade.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
            inputMesPeriodoApuracao.focus();
            inputMatriculaUnicidade.value = '';
            inputCodigoCategoriaUnicidade.value =  '';
            inputDataReconhecidoUnicidade.value = '';
            return;
        }

        btnLancarUnicidade.addEventListener('click', incluirUnicidade); 

        jQuery(document).ready(jQuery => {
            tabelaAnoAbono.bootstrapTable('destroy').bootstrapTable({
                height: 150,
                columns:colunasAnoAbono,
                locale : 'pt-BR',
                cache : false,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'Todos'],
                search : false,
                showRefresh: false,
                showColumns: false,
                uniqueId: "id",
                reorderableRows: true,
                toolbar: '.toolbar',
                class : "table table-sm",
                exportTypes : ['json', 'xml', 'csv', 'txt', 'excel']
            });
        });

        btnLancarAnoAbono.addEventListener('click', incluirRegistroAnoAbono); 

        //Lista de valores para fins previdênciários
        jQuery(document).ready(jQuery => {
            tabelaBaseCalculo.bootstrapTable('destroy').bootstrapTable({
                height: 150,
                columns:colunasBaseCalculo,
                locale : 'pt-BR',
                cache : false,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'Todos'],
                search : false,
                showRefresh: false,
                showColumns: false,
                uniqueId: "id",
                reorderableRows: true,
                toolbar: '.toolbar',
                class : "table table-sm",
                exportTypes : ['json', 'xml', 'csv', 'txt', 'excel']
            });
        });

        btnLancarRegistro.addEventListener('click', incluirRegistroPrevidenciario); 

        //Lista de valores para fins previdênciários
        jQuery(document).ready(jQuery => {
            tabelaFGTS.bootstrapTable('destroy').bootstrapTable({
                height: 150,
                columns:colunasFGTS,
                locale : 'pt-BR',
                cache : false,
                pagination : true,
                pageSize : 10,
                pageList : [10, 25, 50, 100, 200, 'Todos'],
                search : false,
                showRefresh: false,
                showColumns: false,
                uniqueId: "id",
                reorderableRows: true,
                toolbar: '.toolbar',
                class : "table bg-white table-bordered rounded shadow small",
                exportTypes : ['json', 'xml', 'csv', 'txt', 'excel']
            });
        });

        btnLancarFGTS.addEventListener('click', incluirFGTS); 

    };

    const salvarProcessoJudicial = () => {
        let parametros = new FormData(formProcessoJudicial);

        parametros.append('acao', 'salvarProcessoJudicial');
        HttpClient.post('pes4_processojudicial.RPC.php', {body: parametros}).then(response => {
            divMensagemProcesso.removeAttribute("hidden");
            divMensagemProcesso.setAttribute("class", "alert alert-success");
            if (response.erro) {
                divMensagemProcesso.setAttribute("class", "alert alert-danger");
            } else {
                inputSequencialProcesso.value = "";
                desabilitarFormularioProcesso(true);
                formProcessoJudicial.reset();
            }
            divMensagemProcesso.innerHTML = response.mensagem.trim().replace(/\\n/gi, '\n').replace(/\n/gi, '<br>');
            if (divMensagemProcesso.innerHTML == '') {
                divMensagemProcesso.setAttribute("hidden", "hidden");
            }
            controlarAbaServidor(true);
            listaProcessos();
        });
        
    };

    function adicionaAcao(value, row, index, $el) {
        return [
            '<a href="javascript:void(0)" onclick="editarProcesso(\'' + row.sequencial + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirProcesso(\'' + row.sequencial + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    function listaProcessos() {
        let oParam = {};
        oParam.acao = 'listaProcessos';
        // js_divCarregando('Consultando os dados.', 'msgbox');
        new Ajax.Request(
            urlProcesso,
            {
                method: 'post',
                parameters: oParam,
                onComplete: preencheGrid
            }
        );
    }

    function preencheGrid(oJson) {
        js_removeObj('msgbox');
        let oRetorno = JSON.parse(oJson.responseText);
        if (oRetorno.status == 1) {
            dadosGrid = oRetorno;
            tabelaProcessos.bootstrapTable({
                data: oRetorno.dados
            });
            tabelaProcessos.bootstrapTable("load", oRetorno.dados);
        } else {
            var limpa = [];
            alert(oRetorno.sMessage.urlDecode());
            tabelaProcessos.bootstrapTable("load", limpa);
        }
    }

    function editarProcesso(sequencial) {
        let oParam = {};
        let dadosFormulario = formProcessoJudicial.serialize();
        tabelaVinculo.bootstrapTable("load", []);
        oParam.acao = 'editarProcesso';
        oParam.sequencial = sequencial;

        controlarAbaServidor(false);
        
        divMensagemProcesso.removeAttribute("hidden");
        divMensagemProcesso.setAttribute("class", "alert alert-success");
        divMensagemProcesso.innerHTML = "Editar processo. Para vincular um servidor clique na aba <strong>'Vinculação Funcionários'</strong>";

        divMensagemVinculaServidor.removeAttribute("hidden");
        divMensagemVinculaServidor.setAttribute("class", "alert alert-success");
        divMensagemVinculaServidor.innerHTML = "Editar processo. Para uma novo vinculo, em  <strong>'Funcionários Vinculados'</strong>, " +
            "defina o filtro e prossiga com o preenchimento.<br>Para editar o vínculo, em <strong>'Funcionários Vinculados ao Processo Número ...'</strong> " +
            "clique no ícone <i class='fa fa-edit' style='font-size:22px'></i> corresondente da lista.";
        desabilitarFormularioProcesso(false);
        // js_divCarregando('Consultando os dados.', 'msgbox');
        new Ajax.Request(
            urlProcesso,
            {
                method: 'post',
                parameters: oParam,
                onComplete: preencheFormulario
            }
        );
        inputSequencialVinculaProcesso.value = oParam.sequencial;

        listaServidoresVinculados();

        lancadorMatricula.clearAll();
        tabelaFGTS.bootstrapTable('removeAll');
        tabelaBaseCalculo.bootstrapTable('removeAll');
        formVinculaServidor.reset();

    }

    function excluirProcesso(sequencial) {
        let numeroProcessoDigitado = prompt("Confirme o número do processo para exclusão.");
        var numeroProcesso = '';

        if (numeroProcessoDigitado == null) {
            alert("Número de processo inválido. Favor revisar.");
            return;
        }

        if (!(numeroProcessoDigitado.trim().length == 20 | numeroProcessoDigitado.trim().length == 14)) {
            alert("Número de processo inválido. Favor revisar.");
           return;
        }

        let oParam = {};
        var dados = '';
        oParam.acao = 'excluirProcesso';
        oParam.sequencial = sequencial;
        ajax = new Ajax.Request(
            urlProcesso,
            {
                method: 'post',
                parameters: oParam,
                onSuccess: function(transport) {
                    alert("Processo excluído com sucesso!");
                    location.reload();
                }

            }
        );
        return;
    }

    function preencheFormulario(oJson) {
        let oRetorno = JSON.parse(oJson.responseText);
        js_removeObj('msgbox');
        formProcessoJudicial.sequencial.value = oRetorno.dados[0].sequencial;
        formProcessoJudicial.origem.value = oRetorno.dados[0].origem;
        validaOrigemProcesso(oRetorno.dados[0].origem);
        formProcessoJudicial.numeroProcesso.value = oRetorno.dados[0].numeroProcesso;
        formProcessoJudicial.observacao.value = oRetorno.dados[0].observacaoProcesso;
        formProcessoJudicial.dataSentenca.value = oRetorno.dados[0].dataSentenca;
        formProcessoJudicial.UFVara.value = oRetorno.dados[0].ufVara;
        formProcessoJudicial.codigoMunicipio.value = oRetorno.dados[0].codigoMunicipio;
        formProcessoJudicial.codigoVara.value = oRetorno.dados[0].identificacaoVara;
        formProcessoJudicial.dataAcordo.value = oRetorno.dados[0].dataCelebracaoAcordo;
        formProcessoJudicial.tipoAcordo.value = oRetorno.dados[0].ambitoCelebracaoAcordo;
        formProcessoJudicial.cnpjCCP.value = oRetorno.dados[0].cnpjSindicato;
    }

    function desabilitarFormularioProcesso(parLogico) {
        inputOrigemProcesso.disabled = parLogico;
        inputNumeroProcesso.disabled  = parLogico;
        inputObservacaoProcesso.disabled  = parLogico;
        inputDataSentencaProcesso.disabled  = parLogico;
        inputUFVaraProcesso.disabled  = parLogico;
        inputCodigoMunicipioProcesso.disabled  = parLogico;
        inputCodigoVaraProcesso.disabled  = parLogico;
        inputDataAcordoProcesso.disabled  = parLogico;
        inputTipoAcordoProcesso.disabled  = parLogico;
        inputCnpjCCPProcesso.disabled  = parLogico;

        btnSalvarProcessoJudicial.disabled = parLogico;

    }

    function validaOrigemProcesso(origem) {
        inputDataSentencaProcesso.disabled  = true;
        inputUFVaraProcesso.disabled  = true;
        inputCodigoMunicipioProcesso.disabled  = true;
        inputCodigoVaraProcesso.disabled  = true;
        inputDataAcordoProcesso.disabled  = true;
        inputTipoAcordoProcesso.disabled  = true;
        inputCnpjCCPProcesso.disabled  = true;
        if (origem == 1) {
            oToogle2.show(true);
            oToogle3.show(false);
            inputDataSentencaProcesso.disabled  = false;
            inputUFVaraProcesso.disabled  = false;
            inputCodigoMunicipioProcesso.disabled  = false;
            inputCodigoVaraProcesso.disabled  = false;
            inputDataAcordoProcesso.disabled  = true;
            inputTipoAcordoProcesso.disabled  = true;
            inputCnpjCCPProcesso.disabled  = true;
        }
        if (origem == 2) {
            oToogle2.show(false);
            oToogle3.show(true);
            inputDataSentencaProcesso.disabled  = true;
            inputUFVaraProcesso.disabled  = true;
            inputCodigoMunicipioProcesso.disabled  = true;
            inputCodigoVaraProcesso.disabled  = true;
            inputDataAcordoProcesso.disabled  = false;
            inputTipoAcordoProcesso.disabled  = false;
            inputCnpjCCPProcesso.disabled  = false;
        }
    }

    const changeMatricula = () => {
        lancadorMatricula.withIcon = true;
        lancadorMatricula.lHabilitado = true;
        lancadorMatricula.setTextoFieldset('Pesquisa Matrículas');
        lancadorMatricula.setNomeInstancia("lancadorMatricula");
        lancadorMatricula.setLabelAncora("Matrícula: ");
        lancadorMatricula.setParametrosPesquisa("func_rhpessoal.php", ['rh01_regist', 'z01_nome']);
        lancadorMatricula.adicionarItensPrimeiraPosicao(true);
        lancadorMatricula.setGridHeight(150);
        lancadorMatricula.setTituloJanela('Pesquisa de Matrícula');
        lancadorMatricula.show($("containerLancadorMatricula"));
    }

    const inicializarFiltroMatriculas = () => {
        inicializaFiltro();
    };

    const inicializaFiltro = () => {
        divFiltroMatriculaSelecao.addEventListener('change', () => {
            divFiltroMatricula.style.display = 'block';
            divFiltroSelecao.style.display = 'none';
            idCodigoSelecao.value = "";
            idDescricaoSelecao.value = "";
            lancadorMatricula.show($("containerLancadorMatricula"));
            if (filtroCombo.value == 2) {
                divFiltroMatricula.style.display = 'none';
                divFiltroSelecao.style.display = 'block';
                lancadorMatricula.clearAll();
            }
        });
    }

    const vincularServidor = () => {
        let parametros = new FormData(formVinculaServidor);

        parametros.append('acao', 'vinculaServidor');

        let registros = lancadorMatricula.getRegistros();

        if (registros.length > 0) {
            parametros.matriculas = [];
            registros.each(registro => {
                parametros.matriculas.push(registro.sCodigo);
            });
        }

        parametros.append('json', JSON.stringify(parametros));


        HttpClient.post('pes4_processojudicial.RPC.php', {body: parametros}).then(response => {
            divMensagemVinculaServidor.removeAttribute("hidden");
            divMensagemVinculaServidor.setAttribute("class", "alert alert-success");
            if (response.erro) {
                divMensagemVinculaServidor.setAttribute("class", "alert alert-danger");
                alert(response.mensagem.urlDecode().replace(/\\n/g, '\n'));
            } else {
                inputSequencialProcesso.value = "";
                desabilitarFormularioProcesso(true);
                formProcessoJudicial.reset();
            }
            divMensagemVinculaServidor.innerHTML = response.mensagem.urlDecode().replace(/\\n/g, '\n');
            if (divMensagemVinculaServidor.innerHTML == '') {
                divMensagemVinculaServidor.setAttribute("hidden", "hidden");
            }
            listaServidoresVinculados();
            alert(response.mensagem.urlDecode().replace(/\\n/g, '\n'));
        });

        inputLancamentoUnicidade.value = "";

        divMensagemAbono.setAttribute("hidden", "hidden");
        divMensagemAbono.innerHTML == ''
        divMensagemBaseCalculo.setAttribute("hidden", "hidden");
        divMensagemBaseCalculo.innerHTML == ''
        divMensagemMudanca.setAttribute("hidden", "hidden");
        divMensagemMudanca.innerHTML == ''
        divMensagemUnicidade.setAttribute("hidden", "hidden");
        divMensagemUnicidade.innerHTML == ''
        divMensagemFGTS.setAttribute("hidden", "hidden");
        divMensagemFGTS.innerHTML == ''

    }

    function listaServidoresVinculados() {
            let oParam = {};
            oParam.acao = 'listaServidoresVinculados';
            oParam.sequencialProcesso = inputSequencialVinculaProcesso.value;
            // js_divCarregando('Consultando os dados.', 'msgbox');
            new Ajax.Request(
                urlProcesso,
                {
                    method: 'post',
                    parameters: oParam,
                    onComplete: preencheGridServidorVinculado
                }
            );
        }

   
    function preencheGridServidorVinculado(oJson) {

        let oRetorno = JSON.parse(oJson.responseText);
        js_removeObj('msgbox');

        if (oRetorno.status == 1) {
            dadosGrid = oRetorno;
            tabelaVinculo.bootstrapTable({
                data: oRetorno.dados
            });
            tabelaVinculo.bootstrapTable("load", oRetorno.dados);
        } else {
            let limpa = [];
            alert(oRetorno.sMessage.urlDecode());
            tabelaVinculo.bootstrapTable("load", limpa);
        }
    }

    function editarVinculo(registro) {
        let dadoFormulario = new FormData(formVinculaServidor);
        let oParam = {};
        if (typeof registro !== "object") {
            registro = tabelaVinculo.bootstrapTable('getRowByUniqueId', registro);
        }

        oParam.acao = 'editarVinculo';
        oParam.sequencial = registro.sequencial;
        lancadorMatricula.clearAll();
        lancadorMatricula.adicionarRegistro(registro.matricula, registro.nome );

        divMensagemVinculaServidor.removeAttribute("hidden");
        divMensagemVinculaServidor.setAttribute("class", "alert alert-success");
        divMensagemVinculaServidor.innerHTML = "Editar vinculação do funcionário.Para atulizar o(s) registro(s), clique no botão <strong>'VincularFuncionário'</strong> no final da tela.";
        // js_divCarregando('Consultando os dados.', 'msgbox');
        new Ajax.Request(
            urlProcesso,
            {
                method: 'post',
                parameters: oParam,
                onComplete: preencheFormularioVinculo
            }
        );

    }

    function excluirVinculo(registro) {
        let dadoExcluir = tabelaVinculo.bootstrapTable('getRowByUniqueId', registro);

        let confirma = confirm("Atenção! Deseja excluir o servidor \n" + dadoExcluir.matricula + "-" + dadoExcluir.nome + "\n do vínculo processual?");
        
        if (confirma == true) {
            confirma = confirm("Todos os registros relativos ao evento S-2501 do servidor \n" + dadoExcluir.matricula + "-" + dadoExcluir.nome + "\n serão excluídos. Você confirma?");
            if (confirma != true) {
                return;
            }
            let dadoFormulario = new FormData(formVinculaServidor);
            let oParam = {};
            oParam.acao = 'excluirVinculo';
            oParam.sequencialVinculo = parseInt(dadoExcluir.sequencial);
            oParam.sequencialProcesso = parseInt(dadoFormulario.get('sequencialProcesso'));
            oParam.matriculaMenssagem = parseInt(dadoExcluir.matricula);
            oParam.nomeMenssagem = dadoExcluir.nome;

            divMensagemVinculaServidor.removeAttribute("hidden");
            divMensagemVinculaServidor.setAttribute("class", "alert alert-success");
            divMensagemVinculaServidor.innerHTML = 'Excluir vinculação do funcionário.';
            // js_divCarregando('Consultando os dados.', 'msgbox');
             new Ajax.Request(
                urlProcesso,
                {
                    method: 'post',
                    parameters: oParam,
                    onComplete: js_retornoProcessamento
                }
            );
            listaServidoresVinculados();
        }
    }

    function js_retornoProcessamento(retornoJson) {
        let retornoDados = JSON.parse(retornoJson.responseText);
        js_removeObj('msgbox');
        divMensagemVinculaServidor.removeAttribute("hidden");
        divMensagemVinculaServidor.setAttribute("class", "alert alert-success");
        if (retornoDados.erro) {
            divMensagemVinculaServidor.setAttribute("class", "alert alert-danger");
        }
        divMensagemVinculaServidor.innerHTML = retornoDados.mensagem.urlDecode().replace(/\\n/g, '\n');
        alert(retornoDados.mensagem.urlDecode().replace(/\\n/g, '\n'));
    }

    function preencheFormularioVinculo(oJson) {
        let oRetorno = JSON.parse(oJson.responseText);
        js_removeObj('msgbox');

        formVinculaServidor.reset();
        divFiltroSelecao.style.display = 'none';
        divFiltroMatricula.style.display = 'block';
        inputTipoContrato.value = oRetorno.dados.tipoContrato;
        inputIndicativoContrato.value = oRetorno.dados.indicativoContrato;
        inputIndicativoReintegracao.value = oRetorno.dados.indicativoReintegracao;
        inputIndicativoNovaCategoria.value = oRetorno.dados.indicativoCategoria;
        inputNovaAtividade.value = oRetorno.dados.indicativoNaturezaAtividade;
        inputMotivoDesligamento.value = oRetorno.dados.indicativoMotivoDesligamento;
        if (oRetorno.dados.codigoCategoria !== undefined) {
            inputCategoriaMudanca.value = oRetorno.dados.codigoCategoria;
        }
        if (oRetorno.dados.dataMudancaCategoria !== undefined) {
            inputDataReconhecidoMudanca.value = oRetorno.dados.dataMudancaCategoria;
        }
        if (oRetorno.dados.naturezaAtividade !== undefined) {
            inputNaturezaAtividadeMudanca.value = oRetorno.dados.naturezaAtividade;
        }
        if (oRetorno.dados.matriculaUnicidade !== undefined) {
            inputMatriculaUnicidade.value = oRetorno.dados.matriculaUnicidade;
        }
        if (oRetorno.dados.codigoCategoriaUnicidade !== undefined) {
            inputCodigoCategoriaUnicidade.value = oRetorno.dados.codigoCategoriaUnicidade;
        }
        if (oRetorno.dados.dataInicioUnicidade !== undefined) {
            inputDataReconhecidoUnicidade.value = oRetorno.dados.dataInicioUnicidade;
        }
        
        if (oRetorno.dados.lancamentoUnicidade !== undefined) {
            tabelaUnicidade.bootstrapTable('removeAll');

            for (let i = 0; i < oRetorno.dados.lancamentoUnicidade.length; i++) {
                let matriculaUnicidade = oRetorno.dados.lancamentoUnicidade[i].matriculaUnicidade.replace(/[^0-9]/g,'');
                let codigoCategoriaUnicidade = oRetorno.dados.lancamentoUnicidade[i].codigoCategoriaUnicidade;
                let dataInicioUnicidade = oRetorno.dados.lancamentoUnicidade[i].dataInicioUnicidade;
                let dataParamentro = (dataInicioUnicidade === null ) ? '' : dataInicioUnicidade.replace(/[^0-9]/g,'');
                let idUnico = parseInt(matriculaUnicidade + codigoCategoriaUnicidade + dataParamentro);

                itemUnicidade = {
                    id: idUnico,
                    sequencialUnicidade: oRetorno.dados.lancamentoUnicidade[i].sequencial,
                    sequencialProcessoContrato: oRetorno.dados.lancamentoUnicidade[i].sequencialProcessoContrato,
                    matriculaUnicidade: oRetorno.dados.lancamentoUnicidade[i].matriculaUnicidade,
                    codigoCategoriaUnicidade: oRetorno.dados.lancamentoUnicidade[i].codigoCategoriaUnicidade,
                    dataInicioUnicidade: oRetorno.dados.lancamentoUnicidade[i].dataInicioUnicidade
                };

                tabelaUnicidade.bootstrapTable('append', itemUnicidade);
            }
        }

        if (oRetorno.dados.lancamentoAnoAbono !== undefined) {
            tabelaAnoAbono.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.dados.lancamentoAnoAbono.length; i++) {
                let dadoAnoAbono = oRetorno.dados.lancamentoAnoAbono[i].anoAbono;
                let dadoIdUnico = parseInt(oRetorno.dados.lancamentoAnoAbono[i].anoAbono);
                let dadoSequencialContrato = parseInt(oRetorno.dados.lancamentoAnoAbono[i].sequencialContrato);
                let dadoSequencial = parseInt(oRetorno.dados.lancamentoAnoAbono[i].sequencial);

                itemAnoAbono = {
                    id: dadoIdUnico,
                    sequencial: dadoSequencial,
                    sequencialContrato: dadoSequencialContrato,
                    anoAbono: dadoAnoAbono
                };

                tabelaAnoAbono.bootstrapTable('append', itemAnoAbono);
            }
        }

        if (oRetorno.dados.lancamentoMudanca !== undefined) {
            tabelaMudanca.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.dados.lancamentoMudanca.length; i++) {
                let dadoCodigoCategoriaMudanca = oRetorno.dados.lancamentoMudanca[i].codigoCategoriaMudanca;
                let dadoNaturezaAtividadeMudanca = oRetorno.dados.lancamentoMudanca[i].naturezaAtividadeMudanca;
                let dadoDataReconhecidoMudanca = formataData(oRetorno.dados.lancamentoMudanca[i].dataReconhecidoMudanca);
                let dadoIdUnico = 
                parseInt(oRetorno.dados.lancamentoMudanca[i].dataReconhecidoMudanca.replace(/[^0-9]/g,''));
                let dadoSequencialContrato = parseInt(oRetorno.dados.lancamentoMudanca[i].sequencialContrato);
                let dadoSequencial = parseInt(oRetorno.dados.lancamentoMudanca[i].sequencial);

                let itemMudanca = {
                    id: dadoIdUnico,
                    sequencial: dadoSequencial,
                    sequencialProcessoContrato: dadoSequencialContrato,
                    codigoCategoriaMudanca: dadoCodigoCategoriaMudanca,
                    naturezaAtividadeMudanca: dadoNaturezaAtividadeMudanca,
                    dataReconhecidoMudanca: dadoDataReconhecidoMudanca,
                };

                tabelaMudanca.bootstrapTable('append', itemMudanca);
            }
        }
        if (oRetorno.dados.lancamentoPrevidenciario !== undefined) {
            tabelaBaseCalculo.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.dados.lancamentoPrevidenciario.length; i++) {
                let y = oRetorno.dados.lancamentoPrevidenciario[i].periodo.split('-');
                let idUnico = parseInt(y[0]+y[1]);
                itemBaseCalculo = {
                    id: idUnico,
                    sequencial : oRetorno.dados.lancamentoPrevidenciario[i].sequencial,
                    periodoApuracao: oRetorno.dados.lancamentoPrevidenciario[i].periodo,
                    mensalContribuicao: oRetorno.dados.lancamentoPrevidenciario[i].valorBasePrevidenciaMensal,
                    contribuicao13: oRetorno.dados.lancamentoPrevidenciario[i].valorBasePrevidenciaMensal13,
                    grauExposicao: oRetorno.dados.lancamentoPrevidenciario[i].grauExposicao,
                    codigoMudancaoCategoria: oRetorno.dados.lancamentoPrevidenciario[i].codigoMudancaoCategoria,
                    valorBaseMudancaoCategoria: oRetorno.dados.lancamentoPrevidenciario[i].valorBaseMudancaoCategoria
                };

                tabelaBaseCalculo.bootstrapTable('append', itemBaseCalculo);
            }
        }

        if (oRetorno.dados.lancamentoFGTS !== undefined) {
            tabelaFGTS.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.dados.lancamentoFGTS.length; i++) {
                let y = oRetorno.dados.lancamentoFGTS[i].periodo.split('-');
                let idUnico = parseInt(y[0]+y[1]);
                itemFGTS = {
                    id: idUnico,
                    periodoApuracao: oRetorno.dados.lancamentoFGTS[i].periodo,
                    valorFGTSSemSEFIP: oRetorno.dados.lancamentoFGTS[i].valorBaseFGTSProcesso,
                    valorFGTSComSEFIP: oRetorno.dados.lancamentoFGTS[i].valorBaseFGTSSefip,
                    valorFGTSAnterior: oRetorno.dados.lancamentoFGTS[i].valorBaseFGTSDeclaradaAnteriormente
                };
                tabelaFGTS.bootstrapTable('append', itemFGTS);
            }
        }
        inputMesInicialProcesso.value = oRetorno.dados.competenciaInicial.split("-")[0];
        inputAnoInicialProcesso.value = oRetorno.dados.competenciaInicial.split("-")[1];
        inputMesFinalProcesso.value = oRetorno.dados.competenciaFinal.split("-")[0];
        inputAnoFinalProcesso.value = oRetorno.dados.competenciaFinal.split("-")[1];
        inputIndicativoRepercussao.value = oRetorno.dados.indicativoRepercussao;
        inputIndicativoIndenizacaoSD.value = oRetorno.dados.indicativoIndenizacaoSD;
        inputIndenizacaoAbono.value = oRetorno.dados.indenizacaoAbono;
    }

    const incluirRegistroAnoAbono = () => {
        const camposAnoAbono = new FormData(formVinculaServidor);
        let anoAbono = camposAnoAbono.get('anoAbono');
        let idUnico = parseInt(camposAnoAbono.get('anoAbono'));
        if (anoAbono.length != 4) {
            alert("Ano de abono é inválido. Favor revisar.");
            return;
        };

        let itemAnoAbono = {
            id: idUnico,
            sequencial: 0,
            sequencialContrato: 0,
            anoAbono: anoAbono
        };

        let verificaAnoAbono = tabelaAnoAbono.bootstrapTable('getRowByUniqueId', itemAnoAbono.id);

        if (verificaAnoAbono) {
            if (!confirm("Ano de abono já lançado. Deseja sobrescrever?")) {
                    inputAnoAbono.focus();
                    return;
                }
                tabelaAnoAbono.bootstrapTable('updateByUniqueId', {
                    id: itemAnoAbono.id,
                    row: itemAnoAbono
                });
                inputAnoAbono.value = '';
                divMensagemAbono.removeAttribute("hidden");
                divMensagemAbono.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";

                inputLancamentoAnoAbono.value = JSON.stringify(tabelaAnoAbono.bootstrapTable('getData'));
                return;
        }
        tabelaAnoAbono.bootstrapTable('append', itemAnoAbono);

        inputAnoAbono.value = '';

        
        inputLancamentoAnoAbono.value = JSON.stringify(tabelaAnoAbono.bootstrapTable('getData'));
        divMensagemAbono.removeAttribute("hidden");
        divMensagemAbono.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
        inputAnoAbono.focus();
        return;
    }

    function excluirAnoAbono(registro) {
        let linhaAnoAbono= tabelaAnoAbono.bootstrapTable('getRowByUniqueId', registro);
         if (!confirm("Confirma excluir o ano de abono?")) {
            return;
        }
        sequencialExcluidoAnoAbono.push(linhaAnoAbono.sequencialAnoAbono);
        sequencialAnoAbonoExcluir.value = sequencialExcluidoAnoAbono;
        tabelaAnoAbono.bootstrapTable('removeByUniqueId', registro);
        inputLancamentoAnoAbono.value = JSON.stringify(tabelaAnoAbono.bootstrapTable('getData'));
        divMensagemAbono.removeAttribute("hidden");
        divMensagemAbono.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
        inputAnoAbono.focus();
        return;
    }

    function editarAnoAbono(id) {
        let linhaAnoAbono = tabelaAnoAbono.bootstrapTable('getRowByUniqueId', id);

        inputAnoAbono.value = linhaAnoAbono.anoAbono;
        inputAnoAbono.focus();

        return;
    }

    const incluirRegistroPrevidenciario = () => {
        const camposPrevidenciario = new FormData(formVinculaServidor);
        let mesApuracao = camposPrevidenciario.get('mesPeriodoApuracao');
        let anoApuracao = camposPrevidenciario.get('anoPeriodoApuracao');
        let periodoApuracao = anoApuracao + '-' + mesApuracao;
        let idUnico = parseInt(anoApuracao + mesApuracao );
        let mensalContribuicao =  parseFloat(camposPrevidenciario.get('mensalContribuicao'));
        let contribuicao13 = parseFloat(camposPrevidenciario.get('contribuicao13'));
        let FGTSContribuicao = parseFloat(camposPrevidenciario.get('FGTSContribuicao'));
        let FGTSContribuicao13 = parseFloat(camposPrevidenciario.get('FGTSContribuicao13'));
        let grauExposicao = parseInt(camposPrevidenciario.get('grauExposicao'));
        let codigoMudancaoCategoria = parseInt(camposPrevidenciario.get('codigoMudancaoCategoria'));
        let valorBaseMudancaoCategoria = parseFloat(camposPrevidenciario.get('baseMudancaoCategoria'));
        let sequencial = 0;

        if (isNaN(mensalContribuicao)) {
            mensalContribuicao = 0.00;
        };

        if (isNaN(contribuicao13)) {
            contribuicao13 = 0.00;
        };

        if (isNaN(valorBaseMudancaoCategoria)) {
            valorBaseMudancaoCategoria = 0.00;
        };

        if (isNaN(grauExposicao)) {
            alert("Grau de exposição a agentes nocivos não definido. Favor revisar.");
            return;
        };

        if (isNaN(codigoMudancaoCategoria)) {
            codigoMudancaoCategoria = '';
        };

        if (mensalContribuicao < 0) {
            alert("Valor da base de cálculo da contribuição previdenciária sobre a remuneração mensal do trabalhador tem que ser maior que zero. Favor revisar.");
            return;
        };

        if (contribuicao13 < 0) {
            alert("'Valor da base de cálculo da contribuição previdenciária sobre a remuneração mensal do trabalhador' tem que ser maior que zero. Favor revisar.");
            return;
        };

        if (valorBaseMudancaoCategoria < 0) {
            alert("'Valor da remuneração do trabalhador a ser considerada para fins previdenciários declarada em GFIP ou em S-1200 de trabalhador sem cadastro no S-2300' tem que ser maior que zero. Favor revisar.");
            return;
        };

        if (mesApuracao == '' ) {
            alert("Não é possível lançar mês de apuração vazio. Favor revisar.");
            return;
        }

        if (anoApuracao == '' ) {
            alert("Não é possível lançar ano de apuração vazio. Favor revisar.");
            return;
        }

        if (anoApuracao.length != 4 ) {
            alert("Valor do ano de apuração, não válido. Favor revisar.");
            return;
        }

        if (parseInt(anoApuracao) < parseInt(inputAnoInicialProcesso.value)) {
            alert("Valor do ano menor que a competência inicial definida. Favor revisar.");
            return;
        }

        if (parseInt(anoApuracao) >= parseInt(inputAnoInicialProcesso.value)) {
            if (parseInt(anoApuracao) == parseInt(inputAnoInicialProcesso.value) &&
                parseInt(mesApuracao) < parseInt(inputMesInicialProcesso.value) )  {
                    alert("Valor do mês menor que a competência inicial definida. Favor revisar.");
                    return;
            }
        }

        if (parseInt(anoApuracao) > parseInt(inputAnoFinalProcesso.value)) {
            alert("Valor do ano maior que a competência final definida. Favor revisar.");
            return;
        }

        if (parseInt(anoApuracao) <= parseInt(inputAnoFinalProcesso.value)) {
            if (parseInt(anoApuracao) == parseInt(inputAnoFinalProcesso.value) &&
                parseInt(mesApuracao) > parseInt(inputMesFinalProcesso.value) )  {
                    alert("Valor do mês maior que a competência final definida. Favor revisar.");
                    return;
            }
        }

        let verificaApuracao = tabelaBaseCalculo.bootstrapTable('getRowByUniqueId', idUnico);

        if (Boolean(verificaApuracao)){
            sequencial =  verificaApuracao.sequencial;
        }

        let itemBaseCalculo = {
            id: idUnico,
            sequencial: sequencial,
            periodoApuracao: periodoApuracao,
            mensalContribuicao: mensalContribuicao,
            contribuicao13: contribuicao13,
            grauExposicao: grauExposicao,
            codigoMudancaoCategoria: codigoMudancaoCategoria,
            valorBaseMudancaoCategoria: valorBaseMudancaoCategoria
        };

        if (Boolean(verificaApuracao)) {
            if (!confirm("Competência já lançada. Deseja sobrescrever?")) {
                    inputMesPeriodoApuracao.focus();
                    return;
                }

                tabelaBaseCalculo.bootstrapTable('updateByUniqueId', {
                    id: itemBaseCalculo.id,
                    row: itemBaseCalculo
                });
                inputMesPeriodoApuracao.value = '';
                inputAnoPeriodoApuracao.value = '';
                inputMensalContribuicao.value = 0.00;
                inputContribuicao13.value = 0.00;
                inputGrauExposicao.value = ''

                inputLancamentoPrevidenciario.value = JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData'));
                divMensagemBaseCalculo.removeAttribute("hidden");
                divMensagemBaseCalculo.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
                return;
        }
        tabelaBaseCalculo.bootstrapTable('append', itemBaseCalculo);

        inputMesPeriodoApuracao.value = '';
        inputAnoPeriodoApuracao.value = '';
        inputMensalContribuicao.value = 0.00;
        inputContribuicao13.value = 0.00;
        inputGrauExposicao.value = '';

        inputLancamentoPrevidenciario.value = JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData'));
        divMensagemBaseCalculo.removeAttribute("hidden");
        divMensagemBaseCalculo.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
        inputMesPeriodoApuracao.focus();

        return;

    }

    function excluirBaseCalculo(index) {
        index = parseInt(index);
        if (!confirm("Confirma excluir a competência?")) {
            return;
        }
        tabelaBaseCalculo.bootstrapTable('remove', {
            field: '$index',
            values: [index]
        });

        inputLancamentoPrevidenciario.value = JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData'));

        inputMesPeriodoApuracao.value = '';
        inputAnoPeriodoApuracao.value = '';
        inputMensalContribuicao.value = 0.00;
        inputContribuicao13.value = 0.00;
        inputGrauExposicao.value = '';
        inputCodigoMudancaoCategoria = '';
        inputBaseMudancaoCategoria = 0.00;

        alert("Registro excluído com sucesso.");
        return;
    }

    function editarUnicidade(id) {
        let linhaUnicidade = tabelaUnicidade.bootstrapTable('getRowByUniqueId', id);
        inputMatriculaUnicidade.value = linhaUnicidade.matriculaUnicidade;
        inputCodigoCategoriaUnicidade.value =  linhaUnicidade.codigoCategoriaUnicidade;
        inputDataReconhecidoUnicidade.value = formataDataDado(linhaUnicidade.dataInicioUnicidade);
        inputMatriculaUnicidade.focus();
        return;
    }

    function editarBaseCalculo(id) {
        let linhaBaseCalculo = tabelaBaseCalculo.bootstrapTable('getRowByUniqueId', id);
        let competencia = linhaBaseCalculo.periodoApuracao.split("-");

        inputMesPeriodoApuracao.value = competencia[1];
        inputAnoPeriodoApuracao.value = competencia[0];
        inputMensalContribuicao.value = linhaBaseCalculo.mensalContribuicao;
        inputContribuicao13.value = linhaBaseCalculo.contribuicao13;
        inputGrauExposicao.value = linhaBaseCalculo.grauExposicao;
        inputCodigoMudancaoCategoria.value = linhaBaseCalculo.codigoMudancaoCategoria;
        inputBaseMudancaoCategoria.value = linhaBaseCalculo.valorBaseMudancaoCategoria;
        inputMesPeriodoApuracao.focus();
        return;
    }

    const incluirFGTS = () => {
        const camposFGTS = new FormData(formVinculaServidor);
        let mesApuracao = camposFGTS.get('mesPeriodoApuracaoFGTS');
        let anoApuracao = camposFGTS.get('anoPeriodoApuracaoFGTS');
        let periodoApuracao = anoApuracao + '-' + mesApuracao;
        let idUnico = parseInt(anoApuracao + mesApuracao);
        let valorFGTSSemSEFIP =  parseFloat(camposFGTS.get('valorFGTSSemSEFIP'));
        let valorFGTSComSEFIP = parseFloat(camposFGTS.get('valorFGTSComSEFIP'));
        let valorFGTSAnterior = parseFloat(camposFGTS.get('valorFGTSAnterior'));


        if (isNaN(valorFGTSSemSEFIP)) {
            valorFGTSSemSEFIP = 0.00;
        };

        if (isNaN(valorFGTSComSEFIP)) {
            valorFGTSComSEFIP = 0.00;
        };

        if (isNaN(valorFGTSAnterior)) {
            valorFGTSAnterior = 0.00;
        };

        if (valorFGTSSemSEFIP < 0) {
            alert("'Valor da base de cálculo de FGTS ainda não declarada em SEFIP ou no eSocial, inclusive de verba reconhecida no processo trabalhista' tem que ser maior que zero. Favor revisar.");
            inputValorFGTSSemSEFIP.focus();
            return;
        };

        if (valorFGTSComSEFIP < 0) {
            alert("'Valor da base de cálculo de FGTS declarada apenas em SEFIP (não informada no eSocial) e ainda não recolhida' tem que ser maior que zero. Favor revisar.");
            inputValorFGTSComSEFIP.focus();
            return;
        };

        if (valorFGTSAnterior < 0) {
            alert("'Valor da base de cálculo de FGTS declarada apenas em SEFIP (não informada no eSocial) e ainda não recolhida' tem que ser maior que zero. Favor revisar.");
            inputValorFGTSAnterior.focus();
            return;
        };

        if (mesApuracao == '' ) {
            alert("Não é possível lançar mês de apuração vazio. Favor revisar.");
            inputMesPeriodoApuracaoFGTS.focus();
            return;
        }

        if (anoApuracao == '' ) {
            alert("Não é possível lançar ano de apuração vazio. Favor revisar.");
            inputAnoPeriodoApuracaoFGTS.focus();
            return;
        }

        if (anoApuracao.length != 4 ) {
            alert("Valor do ano de apuração, não válido. Favor revisar.");
            inputAnoPeriodoApuracaoFGTS.focus();
            return;
        }

        if (parseInt(anoApuracao) < parseInt(inputAnoInicialProcesso.value)) {
            alert("Valor do ano menor que a competência inicial definida. Favor revisar.");
            return;
        }

        if (parseInt(anoApuracao) >= parseInt(inputAnoInicialProcesso.value)) {
            if (parseInt(anoApuracao) == parseInt(inputAnoInicialProcesso.value) &&
                parseInt(mesApuracao) < parseInt(inputMesInicialProcesso.value) )  {
                    alert("Valor do mês menor que a competência inicial definida. Favor revisar.");
                    return;
            }
        }

        if (parseInt(anoApuracao) > parseInt(inputAnoFinalProcesso.value)) {
            alert("Valor do ano maior que a competência final definida. Favor revisar.");
            return;
        }

        if (parseInt(anoApuracao) <= parseInt(inputAnoFinalProcesso.value)) {
            if (parseInt(anoApuracao) == parseInt(inputAnoFinalProcesso.value) &&
                parseInt(mesApuracao) > parseInt(inputMesFinalProcesso.value) )  {
                    alert("Valor do mês maior que a competência final definida. Favor revisar.");
                    return;
            }
        }

        let itemFGTS = {
            id: idUnico,
            periodoApuracao: periodoApuracao,
            valorFGTSSemSEFIP: valorFGTSSemSEFIP,
            valorFGTSComSEFIP: valorFGTSComSEFIP,
            valorFGTSAnterior: valorFGTSAnterior
        };

        let verificaApuracao = tabelaFGTS.bootstrapTable('getRowByUniqueId', itemFGTS.id);

        if (verificaApuracao) {
            if (!confirm("Competência já lançada. Deseja sobrescrever?")) {
                    inputMesPeriodoApuracao.focus();
                    return;
                }
                tabelaFGTS.bootstrapTable('updateByUniqueId', {
                    id: itemFGTS.id,
                    row: itemFGTS
                });
                inputMesPeriodoApuracaoFGTS.value = '';
                inputAnoPeriodoApuracaoFGTS.value = '';
                inputValorFGTSSemSEFIP.value = 0.00;
                inputValorFGTSComSEFIP.value = 0.00;
                inputValorFGTSAnterior.value = 0.00;
                inputMesPeriodoApuracaoFGTS.focus();
                inputLancamentoFGTS.value = JSON.stringify(tabelaFGTS.bootstrapTable('getData'));
                divMensagemFGTS.removeAttribute("hidden");
                divMensagemFGTS.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
                return;
        }

        tabelaFGTS.bootstrapTable('append', itemFGTS);

        inputMesPeriodoApuracaoFGTS.value = '';
        inputAnoPeriodoApuracaoFGTS.value = '';
        inputValorFGTSSemSEFIP.value = 0.00;
        inputValorFGTSComSEFIP.value = 0.00;
        inputValorFGTSAnterior.value = 0.00;
        inputMesPeriodoApuracaoFGTS.focus();
        inputLancamentoFGTS.value = JSON.stringify(tabelaFGTS.bootstrapTable('getData'));
        divMensagemFGTS.removeAttribute("hidden");
        divMensagemFGTS.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";

        return;

    }

    function excluirFGTS(index) {
        index = parseInt(index);
        if (!confirm("Confirma excluir a competência?")) {
            return;
        }
        tabelaFGTS.bootstrapTable('remove', {
            field: '$index',
            values: [index]
        });
        inputLancamentoFGTS.value = JSON.stringify(tabelaFGTS.bootstrapTable('getData'));
        alert("Registro excluído com sucesso.");
        return;
    }

    function editarFGTS(id) {
        let linhaFGTS = tabelaFGTS.bootstrapTable('getRowByUniqueId', id);
        let competencia = linhaFGTS.periodoApuracao.split("-");

        inputMesPeriodoApuracaoFGTS.value = competencia[1];
        inputAnoPeriodoApuracaoFGTS.value = competencia[0];
        inputValorFGTSSemSEFIP.value = linhaFGTS.valorFGTSSemSEFIP;
        inputValorFGTSComSEFIP.value = linhaFGTS.valorFGTSComSEFIP;
        inputValorFGTSAnterior.value = linhaFGTS.valorFGTSAnterior;
        inputMesPeriodoApuracaoFGTS.focus();
        inputLancamentoFGTS.value = JSON.stringify(tabelaFGTS.bootstrapTable('getData'));
        return;
    }


    function excluirUnicidade(registro) {
        let linhaUnicidade= tabelaUnicidade.bootstrapTable('getRowByUniqueId', registro);
        sequencialExcluidoUnicidade.push(linhaUnicidade.sequencialUnicidade);
        sequencialUnicidadeExcluir.value = sequencialExcluidoUnicidade;
        tabelaUnicidade.bootstrapTable('removeByUniqueId', registro);
        inputLancamentoUnicidade.value = JSON.stringify(tabelaUnicidade.bootstrapTable('getData'));
        divMensagemUnicidade.removeAttribute("hidden");
        divMensagemUnicidade.innerHTML = "Ao finalizar, clique no botão <strong>'Vincular Funcionário'</strong>, no final da tela, para incluir/atualizar o registro.";
        return;
    }

    inicializar();

</script>
</body>
</html>
