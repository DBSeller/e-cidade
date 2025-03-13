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

use App\Domain\Configuracao\Instituicao\Model\DBConfig;

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
$insituicoes = DBConfig::query()->orderBy('codigo')->get();

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <title>DBSeller Informática Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <style>
        .fade {
            position: absolute;
            top: 0;
            display: none;
            height: 100vh;
            align-items: center;
            justify-content: center;
            width: 100%;
            z-index: 1;
            background: rgba(0, 0, 0, 0.7);
        }

        .ctnModal {
            width: 90%;
            top: 0;
            height: 80vh;
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 2px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        .close {
            position: absolute;
            float: right;
            right: 5px;
            cursor: pointer;
            color: #0f0f0f;
        }

        .modal-info {
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
            border-radius: 2px;
            z-index: 999999;
            position: absolute;
            align-items: center;
            justify-content: center;
            width: 400px;
            background-color: #e1dede;
            padding-left: 7px;
            padding-bottom: 10px;
        }

        .nivel1 {
            width: 15px;
        }

        .nivel2 {
            width: 21px;
        }

        .campoEstrutural {
            width: 137px;
        }

        .campoNomeEstrutural {
            width: 300px;
        }

        .ocultaLinhas {
            display: none;
        }

        .mostraLinhas {
            display: table-row;
        }
    </style>
</head>
<body>

<div id='ctnAbas'></div>

<div id="abaTipoConta">
    <div class="container">
        <fieldset>

            <legend>Selecione o tipo de conta</legend>
            <table class="form-container">
                <tr>
                    <td><label for="tipoContaManutencao">Tipo de Conta:</label></td>
                    <td>
                        <select id="tipoContaManutencao">
                            <option value="">Selecione</option>
                            <option value="contasCaixa">Conta Caixa</option>
                            <option value="contasBancarias">Conta Bancária</option>
                            <option value="contasExtrasOrcamentarias">Conta Extra-orçamentária</option>
                            <option value="outrasContas">Outras Contas</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button type="button" id="btnProximoAbaConta">
            Próximo
            <i class="fas fa-chevron-right"></i>
        </button>

    </div>
</div>

<div id="abaConta" style="display: none">
    <div class="alert alert-primary text-left" role="alert">
        bla bla bla...<br>
    </div>
    <div class="container">
        <form id="formConta">
            <div>
                <fieldset>
                    <legend>Atualização do Plano PCASP</legend>
                    <table class="form-container">
                        <tr>
                            <td><label id="ancoraPlanoUniao">
                                    <a href="#" class="bold">Plano da União:</a>&nbsp;
                                </label>
                            </td>
                            <td>
                                <input type="text" readonly id="planoUniao" class="campoEstrutural readonly">
                                <input type="text" readonly id="descricaoPlanoUniao"
                                       class="campoNomeEstrutural readonly">
                            </td>
                        </tr>
                        <tr>
                            <td><label id="ancoraPlanoEstadual">
                                    <a href="#" class="bold">Plano Estadual:</a>&nbsp;
                                </label>
                            </td>
                            <td>
                                <input type="text" readonly id="planoEstadual" class="campoEstrutural readonly">
                                <input type="text" readonly id="descricaoPlanoEstadual"
                                       class="campoNomeEstrutural readonly">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="naturezaSaldo">Natureza de Saldo</label></td>
                            <td>
                                <select id="naturezaSaldo" name="naturezaSaldo" disabled class="readonly">
                                    <option value=''></option>
                                    <option value='C'>Credora</option>
                                    <option value='D'>Devedora</option>
                                    <option value='C/D'>Credora/Devedora</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="indicadorSuperavit">Indicador de Superávit:</label>&nbsp;</td>
                            <td>
                                <select id="indicadorSuperavit" name="indicadorSuperavit" disabled class="readonly">
                                    <option value='N'>Não se aplica</option>
                                    <option value='F'>Financeiro</option>
                                    <option value='P'>Permanente</option>
                                    <option value='F/P'>Financeiro/Permanente</option>
                                </select>
                                <br>
                                <select id="indicadorSuperavitFP" name="indicadorSuperavitFP" style="display: none;">
                                    <option value='F'>Financeiro</option>
                                    <option value='P'>Permanente</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="estruturalEcidade">Estrutural:</label></td>
                            <td>
                                <db-estrutural id="estruturalEcidade"></db-estrutural>
                                <label id="pesquisarEstruturais" style="cursor:pointer;"><i
                                        class="fas fa-search"></i></label>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="nomeConta">Título:</label></td>
                            <td>
                                <input type="text" id="nomeConta" name="nomeConta" class="field-size-max">
                            </td>
                        </tr>

                        <tr>
                            <td><label for="transferenciaSaldo">Transfere Saldo:</label></td>
                            <td>
                                <select id="transferenciaSaldo" name="transferenciaSaldo">
                                    <option value='N'>Não</option>
                                    <option value='S'>Sim</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <fieldset class="separator">
                                    <legend>Funcionamento</legend>
                                    <textarea id="funcionamento" name="funcionamento" rows="3" cols="65"></textarea>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <fieldset class="separator">
                                    <legend>Função</legend>
                                    <textarea id="funcao" name="funcao" rows="3" cols="65"></textarea>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </div>

            <button type="button" id="btnRetornar">
                <i class="fas fa-chevron-left"></i>
                Retornar
            </button>
            <button type="button" id="btnProximo">
                Próximo
                <i class="fas fa-chevron-right"></i>
            </button>

            <button type="button" id="btnPesquisarConta">
                <i class="fas fa-search"></i>
                Pesquisar
            </button>

            <button type="button" id="btnNovaConta" style="display: none">
                <i class="far fa-file"></i>
                Cadastrar Nova Conta
            </button>
        </form>
    </div>
</div>

<div id="abaReduzidos" style="display: none;">
    <div class="container">
        <fieldset>
            <legend>Reduzidos</legend>
            <table class="form-container">
                <tr>
                    <td><a href="#" id="ancoraFonteRecurso">Fonte de Recurso:</a>&nbsp;</td>
                    <td>
                        <input type="text" id="fonteGestao" name="fonteGestao" lang="o15_codigo" readonly
                               class="readonly">
                        <input type="text" id="nomeRecurso" name="nomeRecurso" readonly class="readonly">
                    </td>
                </tr>
                <tr>
                    <td><label for="complementoRecurso" class="bold">Complemento:</label></td>
                    <td>
                        <input type="text" name="complementoRecurso" id="complementoRecurso" readonly
                               class="readonly field-size-max">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="instituicao">Instituição:</label>
                    </td>
                    <td>
                        <select id="instituicao">
                            <?php foreach ($insituicoes as $instituicao): ?>
                                <option value="<?= $instituicao->codigo ?>"><?= $instituicao->nomeinst ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr class="contaBancaria ocultaLinhas">
                    <td><a href="#" id="ancoraContaBancaria">Conta Bancária: </a>&nbsp;</td>
                    <td>
                        <input type="text" id="contaBancaria" name="contaBancaria" lang="db83_sequencial" readonly
                               class="readonly">
                        <input type="text" id="descricaoContaBancaria" name="descricaoContaBancaria" lang="db_conta"
                               readonly class="readonly">
                        <button id="novaContaBancaria">
                            <i class="far fa-file"></i>
                        </button>
                    </td>
                </tr>
                <tr class="contaBancaria ocultaLinhas">
                    <td><label>Convênio</label></td>
                    <td>
                        <input type="text" id="convenio" name="convenio" class="field-size2">
                    </td>
                </tr>
                <tr class="contaBancaria ocultaLinhas">
                    <td><label>Cheque:</label></td>
                    <td><input type="text" id="cheque" name="cheque" class="field-size2" value="0"></td>
                </tr>
            </table>
        </fieldset>
        <button type="button" id="btnAdicionarReduzido">
            <i class="fas fa-plus"></i>
            Adicionar
        </button>
        <fieldset>
            <legend>Lista dos Reduzidos Adicionados</legend>

            <div id="cntTabelaReduzidos">
                <table id="tabelaReduzidos"
                       class="table table-sm"
                       data-detail-view="true"
                       style="width: 100%;">
                </table>
            </div>
        </fieldset>
        <button type="button" id="btnSalvarConta">
            <i class="fas fa-save"></i>
            Salvar
        </button>
    </div>
</div>

<div id="abaContaCorrente" style="display: none;">
    <div class="container">
        <fieldset style="width: 500px;">
            <legend>Conta Corrente</legend>
            <table class="form-container">
                <tr id='conta-corrente'>
                    <td><a href="#" id="ancoraContaCorrente">Conta Corrente:</a>
                    </td>
                    <td>
                        <input type="text" id="codigoContaCorrente" name="codigoContaCorrente" lang="c122_sequencial">
                        <input type="text" id="nomeContaCorrente" name="nomeContaCorrente" lang="c122_descricao">
                    </td>
                </tr>
            </table>
        </fieldset>
        <button type="button" id="btnSalvarContaCorrente">
            <i class="fas fa-save"></i>
            Adicionar
        </button>
    </div>

    <div class="subcontainer">
        <fieldset style="width: 800px;">
            <legend>Contas Correntes Vinculadas</legend>
            <table id="tabelaContasCorrente"
                   class="table table-sm"
                   style="width: 100%;">
            </table>
        </fieldset>
    </div>
</div>


<div class="fade" id="modalArvoreEstrutural">
    <div class="ctnModal">
        <div class="alert text-left" role="alert">
            <p class="close"><i id="fecharModalArvoreEstrutural" class="fas fa-window-close"></i></p>
            Lista os estruturais existentes no e-cidade filhos do estrutural do plano do governo.
        </div>
        <div class="container">
            <fieldset style="width: 1200px">
                <legend>Plano de Contas e-Cidade para edição do estrutural</legend>
                <table id="tableAlteracaoEstrutural"
                       class="table table-sm"
                       style="width: 100%;">
                </table>
            </fieldset>
            <br>
            <button type="button" id="btnEditarEstruturais">
                <i class="fas fa-save"></i>
                Editar Estruturais
            </button>
        </div>
    </div>
</div>

<div class="fade" id="modalPesquisaPlanoContas">
    <div class="ctnModal">
        <div class="alert text-left" role="alert">
            <p class="close"><i id="fecharModalPlanoContas" class="fas fa-window-close"></i></p>
            Selecione a conta...
        </div>
        <fieldset>
            <legend>Plano de Contas - <span id="labelTipoPlano"></span></legend>

            <div id="ctnPesquisaPcasp" style="display: none">
                <p>
                    <label for="consultaEstruturalPadrao" class="bold">Estrutural:</label>
                    <input type="text" id="consultaEstruturalPadrao" name="consultaEstruturalPadrao" maxlength="15"
                           oninput="js_ValidaCampos(this, 1, 'Estrutural', 't', 'f', event);">
                    <span id="pesquisaContaPadrao" style="cursor: pointer"><i class="fas fa-search"></i></span>
                </p>
                <table id="tablePlanoConta"
                       class="table table-sm"
                       style="width: 100%;">
                </table>
            </div>

            <div id="ctnPesquisaPcaspEcidade" style="display: none">
                <p>
                    <label for="consultaEstrutural" class="bold">Estrutural:</label>
                    <input type="text" id="consultaEstrutural" name="consultaEstrutural" maxlength="15"
                           oninput="js_ValidaCampos(this, 1, 'Estrutural', 't', 'f', event);">
                    <span id="pesquisaContaEcidade" style="cursor: pointer"><i class="fas fa-search"></i></span>
                </p>

                <table id="tablePlanoContaEcidade"
                       class="table table-sm"
                       style="width: 100%;">
                </table>
            </div>
        </fieldset>
    </div>
</div>

<div class="modal-info" tabindex="-1" role="dialog" id="janelaInformacao" style="display: none">
    <h3 style="text-decoration: solid">Lista dos reduzidos</h3>
    <div id="listaReduzidos"></div>
</div>

<div class="fade" tabindex="-1" role="dialog" id="janelaContaBancaria" style="display: none">
    <div class="ctnModal" id="cnt">
        <div class="alert text-left" role="alert">
            <p class="close"><i id="fecharModalContaBancaria" class="fas fa-window-close"></i></p>
            Cadastre a conta bancária
        </div>
        <iframe src="con1_contabancaria001.php" style="width: 100%; height: 80%"></iframe>
    </div>
</div>


<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
<script type="text/javascript" src="scripts/classes/bootstrapTable/detailFormaterTable.js"></script>
<script type="text/javascript" src="scripts/classes/financeiro/EstruturalPcasp.js"></script>
<script type="text/javascript" src="scripts/components/EstruturalComponent.js"></script>
<script type="text/javascript">

    $.noConflict();

    const cboTipoContaManutencao = document.getElementById('tipoContaManutencao');

    /**
     * A variável codigoConta armazena o codcon da conta que esta se dando manutenção
     * Em caso de adição de conta ela deve ir com valor vazio
     * @type {string}
     */
    var codigoConta = '';

    /**
     * As variáveis selecionadaContaUniao, selecionadaContaEstado são objetos que armazenam os dados das contas
     * da União e do Estado selecionado ou vínculada a conta (conplano) em caso de edição.
     */
    let selecionadaContaUniao, selecionadaContaEstado;
    /**
     * Na aba de reduzidos após selecionar o recurso, é realizado uma requisição para buscar os dados do recurso.
     * Essa variável é um objeto com todos dados do recurso para adição
     * @type {{}}
     */
    let recursoSelecionado = {};
    /**
     * O objetivo desse array é ser uma collection para mapear os reduzidos já adicionados na conta assim como os novos
     * Eu o utilizo para controle da apresentação dos dados em tela e posterior para saber o que tenho que persistir.
     * @type {*[]}
     */
    let reduzidos = [];

    // cria as abas
    const ctnAbaReduzidos = document.getElementById('abaReduzidos');
    const ctnAbaContaCorrente = document.getElementById('abaContaCorrente');
    const cntAbaConta = document.getElementById('abaConta');

    const ctnAbas = new DBAbas(document.getElementById('ctnAbas'));
    const abaTipoConta = ctnAbas.adicionarAba("Definição da Conta", document.getElementById('abaTipoConta'));
    const abaConta = ctnAbas.adicionarAba("Conta", cntAbaConta);
    const abaReduzido = ctnAbas.adicionarAba("Reduzidos", ctnAbaReduzidos);
    const abaContaCorrente = ctnAbas.adicionarAba("Conta Corrente", ctnAbaContaCorrente);

    abaConta.bloquear();
    abaReduzido.bloquear();
    abaContaCorrente.bloquear();
    cntAbaConta.style.display = '';
    ctnAbaReduzidos.style.display = '';
    ctnAbaContaCorrente.style.display = '';

    const liberaAbaCorrente = () => {
        abaContaCorrente.desbloquear();
    };

    const resetaVariaveisDeControle = () => {
        codigoConta = '';
        selecionadaContaUniao = undefined;
        selecionadaContaEstado = undefined;
        recursoSelecionado = {};
        reduzidos = [];
    };

    document.getElementById('btnRetornar').addEventListener('click', () => {
        abaTipoConta.setVisibilidade(true);
        abaConta.setVisibilidade(false);
    });

    document.getElementById('btnProximoAbaConta').addEventListener('click', () => {

        if (cboTipoContaManutencao.value === '') {
            alert('Selecione o "Tipo de Conta" a dar manutenção.');
            return;
        }

        let linhas = document.querySelectorAll('tr.contaBancaria');
        linhas.forEach((elemento)=> {
            elemento.className = 'contaBancaria ocultaLinhas';
        });
        if (isContaBancaria()) {
            linhas.forEach((elemento)=> {
                elemento.className = 'contaBancaria mostraLinhas';
            });
        }

        resetaVariaveisDeControle();
        abaTipoConta.setVisibilidade(false);
        abaConta.setVisibilidade(true);
        abaConta.desbloquear();
    });

    cboTipoContaManutencao.addEventListener('change', () => {
        limparDadosFormulario();
        btnNovaConta.style.display = 'none';
    });



    let labelTipoPlano = document.getElementById('labelTipoPlano');
    const ancoraPlanoUniao = document.getElementById('ancoraPlanoUniao');
    const ancoraPlanoEstadual = document.getElementById('ancoraPlanoEstadual');
    const inputPlanoUniao = document.getElementById('planoUniao');
    const inputDescricaoPlanoUniao = document.getElementById('descricaoPlanoUniao');
    const inputPlanoEstadual = document.getElementById('planoEstadual');
    const inputDescricaoPlanoEstadual = document.getElementById('descricaoPlanoEstadual');
    const cboNaturezaSaldo = document.getElementById('naturezaSaldo');
    const cboIndicadorSuperavit = document.getElementById('indicadorSuperavit');
    const cboIndicadorSuperavitFP = document.getElementById('indicadorSuperavitFP');
    const inputNomeConta = document.getElementById('nomeConta');
    const cboTransferenciaSaldo = document.getElementById('transferenciaSaldo');
    const inputFuncionamento = document.getElementById('funcionamento');
    const inputFuncao = document.getElementById('funcao');

    // componente do estrutural do e-cidade
    const estruturalEcidade = document.getElementById('estruturalEcidade');
    // ícone para pesquisar e abrir lockup que permite editar o estrutural
    const pesquisarEstruturais = document.getElementById('pesquisarEstruturais');

    // containers das tabelas das modais
    const containerTabelaGoverno = document.getElementById('ctnPesquisaPcasp');
    const containerTabelaEcidade = document.getElementById('ctnPesquisaPcaspEcidade');

    const btnPesquisarConta = document.getElementById('btnPesquisarConta');
    const btnProximo = document.getElementById('btnProximo');
    const btnNovaConta = document.getElementById('btnNovaConta');
    const btnAdicionarReduzido = document.getElementById('btnAdicionarReduzido');

    // inputs da aba Reduzidos
    const ancoraFonteRecurso = document.getElementById('ancoraFonteRecurso');
    const inputFonteGestao = document.getElementById('fonteGestao');
    const inputNomeRecurso = document.getElementById('nomeRecurso');
    const inputComplementoRecurso = document.getElementById('complementoRecurso');
    const cboInstituicao = document.getElementById('instituicao');
    const ancoraContaBancaria = document.getElementById('ancoraContaBancaria');
    const inputContaBancaria = document.getElementById('contaBancaria');
    const inputDescricaoContaBancaria = document.getElementById('descricaoContaBancaria');
    const inputConvenio = document.getElementById('convenio');
    const inputCheque = document.getElementById('cheque');

    const btnSalvarConta = document.getElementById('btnSalvarConta');

    // Modal Plano de contas
    const modalPesquisaPlanoContas = document.getElementById('modalPesquisaPlanoContas');
    const fecharModalPlanoContas = document.getElementById('fecharModalPlanoContas');
    fecharModalPlanoContas.onclick = () => {
        tablePlano.bootstrapTable('load', []);
        tablePlanoEcidade.bootstrapTable('load', []);
        modalPesquisaPlanoContas.style.display = "none"
    }
    const inputConsultaEstruturalPadrao = document.getElementById('consultaEstruturalPadrao');
    const pesquisaContaPadrao = document.getElementById('pesquisaContaPadrao');

    // modal para apresentar os estruturais filhos do estrutural selecionado e permitir edição
    const modalArvoreEstrutural = document.getElementById('modalArvoreEstrutural');
    const fecharModalArvoreEstrutural = document.getElementById('fecharModalArvoreEstrutural');
    fecharModalArvoreEstrutural.onclick = () => {
        modalArvoreEstrutural.style.display = 'none';
    }

    // modal conta bancária
    const modalContaBancaria = document.getElementById('janelaContaBancaria');
    const fecharModalContaBancaria = document.getElementById('fecharModalContaBancaria');
    fecharModalContaBancaria.onclick = () => {
        modalContaBancaria.style.display = 'none';
        ancoraContaBancaria.dispatchEvent(new Event('click'));
    }

    const btnEditarEstruturais = document.getElementById('btnEditarEstruturais');

    btnProximo.addEventListener('click', () => {
        if (inputNomeConta.value === '') {
            alert('preencha o formulário');
            return;
        }
        abaReduzido.desbloquear();
        abaConta.setVisibilidade(false);
        abaReduzido.setVisibilidade(true);
    })

    const routs = {
        planoPcaspUniao: 'financeiro/contabilidade/plano-contas/consulta/pcasp/padrao',
        planoPcaspEcidade: 'financeiro/contabilidade/plano-contas/consulta/pcasp/ecidade',
        validaEstrutural: 'financeiro/contabilidade/plano-contas/pcasp/estrural-existe',
        estruturaisEdicoes: 'financeiro/contabilidade/plano-contas/pcasp/editar-estruturais',
        salvarOutrasConta: 'financeiro/contabilidade/plano-contas/pcasp/salvar-outras-contas',
        salvarContaCaixa: 'financeiro/contabilidade/plano-contas/pcasp/salvar-conta-caixa',
        salvarContasBancarias: 'financeiro/contabilidade/plano-contas/pcasp/salvar-conta-bancaria',
        salvarExtrasOrcamentarias: 'financeiro/contabilidade/plano-contas/pcasp/salvar-conta-extra',
        removerReduzido: 'financeiro/contabilidade/plano-contas/pcasp/remover-reduzido',
        sistemas: 'financeiro/contabilidade/sistemas',
        sistemaConta: 'financeiro/contabilidade/sistema-conta',
        getRecurso: 'financeiro/orcamento/cadastro/recurso',
        buscarContaCorrente: 'financeiro/contabilidade/plano-contas/pcasp/conta-corrente',
        adicionarContaCorrente: 'financeiro/contabilidade/plano-contas/pcasp/conta-corrente/salvar',
        removerContaCorrente: 'financeiro/contabilidade/plano-contas/pcasp/conta-corrente/remover',
    };

    const fecharModal = () => {
        fecharModalPlanoContas.dispatchEvent(new Event('click'));
    };

    const setDadosPlanoUniao = (dadosUniao) => {
        limparFormularioConta();
        selecionadaContaUniao = dadosUniao;
        inputPlanoUniao.value = dadosUniao.mascara;
        inputDescricaoPlanoUniao.value = dadosUniao.nome
    };

    const setDadosPlanoEstado = (dadosEstado) => {
        selecionadaContaEstado = dadosEstado;
        inputPlanoEstadual.value = dadosEstado.mascara;
        inputDescricaoPlanoEstadual.value = dadosEstado.nome
        setDadosFormulario(dadosEstado);
    }

    const tablePlano = jQuery('#tablePlanoConta');
    tablePlano.bootstrapTable({
        locale: 'pt-BR',
        uniqueId: "conta",
        cache: false,
        height: 500,
        search: true,
        class: "table table-sm",
        onClickRow: (row, $element, field) => {
            if (row.uniao) {
                setDadosPlanoUniao(row);
            } else {
                setDadosPlanoEstado(row);
            }

            fecharModal();
        },
        columns: [
            {
                "title": "Conta",
                "field": 'conta',
                "align": 'center',
                "valign": 'middle',
                "width": "150"
            },
            {
                "title": "Nome",
                "field": 'nome',
                "align": 'left',
                "valign": 'middle'
            }
        ]
    });

    const formatterInfo = (value, row, index) => {
        return [
            '<a class="informacao" href="javascript:void(0)">',
            '  <i class="fas fa-info-circle"></i>',
            '</a>'
        ].join('')
    };

    window.operateEvents = {
        'mouseenter .informacao': function (e, value, row, index) {
            let lista = row.reduzidos.map(reduzido => {
                let instituicao = reduzido.instituicao;
                return `<div>${reduzido.c61_reduz} - ${instituicao.codigo} - ${instituicao.nomeinst}</div>`;
            });

            let alvo = e.target.parentNode.getBoundingClientRect();

            document.getElementById('janelaInformacao').style.left = (alvo.left - 410) + 'px';
            document.getElementById('janelaInformacao').style.top = alvo.top + 'px'
            document.getElementById('janelaInformacao').style.display = '';
            document.getElementById('listaReduzidos').innerHTML = lista.join('');
        },
        'mouseout .informacao': function (e, value, row, index) {
            document.getElementById('janelaInformacao').style.display = 'none';
            document.getElementById('listaReduzidos').innerHTML = '';
        },
        'click .removerReduzido': function (e, value, row, index) {
            if (row.c61_reduz != '') {
                alertify.confirm('Tem certeza que deseja excluir o reduzido?', (e) => {
                    removerReduzidos(row.c61_reduz, row.c61_anousu, row.c61_instit);
                });
            } else {
                removeReduzidoTabela(row.c61_instit)
            }
        },
        'click .removerContaCorrente': function (e, value, row, index) {
            alertify.confirm('Tem certeza que deseja excluir a conta corrente?', (e) => {
                executarContaCorrente(row.c122_sequencial, routs.removerContaCorrente);
            });
        }
    };

    const removerReduzidos = (reduzido, exercicio, instituicao) => {

        const formData = new FormData();
        formData.append('codcon', codigoConta);
        formData.append('reduzido', reduzido);
        formData.append('exercicio', exercicio);
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routs.removerReduzido}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
            if (response.data.excluiuConta) {
                location.href = 'con1_manutencaopcasp001.php';
            }
            removeReduzidoTabela(instituicao)
        });
    };

    /**
     * tabela de consulta para selecionar uma conta adicionada no e-cidade
     * @type {jQuery|HTMLElement|*}
     */
    const tablePlanoEcidade = jQuery('#tablePlanoContaEcidade');
    tablePlanoEcidade.bootstrapTable({
        locale: 'pt-BR',
        uniqueId: "conta",
        cache: false,
        height: 500,
        search: true,
        class: "table table-sm",
        onClickCell: (field, value, row, $element) => {
            if (field === 'informacoes') {
                return;
            }

            if (row.pcasp_uniao.length === 0 || row.pcasp_estadual.length === 0) {
                alert('Antes de selecionar essa conta você deve realizar o mapeamento das contas com os planos do governo.');
                return;
            }

            let pcaspUniao = row.pcasp_uniao.shift();
            let pcaspEstadual = row.pcasp_estadual.shift();

            setDadosPlanoUniao(pcaspUniao);
            setDadosPlanoEstado(pcaspEstadual);

            codigoConta = row.c60_codcon;
            setBaseEstrutural(row.mascara, false);
            // seta os dados de alteração
            inputNomeConta.value = row.c60_descr;
            inputFuncao.value = row.c60_funcao;
            inputFuncionamento.value = row.c60_finali;

            cboTransferenciaSaldo.value = row.c60_saldocontinuo ? 'S' : 'N';
            reduzidos = row.reduzidos;
            atualizaTabelaReduzidos(row.reduzidos);

            fecharModal();
            liberaAbaCorrente();
            buscarContasCorrente(codigoConta);
        },
        columns: [
            {
                "title": "Conta",
                "field": 'mascara',
                "align": 'center',
                "valign": 'middle',
                "width": "150"
            },
            {
                "title": "Nome",
                "field": 'c60_descr',
                "align": 'left',
                "valign": 'middle'
            },
            {
                "title": "Informações",
                "field": 'informacoes',
                "align": 'center',
                "valign": 'middle',
                "width": "100",
                events: window.operateEvents,
                formatter: formatterInfo
            }
        ]
    });


    /** *************************************************************************************************************
     *          Funções que interagem com a modal de pesquisa dos planos de contas padrões (União e Estado)
     * ***************************************************************************************************************
     */
    pesquisaContaPadrao.addEventListener('click', () => {
        buscarPlanoGoverno('uniao', inputConsultaEstruturalPadrao.value);
    });

    document.getElementById('ancoraPlanoUniao').addEventListener('click', () => {
        labelTipoPlano.innerHTML = 'Plano da União';
        inputConsultaEstruturalPadrao.value = '';
        inputConsultaEstruturalPadrao.removeAttribute('readonly');
        opemModel(true, false);
    });

    document.getElementById('ancoraPlanoEstadual').addEventListener('click', () => {
        if (selecionadaContaUniao === undefined) {
            alert('Antes de buscar a conta do Plano Estadual, selecione o Plano da União.');
            return;
        }

        labelTipoPlano.innerHTML = 'Plano Estadual';
        inputConsultaEstruturalPadrao.setAttribute('readonly', 'readonly');
        inputConsultaEstruturalPadrao.value = selecionadaContaUniao.conta;

        opemModel(true, false);
        let ateNivel = (new EstruturalPcasp(selecionadaContaUniao.conta)).estruturalAteNivel();
        buscarPlanoGoverno('UF', ateNivel);
    });

    const opemModel = (governo, ecidade) => {
        containerTabelaGoverno.style.display = 'none';
        containerTabelaEcidade.style.display = 'none';
        if (governo) {
            containerTabelaGoverno.style.display = '';
        }
        if (ecidade) {
            containerTabelaEcidade.style.display = '';
        }
        modalPesquisaPlanoContas.style.display = "flex";

    };

    const buscarPlanoGoverno = (tipoPlano, conta) => {
        opemModel(true, false);

        const formData = new FormData();
        formData.append('tipoPlano', tipoPlano);
        formData.append('exercicio', PHPSession.getValueSession('DB_anousu'));
        formData.append('apenasAnaliticas', 1);

        formData.append(cboTipoContaManutencao.value, 1);
        if (conta != undefined) {
            formData.append('conta', conta);
        }

        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routs.planoPcaspUniao}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
            tablePlano.bootstrapTable('load', response.data);
        });
    };
    /** *************************************************************************************************************
     *       FIM - Funções que interagem com a modal de pesquisa dos planos de contas padrões (União e Estado)
     * ***************************************************************************************************************
     */

    const setDadosFormulario = (dados) => {
        naturezaSaldo.value = dados.natureza;
        indicadorSuperavit.value = dados.indicador;

        if (dados.indicador === 'F/P') {
            cboIndicadorSuperavitFP.style.display = '';
        }

        inputNomeConta.value = dados.nome;
        inputFuncao.value = dados.funcao;
        cboTransferenciaSaldo.value = 'N';

        // essa lista é o inicio dos estruturais das contas contábeis
        let lista = [1, 2, 7, 8, 53, 63];
        if (lista.includes(dados.conta.substring(0, 2)) || lista.includes(dados.conta.substring(0, 1))) {
            cboTransferenciaSaldo.value = 'S';
        }

        setBaseEstrutural(dados.mascara, true);
    };

    const setBaseEstrutural = (estrutural, bloquearEstrutural) => {
        let nivel = (new EstruturalPcasp(estrutural)).getNivel();
        estruturalEcidade.setValue(estrutural);
        estruturalEcidade.bloquearAteNivel(nivel);
    };


    /**
     * Pesquisar os estruturais
     */
    pesquisarEstruturais.addEventListener('click', () => {
        let exercicio = PHPSession.getValueSession('DB_anousu');
        let conta = selecionadaContaEstado.conta

        const formData = new FormData();
        formData.append('estrutural', conta);
        formData.append('exercicio', exercicio);
        formData.append('tipoConta', 1);
        HttpClient.post(`${PHPSession.requestApi}/${routs.planoPcaspEcidade}`, {body: formData}).then(response => {
            tableEdicaoEstrutural.bootstrapTable('load', response.data);
            modalArvoreEstrutural.style.display = "flex";
        });
    });


    btnPesquisarConta.addEventListener('click', () => {
        labelTipoPlano.innerHTML = 'e-Cidade';
        opemModel(false, true);
    });

    document.getElementById('pesquisaContaEcidade').addEventListener('click', () => {

        let estrutural = document.getElementById('consultaEstrutural').value;
        let exercicio = PHPSession.getValueSession('DB_anousu');
        tablePlanoEcidade.bootstrapTable('load', []);

        const formData = new FormData();
        formData.append('exercicio', exercicio);
        formData.append('estrutural', estrutural);
        formData.append(cboTipoContaManutencao.value, 1);
        formData.append('comPlanosGoverno', 1);
        formData.append('comReduzidos', 1);
        formData.append('apenasAnaliticas', 1);
        PHPSession.appendFormData(formData);

        tablePlanoEcidade.bootstrapTable('load', []);
        HttpClient.post(`${PHPSession.requestApi}/${routs.planoPcaspEcidade}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }
            tablePlanoEcidade.bootstrapTable('load', response.data);
            btnNovaConta.style.display = '';
            abaReduzido.desbloquear();
        });
    });


    btnNovaConta.addEventListener('click', () => {
        btnNovaConta.style.display = 'none';
        limparDadosFormulario();
    })

    /** **************************************************************************************************************
     * *********************************************** ABA REDUZIDOS *************************************************
     * ***************************************************************************************************************
     */

    document.getElementById('novaContaBancaria').addEventListener('click', function (){
        modalContaBancaria.style.display = "flex";
    });

    /**
     *
     * @param index
     * @param row
     * @returns {string}
     */
    const detailReduzidos = (index, row) => {
        let detalhes = []
        detalhes.push([{label: "Recurso:", valor: row.recurso.descricao}])
        detalhes.push([
            {label: "Fonte Gestão:", valor: row.recurso.gestao},
            {label: "Fonte Siconfi:", valor: row.recurso.siconfi}
        ]);

        detalhes.push([{label: "Instituição:", valor: `${row.instituicao.codigo} - ${row.instituicao.nomeinst}`}]);

        if (isContaBancaria()) {
            detalhes.push([{label: "Conta Bancária:", valor: `${row.conta_bancaria.domicilio_bancario}`}]);
            detalhes.push([
                {label: "Convênio", valor: row.conta_bancaria.convenio},
                {label: "Cheque", valor: row.conta_bancaria.cheque}
            ]);
        }

        return detailFormaterTable.createDetail(detalhes, `Dados do reduzido`);
    };

    /**
     *
     * colocar informações da fonte de recurso, complemento e conta bancária no DETAILS
     *
     *
     * @type {jQuery|HTMLElement|*}
     */
    const tabelaReduzidos = jQuery('#tabelaReduzidos');
    tabelaReduzidos.bootstrapTable({
        locale: 'pt-BR',
        uniqueId: "conta",
        cache: false,
        height: 350,
        class: "table table-sm",
        detailFormatter: detailReduzidos,
        columns: [
            {
                "title": "Código",
                "field": 'c61_reduz',
                "align": 'left',
                "valign": 'middle',
                "width": "100"
            },
            {
                "title": "Recurso",
                "field": 'recurso.gestao',
                "align": 'center',
                "valign": 'middle',
                "width": "100"
            },
            {
                "title": "Instituição",
                "field": 'instituicao.nomeinst',
                "align": 'left',
                "valign": 'middle'
            },
            {
                "title": "Remover",
                "field": 'remover',
                "align": 'center',
                "valign": 'middle',
                "width": "100",
                events: window.operateEvents,
                formatter: (index, row) => {
                     if (row.em_uso) {
                        return '';
                    }
                    return [
                        '<a class="removerReduzido" href="javascript:void(0)" title="Excluir">',
                        '  <i class="fas fa-trash-alt"></i>',
                        '</a>'
                    ].join('')
                }
            }
        ]
    });

    var lookupRecurso = new DBLookUp(ancoraFonteRecurso, inputFonteGestao, inputNomeRecurso, {
        "sArquivo": "func_novosRecursos.php",
        "sObjetoLookUp": "db_iframe_orctiporec",
        "sLabel": "Pesquisar Recursos"
    });

    lookupRecurso.setCallBack('onClick', (dados) => {
        let codigo = dados[0];
        let excicio = PHPSession.getValueSession('DB_anousu');
        HttpClient.get(`${PHPSession.requestApi}/${routs.getRecurso}/${codigo}/${excicio}`).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }

            let recurso = response.data;

            inputFonteGestao.value = recurso.fonte_recurso.gestao;
            inputNomeRecurso.value = recurso.o15_descr;
            inputComplementoRecurso.value = recurso.complemento.descricao;

            // monta a estrutura do recurso
            recursoSelecionado = {
                codigo: recurso.o15_codigo,
                descricao: recurso.o15_descr,
                gestao: recurso.fonte_recurso.gestao,
                siconfi: recurso.fonte_recurso.codigo_siconfi,
                complemento: {
                    codigo: recurso.complemento.codigo,
                    descricao: recurso.complemento.descricao
                }
            };
        });
    });

    var lookupContaBancaria = new DBLookUp(ancoraContaBancaria, contaBancaria, descricaoContaBancaria, {
        "sArquivo": "func_contabancariacadastro.php",
        "sObjetoLookUp": "db_iframe_contabancaria",
        "sLabel": "Pesquisar ContaBancaria"
    });

    const validaAdicaoReduzidos = () => {
        let instituicao = getInstituicaoSelecionada();
        try {
            if (inputFonteGestao.value === '') {
                throw 'Selecione o recurso.';
            }

            if (!isContaBancaria()) {
                if (reduzidos.findIndex(reduzido => reduzido.c61_instit === instituicao.codigo) !== -1) {
                    throw `Já existe um reduzido adicionado para a instituição ${instituicao.nomeinst}.`;
                }
            }
            if (isContaBancaria()) {
                if (reduzidos.findIndex(reduzido => reduzido.c61_instit !== instituicao.codigo) !== -1) {
                    throw `Em contas bancárias você não pode adicionar reduzidos para instituições diferentes.`;
                }

                if (reduzidos.findIndex(reduzido => reduzido.c61_codigo === recursoSelecionado.codigo) !== -1) {
                    throw `Você não pode adicionar um reduzido com o mesmo recurso.`;
                }

                if (inputContaBancaria.value === '') {
                    throw 'Você deve informar o campo "Conta Bancária"'
                }
                if (inputConvenio.value === '') {
                    throw 'Você deve informar o campo "Convênio"'
                }
                if (inputCheque.value === '') {
                    throw 'Você deve informar o campo "Cheque"'
                }
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    };

    const getInstituicaoSelecionada = () => {
        return {
            "codigo": Number(cboInstituicao.value),
            "nomeinst": cboInstituicao.options[cboInstituicao.selectedIndex].innerHTML
        };
    };


    /**
     * Adiciona um novo reduzido na tabela de reduzidos para adiciona-lo na conta(conplano).
     */

    const validaAdicaoReduzido = () => {
        let instituicao = getInstituicaoSelecionada();
        try {
            if (cboTipoContaManutencao.value !== 'contasBancarias') {
                if (reduzidos.findIndex(reduzido => reduzido.c61_instit === instituicao.codigo) !== -1) {
                     throw `Já existe um reduzido adicionado para a instituição ${instituicao.nomeinst}.`;
                }
            }
            if (cboTipoContaManutencao.value === 'contasBancarias') {
                if (reduzidos.findIndex(reduzido => reduzido.c61_instit !== instituicao.codigo) !== -1) {
                    throw `Você não pode adicionar um reduzido para outra instituição.`;
                }

                if (reduzidos.findIndex(reduzido => reduzido.c61_codigo !== recursoSelecionado.codigo) !== -1) {
                    throw `Você não pode adicionar um reduzido com o mesmo recurso.`;
                }

            }
        } catch (e) {
            alert(e);
            return false;
        }
        return true;

    }
    btnAdicionarReduzido.addEventListener('click', () => {
        if (!validaAdicaoReduzidos()) {
            return;
        }

        let instituicao = getInstituicaoSelecionada();

        let conta_bancaria = null;
        if (isContaBancaria()) {
            conta_bancaria = {
                convenio : inputConvenio.value,
                cheque : inputCheque.value,
                domicilio_bancario : inputDescricaoContaBancaria.value,
                id_contabancaria : inputContaBancaria.value,
            }
        }

        let reduzido = {
            "c61_anousu": null,
            "c61_codcon": null,
            "c61_codigo": recursoSelecionado.codigo,
            "c61_contrapartida": null,
            "c61_instit": instituicao.codigo,
            "c61_reduz": null,
            "instituicao": getInstituicaoSelecionada(),
            "recurso": recursoSelecionado,
            "conta_bancaria" : conta_bancaria
        }

        reduzidos.push(reduzido);
        atualizaTabelaReduzidos(reduzidos);
    });

    /**
     * atualiza os reduzidos na tabela(grid)
     * @param reduzidos
     */
    const atualizaTabelaReduzidos = reduzidos => {
        tabelaReduzidos.bootstrapTable('load', reduzidos);
        limparFormularioReduzido();
    };

    /**
     * remove do array que armazena os reduzidos o reduzido que contém a instituição informada no parâmetro.
     * @param codigo da instituicao
     */
    const removeReduzidoTabela = codigo => {
        reduzidos.splice(reduzidos.findIndex(reduzido => reduzido.c61_instit === codigo), 1);
        atualizaTabelaReduzidos(reduzidos);
    }

    /** **************************************************************************************************************
     * ------------------------------------------- FIM ABA REDUZIDOS -------------------------------------------------
     * ***************************************************************************************************************
     */

    /**
     *
     * @returns {boolean}
     */
    const validaCamposObrigatorios = () => {
        try {
            if (inputNomeConta.value == '') {
                throw 'Informe o nome da conta.';
            }

            if (reduzidos.length === 0) {
                throw 'Informe ao menos um reduzido.';
            }
        } catch (e) {
            alert(e)
            return false;
        }
        return true;
    };

    /**
     *
     */
    btnSalvarConta.addEventListener('click', function () {

        if (!validaCamposObrigatorios()) {
            return null;
        }


        let exercicio = PHPSession.getValueSession('DB_anousu');
        const formData = new FormData();
        formData.append('exercicio', exercicio);
        formData.append('codcon', codigoConta);
        formData.append('contaUniao', selecionadaContaUniao.id);
        formData.append('contaEstado', selecionadaContaEstado.id);
        formData.append('indicadorSuperavit', selecionadaContaEstado.indicador);

        if (selecionadaContaEstado.indicador === 'F/P') {
            formData.append('indicadorSuperavit', indicadorSuperavitFP.value);
        }

        formData.append('nomeConta', inputNomeConta.value);
        formData.append('estrutural', estruturalEcidade.getEstrutural().estruturalSemMascara());
        formData.append('transferenciaSaldo', cboTransferenciaSaldo.value);
        formData.append('funcionamento', inputFuncionamento.value);
        formData.append('funcao', inputFuncao.value);

        let listaReduzidos = tabelaReduzidos.bootstrapTable('getData');
        let reduzidosSalvar = [];
        for (let reduzido of listaReduzidos) {
            let adicionarReduzido = {
                "codigoRecuso": reduzido.c61_codigo,
                "codigoInstituicao": reduzido.c61_instit,
                "reduzido": reduzido.c61_reduz
            };

            if (isContaBancaria()) {
                adicionarReduzido.convenio = reduzido.conta_bancaria.convenio;
                adicionarReduzido.cheque = reduzido.conta_bancaria.cheque;
                adicionarReduzido.id_contabancaria = reduzido.conta_bancaria.id_contabancaria;
            }

            reduzidosSalvar.push(adicionarReduzido);
        }

        PHPSession.appendFormData(formData);
        let rota = '';
        switch (cboTipoContaManutencao.value) {
            case 'contasCaixa':
                rota = routs.salvarContaCaixa;
                break;
            case 'contasBancarias':
                rota = routs.salvarContasBancarias;
                break;
            case 'contasExtrasOrcamentarias':
                rota = routs.salvarExtrasOrcamentarias;
                break;
            case 'outrasContas':
                rota = routs.salvarOutrasConta;
                break;
        }

        formData.append('reduzidos', JSON.stringify(reduzidosSalvar));
        HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.error) {
                return;
            }

            codigoConta = response.data.c60_codcon;
            reduzidos = response.data.reduzidos;
            atualizaTabelaReduzidos(reduzidos);
            liberaAbaCorrente();
        });
    });


    /** **************************************************************************************************************
     * ---------------------------------------------- Edição do estrutural -------------------------------------------
     * ***************************************************************************************************************
     */
    const colunaEdicaoEstrutural = (value, row, index, field) => {
        return `<db-estrutural estrutural="${row.mascara}" id="edicaoEstrutural_${row.c60_codigo}"></db-estrutural>`;
    };

    const tableEdicaoEstrutural = jQuery('#tableAlteracaoEstrutural');
    tableEdicaoEstrutural.bootstrapTable({
        locale: 'pt-BR',
        uniqueId: "conta",
        cache: false,
        height: 320,
        search: true,
        class: "table table-sm",
        columns: [
            {
                "title": "Conta",
                "field": 'mascara',
                "align": 'center',
                "valign": 'middle',
                "width": "150"
            },
            {
                "title": "Editar Conta",
                "field": 'conta_editada',
                "align": 'left',
                "valign": 'middle',
                "width": "300",
                formatter: colunaEdicaoEstrutural
            },
            {
                "title": "Nome",
                "field": 'c60_descr',
                "align": 'left',
                "valign": 'middle'
            },
            {
                "title": "ISF",
                "field": 'indicador',
                "align": 'center',
                "valign": 'middle',
                formatter: (value, row) => {
                    if (!row.analitica) {
                        return '-'
                    }
                    return row.c60_identificadorfinanceiro
                }
            },
            {
                "title": "Analítica",
                "field": 'analitica',
                "align": 'left',
                "valign": 'middle',
                formatter: (value) => {
                    return value ? 'Sim' : 'Não';
                }
            },
        ]
    });


    btnEditarEstruturais.addEventListener('click', () => {
        alertify.confirm(`Tem certeza que alterar os estruturais?`, (e) => {
            if (e) {
                let edicoes = [];
                let exercicio = PHPSession.getValueSession('DB_anousu');
                const formData = new FormData();
                formData.append('exercicio', exercicio);

                let registros = tableEdicaoEstrutural.bootstrapTable('getData');
                registros.map((conta) => {
                    let componente = document.getElementById(`edicaoEstrutural_${conta.c60_codigo}`);
                    edicoes.push({
                        codcon: conta.c60_codcon,
                        codigo: conta.c60_codigo,
                        estruturalAntigo: conta.c60_estrut,
                        estruturalNovo: componente.getEstrutural().estruturalSemMascara()
                    });
                });

                formData.append('contasEditar', JSON.stringify(edicoes));
                PHPSession.appendFormData(formData);

                let rota = `${PHPSession.requestApi}/${routs.estruturaisEdicoes}`;
                HttpClient.post(rota, {body: formData}).then(response => {
                    if (response.error) {
                        alert(response.message);
                        return;
                    }

                    tableEdicaoEstrutural.bootstrapTable('load', []);
                    fecharModalArvoreEstrutural.dispatchEvent(new Event('click'));
                });
            }
        });
    });

    /** **************************************************************************************************************
     * ---------------------------------------- FIM - Edição do estrutural -------------------------------------------
     * ***************************************************************************************************************
     */

    /** **************************************************************************************************************
     * ---------------------------------------- ABA CONTA CORRENTE --------------------------------------------------
     * ***************************************************************************************************************
     */

    const ancoraContaCorrente = document.getElementById('ancoraContaCorrente');
    const inputCodigoContaCorrente = document.getElementById('codigoContaCorrente');
    const inputNomeContaCorrente = document.getElementById('nomeContaCorrente');
    const btnAdicionarContaCorrente = document.getElementById('btnSalvarContaCorrente');

    const tabelaContasCorrente = jQuery('#tabelaContasCorrente');
    tabelaContasCorrente.bootstrapTable({
        locale: 'pt-BR',
        uniqueId: "conta",
        cache: false,
        height: 350,
        class: "table table-sm",
        detailFormatter: detailReduzidos,
        columns: [
            {
                "title": "Conta Corrente",
                "field": 'descricao',
                "align": 'left',
                "valign": 'middle'
            },
            {
                "title": "Remover",
                "field": 'remover',
                "align": 'center',
                "valign": 'middle',
                "width": "100",
                events: window.operateEvents,
                formatter: (index, row) => {
                    if (row.conta_usada) {
                        return '';
                    }
                    return [
                        '<a class="removerContaCorrente" href="javascript:void(0)" title="Excluir">',
                        '  <i class="fas fa-trash-alt"></i>',
                        '</a>'
                    ].join('')
                }
            }
        ]
    });

    var lookupContaCorrente = new DBLookUp(ancoraContaCorrente, inputCodigoContaCorrente, inputNomeContaCorrente, {
        "sArquivo": "func_conplanosistema.php",
        "sObjetoLookUp": "db_iframe_conplanosistema",
        "sLabel": "Pesquisar Conta Corrente",
        "aParametrosAdicionais": ['tipo=2']
    });

    const buscarContasCorrente = codcon => {
        let exercicio = PHPSession.getValueSession('DB_anousu');

        let url = `${PHPSession.requestApi}/${routs.buscarContaCorrente}/${codcon}/${exercicio}`;
        HttpClient.get(url).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }

            populaGridContasCorrentes(response.data);
        });
    };

    const populaGridContasCorrentes = (dados) => {
        let contas = dados.map(function (cc) {
            cc.descricao = `${cc.c122_sequencial} - ${cc.c122_descricao}`;
            return cc;
        });

        tabelaContasCorrente.bootstrapTable('load', contas);
    };

    btnAdicionarContaCorrente.addEventListener('click', () => {
        if (inputCodigoContaCorrente.value === '') {
            alert('Informe a conta corrente.');
            return;
        }

        executarContaCorrente(inputCodigoContaCorrente.value, routs.adicionarContaCorrente);
    });

    const executarContaCorrente = (contaCorrente, rota) => {
        let exercicio = PHPSession.getValueSession('DB_anousu');
        const formData = new FormData();
        formData.append('codcon', codigoConta);
        formData.append('exercicio', exercicio);
        formData.append('contaCorrente', contaCorrente);

        PHPSession.appendFormData(formData);
        HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
            if (response.error) {
                alert(response.message);
                return;
            }

            populaGridContasCorrentes(response.data);
        });
    };


    /**
     *
     */

    const limparFormularioConta = () => {
        cboIndicadorSuperavitFP.style.display = 'none';
        resetaVariaveisDeControle();
        inputPlanoUniao.value = '';
        inputDescricaoPlanoUniao.value = '';
        inputPlanoEstadual.value = '';
        inputDescricaoPlanoEstadual.value = '';
        cboNaturezaSaldo.value = '';
        cboIndicadorSuperavit.value = '';
        inputNomeConta.value = '';
        cboTransferenciaSaldo.value = '';
        inputFuncionamento.value = '';
        inputFuncao.value = '';
        estruturalEcidade.reset();
        tabelaReduzidos.bootstrapTable('load', []);
    };

    /**
     *
     */
    const limparFormularioReduzido = () => {
        recursoSelecionado = {};
        inputFonteGestao.value = '';
        inputNomeRecurso.value = '';
        inputComplementoRecurso.value = '';
        inputConvenio.value = '';
        inputCheque.value = 0;
        inputDescricaoContaBancaria.value = '';
        inputContaBancaria.value = '';
    };

    const limparDadosFormulario = () => {
        tabelaReduzidos.bootstrapTable('load', []);
        tabelaContasCorrente.bootstrapTable('load', []);
        limparFormularioReduzido();
        limparFormularioConta();
    };

    const isContaBancaria = () => cboTipoContaManutencao.value === 'contasBancarias';

    inputConvenio.addEventListener('input', (e) => {
        apenasNumero(e);
    })
    inputCheque.addEventListener('input', (e) => {
        apenasNumero(e);
    });
    const apenasNumero = (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    }
</script>
<?php db_menu(); ?>
</body>
</html>
