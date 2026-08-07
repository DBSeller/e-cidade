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
    <div id="abaTributo">
        <?php require_once modification('forms/db_fromlancartributoprocessojudicial.php'); ?>
    </div>
    <div id="abaComplemento">
        <?php require_once modification('forms/db_fromcomplementotributoprocessojudicial.php'); ?>
    </div>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript">
    const dbAbas = new DBAbas(document.querySelector('#ctnAbas'));
    const abaTributo = dbAbas.adicionarAba('Tributos do Processo', document.querySelector('#abaTributo'));
    const abaComplemento = dbAbas.adicionarAba('Complemento Tributos', document.querySelector('#abaComplemento'));
    const controlarAbaTributo = bloqueia => {
        abaComplemento.lBloqueada = bloqueia;
    };
    const formTributoProcessoJudicial = document.querySelector('#idFormTributoProcessoJudicial');
    const btnSalvarProcessoJudicial = document.querySelector('#idSalvarProcessoJudicial');
    const divMensagemProcesso = formTributoProcessoJudicial.querySelector('#idMensagem');

    const idFiltro = formTributoProcessoJudicial.querySelector('#idFiltro');
    const dataSentencaAcordo = formTributoProcessoJudicial.querySelector('#idDataSentencaAcordo');
    const numeroProcessoDefinido = formTributoProcessoJudicial.querySelector('#idNumeroProcessoDefinido');
    const idMatriculaLinha = formTributoProcessoJudicial.querySelector('#idMatriculaLinha');
    const idProcessoLinha = formTributoProcessoJudicial.querySelector('#idProcessoLinha');
    const idSelectProcesso = formTributoProcessoJudicial.querySelector('#idSelectProcesso');
    const idLabelProcesso = formTributoProcessoJudicial.querySelector('#idLabelProcesso');
    const idTabelaPeriodo = formTributoProcessoJudicial.querySelector('#idTabelaPeriodo');
    const idPeriodoReferencia = formTributoProcessoJudicial.querySelector('#idPeriodoReferencia');
    const idLabelPeriodo = formTributoProcessoJudicial.querySelector('#idLabelPeriodo');
    const codigoMatricula = formTributoProcessoJudicial.querySelector('#codigoMatricula');
    const idNumeroProcesso = formTributoProcessoJudicial.querySelector('#idNumeroProcesso');
    const nomeServidor = formTributoProcessoJudicial.querySelector('#nomeServidor');

    const lancarContribuicaoTributaria  = formTributoProcessoJudicial.querySelector('#idLancarContribuicaoTributaria');
    const periodoApuracaoMes  = formTributoProcessoJudicial.querySelector('#idPerApuraMes');
    const periodoApuracaoAno  = formTributoProcessoJudicial.querySelector('#idPerApuraAno');
    const observacao  = formTributoProcessoJudicial.querySelector('#idObservacao');

    const contribuicaoMensal  = formTributoProcessoJudicial.querySelector('#idMensalContribuicao');
    const contribuicaoMensal13  = formTributoProcessoJudicial.querySelector('#idContribuicao13');

    const lancamentosTributosPagamento = formTributoProcessoJudicial.querySelector('#idInputTributosPagamento');
    const lancamentosTributosPrevidencial = formTributoProcessoJudicial.querySelector('#idInputTributosPrevidencial');
    const lancamentosTributosIRRF = formTributoProcessoJudicial.querySelector('#idInputTributosIRRF');
    const linhatabelaContribuicao = formTributoProcessoJudicial.querySelector('#idLinhatabelaContribuicao');
    const tabelaContribuicao = formTributoProcessoJudicial.querySelector('#idTabelaContribuicao');

    const lancarTributoPrevidencial = formTributoProcessoJudicial.querySelector('#idLancarTributoPrevidencial');

    const codigoReceita = formTributoProcessoJudicial.querySelector('#idtpCR');
    const CodigoReceitaLabel = formTributoProcessoJudicial.querySelector('#idtpCRLabel');
    const tabela29 = [
        "113851 - CP patronal a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "164651 - CP GILRAT a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso	",
        "114151 - CP para financiamento de aposentadoria especial a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "113852 - CP adicional a cargo das instituições financeiras sobre a remuneração do segurado empregado ou trabalhador avulso",
        "113854 - CP patronal a cargo da empresa sobre a remuneração do segurado contribuinte individual",
        "114155 - CP para financiamento de aposentadoria especial a cargo da empresa sobre a remuneração do segurado contribuinte individual",
        "113855 - CP adicional a cargo das instituições financeiras sobre a remuneração do segurado contribuinte individual",
        "113858 - CP patronal a cargo do empregador domêstico sobre a remuneração do segurado empregado domêstico",
        "164659 - CP GILRAT a cargo do empregador domêstico sobre a remuneração do segurado empregado domêstico",
        "113857 - CP patronal a cargo do Microempreendedor - MEI sobre a remuneração do segurado empregado",
        "113853 - CP patronal a cargo da empresa SIMPLES com atividade concomitante sobre a remuneração do segurado empregado ou trabalhador avulso",
        "164652 - CP GILRAT a cargo da empresa SIMPLES com atividade concomitante sobre a remuneração do segurado empregado ou trabalhador avulso",
        "114152 - CP adicional GILRAT a cargo da empresa SIMPLES com atividade concomitante sobre a remuneração do segurado empregado ou trabalhador avulso",
        "113856 - CP patronal a cargo da empresa SIMPLES com atividade concomitante sobre a remuneração do segurado contribuinte individual",
        "117051 - Salário-Educação a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "117651 - Incra a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "117652 - Incra (FPAS 531/795/825) a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "118151 - Senai a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "118451 - Sesi a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "119151 - Senac a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "119651 - Sesc a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "120051 - Sebrae a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "120052 - Sebrae (FPAS 566/574/647) a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "120551 - FDEPM a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "120951 - Fundo Aeroviário a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "121353 - Senar a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "121851 - Sest a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "121852 - Sest a cargo do trabalhador (descontado pela empresa) sobre a remuneração do segurado transportador aut?nomo",
        "122151 - Senat a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "122152 - Senat a cargo do trabalhador (descontado pela empresa) sobre a remuneração do segurado transportador aut?nomo",
        "122551 - Sescoop a cargo da empresa sobre a remuneração do segurado empregado ou trabalhador avulso",
        "108251 - CP do segurado empregado e trabalhador avulso",
        "108252 - CP do segurado empregado contratado por curto prazo - Lei 11.718/2009",
        "108253 - CP do segurado empregado domêstico",
        "108254 - CP do segurado empregado contratado por curto prazo por empregador segurado especial - Lei 11.718/2009",
        "108255 - CP do segurado empregado contratado por empregador segurado especial",
        "108257 - CP do segurado empregado contratado por empregador MEI",
        "109951 - CP do segurado contribuinte individual",
        "109952 - CP do segurado contribuinte individual"];
    const periodoPagamentoContemplado = formTributoProcessoJudicial.querySelector('#idPeriodoPagamentoContemplado');
    const sequencialBaseExcluir = formTributoProcessoJudicial.querySelector('#idSequencialBaseExcluir');
    var sequecialBaseExcluido = [];
    var sequencialPrevidenciaExcluido = [];
    var sequencialExcluidoIRRF = [];
    const sequencialExcluirIRRF = formTributoProcessoJudicial.querySelector('#idSequencialExcluirIRRF');
    const sequencialPrevidenciaExcluir = formTributoProcessoJudicial.querySelector('#idSequencialPrevidenciaExcluir');
    const sequencialBaseEditar = formTributoProcessoJudicial.querySelector('#idSequencialBaseEditar');
    const sequencialPrevidencialEditar = formTributoProcessoJudicial.querySelector('#idSequencialPrevidencialEditar');
    
    const codigoReceitaImpostoRendaRetido = [
        "593656 - IRRF - Decisão da Justiça do Trabalho",
        "056152 - IRRF - CCP/NINTER",
        "188951 - IRRF - RRA"
    ];
    const lancarTributoImpostoRenda  = formTributoProcessoJudicial.querySelector('#idTributoImpostoRenda');
    const tabelaImpostoRenda  = formTributoProcessoJudicial.querySelector('#idTabelaImpostoRenda');
    const codigoReceitaIRRF  = formTributoProcessoJudicial.querySelector('#idtpCRImpostoRenda');
    const valorIRRF  = formTributoProcessoJudicial.querySelector('#idvrCRImpostoRenda');
    const valorCR  = formTributoProcessoJudicial.querySelector('#idvrCR');
    const pgtoImpostoRenda  = formTributoProcessoJudicial.querySelector('#idPerApurPgtoImpostoRenda');

    const navegacaoTributoProcesso = formTributoProcessoJudicial.querySelector("#idNavegacao");
    const btnProximaAba = formTributoProcessoJudicial.querySelector("#idProximaAba");

    const formComplementoTributoProcessoJudicial = document.querySelector('#idFormComplementoTributoProcessoJudicial');
    const lancarCodigoIRRF  = formComplementoTributoProcessoJudicial.querySelector('#idLancarCodigoIRRF');
    const tabelaCodigoRelativoIRRF  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaCodigoIRRF');
    const campoCodigoRelativoIRRF = formComplementoTributoProcessoJudicial.querySelector('#idCodigoRelativoIRRF');
    const campoValorRendimentoMensal = formComplementoTributoProcessoJudicial.querySelector('#idValorRendimentoMensal');
    const campoValorRendimento13Mensal = formComplementoTributoProcessoJudicial.querySelector('#idValorRendimento13Mensal');
    const campoValorMolestiaGrave = formComplementoTributoProcessoJudicial.querySelector('#idValorMolestiaGrave');
    const campoValorIsenta65 = formComplementoTributoProcessoJudicial.querySelector('#idValorIsenta65');
    const campoValorJuroMora = formComplementoTributoProcessoJudicial.querySelector('#idValorJuroMora');
    const campoValorNaoTributavel = formComplementoTributoProcessoJudicial.querySelector('#idValorNaoTributavel');
    const campoDescricaoNaoTributavel = formComplementoTributoProcessoJudicial.querySelector('#idDescricaoNaoTributavel');
    const campoValorPrevidenciaOficial = formComplementoTributoProcessoJudicial.querySelector('#idValorPrevidenciaOficial');
    const campoDescricaoRRA = formComplementoTributoProcessoJudicial.querySelector('#idDescricaoRRA');
    const campoQuantidadeRRA= formComplementoTributoProcessoJudicial.querySelector('#idQuantidadeRRA');
    const campoDespCustas = formComplementoTributoProcessoJudicial.querySelector('#idDespCustas');
    const campoDespAdvogados = formComplementoTributoProcessoJudicial.querySelector('#idDespAdvogados');

    const lancamentosCodigoIRRF  = formComplementoTributoProcessoJudicial.querySelector('#idInputCodigoIRRF');
    const sequencialCodigoIRRFExcluir  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialCodigoIRRFExcluir');
    var sequencialCodigoIRRFExcluido = [];

    const tableAdvogado  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaAdvogado');
    const lancarAdvogado  = formComplementoTributoProcessoJudicial.querySelector('#idLancarAdvogado');
    const lancamentosAdvogado  = formComplementoTributoProcessoJudicial.querySelector('#idInputAdvogado');
    const sequencialExcluirAdvogado  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialAdvogadoExcluir');
    var sequencialExcluidoAdvogado = [];
    const campoTipoInscricaoADV = formComplementoTributoProcessoJudicial.querySelector('#idTipoInscricaoADV');
    const campoCnpjADV= formComplementoTributoProcessoJudicial.querySelector('#idCNPJADV');
    const campoCpfADV = formComplementoTributoProcessoJudicial.querySelector('#idCPFADV');
    const campoValorADV = formComplementoTributoProcessoJudicial.querySelector('#idValorADV');
    const labelCnpjADV= formComplementoTributoProcessoJudicial.querySelector('#idCNPJADVLabel');
    const labelCpfADV = formComplementoTributoProcessoJudicial.querySelector('#idCPFADVLabel');

    const tableDependente  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaDependente');
    const lancarDependente  = formComplementoTributoProcessoJudicial.querySelector('#idLancarDependente');
    const lancamentosDependente  = formComplementoTributoProcessoJudicial.querySelector('#idInputDependente');
    const sequencialExcluirDependente  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialDependenteExcluir');
    var sequencialExcluidoDependente = [];
    const campoTipoRendimentoDEP = formComplementoTributoProcessoJudicial.querySelector('#idTipoRendimentoDEP');
    const campoCpfDEP = formComplementoTributoProcessoJudicial.querySelector('#idCPFDEP');
    const campoValorDEP = formComplementoTributoProcessoJudicial.querySelector('#idValorDEP');
    const labelCpfDEP = formComplementoTributoProcessoJudicial.querySelector('#idCPFDEPLabel');

    const tablePensao  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaPensao');
    const lancarPensao  = formComplementoTributoProcessoJudicial.querySelector('#idLancarPensao');
    const lancamentosPensao  = formComplementoTributoProcessoJudicial.querySelector('#idInputPensao');
    const sequencialExcluirPensao  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialPensaoExcluir');
    var sequencialExcluidoPensao = [];
    const campoTipoRendimentoPEN = formComplementoTributoProcessoJudicial.querySelector('#idTipoRendimentoPEN');
    const campoCpfPEN = formComplementoTributoProcessoJudicial.querySelector('#idCPFPEN');
    const campoValorPEN = formComplementoTributoProcessoJudicial.querySelector('#idValorPEN');
    const labelCpfPEN = formComplementoTributoProcessoJudicial.querySelector('#idCPFPEN');

    const tableRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaRetencao');
    const lancarRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idLancarRetencao');
    const lancamentosRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idInputRetencao');
    const sequencialExcluirRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialRetencaoExcluir');
    var sequencialExcluidoRetencao = [];
    const campoNumeroRetencao = formComplementoTributoProcessoJudicial.querySelector('#idNumeroRetencao');
    const campoCodigoSuspensaoRetencao = formComplementoTributoProcessoJudicial.querySelector('#idCodigoSuspensao');
    const campoTipoRetencao = formComplementoTributoProcessoJudicial.querySelector('#idTipoRetencao');

    const tableValorRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaValorRetencao');
    const lancarValorRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idLancarValorRetencao');
    const lancamentosValorRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idInputValorRetencao');
    const sequencialExcluirValorRetencao  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialValorRetencaoExcluir');
    var sequencialExcluidoValorRetencao = [];
    const campoProcessoRetencao = formComplementoTributoProcessoJudicial.querySelector('#idProcessoRetencao');
    const campoPeriodoApuracao = formComplementoTributoProcessoJudicial.querySelector('#idPeriodoApuracao');
    const campoValorRetencao = formComplementoTributoProcessoJudicial.querySelector('#idValorRetencao');
    const campoValorDeposito = formComplementoTributoProcessoJudicial.querySelector('#idValorDeposito');
    const campoValorAnoCalendario = formComplementoTributoProcessoJudicial.querySelector('#idValorAnoCalendario');
    const campoValorAnoAnterior = formComplementoTributoProcessoJudicial.querySelector('#idValorAnoAnterior');
    const campoValorRendimentoSuspenso = formComplementoTributoProcessoJudicial.querySelector('#idValorRendimentoSuspenso');

    const tableDeducaoSuspensa  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaDeducaoSuspensa');
    const lancarDeducaoSuspensa  = formComplementoTributoProcessoJudicial.querySelector('#idLancarDeducaoSuspensa');
    const lancamentosDeducaoSuspensa  = formComplementoTributoProcessoJudicial.querySelector('#idInputDeducaoSuspensa');
    const sequencialExcluirDeducaoSuspensa  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialDeducaoSuspensaExcluir');
    var sequencialExcluidoDeducaoSuspensa = [];
    const campoProcessoDeducaoSuspensa = formComplementoTributoProcessoJudicial.querySelector('#idProcessoDeducaoSuspensa');
    const campoTipoDeducao = formComplementoTributoProcessoJudicial.querySelector('#idTipoDeducao');
    const campoValorDeducaoSuspensa = formComplementoTributoProcessoJudicial.querySelector('#idValorDeducaoSuspensa');

    const tableSuspensaPensao  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaSuspensaPensao');
    const lancarSuspensaPensao  = formComplementoTributoProcessoJudicial.querySelector('#idLancarSuspensaPensao');
    const lancamentosSuspensaPensao  = formComplementoTributoProcessoJudicial.querySelector('#idInputSuspensaPensao');
    const sequencialExcluirSuspensaPensao  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialSuspensaPensaoExcluir');
    var sequencialExcluidoSuspensaPensao = [];
    const campoProcessoSuspensaPensao = formComplementoTributoProcessoJudicial.querySelector('#idProcessoSuspensaPensao');
    const campoCPFSuspensaPensao = formComplementoTributoProcessoJudicial.querySelector('#idCPFSuspensaPensao');
    const campoValorSuspensaPensao = formComplementoTributoProcessoJudicial.querySelector('#idValorSuspensaPensao');

    const tableIRComplementar  = formComplementoTributoProcessoJudicial.querySelector('#idTabelaIRComplementar');
    const lancarIRComplementar  = formComplementoTributoProcessoJudicial.querySelector('#idLancarIRComplementar');
    const lancamentosIRComplementar  = formComplementoTributoProcessoJudicial.querySelector('#idInputIRComplementar');
    const sequencialExcluirIRComplementar  = formComplementoTributoProcessoJudicial.querySelector('#idSequencialIRComplementarExcluir');
    var sequencialExcluidoIRComplementar = [];
    const campoDataLaudo = formComplementoTributoProcessoJudicial.querySelector('#idDataLaudo');
    const campoCPFIRComplementar = formComplementoTributoProcessoJudicial.querySelector('#idCPFIRComplementar');
    const campoDataNascimento = formComplementoTributoProcessoJudicial.querySelector('#idDataNascimento');
    const campoNomeDependente = formComplementoTributoProcessoJudicial.querySelector('#idNomeDependente');
    const campoDepIRRF = formComplementoTributoProcessoJudicial.querySelector('#idDepIRRF');
    const campoTipoDependente = formComplementoTributoProcessoJudicial.querySelector('#idTipoDependente');
    const campoDescricaoDependencia = formComplementoTributoProcessoJudicial.querySelector('#idDescricaoDependencia');

    const btnAnteriorAba = formComplementoTributoProcessoJudicial.querySelector("#idAnteriorAba");
    const btnSalvarComplemento = formComplementoTributoProcessoJudicial.querySelector("#idSalvarComplemento");

    const divMensagemCodigoIRRF = formComplementoTributoProcessoJudicial.querySelector('#idMensagemCodigoIRRF');
    const divMensagemAdvogado = formComplementoTributoProcessoJudicial.querySelector('#idMensagemAdvogado');
    const divMensagemDependente = formComplementoTributoProcessoJudicial.querySelector('#idMensagemDependente');
    const divMensagemBasePensao = formComplementoTributoProcessoJudicial.querySelector('#idMensagemPensao');
    const divMensagemRetencao = formComplementoTributoProcessoJudicial.querySelector('#idMensagemRetencao');
    const divMensagemValorRetencao = formComplementoTributoProcessoJudicial.querySelector('#idMensagemValorRetencao');
    const divMensagemDeducaoSuspensa = formComplementoTributoProcessoJudicial.querySelector('#idMensagemDeducaoSuspensa');
    const divMensagemSuspensaPensao = formComplementoTributoProcessoJudicial.querySelector('#idMensagemSuspensaPensao');
    const divMensagemIRComplementar = formComplementoTributoProcessoJudicial.querySelector('#idMensagemIRComplementar');
    const menssagemPadrao = "Ao finalizar, clique no botão <strong>'Salvar'</strong>, no final da tela, para incluir/atualizar o(s) registro(s).";

    var rpcProcesso = 'pes4_processojudicial.RPC.php';

    function limpaMenssagemLancamentos() {
        divMensagemCodigoIRRF.setAttribute("hidden", "hidden");
        divMensagemCodigoIRRF.innerHTML == ''
        divMensagemAdvogado.setAttribute("hidden", "hidden");
        divMensagemAdvogado.innerHTML == ''
        divMensagemDependente.setAttribute("hidden", "hidden");
        divMensagemDependente.innerHTML == ''
        divMensagemBasePensao.setAttribute("hidden", "hidden");
        divMensagemBasePensao.innerHTML == ''
        divMensagemRetencao.setAttribute("hidden", "hidden");
        divMensagemRetencao.innerHTML == ''
        divMensagemValorRetencao.setAttribute("hidden", "hidden");
        divMensagemValorRetencao.innerHTML == ''
        divMensagemDeducaoSuspensa.setAttribute("hidden", "hidden");
        divMensagemDeducaoSuspensa.innerHTML == ''
        divMensagemSuspensaPensao.setAttribute("hidden", "hidden");
        divMensagemSuspensaPensao.innerHTML == ''
        divMensagemIRComplementar.setAttribute("hidden", "hidden");
        divMensagemIRComplementar.innerHTML == ''
        return;
    }
    //Início tabela base de cálculo dos tributos
    let tabelaBaseCalculo = jQuery('#dataBaseCalculo-tabela');

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
            title: 'Observacao',
            field: 'observacao',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Mês e Ano Pagamento',
            field: 'periodoPagamento',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Período Contemplado',
            field: 'periodoContemplado',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Base Previdenciário',
            field: 'mensalContribuicao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Base Previdenciário 13º',
            field: 'contribuicao13',
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
            width: `200px`,
            formatter: adicionaAcaoBaseCalculo
        }
    ];

    abaComplemento.bloquear();
    abaComplemento.setVisibilidade(false);

    //Lista de valores para fins previdenciários
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

    function adicionaAcaoBaseCalculo(value, row, index) {
        return [
            '<a href="#" onclick="editarBaseCalculo(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="#" onclick="excluirBaseCalculo(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarContribuicaoTributaria.addEventListener('click', incluirContribuicaoTributaria); 

    function incluirContribuicaoTributaria() {
        let valorCampoContribuicaoTributaria = new FormData(formTributoProcessoJudicial);
        let mesPagamento = valorCampoContribuicaoTributaria.get('periodoApuracaoMes');
        let anoPagamento = valorCampoContribuicaoTributaria.get('periodoApuracaoAno');
        let periodoPagamento = anoPagamento + '-' + mesPagamento;
        let periodoContemplado = valorCampoContribuicaoTributaria.get('periodoRef');
        let idUnico = parseInt(anoPagamento + mesPagamento + periodoContemplado.split('-')[0] + periodoContemplado.split('-')[1]);
        let sequencialBaseCalculo = valorCampoContribuicaoTributaria.get('sequencialBaseEditar');
        let mensalContribuicao =  parseFloat(valorCampoContribuicaoTributaria.get('mensalContribuicao'));
        let contribuicao13 = parseFloat(valorCampoContribuicaoTributaria.get('contribuicao13'));
        let FGTSContribuicao = parseFloat(valorCampoContribuicaoTributaria.get('FGTSContribuicao'));
        let FGTSContribuicao13 = parseFloat(valorCampoContribuicaoTributaria.get('FGTSContribuicao13'));
        let dataSentencaAcordo = valorCampoContribuicaoTributaria.get('dataSentencaAcordo');
        let observacao = valorCampoContribuicaoTributaria.get('observacao');

        if (isNaN(mensalContribuicao)) {
            mensalContribuicao = 0.00;
        }

        if (isNaN(contribuicao13)) {
            contribuicao13 = 0.00;
        }

        if (mesPagamento == '' ) {
            alert("Não é possível lançar mês de pagamento vazio. Favor revisar.");
            return;
        }

        if (anoPagamento == '' ) {
            alert("Não é possível lançar ano de pagamento vazio. Favor revisar.");
            return;
        }

        if (anoPagamento.length != 4 ) {
            alert("Valor do ano de pagamento não é válido. Favor revisar.");
            return;
        }

        if (parseInt(anoPagamento) < parseInt(dataSentencaAcordo.split('/')[2])) {
            alert("O ano de pagamento (" + anoPagamento + ") menor que o ano sentença/acordo (" + dataSentencaAcordo.split('/')[2] + "). Favor revisar.");
            return;
        }

        if (parseInt(anoPagamento) == parseInt(dataSentencaAcordo.split('/')[2]) &&
            parseInt(mesPagamento) < parseInt(dataSentencaAcordo.split('/')[1]))  {
                alert("Valor do mês de pagamento menor que o mês sentença/acordo. Favor revisar.");
                return;
        }

        if (mensalContribuicao < 0) {
            alert("Valor de base de cálculo da contribuição previdenciária mensal. Favor revisar.");
            return;
        };

        if (contribuicao13 < 0) {
            alert("Valor base de cálculo da contribuição previdenciária mensal de 13º salário. Favor revisar.");
            return;
        };

        if (periodoContemplado == "0") {
            alert("Ano e mês contemplado não definido. Favor revisar.");
            return;
        }

        const itemBaseCalculo = {
            id: idUnico,
            sequencialBaseCalculo: sequencialBaseCalculo,
            periodoPagamento: periodoPagamento,
            periodoContemplado: periodoContemplado,
            mensalContribuicao: mensalContribuicao,
            contribuicao13: contribuicao13,
            observacao: observacao
        };

        let verificaApuracao = tabelaBaseCalculo.bootstrapTable('getRowByUniqueId', itemBaseCalculo.id);

        if (verificaApuracao) {
            if (!confirm("Pagamento já lançada. Deseja sobrescrever?")) {
                periodoApuracaoMes.focus();
                    return;
                }
                tabelaBaseCalculo.bootstrapTable('updateByUniqueId', {
                    id: itemBaseCalculo.id,
                    row: itemBaseCalculo
                });
                limpaValoresTela()
                lancamentosTributosPagamento.value = JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData'));

                divMensagemCodigoIRRF.removeAttribute("hidden");
                divMensagemCodigoIRRF.innerHTML = menssagemPadrao;

                return;
        } else {
            sequencialBaseEditar.value = "";
            itemBaseCalculo.sequencialBaseCalculo = 0;
        }
        tabelaBaseCalculo.bootstrapTable('append', itemBaseCalculo);

        limpaValoresTela()

        lancamentosTributosPagamento.value = JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData'));

        tabelaContribuicao.style.display = 'none';
        periodoPagamentoContemplado.style.display = 'none';
        tabelaImpostoRenda.style.display = 'none';
        if (tabelaBaseCalculo.bootstrapTable('getData').length > 0) {
            renderSelectPagamentoContemplado(tabelaBaseCalculo.bootstrapTable('getData'));
            renderSelectImpostoRenda();
            renderSelectPagamentoImpostoRenda();
            tabelaContribuicao.style.display = 'block';
            periodoPagamentoContemplado.style.display = 'block';
            tabelaImpostoRenda.style.display = 'block';
        }

        periodoApuracaoMes.focus();

        divMensagemCodigoIRRF.removeAttribute("hidden");
        divMensagemCodigoIRRF.innerHTML = menssagemPadrao;


        return;
    }

    function excluirBaseCalculo(registro) {
        let dadoExcluir = tabelaBaseCalculo.bootstrapTable('getRowByUniqueId', registro);
        let dadosCodigoReceita = tabelaTributoPrevidencial.bootstrapTable('getData');
        let excluirTodosRegistro = false;
        let respondido = false;
        let excluirRegistro = confirm("Deseja excluir o registro do período de pagamento " + dadoExcluir.periodoPagamento + " e período contemplado igual a " + dadoExcluir.periodoContemplado + "?");
        let sequencialExcluido = [];
        let sequencialPrevidenciaExcluido = [];

        if (excluirRegistro == true) {
            if (dadosCodigoReceita.length > 0) {
                let dados = [];
                for (let i = 0; i < dadosCodigoReceita.length; i++) {
                    dados[i] = dadosCodigoReceita[i];
                }
                excluirTodosRegistro = confirm("Há 'Códigos da Receita' lançados relacionados ao regitros a ser excluído. Confirma a exclusão de todos?");
                if (excluirTodosRegistro == true) {
                    for (let dado of dados) {
                        if (dado.idContempladoPagamento == dadoExcluir.id) {
                            excluirTributoPrevidencial(dado.id);
                        }
                    }
                    
                }
                if (excluirTodosRegistro == false) {
                    return;
                }
            }

            tabelaBaseCalculo.bootstrapTable('removeByUniqueId', dadoExcluir.id)

            tabelaContribuicao.style.display = 'none';
            periodoPagamentoContemplado.style.display = 'none';
            tabelaImpostoRenda.style.display = 'none';
            if (tabelaBaseCalculo.bootstrapTable('getData').length > 0) {
                renderSelectPagamentoContemplado(tabelaBaseCalculo.bootstrapTable('getData'));
                tabelaContribuicao.style.display = 'block';
                periodoPagamentoContemplado.style.display = 'block';
                tabelaImpostoRenda.style.display = 'block';
                renderSelectPagamentoImpostoRenda();
            }
            lancamentosTributosPrevidencial.value = JSON.stringify(tabelaTributoPrevidencial.bootstrapTable('getData'));
            if (tabelaBaseCalculo.bootstrapTable('getData').length == 0) {
                tabelaContribuicao.style.display = 'none';
                periodoPagamentoContemplado.style.display = 'none';
                tabelaImpostoRenda.style.display = 'none'; 
                tabelaTributoPrevidencial.bootstrapTable('removeAll');
                tabelaTributoImpostoRenda.bootstrapTable('removeAll');
                lancamentosTributosPrevidencial.value = "";
            }
            lancamentosTributosPagamento.value = JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData'));
            sequecialBaseExcluido.push(dadoExcluir.sequencialBaseCalculo);
            sequencialBaseExcluir.value = sequecialBaseExcluido;
        }
        divMensagemCodigoIRRF.removeAttribute("hidden");
        divMensagemCodigoIRRF.innerHTML = menssagemPadrao;
        return;
    }

    function atualizaCalculoTributos(sequencialProcessoServidor, matricula) {
        let oParam = {};
        oParam.acao = 'preencheDadosTabelaTributos';
        oParam.sequencialProcessoServidor = sequencialProcessoServidor;
        oParam.matricula = matricula;
        js_divCarregando('Consultando os dados.', 'msgbox');
        new Ajax.Request(
            rpcProcesso,
            {
                method: 'post',
                parameters: oParam,
                onComplete: preencheTabelaTributos
            }
        );
    }

    function preencheTabelaTributos(oJson) {
        js_removeObj('msgbox');
        let oRetorno = JSON.parse(oJson.responseText);
        let chaveTributoComplementar = [];
        if (oRetorno.dados.length > 0) {
            tabelaBaseCalculo.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.dados.length; i++) {
                if (oRetorno.dados[i].base !== undefined) {
                    let c = oRetorno.dados[i].base.competencia.split('-');
                    let p = oRetorno.dados[i].base.pagamento.split('-');
                    let idUnico = parseInt(p[0] + p[1] + c[0] + c[1]);
                    itemBaseCalculo = {
                        id: idUnico,
                        sequencialBaseCalculo: oRetorno.dados[i].base.sequencial,
                        periodoPagamento: oRetorno.dados[i].base.pagamento,
                        periodoContemplado: oRetorno.dados[i].base.competencia,
                        mensalContribuicao: oRetorno.dados[i].base.valorBaseMensal,
                        contribuicao13: oRetorno.dados[i].base.valorBaseMensal13,
                        observacao: oRetorno.dados[i].base.observacao  
                    };
                    tabelaBaseCalculo.bootstrapTable('append', itemBaseCalculo);
                }
            }

            tabelaContribuicao.style.display = 'none';
            periodoPagamentoContemplado.style.display = 'none';
            tabelaImpostoRenda.style.display = 'none';
            if (tabelaBaseCalculo.bootstrapTable('getData').length > 0) {
                renderSelectPagamentoContemplado(tabelaBaseCalculo.bootstrapTable('getData'));
                renderSelectImpostoRenda();
                periodoPagamentoContemplado.style.display = 'block';
                tabelaImpostoRenda.style.display = 'block';
                tabelaContribuicao.style.display = 'block';
                tabelaTributoPrevidencial.bootstrapTable('removeAll');
                tabelaTributoImpostoRenda.bootstrapTable('removeAll');
                tabelaCodigoIRRF.bootstrapTable('removeAll');
                renderSelectPagamentoImpostoRenda();
                for (let i = 0; i < oRetorno.dados.length; i++) {
                    if (oRetorno.dados[i].contribuicao !== undefined) {
                        itemContribuicao = {
                            id: oRetorno.dados[i].contribuicao.periodos + oRetorno.dados[i].contribuicao.codigoReceita,
                            sequencialCodigoReceita:  oRetorno.dados[i].contribuicao.sequencial,
                            sequencialBaseCalculo: oRetorno.dados[i].contribuicao.sequencialTributoBase,
                            idContempladoPagamento: oRetorno.dados[i].contribuicao.periodos,
                            periodoContempladoPagamento: oRetorno.dados[i].contribuicao.periodoContempladoPagamento,
                            codigoReceita: oRetorno.dados[i].contribuicao.codigoReceita,
                            valorCodigoReceita: oRetorno.dados[i].contribuicao.valorContribuicao
                        }
                        tabelaTributoPrevidencial.bootstrapTable('append', itemContribuicao);
                    }

                    if (oRetorno.dados[i].irrf !== undefined) {
                        itemTributoImpostoRenda = {
                            id: parseInt(oRetorno.dados[i].irrf.codigoReceita + oRetorno.dados[i].irrf.contemplado.replace('-','')),
                            sequencialIRRF: parseInt(oRetorno.dados[i].irrf.sequencial),
                            periodoPagamento: oRetorno.dados[i].irrf.contemplado,
                            codigoIRRF: oRetorno.dados[i].irrf.codigoReceita,
                            valorIRRF: oRetorno.dados[i].irrf.valorIRRF
                        };
                        tabelaTributoImpostoRenda.bootstrapTable('append', itemTributoImpostoRenda);
                        
                        chaveTributoComplementar[parseInt(oRetorno.dados[i].irrf.sequencial)] = oRetorno.dados[i].irrf.contemplado + '|' + oRetorno.dados[i].irrf.codigoReceita;
                    }

                    if (oRetorno.dados[i].complementar !== undefined) {

                        if (oRetorno.dados[i].complementar.codigoReceita != '') {
                            soma = parseFloat(oRetorno.dados[i].complementar.valorRendimentoTributavel) +
                                parseFloat(oRetorno.dados[i].complementar.valorRendimentoTributavel13) +
                                parseFloat(oRetorno.dados[i].complementar.valorRendimentoMolestia) +
                                parseFloat(oRetorno.dados[i].complementar.valorIsenta65) +
                                parseFloat(oRetorno.dados[i].complementar.valorJurosMora) +
                                parseFloat(oRetorno.dados[i].complementar.valorRendimentoIsento) +
                                parseFloat(oRetorno.dados[i].complementar.valorPrevidenciaOficial) +
                                parseFloat(oRetorno.dados[i].complementar.valorDespesaCusta) +
                                parseFloat(oRetorno.dados[i].complementar.valorDespesaAdvogados);
                            if (soma > 0) {
                                let sequencialCodigo = chaveTributoComplementar.findIndex(item => {
                                    return item === oRetorno.dados[i].irrf.contemplado+ '|' + oRetorno.dados[i].complementar.codigoReceita;
                                });
                                itemCodigoIRRF = {
                                    id: oRetorno.dados[i].irrf.contemplado+ '|' + oRetorno.dados[i].complementar.codigoReceita,
                                    sequencial: sequencialCodigo,
                                    codigoRelativoIRRF: oRetorno.dados[i].irrf.contemplado+ '|' + oRetorno.dados[i].complementar.codigoReceita,
                                    valorRendimentoMensal: oRetorno.dados[i].complementar.valorRendimentoTributavel,
                                    valorRendimento13Mensal: oRetorno.dados[i].complementar.valorRendimentoTributavel13,
                                    valorMolestiaGrave: oRetorno.dados[i].complementar.valorRendimentoMolestia,
                                    valorIsenta65: oRetorno.dados[i].complementar.valorIsenta65,
                                    valorJuroMora: oRetorno.dados[i].complementar.valorJurosMora,
                                    valorNaoTributavel: oRetorno.dados[i].complementar.valorRendimentoIsento,
                                    descricaoNaoTributavel: oRetorno.dados[i].complementar.descricaoIsento,
                                    valorPrevidenciaOficial: oRetorno.dados[i].complementar.valorPrevidenciaOficial,
                                    descricaoRRA: oRetorno.dados[i].complementar.descricaoRendimentoAcumula,
                                    quantidadeRRA: oRetorno.dados[i].complementar.quantidadeMesAcumula,
                                    despCustas: oRetorno.dados[i].complementar.valorDespesaCusta,
                                    despAdvogados: oRetorno.dados[i].complementar.valorDespesaAdvogados,
                                }
                                tabelaCodigoIRRF.bootstrapTable('append', itemCodigoIRRF);
                            }
                        }
                    }
                }
            }
        }

        if (oRetorno.advogados.length > 0) {
            tabelaAdvogado.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.advogados.length; i++) {
                
                if (oRetorno.advogados[i] !== undefined) {
                    if (oRetorno.advogados[i].cnpj == "") {
                        id = oRetorno.advogados[i].cpf;
                    }
                    if (oRetorno.advogados[i].cpf == "") {
                        id = oRetorno.advogados[i].cnpj;
                    }
                    itemAdvogado = {
                        id: id,
                        sequencial: oRetorno.advogados[i].sequencial,
                        tipoInscricaoADV: oRetorno.advogados[i].tipoInscricao,
                        cnpjADV: oRetorno.advogados[i].cnpj,
                        cpfADV : oRetorno.advogados[i].cpf,
                        valorDespesaADV : oRetorno.advogados[i].valorDespesa
                    };
                    tabelaAdvogado.bootstrapTable('append', itemAdvogado);
                }
            }
        }

        if (oRetorno.dependentes.length > 0) {
            tabelaDependente.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.dependentes.length; i++) {
                if (oRetorno.dependentes[i] !== undefined) {
                    let id = parseInt(oRetorno.dependentes[i].tipoRendimentoDEP.trim() + oRetorno.dependentes[i].cpfDEP.trim())
                     itemDependente = {
                        id: id,
                        sequencial: oRetorno.dependentes[i].sequencial,
                        tipoRendimentoDEP: oRetorno.dependentes[i].tipoRendimentoDEP,
                        cpfDEP : oRetorno.dependentes[i].cpfDEP,
                        valorDEP : oRetorno.dependentes[i].valorDEP
                    };
                    tabelaDependente.bootstrapTable('append', itemDependente);
                }
            }
        }

        if (oRetorno.pensoes.length > 0) {
            tabelaPensao.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.pensoes.length; i++) {
                if (oRetorno.pensoes[i] !== undefined) {
                    let id = parseInt(oRetorno.pensoes[i].tipoRendimentoPEN.trim() + oRetorno.pensoes[i].cpfPEN.trim())
                     itemPensao = {
                        id: id,
                        sequencial: oRetorno.pensoes[i].sequencial,
                        tipoRendimentoPEN: oRetorno.pensoes[i].tipoRendimentoPEN,
                        cpfPEN : oRetorno.pensoes[i].cpfPEN,
                        valorPEN : oRetorno.pensoes[i].valorPEN
                    };
                    tabelaPensao.bootstrapTable('append', itemPensao);
                }
            }
        }

        if (oRetorno.retencoes.length > 0) {
            tabelaRetencao.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.retencoes.length; i++) {
                if (oRetorno.retencoes[i] !== undefined) {
                    let id = oRetorno.retencoes[i].numeroRetencao;
                    itemRetencao = {
                        id: id,
                        sequencial: oRetorno.retencoes[i].sequencial,
                        tipoRetencao: oRetorno.retencoes[i].tipoRetencao,
                        numeroRetencao : oRetorno.retencoes[i].numeroRetencao,
                        codigoSuspensao : oRetorno.retencoes[i].codigoSuspensao
                    };
                    tabelaRetencao.bootstrapTable('append', itemRetencao);
                }
            }
        }

        if (oRetorno.valorRetencoes.length > 0) {
            tabelaValorRetencao.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.valorRetencoes.length; i++) {
                if (oRetorno.valorRetencoes[i] !== undefined) {
                    let id = oRetorno.valorRetencoes[i].periodoApuracao + oRetorno.valorRetencoes[i].processoRetencao;
                    itemValorRetencao = {
                        id: id,
                        sequencial: oRetorno.valorRetencoes[i].sequencial,
                        processoRetencao: oRetorno.valorRetencoes[i].processoRetencao,
                        periodoApuracao: oRetorno.valorRetencoes[i].periodoApuracao,
                        valorRetencao: oRetorno.valorRetencoes[i].valorRetencao,
                        valorDeposito: oRetorno.valorRetencoes[i].valorDeposito,
                        valorAnoCalendario: oRetorno.valorRetencoes[i].valorAnoCalendario,
                        valorAnoAnterior: oRetorno.valorRetencoes[i].valorAnoAnterior,
                        valorRendimentoSuspenso: oRetorno.valorRetencoes[i].valorRendimentoSuspenso
                    };
                    tabelaValorRetencao.bootstrapTable('append', itemValorRetencao);
                }
            }
        }

        if (oRetorno.deducoes.length > 0) {
            tabelaDeducaoSuspensa.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.deducoes.length; i++) {
                if (oRetorno.deducoes[i] !== undefined) {
                    let id = oRetorno.deducoes[i].tipoDeducao + oRetorno.deducoes[i].processoDeducaoSuspensa;
                    itemDeducaoSuspensa = {
                        id: id,
                        sequencial: oRetorno.deducoes[i].sequencial,
                        processoDeducaoSuspensa: oRetorno.deducoes[i].processoDeducaoSuspensa,
                        tipoDeducao: oRetorno.deducoes[i].tipoDeducao,
                        valorDeducaoSuspensa: oRetorno.deducoes[i].valorDeducaoSuspensa
                    };
                    tabelaDeducaoSuspensa.bootstrapTable('append', itemDeducaoSuspensa);
                }
            }
        }

        if (oRetorno.deducoesSuspensa.length > 0) {
            tabelaSuspensaPensao.bootstrapTable('removeAll');
            for (let i = 0; i < oRetorno.deducoesSuspensa.length; i++) {
                if (oRetorno.deducoesSuspensa[i] !== undefined) {
                    let idUnico = oRetorno.deducoesSuspensa[i].processoDeducaoSuspensa + oRetorno.deducoesSuspensa[i].CPFSuspensaPensao;
                    itemSuspensaPensao = {
                        id: idUnico,
                        sequencial: oRetorno.deducoesSuspensa[i].sequencial,
                        processoSuspensaPensao: oRetorno.deducoesSuspensa[i].processoDeducaoSuspensa,
                        CPFSuspensaPensao: oRetorno.deducoesSuspensa[i].CPFSuspensaPensao,
                        valorSuspensaPensao: oRetorno.deducoesSuspensa[i].valorSuspensaPensao
                    };
                    tabelaSuspensaPensao.bootstrapTable('append', itemSuspensaPensao);
                }
            }
        }


        if (oRetorno.IRComplementar.length > 0) {
            tabelaIRComplementar.bootstrapTable('removeAll')
            for (let i = 0; i < oRetorno.IRComplementar.length; i++) {
                if (oRetorno.IRComplementar[i] !== undefined) {
                    let idUnico = oRetorno.IRComplementar[i].cpfDependente;
                    itemIRComplementar = {
                        id: idUnico,
                        sequencial: oRetorno.IRComplementar[i].sequencial,
                        dataLaudo: oRetorno.IRComplementar[i].dataLaudo,
                        CPFIRComplementar: oRetorno.IRComplementar[i].cpfDependente,
                        dataNascimento: oRetorno.IRComplementar[i].dataNascimento,
                        nomeDependente: oRetorno.IRComplementar[i].nome,
                        depIRRF: oRetorno.IRComplementar[i].IRRFDependenteTributavel,
                        tipoDependente: oRetorno.IRComplementar[i].tipoDependente,
                        descricaoDependencia: oRetorno.IRComplementar[i].descricaoDependencia
                    };
                    tabelaIRComplementar.bootstrapTable('append', itemIRComplementar);
                }
            }
        }
        lancamentosTributosPagamento.value = JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData'));
        lancamentosTributosPrevidencial.value = JSON.stringify(tabelaTributoPrevidencial.bootstrapTable('getData'));
        validaExibeTableIRComplementar();
        lancamentosCodigoIRRF.value = JSON.stringify(tabelaCodigoIRRF.bootstrapTable('getData'));
        validaExibeTableCodigoRelativoIRRF();
        validaExibeTableAdvogado();
        validaExibeTableDependente();
        validaExibeTablePensao();
        validaExibeTableRetencao();
        lancamentosAdvogado.value = JSON.stringify(tabelaAdvogado.bootstrapTable('getData'));
        lancamentosDependente.value = JSON.stringify(tabelaDependente.bootstrapTable('getData'));
        lancamentosPensao.value = JSON.stringify(tabelaPensao.bootstrapTable('getData'));
        lancamentosRetencao.value = JSON.stringify(tabelaRetencao.bootstrapTable('getData'));
        validaExibeTableValorRetencao();
        lancamentosValorRetencao.value = JSON.stringify(tabelaValorRetencao.bootstrapTable('getData'));
        validaExibeTableDeducaoSuspensa();
        renderSelectProcessoDeducao();
        lancamentosDeducaoSuspensa.value = JSON.stringify(tabelaDeducaoSuspensa.bootstrapTable('getData'));
        validaExibeTableSuspensaPensao();
        renderSelectSuspensaPensao();
        $lancamentosSuspensaPensao.value = JSON.stringify(tabelaSuspensaPensao.bootstrapTable('getData'));
        lancamentosIRComplementar.value = JSON.stringify(tabelaIRComplementar.bootstrapTable('getData'));
        limpaMenssagemLancamentos()
        return;
    }

    function editarBaseCalculo(id) {
        let linhaBaseCalculo = tabelaBaseCalculo.bootstrapTable('getRowByUniqueId', id);
        periodoApuracaoMes.value  = linhaBaseCalculo.periodoPagamento.split('-')[1];
        periodoApuracaoAno.value  = linhaBaseCalculo.periodoPagamento.split('-')[0];
        idPeriodoReferencia.value = linhaBaseCalculo.periodoContemplado;
        contribuicaoMensal.value = linhaBaseCalculo.mensalContribuicao;
        contribuicaoMensal13.value = linhaBaseCalculo.contribuicao13;
        observacao.value = linhaBaseCalculo.observacao;
        sequencialBaseEditar.value = linhaBaseCalculo.sequencialBaseCalculo
        return;
    }

    //Fim tabela base de cálculo dos tributos

    //Início tabela Código da receita
    let tabelaTributoPrevidencial = jQuery('#dataTributoPrevidencial-tabela');

    let colunasTributoPrevidencial =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Salvo BD',
            field: 'sequencialCodigoReceita',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Sequencial Base Calculo',
            field: 'sequencialBaseCalculo',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Identifica??o Contemplado/Pagamento',
            field: 'idContempladoPagamento',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'período Pagamento/codigoReceitaItem',
            field: 'periodoContempladoPagamento',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `150px`
        },{
            title: 'Código Receita',
            field: 'codigoReceita',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `150px`
        },{
            title: 'Valor Correspondente',
            field: 'valorCodigoReceita',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `150px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaTributoPrevidencial
        }
    ];

    //Lista de valores para fins previdenciários
    jQuery(document).ready(jQuery => {
        tabelaTributoPrevidencial.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasTributoPrevidencial,
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

    function adicionaTributoPrevidencial(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarTributoPrevidencial(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirTributoPrevidencial(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarTributoPrevidencial.addEventListener('click', incluirTributoPrevidencial); 

    function incluirTributoPrevidencial() {

        let dadoCodigoReceita = new FormData(formTributoProcessoJudicial);
        let codigoReceitaItem = dadoCodigoReceita.get('tpCR');
        let valorCodigoReceita = parseFloat(dadoCodigoReceita.get('vrCR'));
        let idContempladoPagamento = parseFloat(dadoCodigoReceita.get('periodoPagamentoContemplado'));
        let idUnico = idContempladoPagamento + codigoReceitaItem;
        let sequencialCodigoReceita = 0;
        let codigoContempladoPagamento = periodoPagamentoContemplado.options[periodoPagamentoContemplado.selectedIndex].value.split('-')[0];
        let sequencialBaseCalculo = periodoPagamentoContemplado.options[periodoPagamentoContemplado.selectedIndex].value.split('-')[1];
        let periodoContempladoPagamento = periodoPagamentoContemplado.options[periodoPagamentoContemplado.selectedIndex].text;
        
        if (valorCodigoReceita <= 0) {
            alert("Valor correspondente tem que ser maior que zero. Favor revisar.");
            return;
        };

        const itemTributoPrevidencial = {
            id: idUnico,
            sequencialCodigoReceita: sequencialCodigoReceita,
            sequencialBaseCalculo: sequencialBaseCalculo,
            idContempladoPagamento: codigoContempladoPagamento,
            periodoContempladoPagamento: periodoContempladoPagamento,
            codigoReceita: codigoReceitaItem,
            valorCodigoReceita: valorCodigoReceita
        };

        let verificaTributoPrevidencial = tabelaTributoPrevidencial.bootstrapTable('getRowByUniqueId', itemTributoPrevidencial.id);

        if (verificaTributoPrevidencial) {
            if (!confirm("Código receita já lançado. Deseja sobrescrever?")) {
                periodoPagamentoContemplado.focus();
                return;
            }
            tabelaTributoPrevidencial.bootstrapTable('updateByUniqueId', {
                id: itemTributoPrevidencial.id,
                row: itemTributoPrevidencial
            });
            periodoPagamentoContemplado.value = "0";
            codigoReceita.value = "0";
            valorCR.value = 0.00;
            lancamentosTributosPrevidencial.value = JSON.stringify(tabelaTributoPrevidencial.bootstrapTable('getData'));
            return;
        }
        tabelaTributoPrevidencial.bootstrapTable('append', itemTributoPrevidencial);

        periodoPagamentoContemplado.value = "0";
        codigoReceita.value = "0";
        valorCR.value = 0.00;
        lancamentosTributosPrevidencial.value = JSON.stringify(tabelaTributoPrevidencial.bootstrapTable('getData'));
        return;
    }

    function renderSelectTabela29PagamentoContemplado() {
        let opcoesTabela29 = `
               <option value="0">Selecione...</option>
           `;
        tabela29.sort(ordenaDescricaoCodigoReceita);
        tabela29.forEach(item => {
            opcoesTabela29 += `
               <option value="${item.substring(0,6).trim()}">${item.trim()}</option>
           `;
       
        });
        codigoReceita.innerHTML = `<select  >` + opcoesTabela29 + `</select>`;
        codigoReceita.style.display = 'block';
        codigoReceitaLabel.style.display = 'block';
        return;
    }

    function ordenaDescricaoCodigoReceita(a, b) {
        a = a.toLowerCase().substr(8);
        b = b.toLowerCase().substr(8);
        if (a > b) {
            return 1;
        } else if (a  < b) {
            return -1;
        } else if (a === b) {
            return 0;
        }
    }

    function renderSelectPagamentoContemplado(registro) {
        let opcoes = '';
        registro.sort();
        opcoes += `
               <option value="0">Selecione...</option>
           `;
        registro.forEach(item => {
            opcoes += `
               <option value="${item.id}-${item.sequencialBaseCalculo}">${item.periodoPagamento}/${item.periodoContemplado}</option>
           `;

        });

        periodoPagamentoContemplado.innerHTML = `<select >` + opcoes + `</select>`;
        periodoPagamentoContemplado.style.display = 'block';
    }

    function editarTributoPrevidencial(id) {
        let linhaPrevidencial = tabelaTributoPrevidencial.bootstrapTable('getRowByUniqueId', id);
        periodoPagamentoContemplado.value = linhaPrevidencial.idContempladoPagamento + "-" + linhaPrevidencial.sequencialBaseCalculo;
        codigoReceita.value = linhaPrevidencial.codigoReceita;
        valorCR.value =  linhaPrevidencial.valorCodigoReceita;
        return;
    }


    function excluirTributoPrevidencial(registro) {
        let linhaPrevidencia= tabelaTributoPrevidencial.bootstrapTable('getRowByUniqueId', registro);
        sequencialPrevidenciaExcluido.push(linhaPrevidencia.sequencialCodigoReceita);
        sequencialPrevidenciaExcluir.value = sequencialPrevidenciaExcluido;
        tabelaTributoPrevidencial.bootstrapTable('removeByUniqueId', registro);
        lancamentosTributosPrevidencial.value = JSON.stringify(tabelaTributoPrevidencial.bootstrapTable('getData'));
        return;
    }

    //Fim tabela Código da receita

    //Início tabela Código da receita imposto de renda
    let tabelaTributoImpostoRenda = jQuery('#dataImpostoRenda-tabela');

    let colunasTributoImpostoRenda =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            field: 'codigoIRRF',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'período',
            field: 'periodoPagamento',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Código Receita',
            field: 'codigoIRRF',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'Valor Correspondente',
            field: 'valorIRRF',
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
            formatter: adicionaTributoImpostoRenda
        }
    ];

    //Lista de valores para fins previdenciários
    jQuery(document).ready(jQuery => {
        tabelaTributoImpostoRenda.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasTributoImpostoRenda,
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

    function adicionaTributoImpostoRenda(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarImpostoRenda(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirImpostoRenda(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }


    lancarTributoImpostoRenda.addEventListener('click', incluirTributoImpostoRenda); 

    function incluirTributoImpostoRenda() {
        let dadoIRRF = new FormData(formTributoProcessoJudicial);
        let mesPagamento = dadoIRRF.get('periodoApuracaoMes');
        let anoPagamento = dadoIRRF.get('periodoApuracaoAno');
        let periodoPagamento = dadoIRRF.get('perApurPgtoImpostoRenda');
        let codigoReceita = dadoIRRF.get('tpCRImpostoRenda');
        let valorImpostoRenda = parseFloat(dadoIRRF.get('vrCRImpostoRenda'));
        let idUnico = parseInt(codigoReceita + periodoPagamento.replace('-',''));
        let sequencialIRRF = 0;

        if (valorImpostoRenda <= 0) {
            alert("Valor correspondente tem que ser maior que zero. Favor revisar.");
            return;
        };

        if (parseInt(codigoReceita) <= 0) {
            alert("Selecione um Código de receita válido. Favor revisar.");
            return;
        };

        const itemTributoImpostoRenda = {
            id: idUnico,
            sequencialIRRF: sequencialIRRF,
            periodoPagamento: periodoPagamento,
            codigoIRRF: codigoReceita,
            valorIRRF: valorImpostoRenda,
            contempladoIRRF : periodoPagamento
        };

        let verificaTributoImpostoRenda = tabelaTributoImpostoRenda.bootstrapTable('getRowByUniqueId', itemTributoImpostoRenda.id);

        if (verificaTributoImpostoRenda) {
            if (!confirm("Código receita já lançado. Deseja sobrescrever?")) {
                periodoPagamentoContemplado.focus();
                return;
            }
            tabelaTributoImpostoRenda.bootstrapTable('updateByUniqueId', {
                id: itemTributoImpostoRenda.id,
                row: itemTributoImpostoRenda
            });

            valorIRRF.value = 0.00;
            codigoReceitaIRRF.value = "0";
            pgtoImpostoRenda.value = "0";
            periodoPagamento.value = "0";
            lancamentosTributosIRRF.value = JSON.stringify(tabelaTributoImpostoRenda.bootstrapTable('getData'));
            validaExibeTableCodigoRelativoIRRF();
            return;
        }
        tabelaTributoImpostoRenda.bootstrapTable('append', itemTributoImpostoRenda);

        valorIRRF.value = 0.00;
        codigoReceitaIRRF.value = "0";
        pgtoImpostoRenda.value = "0";
        periodoPagamento.value = "0";
        lancamentosTributosIRRF.value = JSON.stringify(tabelaTributoImpostoRenda.bootstrapTable('getData'));

        validaExibeTableCodigoRelativoIRRF();

        divMensagemCodigoIRRF.removeAttribute("hidden");
        divMensagemCodigoIRRF.innerHTML = menssagemPadrao;

        return;
    }

    function renderSelectImpostoRenda() {
        let opcoes = '';
        codigoReceitaImpostoRendaRetido.sort();
        opcoes += `
               <option value="0">Selecione...</option>
           `;
           codigoReceitaImpostoRendaRetido.forEach(item => {
            opcoes += `
               <option value="${item.trim().substr(0,6)}">${item}</option>
           `;
       
        });

        codigoReceitaIRRF.innerHTML = `<select >` + opcoes + `</select>`;
        codigoReceitaIRRF.style.display = 'block';
    }

    function renderSelectPagamentoImpostoRenda() {
        let opcoes = '';

        opcoes += `
               <option value="0">Selecione...</option>
           `;
        tabelaBaseCalculo.bootstrapTable('getData').forEach(item => {
            opcoes += `
               <option value="${item.periodoPagamento}">${item.periodoPagamento}</option>
           `;
       
        });

        pgtoImpostoRenda.innerHTML = `<select >` + opcoes + `</select>`;
        pgtoImpostoRenda.style.display = 'block';
    }

    function excluirImpostoRenda(registro) {
        let linhaIRRF = tabelaTributoImpostoRenda.bootstrapTable('getRowByUniqueId', registro);
        chaveCodigoIRRF = linhaIRRF.periodoPagamento + '|' +linhaIRRF.codigoIRRF;
        let codigoRelativoIRRF = tabelaCodigoIRRF.bootstrapTable('getRowByUniqueId', chaveCodigoIRRF);
        if (Boolean(codigoRelativoIRRF)) {
            let competencia = chaveCodigoIRRF.substr(5,2) + '/' + chaveCodigoIRRF.substr(0,4);
            let codigo = chaveCodigoIRRF.substr(8);
            let excluirRegistro = confirm("Deseja excluir informações complementares do 'Código de Receita' número " + codigo + " competência " + competencia + "?");
            if (!Boolean(excluirRegistro)) {
                return;
            }
            excluindoCodigoIRRF(chaveCodigoIRRF);
            divMensagemCodigoIRRF.removeAttribute("hidden");
            divMensagemCodigoIRRF.innerHTML = menssagemPadrao;

        }
        sequencialExcluidoIRRF.push(linhaIRRF.sequencialIRRF);
        sequencialExcluirIRRF.value = sequencialExcluidoIRRF;
        tabelaTributoImpostoRenda.bootstrapTable('removeByUniqueId', registro)
        lancamentosTributosIRRF.value = JSON.stringify(tabelaTributoImpostoRenda.bootstrapTable('getData'));

        validaExibeTableCodigoRelativoIRRF();

        return;
    }

    function editarImpostoRenda(id) {
        let linhaIRRF = tabelaTributoImpostoRenda.bootstrapTable('getRowByUniqueId', id);
        valorIRRF.value = parseFloat(linhaIRRF.valorIRRF);
        codigoReceitaIRRF.value = linhaIRRF.codigoIRRF;
        pgtoImpostoRenda.value = linhaIRRF.periodoPagamento;
        return;
    }
    //Fim tabela Código da receita imposto de renda

    //Matrícula
    let lookupMatricula = new DBLookUp(
        $('ancoraMatricula'),
        $('codigoMatricula'),
        $('nomeServidor'),
        {
        'sArquivo': 'func_rhpessoal.php',
        'sLabel': 'Pesquisar Matr?cula'
        }
    );

    const buscarProcessos = function() {
        let parametros = new FormData(formTributoProcessoJudicial);
        parametros.append('acao', 'buscarProcessosMatricula');
        parametros.append('json', JSON.stringify(parametros));

        idPeriodoReferencia.style.display = 'none';
        idLabelPeriodo.style.display = 'none';
        idTabelaPeriodo.style.display = 'none';
        tabelaContribuicao.style.display = 'none';

        HttpClient.post(rpcProcesso, {body: parametros}).then(response => {
            divMensagemProcesso.removeAttribute("hidden");
            divMensagemProcesso.setAttribute("class", "alert alert-success");
            if (response.erro) {
                divMensagemProcesso.setAttribute("class", "alert alert-danger");
            } else {
                if (response.dados.length == 0) {
                    response.mensagem = "Nenhum processo encontrado.";
                    let idSelectProcesso = document.querySelector('#idSelectProcesso');
                    let idLabelProcesso = document.querySelector('#idLabelProcesso');
                    idSelectProcesso.style.display = 'none';
                    idLabelProcesso.style.display = 'none';
                }
                if (response.dados.length > 0) {
                    renderSelectProcesso(response.dados);
                }
            }
            divMensagemProcesso.innerHTML = response.mensagem.trim().replace(/\\n/gi, '\n').replace(/\n/gi, '<br>');
            if (divMensagemProcesso.innerHTML == '') {
                divMensagemProcesso.setAttribute("hidden", "hidden");
            }

        });
    }
    lookupMatricula.setCallBack('onChange', buscarProcessos);
    lookupMatricula.setCallBack('onClick', buscarProcessos);


    function defineFiltro() {
        idSelectProcesso.style.display = 'none';
        idLabelProcesso.style.display = 'none';
        idPeriodoReferencia.style.display = 'none';
        idLabelPeriodo.style.display = 'none';
        idTabelaPeriodo.style.display = 'none';
        tabelaContribuicao.style.display = 'none';
        idMatriculaLinha.style.display = 'none';
        idProcessoLinha.style.display = 'none';
        codigoMatricula.value = '';
        idNumeroProcesso.value = '';
        nomeServidor.value = '';
        if (idFiltro.value === 'processo') {
            idProcessoLinha.style.display = 'block';
        }
        if (idFiltro.value === 'matricula') {
            idMatriculaLinha.style.display = 'block';
        }
    }

    function mensagemBox(mensagem, tipo) {
        divMensagemProcesso.removeAttribute("hidden");
        divMensagemProcesso.setAttribute("class", "alert alert-success");
        if (tipo == 1) {
            divMensagemProcesso.setAttribute("class", "alert alert-danger");
        }
        divMensagemProcesso.innerHTML = mensagem.trim().replace(/\\n/gi, '\n').replace(/\n/gi, '<br>');
        if (divMensagemProcesso.innerHTML == "") {
            divMensagemProcesso.setAttribute("hidden", "hidden");
            return;
        }
        idPeriodoReferencia.style.display = 'none';
        return;
    }

    function renderSelectProcesso(registro) {
        let opcoesProcesso = '';
        registro.sort();

        opcoesProcesso += `
               <option value="0">Selecione o processo..</option>
           `;
        registro.forEach(item => {
            opcoesProcesso += `
               <option value="${item.sequencialProcesso}">${item.numeroProcesso}</option>
           `;
       
        });

        idSelectProcesso.innerHTML = `<select >` + opcoesProcesso + `</select>`;
        idSelectProcesso.style.display = 'block';
        idLabelProcesso.style.display = 'block';
    }

    function lancamentoProcessos() {
        let valorSelecao = idSelectProcesso.options[idSelectProcesso.selectedIndex].value;
        let valorTexto = idSelectProcesso.options[idSelectProcesso.selectedIndex].text;

        let matricula = $('codigoMatricula').value;
        idPeriodoReferencia.style.display = 'none';
        idLabelPeriodo.style.display = 'none';
        idTabelaPeriodo.style.display = 'none';
        tabelaContribuicao.style.display = 'none';
        tabelaImpostoRenda.style.display = 'none';

        tabelaBaseCalculo.bootstrapTable('removeAll');
        tabelaTributoPrevidencial.bootstrapTable('removeAll');
        tabelaTributoImpostoRenda.bootstrapTable('removeAll');

        numeroProcessoDefinido.value = "";
        dataSentencaAcordo.value = "";
        if (valorTexto.indexOf("-") > 0) {
           numeroProcessoDefinido.value = valorTexto.split('-')[0].trim();
           idNumeroProcesso.value = numeroProcessoDefinido.value;
           dataSentencaAcordo.value = (valorTexto.split('-')[1].trim()).split(':')[1].trim();
        }

        if (parseInt(valorSelecao) > 0) {
            let oParam = {};
            oParam.acao = 'buscarDadosProcesso';
            oParam.sequencialProcesso = valorSelecao;
            oParam.matricula = matricula;
            js_divCarregando('Consultando os dados.', 'msgbox');
            new Ajax.Request(
                rpcProcesso,
                {
                    method: 'post',
                    parameters: oParam,
                    onComplete: preenchimentoProcesso
                }
            );

            limpaValoresTela();
        }
        navegacaoTributoProcesso.style.display = 'block';
        sequencialBaseExcluir.value = [];
    }

    function preenchimentoProcesso(parametro) {
        js_removeObj('msgbox');
        let retorno = JSON.parse(parametro.responseText);
        divMensagemProcesso.innerHTML == '';
        divMensagemProcesso.setAttribute("hidden", "hidden");
        if (retorno.dados.lancamentos.length === 0) {
            mensagemBox("Nenhum período lançado no evento S-2500 para o processo definido. Favor revisar.", 1)
            return;
        }
        idTabelaPeriodo.style.display = 'block';
        atualizaCalculoTributos(retorno.dados.sequencialProcessoServidor, retorno.dados.matricula);
        renderSelectPeriodoReferencia(retorno.dados.lancamentos);
        renderSelectTabela29PagamentoContemplado();
        return;
    }

    function renderSelectPeriodoReferencia(lancamentos) {
        let opcoesPeriodo = '';

        lancamentos.sort();
        opcoesPeriodo += `
               <option value="0">Selecione ano e mês comtemplado...</option>
           `;
        lancamentos.forEach(item => {
            opcoesPeriodo += `
               <option value="${item}">${item}</option>
           `;
       
        });

        idPeriodoReferencia.innerHTML = `<select>` + opcoesPeriodo + `</select>`;
        idPeriodoReferencia.style.display = 'block';
        idLabelPeriodo.style.display = 'block';
       
    }

    function validaPeriodo() {
        let dataAtual = new Date();
        let mesAtual = String(dataAtual.getMonth() + 1).padStart(2, '0');
        let anoAtual = dataAtual.getFullYear();
        if (idPeriodoReferencia.length === 0) {
            mensagemBox("Nenhum período lançado no evento S-2500 para o processo definido. Favor revisar.", 1)
            return;
        }
        if (idPeriodoReferencia.value == '0') {
            mensagemBox("");
            return;
        }
        let anoMes = idPeriodoReferencia.value.split("-");
        if (parseInt(anoMes[0]) > anoAtual) {
            mensagemBox("Ano definido(" + anoMes[0] + ") maior que o ano atual(" + anoAtual + "). Favor revisar.", 1);
            idPeriodoReferencia.style.display = 'block';
            idLabelPeriodo.style.display = 'block';
            return;
        }
        if (parseInt(anoMes[0]) == anoAtual) {
            if (parseInt(anoMes[1]) > mesAtual) {
                mensagemBox("Mês definido maior que o mês atual. Favor revisar.", 1)
                return;
            }

        }
        idPeriodoReferencia.style.display = 'block';
        idLabelPeriodo.style.display = 'block';
    }

    function limpaTela() {
        idMatriculaLinha.style.display = 'none';
        idMatriculaLinha.style.display = 'none';
        idSelectProcesso.style.display = 'none';
        idLabelProcesso.style.display = 'none';
        idPeriodoReferencia.style.display = 'none';
        idLabelPeriodo.style.display = 'none';
        idTabelaPeriodo.style.display = 'none';
        tabelaContribuicao.style.display = 'none';
        return;
    }

    function limpaValoresTela() {
        periodoApuracaoMes.value = "";
        periodoApuracaoAno.value = "";
        observacao.value  = "";
        idPeriodoReferencia.value = "0";
        contribuicaoMensal.value = 0.00;
        contribuicaoMensal13.value = 0.00;
        lancamentosTributosPagamento.value = "";
        valorCR.value = 0.00;
        return;
    }

    const salvaTributos = () => {
        let parametros = new FormData(formTributoProcessoJudicial);
        let dadosComplementares = new FormData(formComplementoTributoProcessoJudicial);
        if (parametros.has('lancamentosTributosPagamento')) {
            parametros.delete('lancamentosTributosPagamento')
        }
        parametros.append('lancamentosTributosPagamento', JSON.stringify(tabelaBaseCalculo.bootstrapTable('getData')));

        if (parametros.has('lancamentosTributosPrevidencial')) {
            parametros.delete('lancamentosTributosPrevidencial')
        }
        parametros.append('lancamentosTributosPrevidencial', JSON.stringify(tabelaTributoPrevidencial.bootstrapTable('getData')));

        if (parametros.has('lancamentosTributosIRRF')) {
            parametros.delete('lancamentosTributosIRRF')
        }
        parametros.append('lancamentosTributosIRRF', JSON.stringify(tabelaTributoImpostoRenda.bootstrapTable('getData')));

        if (parametros.has('lancamentosCodigoIRRF')) {
            parametros.delete('lancamentosCodigoIRRF')
        }
        parametros.append('lancamentosCodigoIRRF', JSON.stringify(tabelaCodigoIRRF.bootstrapTable('getData')));

        if (parametros.has('lancamentosAdvogado')) {
            parametros.delete('lancamentosAdvogado')
        }
        parametros.append('lancamentosAdvogado', JSON.stringify(tabelaAdvogado.bootstrapTable('getData')));

        if (parametros.has('lancamentosDependente')) {
            parametros.delete('lancamentosDependente')
        }
        parametros.append('lancamentosDependente', JSON.stringify(tabelaDependente.bootstrapTable('getData')));

        if (parametros.has('lancamentosPensao')) {
            parametros.delete('lancamentosPensao')
        }
        parametros.append('lancamentosPensao', JSON.stringify(tabelaPensao.bootstrapTable('getData')));

        if (parametros.has('lancamentosRetencao')) {
            parametros.delete('lancamentosRetencao')
        }
        parametros.append('lancamentosRetencao', JSON.stringify(tabelaRetencao.bootstrapTable('getData')));

        if (parametros.has('lancamentosValorRetencao')) {
            parametros.delete('lancamentosValorRetencao')
        }
        parametros.append('lancamentosValorRetencao', JSON.stringify(tabelaValorRetencao.bootstrapTable('getData')));

        if (parametros.has('lancamentosDeducaoSuspensa')) {
            parametros.delete('lancamentosDeducaoSuspensa')
        }
        parametros.append('lancamentosDeducaoSuspensa', JSON.stringify(tabelaDeducaoSuspensa.bootstrapTable('getData')));

        if (parametros.has('lancamentosSuspensaPensao')) {
            parametros.delete('lancamentosSuspensaPensao')
        }
        parametros.append('lancamentosSuspensaPensao', JSON.stringify(tabelaSuspensaPensao.bootstrapTable('getData')));

        if (parametros.has('lancamentosIRComplementar')) {
            parametros.delete('lancamentosIRComplementar')
        }

        parametros.append('lancamentosIRComplementar', JSON.stringify(tabelaIRComplementar.bootstrapTable('getData')));
        
        parametros.append('sequencialAdvogadoExcluir',dadosComplementares.get('sequencialAdvogadoExcluir'));
        parametros.append('sequencialDependenteExcluir',dadosComplementares.get('sequencialDependenteExcluir'));
        parametros.append('sequencialPensaoExcluir',dadosComplementares.get('sequencialPensaoExcluir'));
        parametros.append('sequencialRetencaoExcluir',dadosComplementares.get('sequencialRetencaoExcluir'));
        parametros.append('sequencialValorRetencaoExcluir',dadosComplementares.get('sequencialValorRetencaoExcluir'));
        parametros.append('sequencialValorDeducaoSuspensaExcluir',dadosComplementares.get('sequencialValorDeducaoSuspensaExcluir'));
        parametros.append('sequencialValorSuspensaPensaoExcluir',dadosComplementares.get('sequencialValorSuspensaPensaoExcluir'));
        parametros.append('sequencialIRComplementarExcluir',dadosComplementares.get('sequencialIRComplementarExcluir'));

        parametros.append('acao', 'salvarTributos');
        parametros.append('json', JSON.stringify(parametros));

        HttpClient.post(rpcProcesso, {body: parametros}).then(response => {
            divMensagemProcesso.removeAttribute("hidden");
            divMensagemProcesso.setAttribute("class", "alert alert-success");
            if (response.erro) {
                divMensagemProcesso.setAttribute("class", "alert alert-danger");
                alert(response.mensagem.urlDecode().replace(/\\n/g, '\n'));
                limpaMenssagemLancamentos();
            } else {
                atualizaCalculoTributos(response.sequencialProcessoServidor, response.matricula)
                limpaMenssagemLancamentos();
            }
            divMensagemProcesso.innerHTML = response.mensagem.urlDecode().replace(/\\n/g, '\n');
            if (divMensagemProcesso.innerHTML == '') {
                divMensagemProcesso.setAttribute("hidden", "hidden");
            }

            alert(response.mensagem.urlDecode().replace(/\\n/g, '\n'));
        });

    }

    //Início tabela informAções complementares IRRF
    let tabelaCodigoIRRF = jQuery('#dataCodigoIRRF-tabela');

    let colunasCodigoIRRF =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Código',
            field: 'codigoRelativoIRRF',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'Rendimento Mensal',
            field: 'valorRendimentoMensal',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'Rendimento 13º Mensal',
            field: 'valorRendimento13Mensal',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr Moléstia',
            field: 'valorMolestiaGrave',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr Inseta 65',
            field: 'valorIsenta65',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr Juros Mora',
            field: 'valorJuroMora',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr não tributável',
            field: 'valorNaoTributavel',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Desc. não tributável',
            field: 'descricaoNaoTributavel',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr previdência',
            field: 'valorPrevidenciaOficial',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Desc. RRA',
            field: 'descricaoRRA',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Qtde RRA',
            field: 'quantidadeRRA',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Custas',
            field: 'despCustas',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Desp Advogados',
            field: 'despAdvogados',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `150px`,
            formatter: adicionaCodigoIRRF
        }
    ];

    //Lista de valores para fins previdenciários
    jQuery(document).ready(jQuery => {
        tabelaCodigoIRRF.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasCodigoIRRF,
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

    function adicionaCodigoIRRF(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarCodigoIRRF(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirCodigoIRRF(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarCodigoIRRF.addEventListener('click', incluirCodigoIRRF); 

    function incluirCodigoIRRF() {
        let dadoCodigoRRF = new FormData(formComplementoTributoProcessoJudicial);
        let codigoRelativoIRRF = dadoCodigoRRF.get('codigoRelativoIRRF');
        let valorRendimentoMensal = dadoCodigoRRF.get('valorRendimentoMensal');
        let valorRendimento13Mensal = dadoCodigoRRF.get('valorRendimento13Mensal');
        let valorMolestiaGrave = dadoCodigoRRF.get('valorMolestiaGrave');
        let valorIsenta65 = dadoCodigoRRF.get('valorIsenta65');
        let valorJuroMora = dadoCodigoRRF.get('valorJuroMora');
        let valorNaoTributavel = dadoCodigoRRF.get('valorNaoTributavel');
        let descricaoNaoTributavel = dadoCodigoRRF.get('descricaoNaoTributavel');
        let valorPrevidenciaOficial = dadoCodigoRRF.get('valorPrevidenciaOficial');
        let descricaoRRA = dadoCodigoRRF.get('descricaoRRA');
        let quantidadeRRA = dadoCodigoRRF.get('quantidadeRRA');
        let despCustas = dadoCodigoRRF.get('despCustas');
        let despAdvogados = dadoCodigoRRF.get('despAdvogados');

        let sequencialIRRF = 0

        if (parseInt(codigoRelativoIRRF) == 0 | !Boolean(codigoRelativoIRRF)) {
            alert("'Código de Receita - CR relativo a Imposto de Renda Retido na Fonte' " +
                "não defindo. Favor revisar.");
            campoCodigoRelativoIRRF.focus()
            return;
        };

        if (parseFloat(valorRendimentoMensal) < 0) {
            alert("'Valor do rendimento tributável mensal do Imposto de Renda' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorRendimentoMensal.focus();
            return;
        };
        if (parseFloat(valorRendimento13Mensal) < 0) {
            alert("'Valor do rendimento tributável do Imposto de Renda referente ao 13º salário' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorRendimento13Mensal.focus();
            return;
        };
        if (parseFloat(valorMolestiaGrave) < 0) {
            alert("'Valor do rendimento isento por ser portador de molástia grave atestada por laudo médico' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorMolestiaGrave.focus();
            return;
        };
        if (parseFloat(valorIsenta65) < 0) {
            alert("'Valor de parcela isenta de aposentadoria para beneficiário de 65 anos ou mais' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorIsenta65.focus();
            return;
        };
        if (parseFloat(valorJuroMora) < 0) {
            alert("'Juros de mora recebidos, devidos pelo atraso no pagamento de " +
                "remuneração por exercício de emprego, cargo ou função' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorJuroMora.focus();
            return;
        };
        if (parseFloat(valorNaoTributavel) < 0) {
            alert("'Valor de outros rendimentos isentos ou não tributáveis' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorNaoTributavel.focus();
            return;
        };
        if (parseFloat(valorPrevidenciaOficial) < 0) {
            alert("'Valor referente a previdência oficial' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorPrevidenciaOficial.focus();
            return;
        };
        if (parseFloat(valorPrevidenciaOficial) < 0) {
            alert("'Valor referente a previdência oficial' " +
                "tem que ser maior que zero. Favor revisar.");
            campoValorPrevidenciaOficial.focus();
            return;
        };
        if (parseFloat(valorNaoTributavel) > 0) {
            if (descricaoNaoTributavel.toString().trim() == ''){
                alert("'Descrição do rendimento isento ou não tributável informado' " +
                    "tem que informada, porque 'Valor de outros rendimentos isentos ou não tributáveis' " +
                    "declarado. Favor revisar.");
                    campoDescricaoNaoTributavel.focus();
                return;
            }
        }
        
        const itemCodigoIRRF = {
            id: codigoRelativoIRRF,
            sequencial: sequencialIRRF,
            codigoRelativoIRRF: codigoRelativoIRRF,
            valorRendimentoMensal: valorRendimentoMensal,
            valorRendimento13Mensal: valorRendimento13Mensal,
            valorMolestiaGrave: valorMolestiaGrave,
            valorIsenta65: valorIsenta65,
            valorJuroMora: valorJuroMora,
            valorNaoTributavel: valorNaoTributavel,
            descricaoNaoTributavel: descricaoNaoTributavel,
            valorPrevidenciaOficial: valorPrevidenciaOficial,
            descricaoRRA: descricaoRRA,
            quantidadeRRA: quantidadeRRA,
            despCustas: despCustas,
            despAdvogados: despAdvogados,
        };

        let verificaCodigoIRRF = tabelaCodigoIRRF.bootstrapTable('getRowByUniqueId', itemCodigoIRRF.id);

        if (verificaCodigoIRRF) {
            if (!confirm("Código receita já lançado. Deseja sobrescrever?")) {
                periodoPagamentoContemplado.focus();
                return;
            }
            tabelaCodigoIRRF.bootstrapTable('updateByUniqueId', {
                id: itemCodigoIRRF.id,
                row: itemCodigoIRRF
            });

            limpaCamposCodigoIRRF();
            lancamentosCodigoIRRF.value = JSON.stringify(tabelaCodigoIRRF.bootstrapTable('getData'));
            validaExibeTableAdvogado();
            validaExibeTableDependente();
            validaExibeTablePensao();
            validaExibeTableRetencao();
            divMensagemCodigoIRRF.removeAttribute("hidden");
            divMensagemCodigoIRRF.innerHTML = menssagemPadrao;
            return;
        }
        tabelaCodigoIRRF.bootstrapTable('append', itemCodigoIRRF);

        limpaCamposCodigoIRRF();
        lancamentosCodigoIRRF.value = JSON.stringify(tabelaCodigoIRRF.bootstrapTable('getData'));
        validaExibeTableAdvogado();
        validaExibeTableDependente();
        validaExibeTablePensao();
        validaExibeTableRetencao();

        divMensagemCodigoIRRF.removeAttribute("hidden");
        divMensagemCodigoIRRF.innerHTML = menssagemPadrao;

        return;
    }

    function renderSelectCodigoIRRF() {
        let opcoes = '';
        let codigoPeriodo = '';
        opcoes += `
            <option value="">Selecione...</option>
        `;
        tabelaTributoImpostoRenda.bootstrapTable('getData').forEach(item => {
            codigoPeriodo = item.periodoPagamento + '|' + item.codigoIRRF;
            opcoes += `
            <option value="${codigoPeriodo}">${codigoPeriodo}</option>
        `;
        });

        campoCodigoRelativoIRRF.innerHTML = `<select >` + opcoes + `</select>`;
        campoCodigoRelativoIRRF.style.display = 'block';
    }

    function excluindoCodigoIRRF(registro) {
        let linhaIRRF= tabelaCodigoIRRF.bootstrapTable('getRowByUniqueId', registro);
        sequencialCodigoIRRFExcluido.push(linhaIRRF.sequencial);
        sequencialCodigoIRRFExcluir.value = sequencialCodigoIRRFExcluido;
        tabelaCodigoIRRF.bootstrapTable('removeByUniqueId', registro)
        lancamentosCodigoIRRF.value = JSON.stringify(tabelaCodigoIRRF.bootstrapTable('getData'));
        validaExibeTableAdvogado();
        validaExibeTableDependente();
        validaExibeTablePensao();
        validaExibeTableRetencao();
        divMensagemCodigoIRRF.removeAttribute("hidden");
        divMensagemCodigoIRRF.innerHTML = menssagemPadrao;
        return;
    }

    function excluirCodigoIRRF(registro) {
        let linhaIRRF= tabelaCodigoIRRF.bootstrapTable('getRowByUniqueId', registro);
        sequencialCodigoIRRFExcluido.push(linhaIRRF.sequencial);
        sequencialCodigoIRRFExcluir.value = sequencialCodigoIRRFExcluido;
        tabelaCodigoIRRF.bootstrapTable('removeByUniqueId', registro)
        lancamentosCodigoIRRF.value = JSON.stringify(tabelaCodigoIRRF.bootstrapTable('getData'));
        validaExibeTableAdvogado();
        validaExibeTableDependente();
        validaExibeTablePensao();
        validaExibeTableRetencao();
        divMensagemCodigoIRRF.removeAttribute("hidden");
        divMensagemCodigoIRRF.innerHTML = menssagemPadrao;
        return;
    }

    function editarCodigoIRRF(id) {
        let linhaIRRF = tabelaCodigoIRRF.bootstrapTable('getRowByUniqueId', id);

        campoCodigoRelativoIRRF.value = linhaIRRF.codigoRelativoIRRF;
        campoValorRendimentoMensal.value = parseFloat(linhaIRRF.valorRendimentoMensal);
        campoValorRendimento13Mensal.value = parseFloat(linhaIRRF.valorRendimento13Mensal);
        campoValorMolestiaGrave.value = parseFloat(linhaIRRF.valorMolestiaGrave);
        campoValorIsenta65.value = parseFloat(linhaIRRF.valorIsenta65);
        campoValorJuroMora.value = parseFloat(linhaIRRF.valorJuroMora);
        campoValorNaoTributavel.value = parseFloat(linhaIRRF.valorNaoTributavel);
        campoDescricaoNaoTributavel.value = linhaIRRF.descricaoNaoTributavel;
        campoValorPrevidenciaOficial.value = parseFloat(linhaIRRF.valorPrevidenciaOficial);
        campoDescricaoRRA.value = linhaIRRF.descricaoRRA;
        campoQuantidadeRRA.value = linhaIRRF.quantidadeRRA;
        campoDespCustas.value = parseFloat(linhaIRRF.despCustas);
        campoDespAdvogados.value = parseFloat(linhaIRRF.despAdvogados);
        divMensagemCodigoIRRF.removeAttribute("hidden");
        divMensagemCodigoIRRF.innerHTML = menssagemPadrao;
        return;
    };

    function limpaCamposCodigoIRRF() {
        campoCodigoRelativoIRRF.value = "";
        campoValorRendimentoMensal.value = parseFloat(0.00);
        campoValorRendimento13Mensal.value = parseFloat(0.00);
        campoValorMolestiaGrave.value = parseFloat(0.00);
        campoValorIsenta65.value = parseFloat(0.00);
        campoValorJuroMora.value = parseFloat(0.00);
        campoValorNaoTributavel.value = parseFloat(0.00);
        campoDescricaoNaoTributavel.value = "";
        campoValorPrevidenciaOficial.value = parseFloat(0.00);
        campoDescricaoRRA.value = "";
        campoQuantidadeRRA.value = 0;
        campoDespCustas.value = parseFloat(0.00);
        campoDespAdvogados.value = parseFloat(0.00);
    }

    function validaExibeTableCodigoRelativoIRRF() {
        tabelaCodigoRelativoIRRF.style.display = 'none';
            if (tabelaTributoImpostoRenda.bootstrapTable('getData').length > 0) {
                renderSelectCodigoIRRF();
                tabelaCodigoRelativoIRRF.style.display = 'block';
            }
    }

    function validaExibeTableAdvogado() {
        tableAdvogado.style.display = 'none';
        if (tabelaCodigoIRRF.bootstrapTable('getData').length > 0) {
            tableAdvogado.style.display = 'block';
        }
    }

   //Início tabela Advogado
   let tabelaAdvogado = jQuery('#dataAdvogado-tabela');

    let colunasAdvogado =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Tipo',
            field: 'tipoInscricaoADV',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'CNPJ',
            field: 'cnpjADV',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'CPF',
            field: 'cpfADV',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr Despesa',
            field: 'valorDespesaADV',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaAdvogado
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaAdvogado.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasAdvogado,
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

    function adicionaAdvogado(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarAdvogado(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirAdvogado(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarAdvogado.addEventListener('click', incluirAdvogado); 

    function incluirAdvogado() {
        let dadoAdvogado = new FormData(formComplementoTributoProcessoJudicial);
        let tipoInscricaoADV = dadoAdvogado.get('tipoInscricaoADV');
        let cnpjADV = dadoAdvogado.get('cnpjADV');
        let cpfADV = dadoAdvogado.get('cpfADV');
        let valorADV = dadoAdvogado.get('valorADV');
        let sequencial = 0;

        if (valorADV < 0) {
            alert("Valor de despesa do advogado tem que ser maior ou igual que zero. Favor revisar.");
            return;
        };

        if (cnpjADV.trim() != '' && cpfADV.trim() != '' ) {
            alert("Preencha somente o campo referente ao tipo definido de inscrição. Favor revisar.");
            return;
        };

        if (parseInt(tipoInscricaoADV.trim()) == 2 && cpfADV.trim().length != 11) {
            alert("CPF inválido. Favor revisar.");
            return;
        }

        if (parseInt(tipoInscricaoADV.trim()) == 1 && cnpjADV.trim().length != 14) {
            alert("CNPJ inválido. Favor revisar.");
            return;
        }

        if (cnpjADV.trim() != '') {
            idUnico = cnpjADV.trim();
        }

        if (cpfADV.trim() != '') {
            idUnico = cpfADV.trim();
        }

        const itemAdvogado = {
            id: idUnico,
            sequencial: sequencial,
            tipoInscricaoADV: tipoInscricaoADV,
            cnpjADV: cnpjADV,
            cpfADV : cpfADV,
            valorDespesaADV : valorADV
        };

        let verificaAdvogado = tabelaAdvogado.bootstrapTable('getRowByUniqueId', itemAdvogado.id);

        if (verificaAdvogado) {
            if (!confirm("Advogado já lançado. Deseja sobrescrever?")) {
                campoTipoInscricaoADV.focus();
                return;
            }
            tabelaAdvogado.bootstrapTable('updateByUniqueId', {
                id: itemAdvogado.id,
                row: itemAdvogado
            });

            campoTipoInscricaoADV.value = "";
            campoCnpjADV.value = "";
            campoCpfADV.value = "";
            campoValorADV.value = 0.00;
            lancamentosAdvogado.value = JSON.stringify(tabelaAdvogado.bootstrapTable('getData'));
            divMensagemAdvogado.removeAttribute("hidden");
            divMensagemAdvogado.innerHTML = menssagemPadrao;
            return;
        }
        tabelaAdvogado.bootstrapTable('append', itemAdvogado);

        campoTipoInscricaoADV.value = "";
        campoCnpjADV.value = "";
        campoCpfADV.value = "";
        campoValorADV.value = 0.00;
        lancamentosAdvogado.value = JSON.stringify(tabelaAdvogado.bootstrapTable('getData'));
        divMensagemAdvogado.removeAttribute("hidden");
        divMensagemAdvogado.innerHTML = menssagemPadrao;
        return;
    }

    function excluirAdvogado(registro) {
        let linha= tabelaAdvogado.bootstrapTable('getRowByUniqueId', registro);
        sequencialExcluidoAdvogado.push(linha.sequencial);
        sequencialExcluirAdvogado.value = sequencialExcluidoAdvogado;
        tabelaAdvogado.bootstrapTable('removeByUniqueId', registro)
        lancamentosAdvogado.value = JSON.stringify(tabelaAdvogado.bootstrapTable('getData'));
        divMensagemAdvogado.removeAttribute("hidden");
        divMensagemAdvogado.innerHTML = menssagemPadrao;
        return;
    }

    function editarAdvogado(id) {
        let linha = tabelaAdvogado.bootstrapTable('getRowByUniqueId', id);
        campoTipoInscricaoADV.value = linha.tipoInscricaoADV;
        validaTipoIncricao();
        campoCnpjADV.value = linha.cnpjADV;
        campoCpfADV.value = linha.cpfADV;
        campoValorADV.value = parseFloat(linha.valorDespesaADV);
        divMensagemAdvogado.removeAttribute("hidden");
        divMensagemAdvogado.innerHTML = menssagemPadrao;
        return;
    }

    function validaTipoIncricao() {
        campoCnpjADV.style.display = 'none';
        campoCpfADV.style.display = 'none';
        labelCnpjADV.style.display = 'none';
        labelCpfADV.style.display = 'none';
        if (parseInt(campoTipoInscricaoADV.value) == 1) {
            labelCnpjADV.style.display = 'block';
            campoCnpjADV.style.display = 'block';
            campoCpfADV.value = "";
        }
        if (parseInt(campoTipoInscricaoADV.value) == 2) {
            labelCpfADV.style.display = 'block';
            campoCpfADV.style.display = 'block';
            campoCnpjADV.value = "";
        }
    }

    function validaCnpjAdvogado() {
        if (campoCnpjADV.length != 14) {
            alert('CNPJ inválido. Favor revisar');
            return;
        }
    }

    function validaCpfAdvogado() {
        if (campoCpfADV.length != 11) {
            alert('CPF inválido. Favor revisar');
            return;
        }
    }

   //Início tabela Dependente
   let tabelaDependente = jQuery('#dataDependente-tabela');

    let colunasDependente =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Tipo',
            field: 'tipoRendimentoDEP',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'CPF',
            field: 'cpfDEP',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Vlr Dedução',
            field: 'valorDEP',
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
            formatter: adicionaDependente
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaDependente.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasDependente,
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

    function adicionaDependente(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarDependente(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirDependente(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }


    lancarDependente.addEventListener('click', incluirDependente); 

    function incluirDependente() {
        let dadoDependente = new FormData(formComplementoTributoProcessoJudicial);
        let tipoRendimentoDEP = dadoDependente.get('tipoRendimentoDEP');
        let cpfDEP = dadoDependente.get('cpfDEP');
        let valorDEP = dadoDependente.get('valorDEP');
        let sequencial = 0;

        if (valorDEP <= 0) {
            alert("Valor de rendimento do dependente tem que ser maior que zero. Favor revisar.");
            return;
        };

        if (cpfDEP.trim().length != 11) {
            alert("CPF inválido. Favor revisar.");
            return;
        }

        if (cpfDEP.trim() != '') {
            idUnico = parseInt(tipoRendimentoDEP.trim() + cpfDEP.trim());
        }

        const itemDependente = {
            id: idUnico,
            sequencial: sequencial,
            tipoRendimentoDEP: tipoRendimentoDEP,
            cpfDEP : cpfDEP,
            valorDEP : valorDEP
        };

        let verificaDependente = tabelaDependente.bootstrapTable('getRowByUniqueId', itemDependente.id);

        if (verificaDependente) {
            if (!confirm("Dependente já lançado. Deseja sobrescrever?")) {
                campoTipoRendimentoDEP.focus();
                return;
            }
            tabelaDependente.bootstrapTable('updateByUniqueId', {
                id: itemDependente.id,
                row: itemDependente
            });

            campoTipoRendimentoDEP.value = "";
            campoCpfDEP.value = "";
            campoValorDEP.value = 0.00;
            lancamentosDependente.value = JSON.stringify(tabelaDependente.bootstrapTable('getData'));
            divMensagemDependente.removeAttribute("hidden");
            divMensagemDependente.innerHTML = menssagemPadrao;
            return;
        }
        tabelaDependente.bootstrapTable('append', itemDependente);

        campoTipoRendimentoDEP.value = "";
        campoCpfDEP.value = "";
        campoValorDEP.value = 0.00;
        lancamentosDependente.value = JSON.stringify(tabelaDependente.bootstrapTable('getData'));
        divMensagemDependente.removeAttribute("hidden");
        divMensagemDependente.innerHTML = menssagemPadrao;
        return;
    }

    function excluirDependente(registro) {
        let linha= tabelaDependente.bootstrapTable('getRowByUniqueId', registro);
        sequencialExcluidoDependente.push(linha.sequencial);
        sequencialExcluirDependente.value = sequencialExcluidoDependente;
        tabelaDependente.bootstrapTable('removeByUniqueId', registro)
        lancamentosDependente.value = JSON.stringify(tabelaDependente.bootstrapTable('getData'));
        divMensagemDependente.removeAttribute("hidden");
        divMensagemDependente.innerHTML = menssagemPadrao;
        return;
    }

    function editarDependente(id) {
        let linha = tabelaDependente.bootstrapTable('getRowByUniqueId', id);
        campoTipoRendimentoDEP.value = linha.tipoRendimentoDEP;
        campoCpfDEP.value = linha.cpfDEP;
        campoValorDEP.value = parseFloat(linha.valorDEP);
        return;
    }

    function validaExibeTableDependente() {
        tableDependente.style.display = 'none';
        if (tabelaCodigoIRRF.bootstrapTable('getData').length > 0) {
            tableDependente.style.display = 'block';
        }
    }

    //Início tabela Pensão alimentícia
    let tabelaPensao = jQuery('#dataPensao-tabela');

    let colunasPensao =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Tipo',
            field: 'tipoRendimentoPEN',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `250px`
        },{
            title: 'CPF',
            field: 'cpfPEN',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `250px`
        },{
            title: 'Vlr Dedução',
            field: 'valorPEN',
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
            formatter: adicionaPensao
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaPensao.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasPensao,
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

    function adicionaPensao(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarPensao(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirPensao(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarPensao.addEventListener('click', incluirPensao); 

    function incluirPensao() {
        let dadoPensao = new FormData(formComplementoTributoProcessoJudicial);
        let tipoRendimentoPEN = dadoPensao.get('tipoRendimentoPEN');
        let cpfPEN = dadoPensao.get('cpfPEN');
        let valorPEN = dadoPensao.get('valorPEN');
        let sequencial = 0;

        if (valorPEN <= 0) {
            alert("Valor de rendimento do dependente tem que ser maior que zero. Favor revisar.");
            return;
        };

        if (cpfPEN.trim() != '') {
            idUnico = parseInt(tipoRendimentoPEN.trim() + cpfPEN.trim());
        }

        const itemPensao = {
            id: idUnico,
            sequencial: sequencial,
            tipoRendimentoPEN: tipoRendimentoPEN,
            cpfPEN : cpfPEN,
            valorPEN : valorPEN
        };

        let verificaPensao = tabelaPensao.bootstrapTable('getRowByUniqueId', itemPensao.id);

        if (verificaPensao) {
            if (!confirm("Pensão já lançado. Deseja sobrescrever?")) {
                campoTipoRendimentoPEN.focus();
                return;
            }
            tabelaPensao.bootstrapTable('updateByUniqueId', {
                id: itemPensao.id,
                row: itemPensao
            });

            campoTipoRendimentoPEN.value = "";
            campoCpfPEN.value = "";
            campoValorPEN.value = 0.00;
            lancamentosPensao.value = JSON.stringify(tabelaPensao.bootstrapTable('getData'));
            divMensagemBasePensao.removeAttribute("hidden");
            divMensagemBasePensao.innerHTML = menssagemPadrao;
            return;
        }
        tabelaPensao.bootstrapTable('append', itemPensao);

        campoTipoRendimentoPEN.value = "";
        campoCpfPEN.value = "";
        campoValorPEN.value = 0.00;
        lancamentosPensao.value = JSON.stringify(tabelaPensao.bootstrapTable('getData'));
        return;
    }

    function excluirPensao(registro) {
        let linha= tabelaPensao.bootstrapTable('getRowByUniqueId', registro);
        sequencialExcluidoPensao.push(linha.sequencial);
        sequencialExcluirPensao.value = sequencialExcluidoPensao;
        tabelaPensao.bootstrapTable('removeByUniqueId', registro)
        lancamentosPensao.value = JSON.stringify(tabelaPensao.bootstrapTable('getData'));
        divMensagemBasePensao.removeAttribute("hidden");
        divMensagemBasePensao.innerHTML = menssagemPadrao;
        return;
    }

    function editarPensao(id) {
        let linha = tabelaPensao.bootstrapTable('getRowByUniqueId', id);
        campoTipoRendimentoPEN.value = linha.tipoRendimentoPEN;
        campoCpfPEN.value = linha.cpfPEN;
        campoValorPEN.value = parseFloat(linha.valorPEN);
        return;
    }

    function validaExibeTablePensao() {
        tablePensao.style.display = 'none';
        if (tabelaCodigoIRRF.bootstrapTable('getData').length > 0) {
            tablePensao.style.display = 'block';
        }
    }

    //Início tabela retenção
    let tabelaRetencao = jQuery('#dataRetencao-tabela');

    let colunasRetencao =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Tipo',
            field: 'tipoRetencao',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'Número Processo',
            field: 'numeroRetencao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Código Suspensão',
            field: 'codigoSuspensao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaRetencao
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaRetencao.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasRetencao,
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

    function adicionaRetencao(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarRetencao(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirRetencao(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarRetencao.addEventListener('click', incluirRetencao); 

    function incluirRetencao() {
        let dadoRetencao = new FormData(formComplementoTributoProcessoJudicial);
        let tipoRetencao = dadoRetencao.get('tipoRetencao');
        let numeroRetencao = dadoRetencao.get('numeroRetencao');
        let codigoSuspensao = dadoRetencao.get('codigoSuspensao');
        let sequencial = 0;

        if (numeroRetencao.trim().length < 17 | numeroRetencao.trim().length > 21) {
            alert("Número de processo inválido. Favor revisar.");
            campoNumeroRetencao.focus();
            return;
        }

        if (tipoRetencao.trim() == '') {
            alert("Tipo de processo não definido. Favor revisar.");
            campoTipoRetencao.focus();
            return;
        }

        idUnico = numeroRetencao.trim();

        const itemRetencao = {
            id: idUnico,
            sequencial: sequencial,
            tipoRetencao: tipoRetencao,
            numeroRetencao : numeroRetencao,
            codigoSuspensao : codigoSuspensao
        };

        let verificaRetencao = tabelaRetencao.bootstrapTable('getRowByUniqueId', itemRetencao.id);

        if (verificaRetencao) {
            if (!confirm("Pensão já lançado. Deseja sobrescrever?")) {
                campoTipoInscricaoPEN.focus();
                return;
            }
            tabelaRetencao.bootstrapTable('updateByUniqueId', {
                id: itemRetencao.id,
                row: itemRetencao
            });

            campoTipoRetencao.value = "";
            campoCodigoSuspensaoRetencao.value = "";
            campoNumeroRetencao.value = "";
            lancamentosRetencao.value = JSON.stringify(tabelaRetencao.bootstrapTable('getData'));
            validaExibeTableValorRetencao();
            divMensagemRetencao.removeAttribute("hidden");
            divMensagemRetencao.innerHTML = menssagemPadrao;
            return;
        }
        tabelaRetencao.bootstrapTable('append', itemRetencao);


        campoTipoRetencao.value = "";
        campoCodigoSuspensaoRetencao.value = "";
        campoNumeroRetencao.value = "";
        lancamentosRetencao.value = JSON.stringify(tabelaRetencao.bootstrapTable('getData'));
        validaExibeTableValorRetencao();
        if (tabelaRetencao.bootstrapTable('getData').length > 0) {
            renderSelectRetencao();
        }

        return;
    }

    function excluirRetencao(registro) {
        let linha = tabelaRetencao.bootstrapTable('getRowByUniqueId', registro);
        let apuracaoMes = '1' + linha.numeroRetencao;
        let apuracaoAnual = '2' + linha.numeroRetencao;
        excluirValorRetencao(apuracaoMes);
        excluirValorRetencao(apuracaoAnual);
        sequencialExcluidoRetencao.push(linha.sequencial);
        sequencialExcluirRetencao.value = sequencialExcluidoRetencao;
        tabelaRetencao.bootstrapTable('removeByUniqueId', registro)
        lancamentosRetencao.value = JSON.stringify(tabelaRetencao.bootstrapTable('getData'));
        validaExibeTableValorRetencao();
        divMensagemRetencao.removeAttribute("hidden");
        divMensagemRetencao.innerHTML = menssagemPadrao;
        return;
    }

    function editarRetencao(id) {
        let linha = tabelaRetencao.bootstrapTable('getRowByUniqueId', id);
        campoTipoRetencao.value = linha.tipoRetencao;
        campoCodigoSuspensaoRetencao.value = linha.codigoSuspensao;
        campoNumeroRetencao.value = linha.numeroRetencao;
        return;
    }

    function validaExibeTableRetencao() {
        tableRetencao.style.display = 'none';
        if (tabelaCodigoIRRF.bootstrapTable('getData').length > 0) {
            tableRetencao.style.display = 'block';
        }
    }

    //Início tabela valor retenção
    let tabelaValorRetencao = jQuery('#dataValorRetencao-tabela');

    let colunasValorRetencao =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Número Processo',
            field: 'processoRetencao',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `170px`
        },{
            title: 'Indicativo',
            field: 'periodoApuracao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `170px`
        },{
            title: 'Vlr retenção',
            field: 'valorRetencao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `170px`
        },{
            title: 'Vlr depósito',
            field: 'valorDeposito',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `170px`
        },{
            title: 'Vlr Ano calendário',
            field: 'valorAnoCalendario',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `170px`
        },{
            title: 'Vlr Ano Anterior',
            field: 'valorAnoAnterior',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `170px`
        },{
            title: 'Vlr Rendimento',
            field: 'valorRendimentoSuspenso',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `170px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaValorRetencao
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaValorRetencao.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasValorRetencao,
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

    function adicionaValorRetencao(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarValorRetencao(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirValorRetencao(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }


    lancarValorRetencao.addEventListener('click', incluirValorRetencao); 

    function incluirValorRetencao() {
        let dadoValorRetencao = new FormData(formComplementoTributoProcessoJudicial);
        let processoRetencao = dadoValorRetencao.get('processoRetencao');
        let periodoApuracao = dadoValorRetencao.get('periodoApuracao');
        let valorRetencao = parseFloat(dadoValorRetencao.get('valorRetencao'));
        let valorDeposito = parseFloat(dadoValorRetencao.get('valorDeposito'));
        let valorAnoCalendario = parseFloat(dadoValorRetencao.get('valorAnoCalendario'));
        let valorAnoAnterior = parseFloat(dadoValorRetencao.get('valorAnoAnterior'));
        let valorRendimentoSuspenso = parseFloat(dadoValorRetencao.get('valorRendimentoSuspenso'));
        let sequencial = 0;

        if (periodoApuracao.trim().length == 0) {
            alert("Defina o 'Indicativo de período de apuração'. Favor revisar.");
            return;
        }

        if (valorRetencao < 0) {
            alert("'Valor da retenção que deixou de ser efetuada em função de processo administrativo ou judicial' tem que ser maior que zero. Favor revisar.");
            return;
        }

        if (valorDeposito < 0) {
            alert("'Valor do depósito judicial em função de processo administrativo ou judicial' tem que ser maior que zero. Favor revisar.");
            return;
        }

        if (valorAnoCalendario < 0) {
            alert("'Valor da compensação relativa ao ano calendário em função de processo judicial' tem que ser maior que zero. Favor revisar.");
            return;
        }

        if (valorRendimentoSuspenso < 0) {
            alert("'Valor da compensação relativa a anos anteriores em função de processo judicial' tem que ser maior que zero. Favor revisar.");
            return;
        }

        if (valorRendimentoSuspenso < 0) {
            alert("'Valor do rendimento com exigibilidade suspensa' tem que ser maior que zero. Favor revisar.");
            return;
        }

        idUnico= periodoApuracao + processoRetencao;

        const itemValorRetencao = {
            id: idUnico,
            sequencial: sequencial,
            processoRetencao: processoRetencao,
            periodoApuracao: periodoApuracao,
            valorRetencao: valorRetencao,
            valorDeposito: valorDeposito,
            valorAnoCalendario: valorAnoCalendario,
            valorAnoAnterior:valorAnoAnterior,
            valorRendimentoSuspenso: valorRendimentoSuspenso
        };

        let verificaValorRetencao = tabelaValorRetencao.bootstrapTable('getRowByUniqueId', itemValorRetencao.id);

        if (verificaValorRetencao) {
            if (!confirm("Informações de valores relacionados a não retenção de tributos ou a depósitos judiciais já lançado. Deseja sobrescrever?")) {
                campoProcessoRetencao.focus();
                return;
            }
            tabelaValorRetencao.bootstrapTable('updateByUniqueId', {
                id: itemValorRetencao.id,
                row: itemValorRetencao
            });

            campoProcessoRetencao.value = "";
            campoPeriodoApuracao.value = "";
            campoValorRetencao.value = 0.00;
            campoValorDeposito.value = 0.00;
            campoValorAnoCalendario.value = 0.00;
            campoValorAnoAnterior.value = 0.00;
            campoValorRendimentoSuspenso.value = 0.00;
            lancamentosValorRetencao.value = JSON.stringify(tabelaValorRetencao.bootstrapTable('getData'));
            validaExibeTableDeducaoSuspensa();
            renderSelectProcessoDeducao();
            divMensagemValorRetencao.removeAttribute("hidden");
            divMensagemValorRetencao.innerHTML = menssagemPadrao;
            return;
        }
        tabelaValorRetencao.bootstrapTable('append', itemValorRetencao);

        campoProcessoRetencao.value = "";
        campoPeriodoApuracao.value = "";
        campoValorRetencao.value = 0.00;
        campoValorDeposito.value = 0.00;
        campoValorAnoCalendario.value = 0.00;
        campoValorAnoAnterior.value = 0.00;
        campoValorRendimentoSuspenso.value = 0.00;
        lancamentosValorRetencao.value = JSON.stringify(tabelaValorRetencao.bootstrapTable('getData'));
        validaExibeTableDeducaoSuspensa();
        renderSelectProcessoDeducao();
        divMensagemValorRetencao.removeAttribute("hidden");
        divMensagemValorRetencao.innerHTML = menssagemPadrao;
        return;
    }

    function excluirValorRetencao(registro) {
        let linha= tabelaValorRetencao.bootstrapTable('getRowByUniqueId', registro);
        let excluiDeducao = [];

        if (Boolean(linha)) {
            tabelaDeducaoSuspensa.bootstrapTable('getData').forEach(item => { 
                    if (item.id.trim().substr(1) == registro) {
                        excluiDeducao.push(item.id);
                    }
                }
            );
            excluiDeducao.forEach(item => {
                    excluirDeducaoSuspensa(item);
                }
            );
            sequencialExcluidoValorRetencao.push(linha.sequencial);
            sequencialExcluirValorRetencao.value = sequencialExcluidoValorRetencao;
            tabelaValorRetencao.bootstrapTable('removeByUniqueId', registro)
            lancamentosValorRetencao.value = JSON.stringify(tabelaValorRetencao.bootstrapTable('getData'));
            validaExibeTableDeducaoSuspensa();
            renderSelectProcessoDeducao();
            divMensagemValorRetencao.removeAttribute("hidden");
            divMensagemValorRetencao.innerHTML = menssagemPadrao;
        }
        return;
    }

    function editarValorRetencao(id) {
        let linha = tabelaValorRetencao.bootstrapTable('getRowByUniqueId', id);
        campoProcessoRetencao.value = linha.processoRetencao;
        campoPeriodoApuracao.value = linha.periodoApuracao;
        campoValorRetencao.value = parseFloat(linha.valorRetencao);
        campoValorDeposito.value = parseFloat(linha.valorDeposito);
        campoValorAnoCalendario.value = parseFloat(linha.valorAnoCalendario);
        campoValorAnoAnterior.value = parseFloat(linha.valorAnoAnterior);
        campoValorRendimentoSuspenso.value =  parseFloat(linha.valorRendimentoSuspenso);
        return;
    }

    function renderSelectRetencao() {
        let opcoes = '';
        opcoes += `
            <option value="">Selecione...</option>
        `;
        tabelaRetencao.bootstrapTable('getData').forEach(item => {
            opcoes += `
            <option value="${item.numeroRetencao}">${item.numeroRetencao}</option>
        `;
         });

        campoProcessoRetencao.innerHTML = `<select >` + opcoes + `</select>`;
        campoProcessoRetencao.style.display = 'block';
    }

    function validaExibeTableValorRetencao() {
        tableValorRetencao.style.display = 'none';
        if (tabelaRetencao.bootstrapTable('getData').length > 0) {
            renderSelectRetencao();
            tableValorRetencao.style.display = 'block';
        }
    }

    //Início tabela Dedu??o Suspensa
    let tabelaDeducaoSuspensa = jQuery('#dataDeducaoSuspensa-tabela');

    let colunasDeducaoSuspensa =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Número Processo',
            field: 'processoDeducaoSuspensa',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'Tipo Dedu??o',
            field: 'tipoDeducao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr Dedução',
            field: 'valorDeducaoSuspensa',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaDeducaoSuspensa
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaDeducaoSuspensa.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasDeducaoSuspensa,
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

    function adicionaDeducaoSuspensa(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarDeducaoSuspensa(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirDeducaoSuspensa(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarDeducaoSuspensa.addEventListener('click', incluirDeducaoSuspensa); 

    function incluirDeducaoSuspensa() {
        let dadoDeducaoSuspensa = new FormData(formComplementoTributoProcessoJudicial);
        let processoDeducaoSuspensa = dadoDeducaoSuspensa.get('processoDeducaoSuspensa');
        let tipoDeducao = dadoDeducaoSuspensa.get('tipoDeducao');
        let valorDeducaoSuspensa = parseFloat(dadoDeducaoSuspensa.get('valorDeducaoSuspensa'));
        let sequencial = 0;

        if (processoDeducaoSuspensa.trim().length == 0) {
            alert("Defina o processo relacionado. Favor revisar.");
            return;
        }

        if (tipoDeducao.trim().length == 0) {
            alert("Defina o 'Indicativo de período de apuração'. Favor revisar.");
            return;
        }

        if (valorDeducaoSuspensa < 0) {
            alert("'Valor da dedução da base de cálculo do imposto de renda com exigibilidade suspensa' tem que ser maior que zero. Favor revisar.");
            return;
        }

        idUnico= tipoDeducao + processoDeducaoSuspensa;

        const itemDeducaoSuspensa = {
            id: idUnico,
            sequencial: sequencial,
            processoDeducaoSuspensa: processoDeducaoSuspensa,
            tipoDeducao: tipoDeducao,
            valorDeducaoSuspensa: valorDeducaoSuspensa
        };

        let verificaDeducaoSuspensa = tabelaDeducaoSuspensa.bootstrapTable('getRowByUniqueId', itemDeducaoSuspensa.id);

        if (verificaDeducaoSuspensa) {
            if (!confirm("Informações de detalhamento das deduções com exigibilidade suspensa já lançado. Deseja sobrescrever?")) {
                campoProcessoDeducaoSuspensa.focus();
                return;
            }
            tabelaDeducaoSuspensa.bootstrapTable('updateByUniqueId', {
                id: itemDeducaoSuspensa.id,
                row: itemDeducaoSuspensa
            });

            campoProcessoDeducaoSuspensa.value = "";
            campoTipoDeducao.value = "";
            campoValorDeducaoSuspensa.value = 0.00;
            lancamentosDeducaoSuspensa.value = JSON.stringify(tabelaDeducaoSuspensa.bootstrapTable('getData'));
            validaExibeTableSuspensaPensao();
            renderSelectSuspensaPensao();
            divMensagemDeducaoSuspensa.removeAttribute("hidden");
            divMensagemDeducaoSuspensa.innerHTML = menssagemPadrao;
            return;
        }
        tabelaDeducaoSuspensa.bootstrapTable('append', itemDeducaoSuspensa);

        campoProcessoDeducaoSuspensa.value = "";
        campoTipoDeducao.value = "";
        campoValorDeducaoSuspensa.value = 0.00;
        lancamentosDeducaoSuspensa.value = JSON.stringify(tabelaDeducaoSuspensa.bootstrapTable('getData'));
        validaExibeTableSuspensaPensao();
        renderSelectSuspensaPensao();
        divMensagemDeducaoSuspensa.removeAttribute("hidden");
        divMensagemDeducaoSuspensa.innerHTML = menssagemPadrao;
        return;
    }

    function excluirDeducaoSuspensa(registro) {
        let linha= tabelaDeducaoSuspensa.bootstrapTable('getRowByUniqueId', registro);
        let suspensaPensao = [];
        if (Boolean(linha)) {
            tabelaSuspensaPensao.bootstrapTable('getData').forEach(item => { 
                    if (item.processoSuspensaPensao.trim() == registro) {
                        suspensaPensao.push(item.id);
                    }
                }
            );
            suspensaPensao.forEach(item => {
                excluirSuspensaPensao(item);
                }
            );
            sequencialExcluidoDeducaoSuspensa.push(linha.sequencial);
            sequencialExcluirDeducaoSuspensa.value = sequencialExcluidoDeducaoSuspensa;
            tabelaDeducaoSuspensa.bootstrapTable('removeByUniqueId', registro)
            lancamentosDeducaoSuspensa.value = JSON.stringify(tabelaDeducaoSuspensa.bootstrapTable('getData'));
            validaExibeTableSuspensaPensao();
            renderSelectSuspensaPensao();
            divMensagemDeducaoSuspensa.removeAttribute("hidden");
            divMensagemDeducaoSuspensa.innerHTML = menssagemPadrao;
        }
        return;
    }

    function editarDeducaoSuspensa(id) {
        let linha = tabelaDeducaoSuspensa.bootstrapTable('getRowByUniqueId', id);
        campoProcessoDeducaoSuspensa.value = linha.processoDeducaoSuspensa;
        campoTipoDeducao.value = linha.tipoDeducao;
        campoValorDeducaoSuspensa.value = parseFloat(linha.valorDeducaoSuspensa);
        return;
    }

    function validaExibeTableDeducaoSuspensa() {
        tableDeducaoSuspensa.style.display = 'none';
        if (tabelaValorRetencao.bootstrapTable('getData').length > 0) {
            tableDeducaoSuspensa.style.display = 'block';
        }
    }

    function renderSelectProcessoDeducao() {
        let opcoes = '';
        opcoes += `
            <option value="">Selecione...</option>
        `;
        tabelaValorRetencao.bootstrapTable('getData').forEach(item => {
            let valor = item.periodoApuracao + item.processoRetencao;
            let exibe = "";
            if (parseInt(item.periodoApuracao) == 1) {
                exibe = "Mensal - " + item.processoRetencao
            }
            if (parseInt(item.periodoApuracao) == 2) {
                exibe = "Anual - " + item.processoRetencao
            }
            opcoes += `
            <option value="${valor}">${exibe}</option>
        `;
         });

        campoProcessoDeducaoSuspensa.innerHTML = `<select >` + opcoes + `</select>`;
        campoProcessoDeducaoSuspensa.style.display = 'block';
    }

    //Início tabela de deduções suspensas por dependentes e benefici?rios da pens?o aliment?cia
    let tabelaSuspensaPensao = jQuery('#dataSuspensaPensao-tabela');

    let colunasSuspensaPensao =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Número Processo',
            field: 'processoSuspensaPensao',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'CPF',
            field: 'CPFSuspensaPensao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Vlr Dedução',
            field: 'valorSuspensaPensao',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaSuspensaPensao
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaSuspensaPensao.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasSuspensaPensao,
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

    function adicionaSuspensaPensao(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarSuspensaPensao(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirSuspensaPensao(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }


    lancarSuspensaPensao.addEventListener('click', incluirSuspensaPensao); 

    function incluirSuspensaPensao() {
        let dadoSuspensaPensao = new FormData(formComplementoTributoProcessoJudicial);
        let processoSuspensaPensao = dadoSuspensaPensao.get('processoSuspensaPensao');
        let CPFSuspensaPensao = dadoSuspensaPensao.get('CPFSuspensaPensao');
        let valorSuspensaPensao = parseFloat(dadoSuspensaPensao.get('valorSuspensaPensao'));
        let sequencial = 0;

        if (processoSuspensaPensao.trim().length == 0) {
            alert("Defina o processo relacionado. Favor revisar.");
            return;
        }

        if (valorSuspensaPensao < 0) {
            alert("'Valor da dedução da base de cálculo do imposto de renda com exigibilidade suspensa' tem que ser maior que zero. Favor revisar.");
            return;
        }

        if (CPFSuspensaPensao.trim().length != 11) {
            alert("Defina número de CPF válido. Favor revisar.");
            return;
        }

        idUnico= processoSuspensaPensao + CPFSuspensaPensao;

        const itemSuspensaPensao = {
            id: idUnico,
            sequencial: sequencial,
            processoSuspensaPensao: processoSuspensaPensao,
            CPFSuspensaPensao: CPFSuspensaPensao,
            valorSuspensaPensao: valorSuspensaPensao
        };

        let verificaSuspensaPensao = tabelaSuspensaPensao.bootstrapTable('getRowByUniqueId', itemSuspensaPensao.id);

        if (verificaSuspensaPensao) {
            if (!confirm("Informações de detalhamento das deduções com exigibilidade suspensa já lançado. Deseja sobrescrever?")) {
                campoProcessoSuspensaPensao.focus();
                return;
            }
            tabelaSuspensaPensao.bootstrapTable('updateByUniqueId', {
                id: itemSuspensaPensao.id,
                row: itemSuspensaPensao
            });

            campoProcessoSuspensaPensao.value = "";
            campoCPFSuspensaPensao.value = "";
            campoValorSuspensaPensao.value = 0.00;
            lancamentosSuspensaPensao.value = JSON.stringify(tabelaSuspensaPensao.bootstrapTable('getData'));
            divMensagemSuspensaPensao.removeAttribute("hidden");
            divMensagemSuspensaPensao.innerHTML = menssagemPadrao;
            return;
        }
        tabelaSuspensaPensao.bootstrapTable('append', itemSuspensaPensao);

        campoProcessoSuspensaPensao.value = "";
        campoCPFSuspensaPensao.value = "";
        campoValorSuspensaPensao.value = 0.00;
        lancamentosSuspensaPensao.value = JSON.stringify(tabelaSuspensaPensao.bootstrapTable('getData'));
        divMensagemSuspensaPensao.removeAttribute("hidden");
        divMensagemSuspensaPensao.innerHTML = menssagemPadrao;
        return;
    }

    function excluirSuspensaPensao(registro) {
        let linha= tabelaSuspensaPensao.bootstrapTable('getRowByUniqueId', registro);
        if (Boolean(linha)) {
            sequencialExcluidoSuspensaPensao.push(linha.sequencial);
            sequencialExcluirSuspensaPensao.value = sequencialExcluidoSuspensaPensao;
            tabelaSuspensaPensao.bootstrapTable('removeByUniqueId', registro)
            lancamentosSuspensaPensao.value = JSON.stringify(tabelaSuspensaPensao.bootstrapTable('getData'));
            divMensagemSuspensaPensao.removeAttribute("hidden");
            divMensagemSuspensaPensao.innerHTML = menssagemPadrao;
        }
        return;
    }

    function editarSuspensaPensao(id) {
        let linha = tabelaSuspensaPensao.bootstrapTable('getRowByUniqueId', id);
        campoProcessoSuspensaPensao.value = linha.processoSuspensaPensao;
        campoCPFSuspensaPensao.value = linha.CPFSuspensaPensao;
        campoValorSuspensaPensao.value = parseFloat(linha.valorSuspensaPensao);
        return;
    }

    function validaExibeTableSuspensaPensao() {
        tableSuspensaPensao.style.display = 'none';
        if (tabelaDeducaoSuspensa.bootstrapTable('getData').length > 0) {
            tableSuspensaPensao.style.display = 'block';
        }
    }

    function renderSelectSuspensaPensao() {
        let opcoes = '';
        let exibeSelect = '';
        let apuracao = '';
        let valorSelect = '';
        opcoes += `
            <option value="">Selecione...</option>
        `;
        tabelaDeducaoSuspensa.bootstrapTable('getData').forEach(item => {
            switch (item.id.substr(1,1)){
                case '1':
                    apuracao = 'Mensal - ';
                    break;
                case '2':
                    apuracao = 'Anual - ';
                    break;
                default: 
                    apuracao = '';
                    break;

            }

            switch (item.tipoDeducao){
                case '1':
                    exibeSelect = apuracao + 'Previdência - ' + item.processoDeducaoSuspensa.substr(1);
                    break;
                case '5':
                    exibeSelect = apuracao + 'Pensão - ' + item.processoDeducaoSuspensa.substr(1);
                    break;
                case '7':
                    exibeSelect = apuracao + 'Dependentes - ' + item.processoDeducaoSuspensa.substr(1);
                    break;
                default: 
                    exibeSelect = '';
                    break;
            }

            opcoes += `
            <option value="${item.id}">${exibeSelect}</option>
        `;
         });

         campoProcessoSuspensaPensao.innerHTML = `<select >` + opcoes + `</select>`;
         campoProcessoSuspensaPensao.style.display = 'block';
    }

    //Início tabela IR Complementar
    let tabelaIRComplementar = jQuery('#dataIRComplementar-tabela');

    let colunasIRComplementar =  [
        {
            title: 'Id',
            field: 'id',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            field: 'sequencial',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: false,
            sortable: true,
        },{
            title: 'Data Laudo',
            field: 'dataLaudo',
            checkbox: false,
            align: 'center',
            valign: 'middle',
            visible: true,
            sortable: true,
            width: `70px`
        },{
            title: 'CPF',
            field: 'CPFIRComplementar',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Data Nascimento',
            field: 'dataNascimento',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Nome do Dependente',
            field: 'nomeDependente',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Tributável',
            field: 'depIRRF',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Tipo Dependente',
            field: 'tipoDependente',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Descrição',
            field: 'descricaoDependencia',
            checkbox: false,
            sortable: true,
            align: 'rigth',
            valign: 'middle',
            width: `70px`
        },{
            title: 'Ações',
            field: 'acoes',
            align: 'center',
            valign: 'center',
            width: `250px`,
            formatter: adicionaIRComplementar
        }
    ];

    jQuery(document).ready(jQuery => {
        tabelaIRComplementar.bootstrapTable('destroy').bootstrapTable({
            height: 150,
            columns:colunasIRComplementar,
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

    function adicionaIRComplementar(value, row, index) {
        return [
            '<a href="javascript:void(0)" onclick="editarIRComplementar(\'' + row.id + '\')" title="Editar" style="margin-right: 10px;"><i class="fa fa-edit" style="font-size:22px"></i></a>',
            '<a href="javascript:void(0)" onclick="excluirIRComplementar(\'' + row.id + '\')" title="Excluir" style="margin-right: 10px; color: red"><i class="fa fa-trash-o" style="font-size:22px;color:red"></i></a>',
        ].join('');
    }

    lancarIRComplementar.addEventListener('click', incluirIRComplementar); 

    function incluirIRComplementar() {
        let dadoIRComplementar = new FormData(formComplementoTributoProcessoJudicial);
        let dataLaudo = dadoIRComplementar.get('dataLaudo');
        let CPFIRComplementar = dadoIRComplementar.get('CPFIRComplementar');
        let dataNascimento = dadoIRComplementar.get('dataNascimento');
        let nomeDependente = dadoIRComplementar.get('nomeDependente');
        let depIRRF = dadoIRComplementar.get('depIRRF');
        let tipoDependente = dadoIRComplementar.get('tipoDependente');
        let descricaoDependencia = dadoIRComplementar.get('descricaoDependencia');
        let sequencial = 0;

        if (CPFIRComplementar.trim().length != 11) {
            alert("Número CPF inválido. Favor revisar.");
            return;
        }

        idUnico= CPFIRComplementar;

        const itemIRComplementar = {
            id: idUnico,
            sequencial: sequencial,
            dataLaudo: dataLaudo,
            CPFIRComplementar: CPFIRComplementar,
            dataNascimento: dataNascimento,
            nomeDependente: nomeDependente,
            depIRRF: depIRRF,
            tipoDependente: tipoDependente,
            descricaoDependencia: descricaoDependencia
        };

        let verificaIRComplementar = tabelaIRComplementar.bootstrapTable('getRowByUniqueId', itemIRComplementar.id);

        if (verificaIRComplementar) {
            if (!confirm("InformAções relacionadas ? retenção na fonte, aos rendimentos tributáveis e não tributáveis, deduções e/ou isen??es, etc., de acordo com a legisla??o aplicada ao imposto de renda. Deseja sobrescrever?")) {
                campoProcessoIRComplementar.focus();
                return;
            }
            tabelaIRComplementar.bootstrapTable('updateByUniqueId', {
                id: itemIRComplementar.id,
                row: itemIRComplementar
            });

            campoDataLaudo.value = "";
            campoCPFIRComplementar.value = "";
            campoDataNascimento.value = "";
            campoNomeDependente.value = "";
            campoDepIRRF.value = "";
            campoTipoDependente.value = "";
            campoDescricaoDependencia.value = "";
            lancamentosIRComplementar.value = JSON.stringify(tabelaIRComplementar.bootstrapTable('getData'));
            divMensagemIRComplementar.removeAttribute("hidden");
            divMensagemIRComplementar.innerHTML = menssagemPadrao;
            return;
        }
        tabelaIRComplementar.bootstrapTable('append', itemIRComplementar);

        campoDataLaudo.value = "";
        campoCPFIRComplementar.value = "";
        campoDataNascimento.value = "";
        campoNomeDependente.value = "";
        campoDepIRRF.value = "";
        campoTipoDependente.value = "";
        campoDescricaoDependencia.value = "";
        lancamentosIRComplementar.value = JSON.stringify(tabelaIRComplementar.bootstrapTable('getData'));
        divMensagemIRComplementar.removeAttribute("hidden");
        divMensagemIRComplementar.innerHTML = menssagemPadrao;
        return;
    }

    function excluirIRComplementar(registro) {
        let linha= tabelaIRComplementar.bootstrapTable('getRowByUniqueId', registro);
        sequencialExcluidoIRComplementar.push(linha.sequencial);
        sequencialExcluirIRComplementar.value = sequencialExcluidoIRComplementar;
        tabelaIRComplementar.bootstrapTable('removeByUniqueId', registro)
        lancamentosIRComplementar.value = JSON.stringify(tabelaIRComplementar.bootstrapTable('getData'));
        return;
    }

    function editarIRComplementar(id) {
        let linha = tabelaIRComplementar.bootstrapTable('getRowByUniqueId', id);
        campoDataLaudo.value = linha.dataLaudo;
        campoCPFIRComplementar.value = linha.CPFIRComplementar;
        campoDataNascimento.value = linha.dataNascimento;
        campoNomeDependente.value = linha.nomeDependente;
        campoDepIRRF.value = linha.depIRRF;
        campoTipoDependente.value = linha.tipoDependente;
        campoDescricaoDependencia.value = linha.descricaoDependencia;
        return;
    }

    function validaExibeTableIRComplementar() {
        tableIRComplementar.style.display = 'none';
        if (tabelaTributoImpostoRenda.bootstrapTable('getData').length > 0) {
            tableIRComplementar.style.display = 'block';
        }
    }

    validaExibeTableIRComplementar();

    btnProximaAba.addEventListener('click', () => {
        abaComplemento.desbloquear();
        abaComplemento.setVisibilidade(true);
        abaTributo.bloquear();
        abaTributo.setVisibilidade(false);
    })

    btnAnteriorAba.addEventListener('click', () => {
        abaTributo.desbloquear();
        abaTributo.setVisibilidade(true);
        abaComplemento.bloquear();
        abaComplemento.setVisibilidade(false);

    })

    btnSalvarProcessoJudicial.addEventListener('click', salvaTributos);
    btnSalvarComplemento.addEventListener('click', salvaTributos);

</script>
</body>
</html>
