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
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
</head>
<body class="body-default">

<div id='ctnAbas'></div>
<button style="top: 3px; right: 15px; position: fixed" rel='ignore-css' class="retornar" type="button">
    <i class="fas fa-arrow-left"></i>
    Retornar
</button>

<div id="abaProgramaEstrategico">

    <div class="alert alert-primary text-left" role="alert">
        Clique em <kbd>Salvar</kbd> para criar/alterar o programa.<br>
    </div>

    <form id="formPrograma" class="container">
        <fieldset>
            <legend>Cadastre um programa estratégico</legend>
            <table class="form-container">
                <tr>
                    <td><label for="planejamento">Planejamento: </label></td>
                    <td><input type="text" class="readonly field-size-max" readonly id="planejamento"></td>
                </tr>
                <tr>
                    <td><label id="ancoraPrograma" for="programa"><a href="#">Programa:</a></label></td>
                    <td>
                        <input type="hidden" id="anoOrcamento" lang="o54_anousu">
                        <input type="text" id="programa" lang="o54_programa" class="field-size2">
                        <input type="text" id="descricao" lang="o54_descr" class="readonly field-size8 readonly"
                               readonly>
                    </td>
                </tr>
                <tr>
                    <td><label for="tipologia">Tipologia:</label></td>
                    <td>
                        <input type="text" id="tipologia" lang="db_tipologia" class="field-size-max readonly" readonly>
                    </td>
                </tr>
            </table>
            <fieldset id="fieldValoresPrograma" class="separator " style="display: none">
                <legend>Valores previstos</legend>
                <div id="containerValores"></div>
            </fieldset>
        </fieldset>

        <input type="hidden" id="codigoProgramaEstrategico" name="pl9_codigo">
        <button id="salvarPrograma" type="button">
            <i class="far fa-save"></i>
            Salvar
        </button>

        <button type="button" id="btnNovo">
            <i class="far fa-file"></i>
            Novo
        </button>
    </form>
</div>

<div id="abaOrgao" style="display: none">
    <div class="alert alert-primary text-left" role="alert">
        Informe os órgãos que compõem o programa estratégico e clique em <kbd>salvar</kbd>.
    </div>

    <form id="formOrgao" class="container">
        <div style="width: 650px;" id="ctnLancadorOrgao"></div>

        <button type="button" id="btnSalvarOrgao">
            <i class="far fa-save"></i>
            Salvar
        </button>
    </form>
</div>

<div id="abaObjetivo" style="display: none">
    <div class="alert alert-primary text-left" role="alert">
        Informe um ou mais objetivos que totalizem os valores anuais do programa e clique em <kbd>Salvar</kbd>.
    </div>
    <form id="formObjetivos" class="container">
        <fieldset>
            <legend>Objetivo</legend>
            <div id="fieldTotaisValoresObjetivo" style="display: none">
            <fieldset class="separator">
                <legend>Valores do programa</legend>
                <div id="linhaValoresPrograma"></div>
            </fieldset>
            <fieldset class="separator">
                <legend>Totais dos Objetivos cadastrados</legend>
                <div id="linhaValoresTotaisObjetivos"></div>
            </fieldset>
            </div>
            <fieldset class="separator">
                <legend>Dados do Objetivo</legend>
                <table class="form-container">
                    <tr>
                        <td><label for="numeroObjetivo">Código</label></td>
                        <td><input type="text" id="numeroObjetivo" name="pl11_numero" class="field-size2"></td>

                    </tr>
                    <tr>
                        <td colspan="2">
                            <fieldset class="separator">
                                <legend><label for="descricaoObjetivo">Descrição</label></legend>
                                <textarea rows="3" cols="100" id="descricaoObjetivo" name="pl11_descricao"></textarea>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <td><label id="ancoraOds" for="codigoOds"><a href="#">ODS:</a></label></td>
                        <td>
                            <input type="hidden" id="idOds" name="pl11_ods" lang="dl_id">
                            <input type="text" id="codigoOds" lang="pl26_codigo" class="field-size2">
                            <input type="text" id="descricaoOds" lang="pl26_descricao" class="field-size8 readonly"
                                   readonly>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="separator" id="fieldValoresObjetivo" style="display: none">
                <legend>Valores previstos</legend>
                <div id="containerValoresObjetivo"></div>
            </fieldset>
        </fieldset>
        <input type="hidden" id="codigoObjetivo" name="pl11_codigo">
        <button type="button" id="btnSalvarObjetivo">
            <i class="far fa-save"></i>
            Salvar
        </button>

    </form>
    <div class="container">
        <fieldset style="width: 1200px;">
            <legend>Objetivos cadastrados</legend>
            <table id="tableObjetivo"
                   class="table table-sm"
                   data-height="250"
                   data-virtual-scroll="true"
                   style="width: 100%;">
            </table>
        </fieldset>
    </div>
</div>

<div id="abaIndicadores" style="display: none">
    <div class="alert alert-primary text-left" role="alert">
        Clique em <kbd>Salvar</kbd> para criar/alterar os indicadores.<br>
    </div>

    <div class="container">
        <form action="" class="container" id="formIndicadores">
            <fieldset>
                <legend>Informe o Indicador</legend>
                <table class="form-container" style="text-align: left;">
                    <tr>
                        <td><label id="ancoraIndicador"><a href="#">Indicador:</a></label></td>
                        <td>
                            <input type="text" id="codigoIndicador" name="pl22_orcindica" lang="o10_indica"
                                   class="field-size2">
                            <input type="text" id="descricaoIndicador" lang="o10_descr" readonly
                                   class="readonly field-size8"/>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="anoIndicador">Ano:</label></td>
                        <td><input type="text" id="anoIndicador" name="pl22_ano" class="field-size2"
                                   maxlength="4" oninput="js_ValidaCampos(this, 1, 'Ano', 'f', 'f', event)"></td>
                    </tr>
                    <tr>
                        <td><label for="indiceIndicador">Índice:</label></td>
                        <td>
                            <input type="text" id="indiceIndicador" name="pl22_indice" class="field-size3"
                                   maxlength="8" oninput="js_ValidaCampos(this, 4, 'Índice','f', 'f', event)">
                        </td>
                    </tr>
                </table>

            </fieldset>
            <input type="hidden" value="" name="pl22_codigo" id="pl22_codigo">
            <button type="button" id="btnSalvarIndicador">
                <i class="far fa-save"></i>
                Salvar
            </button>
        </form>
        <fieldset style="margin-top: 20px;">
            <legend>Indicadores Salvos</legend>
            <table
                id="tableIndicadores"
                class="table table-sm"
                data-height="450"
                data-virtual-scroll="true"
                style="width: 100%;"
            >
            </table>
        </fieldset>
    </div>
</div>

<div id="modalMetaObjetivo" style="display: none">
    <form class="container">
        <fieldset>
            <legend>Metas do objetivo:</legend>
            <p class="text-left" id="labelObjetivo"></p><label id="labelObjetivo"></label>
            <fieldset class="separator">
                <legend>Meta</legend>
                <textarea rows="3" cols="100" id="meta" name="pl21_texto"></textarea>
            </fieldset>
            <fieldset id="fieldValoresMetas" class="separator" >
                <legend>Indicadores de Resultado</legend>
                <div id="containerValoresMetas"></div>
            </fieldset>
        </fieldset>
        <input type="hidden" id="codigoMeta" name="pl21_codigo">
        <input type="hidden" id="codigoObjetivoMeta" name="pl21_objetivosprogramaestrategico">
        <button type="button" id="btnSalvarMeta">
            <i class="far fa-save"></i>
            Salvar
        </button>
    </form>

    <fieldset style="width: 1000px;">
        <legend>Metas cadastradas</legend>
        <table id="tableMetasObjetivo"
               class="table table-sm"
               data-height="200"
               data-virtual-scroll="true"
               style="width: 100%;">
        </table>
    </fieldset>

</div>

<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>

<script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
<script type="text/javascript" src="scripts/classes/planejamento/valores.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>

<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<!-- requires bootstrap table -->
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>
<link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">

<script type="text/javascript">
    var lancadorOrgao = new DBLancador("lancadorOrgao");
    const btnNovo = document.getElementById('btnNovo');

    $.noConflict();
    jQuery(document).ready(function (jQuery) {
        const get = js_urlToObject();
        // rotas
        const routs = {
            plano: 'financeiro/planejamento/consulta/plano',
            configuracao: 'financeiro/planejamento/configuracao',
            programa: {
                show: 'financeiro/planejamento/programas-estrategico',
                salvar: 'financeiro/planejamento/programas-estrategico/salvar'
            },
            orgao: {
                salvar: 'financeiro/planejamento/orgao-programa/salvar'
            },
            objetivo: {
                salvar: 'financeiro/planejamento/objetivo-programa/salvar',
                remover: 'financeiro/planejamento/objetivo-programa/remover'
            },
            metas: {
                salvar: 'financeiro/planejamento/meta-objetivo/salvar',
                remover: 'financeiro/planejamento/meta-objetivo/remover'
            },
            indicadores: {
                salvar: 'financeiro/planejamento/indicador-programa/salvar',
                remover: 'financeiro/planejamento/indicador-programa/remover'
            }
        }

        // Abas
        const cntAbaProgramaEstrategico = document.getElementById('abaProgramaEstrategico')
        const cntAbaOrgao = document.getElementById('abaOrgao')
        const cntAbaObjetivo = document.getElementById('abaObjetivo')
        const cntAbaIndicadores = document.getElementById('abaIndicadores')

        const ctnAbas = new DBAbas(document.getElementById('ctnAbas'));
        const abaProgramaEstrategico = ctnAbas.adicionarAba("Programa Estratégico", cntAbaProgramaEstrategico);
        const abaOrgao = ctnAbas.adicionarAba("Órgão", cntAbaOrgao);
        const abaObjetivo = ctnAbas.adicionarAba("Objetivo", cntAbaObjetivo);
        const abaIndicadores = ctnAbas.adicionarAba("Indicadores", cntAbaIndicadores);

        const valoresPrograma = new Valores();

        /**
         * Variáveis globais
         */
        var programaEstrategico = {};
        var plano = {}
        var configuracao = {}

        // -------------------- inputs aba programa estratégico -------------------------
        const ctnPrograma = {
            form: document.getElementById('formPrograma'),
            planejamento: document.getElementById('planejamento'),
            ancora: document.getElementById('ancoraPrograma'),
            tipologia: document.getElementById('tipologia'),
            anoOrcamento: document.getElementById('anoOrcamento'),
            programa: document.getElementById('programa'),
            descricao: document.getElementById('descricao'),
            ctnValores: document.getElementById('containerValores'),
            fieldValoresPrograma: document.getElementById('fieldValoresPrograma'),
            codigo: document.getElementById('codigoProgramaEstrategico'),
            salvar: document.getElementById('salvarPrograma'),
        }

        const lookUpPrograma = new DBLookUp(ctnPrograma.ancora, ctnPrograma.programa, ctnPrograma.descricao, {
            'sArquivo': 'func_orcprograma.php',
            'sLabel': 'Pesquisar Programa',
            'sObjetoLookUp': "db_iframe_orcprograma",
            'aCamposAdicionais': ['db_tipologia']
        });
        lookUpPrograma.setCallBack('onClick', retorno => {
            ctnPrograma.tipologia.value = retorno[2];
        });
        lookUpPrograma.setCallBack('onChange', (erro, retorno) => {
            ctnPrograma.tipologia.value = '';
            if (!erro) {
                ctnPrograma.tipologia.value = retorno[2];
            }
        });


        // -------------------- inputs aba orgão -------------------------
        const ctnOrgao = {
            salvar: document.getElementById('btnSalvarOrgao'),
            ctnLancador: document.getElementById('ctnLancadorOrgao'),
        }

        lancadorOrgao.iGridHeight = 180;
        lancadorOrgao.sTextoFieldset = 'Órgão(s)';
        lancadorOrgao.selecionarAposPesquisar = true;
        lancadorOrgao.setLabelAncora("Órgão:");
        lancadorOrgao.setNomeInstancia("lancadorOrgao");
        lancadorOrgao.setHabilitado(true);
        lancadorOrgao.setParametrosPesquisa("func_orcorgao.php", ["o40_orgao", "o40_descr"]);
        lancadorOrgao.show(document.getElementById('ctnLancadorOrgao'));

        // -------------------- inputs aba objetivo ---------------------
        const ctnObjetivo = {
            form: document.getElementById('formObjetivos'),
            linhaValoresPrograma: document.getElementById('linhaValoresPrograma'),
            linhaValoresTotaisObjetivos: document.getElementById('linhaValoresTotaisObjetivos'),
            codigo: document.getElementById('codigoObjetivo'),
            numero: document.getElementById('numeroObjetivo'),
            descricao: document.getElementById('descricaoObjetivo'),
            ancora: document.getElementById('ancoraOds'),
            idOds: document.getElementById('idOds'),
            codigoOds: document.getElementById('codigoOds'),
            descricaoOds: document.getElementById('descricaoOds'),
            ctnValores: document.getElementById('containerValoresObjetivo'),
            salvar: document.getElementById('btnSalvarObjetivo'),

            fieldTotaisValoresObjetivo: document.getElementById('fieldTotaisValoresObjetivo'),
            fieldValoresObjetivo: document.getElementById('fieldValoresObjetivo'),
            valores: new Valores(),
            totalValoresPrograma: new Valores(),
            totalValoresObjetivo: new Valores(),
            table: jQuery('#tableObjetivo')
        }

        const lookUpOds = new DBLookUp(ctnObjetivo.ancora, ctnObjetivo.codigoOds, ctnObjetivo.descricaoOds, {
            'sArquivo': 'func_ods.php',
            'sLabel': 'Pesquisar os Objetivos de Desenvolvimento Sustentável (ODS)',
            'sObjetoLookUp': "db_iframe_orcprograma",
            'aCamposAdicionais': ['db_id']
        });

        const preencheFormOds = (codigo, label, id) => {
            ctnObjetivo.idOds.value = id;
            ctnObjetivo.codigoOds.value = codigo;
            ctnObjetivo.descricaoOds.value = label;
        };

        lookUpOds.setCallBack('onClick', (retorno) => {
            preencheFormOds(retorno[0], retorno[1], retorno[2]);
        });

        lookUpOds.setCallBack('onChange', (erro, retorno) => {
            if (erro) {
                preencheFormOds('', retorno[0], '');
                return;
            }

            preencheFormOds(ctnObjetivo.codigoOds.value, retorno[0], retorno[2]);
        });

        const fechaModal = (modal) => {
            if (!!modal.oDBMask) {
                modal.oDBMask.destroy();
            }
            modal.hide();
        }

        const ctnMetaObjetivo = {
            modal: document.getElementById('modalMetaObjetivo'),
            label: document.getElementById('labelObjetivo'),
            meta: document.getElementById('meta'),
            codigo: document.getElementById('codigoMeta'),
            codigoObjetivo: document.getElementById('codigoObjetivoMeta'),
            ctnValoresMetas: document.getElementById('containerValoresMetas'),
            valores: new Valores(),
            salvar: document.getElementById('btnSalvarMeta'),
            table: jQuery('#tableMetasObjetivo')
        };

        const windowMeta = new windowAux('windowMeta', 'Meta do Objetivos', 1000, 800);
        windowMeta.setContent(ctnMetaObjetivo.modal);
        windowMeta.setShutDownFunction(() => {
            limpaFormMetaObjetivo();
            fechaModal(windowMeta)
        });

        /**
         * Dispara ao carregar a janela
         */
        PHPSession.loadData().then(() => {
            let urlNovo = 'pla4_programaestrategicomanutencao.php?';
            criaTabelaObjetivos();
            criaTabelaIndicadores();
            buscaConfiguracao();
            /**
             * Quando clicado para adicionar um programa novo
             */
            if (get.planejamento && !get.codigo) {
                urlNovo += `planejamento=${get.planejamento}`;
                HttpClient.get(`${PHPSession.requestApi}/${routs.plano}/${get.planejamento}`).then(response => {
                    plano = response.data;
                    ctnPrograma.planejamento.value = plano.pl2_titulo;
                    valoresPrograma.criaInputValores(ctnPrograma.ctnValores, plano);
                    ctnObjetivo.valores.criaInputValores(ctnObjetivo.ctnValores, plano);
                    lookUpPrograma.setParametrosAdicionais(['previsao=', `ano=${plano.pl2_ano_inicial}`]);
                });
            }

            /**
             * Quando clicado para editar o programa
             */
            if (get.codigo) {
                HttpClient.get(`${PHPSession.requestApi}/${routs.programa.show}/${get.codigo}`).then(response => {
                    programaEstrategico = response.data;
                    plano = programaEstrategico.planejamento;
                    valoresPrograma.criaInputValores(ctnPrograma.ctnValores, plano);

                    ctnPrograma.planejamento.value = plano.pl2_titulo;
                    ctnPrograma.codigo.value = programaEstrategico.pl9_codigo;
                    ctnPrograma.anoOrcamento.value = programaEstrategico.pl9_anoorcamento;
                    ctnPrograma.programa.value = programaEstrategico.programa;
                    ctnPrograma.descricao.value = programaEstrategico.descricao;
                    ctnPrograma.tipologia.value = programaEstrategico.tipologia;

                    // desabilita campos que não podem ser alterados
                    ctnPrograma.planejamento.setAttribute('disabled', 'disabled');
                    ctnPrograma.programa.setAttribute('disabled', 'disabled');
                    ctnPrograma.programa.classname += 'readonly';
                    ancoraPrograma.onclick = () => {
                        return false
                    };

                    for (let valor of programaEstrategico.valores) {
                        valoresPrograma.set(valor.pl10_ano, valor.pl10_valor);
                    }

                    urlNovo += `planejamento=${plano.pl2_codigo}`;
                    carregarDadosVinculadosAoPrograma();
                });
            }

            btnNovo.addEventListener('click', () => {
                location.href = urlNovo;
            });

            // remove os display none das abas
            cntAbaOrgao.style.display = '';
            cntAbaObjetivo.style.display = '';
            cntAbaIndicadores.style.display = '';
        });

        const buscaConfiguracao = () => {
            HttpClient.get(`${PHPSession.requestApi}/${routs.configuracao}`).then(response => {
                configuracao = response.data;
                if (!configuracao.apenas_valor_analitico) {
                    ctnPrograma.fieldValoresPrograma.style.display = '';
                    ctnObjetivo.fieldTotaisValoresObjetivo.style.display = '';
                    ctnObjetivo.fieldValoresObjetivo.style.display = '';
                }
            });
        };

        const validaValores = (valores) => {

            if (configuracao.apenas_valor_analitico) {
                return true;
            }


            if (valores.existeValoresNaoInformados() > 0) {
                alert("Você deve informar um valor positivo para todos os exercícios.");
                return false;
            }
            return true;
        }
        /**
         * Salva os dados do Programa Estratégico.
         */
        ctnPrograma.salvar.addEventListener('click', () => {

            let valoresInformados = valoresPrograma.getValores();
            if (!validaValores(valoresPrograma)) {
                return;
            }


            const formData = new FormData();
            formData.append('pl9_codigo', ctnPrograma.codigo.value);
            formData.append('pl9_planejamento', plano.pl2_codigo);
            formData.append('pl9_anoorcamento', ctnPrograma.anoOrcamento.value);
            formData.append('pl9_orcprograma', ctnPrograma.programa.value);

            formData.append('valores', JSON.stringify(valoresInformados));
            PHPSession.appendFormData(formData);

            const parametros = {
                body: formData,
                reportMessage: `Aguarde, salvando programa estratégico.`
            }
            HttpClient.post(`${PHPSession.requestApi}/${routs.programa.salvar}`, parametros).then(response => {

                alert(response.message);
                if (response.error) {
                    return;
                }

                programaEstrategico = response.data;
                carregarDadosVinculadosAoPrograma();

                abaProgramaEstrategico.setVisibilidade(false);
                abaOrgao.setVisibilidade(true);
            });
        });

        const carregarDadosVinculadosAoPrograma = () => {

            carregaOrgaosRegistros();

            criaElementosObjetivos();

            carregarDadosObjetivos();

            carregarDadosIndicadores();

        }

        const carregaOrgaosRegistros = () => {
            let orgaos = programaEstrategico.orgaos.map(orgao => {
                return [orgao.pl27_orcorgao, orgao.descricao]
            });
            lancadorOrgao.carregarRegistros(orgaos);
        }

        /**
         * Salva os órgãos selecionados no programa
         */
        ctnOrgao.salvar.addEventListener('click', () => {

            if (lancadorOrgao.getRegistros().length === 0) {
                alert('Informe ao menos um CGM se deseja criar uma comissão.');
                return;
            }

            const formData = new FormData();
            formData.append('pl9_codigo', programaEstrategico.pl9_codigo);

            lancadorOrgao.getRegistros().map(orgao => {
                formData.append('orgaos[]', orgao.sCodigo);
            });

            PHPSession.appendFormData(formData);

            const parametros = {
                body: formData,
                reportMessage: `Aguarde, vinculando orgãos selecionado ao programa estratégico.`
            }

            HttpClient.post(`${PHPSession.requestApi}/${routs.orgao.salvar}`, parametros).then(response => {

                alert(response.message);
                if (response.error) {
                    return;
                }

                programaEstrategico.orgaos = response.data;
                abaOrgao.setVisibilidade(false);
                abaObjetivo.setVisibilidade(true);
            });
        });


        /**
         *  --------------------- FUNÇÕES DO OBJETIVO -----------------------
         */

        const criaElementosObjetivos = () => {
            const configuracaoValores = {
                container: {"tableCell": true},
                input: {"readOnly": true},
            }

            ctnObjetivo.valores.criaInputValores(ctnObjetivo.ctnValores, plano);
            ctnObjetivo.totalValoresPrograma.criaInputValores(ctnObjetivo.linhaValoresPrograma, plano, configuracaoValores);
            ctnObjetivo.totalValoresObjetivo.criaInputValores(ctnObjetivo.linhaValoresTotaisObjetivos, plano, configuracaoValores);
        };

        const carregarDadosObjetivos = () => {

            programaEstrategico.valores.map(valor => {
                ctnObjetivo.totalValoresPrograma.set(valor.pl10_ano, valor.pl10_valor);
            });

            ctnObjetivo.table.bootstrapTable('load', programaEstrategico.objetivos);

            atualizaTotaisObjetivo(calculaTotaisObjetivos());
        }

        const formatterActions = (value, row, index) => {
            return [
                '<a class="alterar" href="javascript:void(0)" title="Alterar">',
                '  <i class="fa fa-edit"></i>',
                '</a>',
                '&nbsp;&nbsp;',
                '<a class="excluir" href="javascript:void(0)" title="Excluir">',
                '  <i class="fas fa-trash-alt"></i>',
                '</a>'
            ];
        };

        const formatterActionsObjetivo = (value, row, index) => {

            return formatterActions(value, row, index).concat([
                '&nbsp;&nbsp;',
                '<a class="adicionaMetaObjetivo" href="javascript:void(0)" title="Adicionar Meta">',
                '  <i class="fas fa-list-ul"></i>',
                '</a>'
            ]).join('');
        };

        const criaTabelaObjetivos = () => {

            const colunas = [
                {
                    title: 'Código',
                    field: 'pl11_numero',
                    align: 'center',
                    valign: 'middle',
                    sortable: true,
                    width: '100'
                }, {
                    title: 'Descrição',
                    field: 'pl11_descricao',
                    align: 'left',
                    valign: 'center',
                    sortable: true,
                    formatter: (value) => {
                        return `<div style="width: 600px; height: 20px;"><p style="margin: 0" class="elipse" title="${value}">${value}</p></div>`;
                    }
                }, {
                    title: 'ODS',
                    field: 'ods.pl26_descricao',
                    align: 'left',
                    valign: 'center',
                    sortable: true,
                    formatter: (value) => {
                        if (value == null) {
                            value = '';
                        }
                        return `<div style="width: 300px; height: 20px;"><p style="margin: 0" class="elipse" title="${value}">${value}</p></div>`;
                    }
                }, {
                    title: 'Ações',
                    field: 'acoes',
                    align: 'center',
                    valign: 'center',
                    width: '100',
                    events: window.operateEvents,
                    formatter: formatterActionsObjetivo()
                }
            ];

            ctnObjetivo.table.bootstrapTable({
                columns: colunas,
                uniqueId: "pl11_codigo",
                locale: 'pt-BR',
                cache: false,
                height: 500,
                pagination: true,
                pageSize: 10,
                pageList: [10, 25, 50, 100, 200, 'All'],
                search: true,
                showButtonText: true,
                class: "table table-sm"
            });
        }

        const validaCamposObrigatoriosObjetivo = () => {
            if (ctnObjetivo.numero.value == '') {
                alert('Você deve informar o "Código" do objetivo');
                return false;
            }
            if (ctnObjetivo.descricao.value == '') {
                alert('Você deve informar a "Descrição" do objetivo');
                return false;
            }

            let valoresInformados = ctnObjetivo.valores.getValores();
            if (!validaValores(ctnObjetivo.valores)) {
                return false;
            }

            for (let valor of valoresInformados) {
                let totalObjetivo = ctnObjetivo.totalValoresObjetivo.getValor(valor.ano);
                let totalPrograma = ctnObjetivo.totalValoresPrograma.getValor(valor.ano);
                let resto = totalPrograma - totalObjetivo;

                if (valor.valor > resto) {
                    let vlr = formataValor(valor.valor);
                    alert(`Você não pode incluir um objetivo no valor ${vlr} para o ano de ${valor.ano} pois irá ultrapassar o total do programa.`);
                    return false;
                }
            }

            return true;
        }

        ctnObjetivo.salvar.addEventListener('click', () => {

            let valoresInformados = ctnObjetivo.valores.getValores();
            if (!validaCamposObrigatoriosObjetivo()) {
                return;
            }

            const formData = new FormData(ctnObjetivo.form);
            formData.append('pl9_codigo', programaEstrategico.pl9_codigo);
            formData.append('pl11_codigo', ctnObjetivo.codigo.value);
            formData.append('pl11_ods', ctnObjetivo.idOds.value);
            formData.append('valores', JSON.stringify(valoresInformados));

            PHPSession.appendFormData(formData);

            const parametros = {
                body: formData,
                reportMessage: `Aguarde, salvando objetivo.`
            }
            HttpClient.post(`${PHPSession.requestApi}/${routs.objetivo.salvar}`, parametros).then(response => {

                alert(response.message);
                if (response.error) {
                    return;
                }

                if (ctnObjetivo.codigo.value == '') {
                    programaEstrategico.objetivos.push(response.data);
                } else {
                    let codigo = ctnObjetivo.codigo.value;
                    let index = programaEstrategico.objetivos.findIndex(obj => obj.pl11_codigo == codigo);
                    programaEstrategico.objetivos[index] = response.data;
                }

                limparCamposObjetivo();
                carregarDadosObjetivos();

                ctnObjetivo.valores.reset();
            });
        });

        const calculaTotaisObjetivos = () => {
            let totalizares = ctnObjetivo.totalValoresObjetivo.getValores();
            for (let totalizador of totalizares) {
                totalizador.valor = 0;
            }

            for (let objetivo of programaEstrategico.objetivos) {
                for (let valoresObjetivo of objetivo.valores) {
                    for (let valorTotalizador of totalizares) {
                        if (valorTotalizador.ano == valoresObjetivo.pl10_ano) {
                            valorTotalizador.valor += Number(valoresObjetivo.pl10_valor);
                        }
                    }
                }
            }

            return totalizares;
        };

        const atualizaTotaisObjetivo = (totalizares) => {
            totalizares.map(valor => {
                ctnObjetivo.totalValoresObjetivo.set(valor.ano, valor.valor);
            });
        };

        const limparCamposObjetivo = () => {
            ctnObjetivo.codigo.value = '';
            ctnObjetivo.numero.value = '';
            ctnObjetivo.idOds.value = '';
            ctnObjetivo.codigoOds.value = '';
            ctnObjetivo.descricao.value = '';
            ctnObjetivo.descricaoOds.value = '';
        };

        window.operateEvents = {
            'click .alterar': function (e, value, row, index) {
                ctnObjetivo.codigo.value = row.pl11_codigo;
                ctnObjetivo.descricao.value = row.pl11_descricao;
                ctnObjetivo.numero.value = row.pl11_numero;

                if (row.pl11_ods) {
                    ctnObjetivo.idOds.value = row.pl11_ods;
                    ctnObjetivo.codigoOds.value = row?.ods?.pl26_codigo || '';
                    ctnObjetivo.descricaoOds.value = row?.ods?.pl26_descricao || '';
                }

                atualizaTotaisObjetivo(calculaTotaisObjetivos());
                /**
                 * Diminui o valor dos objetivos cadastrados.
                 */
                row.valores.map(valor => {
                    let valorAtualizado = ctnObjetivo.totalValoresObjetivo.getValor(valor.pl10_ano) - valor.pl10_valor;
                    ctnObjetivo.totalValoresObjetivo.set(valor.pl10_ano, valorAtualizado);

                    ctnObjetivo.valores.set(valor.pl10_ano, valor.pl10_valor)
                });
            },
            'click .excluir': function (e, value, row, index) {
                alertify.confirm(`Tem certeza que deseja excluir o Objetivo?`, (e) => {
                    if (e) {
                        const formData = new FormData;
                        formData.append('pl11_codigo', row.pl11_codigo);
                        PHPSession.appendFormData(formData);

                        const parametros = {
                            body: formData,
                            reportMessage: `Aguarde, excluindo objetivo do programa estratégico.`
                        }

                        HttpClient.post(`${PHPSession.requestApi}/${routs.objetivo.remover}`, parametros).then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }

                            programaEstrategico.objetivos.splice(
                                programaEstrategico.objetivos.findIndex(obj => obj.pl11_codigo == row.pl11_codigo), 1
                            );

                            carregarDadosObjetivos();
                        });
                    }
                });
            },
            'click .adicionaMetaObjetivo': function (e, value, row, index) {
                ctnMetaObjetivo.codigoObjetivo.value = row.pl11_codigo;
                ctnMetaObjetivo.label.innerHTML = `${row.pl11_numero} - ${row.pl11_descricao}`;
                ctnMetaObjetivo.modal.style.display = '';

                ctnMetaObjetivo.table.bootstrapTable('load', row.metas);

                windowMeta.show(0, 0, true);
                let display = {container: {"tableCell": true}};
                ctnMetaObjetivo.valores.criaInputValores(ctnMetaObjetivo.ctnValoresMetas, plano, display);
            }
        };

        /**
         *  --------------------- FUNÇÕES DAS METAS DO OBJETIVO -----------------------
         */

        const formatterActionsMeta = (value, row, index) => {
            return formatterActions(value, row, index).join('');
        };

        window.eventsMeta = {
            'click .alterar': function (e, value, row, index) {
                ctnMetaObjetivo.meta.value = row.pl21_texto;
                ctnMetaObjetivo.codigo.value = row.pl21_codigo;
                ctnMetaObjetivo.valores.reset();
                if (row.valores.length) {
                    row.valores.map(valor => ctnMetaObjetivo.valores.set(valor.pl10_ano, valor.pl10_valor));
                }
            },
            'click .excluir': function (e, value, row, index) {
                alertify.confirm(`Tem certeza que deseja excluir a Meta?`, (e) => {
                    if (e) {
                        const formData = new FormData;
                        formData.append('pl21_codigo', row.pl21_codigo);
                        PHPSession.appendFormData(formData);
                        const parametros = {
                            body: formData,
                            reportMessage: `Aguarde, excluindo meta do objetivo.`
                        }

                        HttpClient.post(`${PHPSession.requestApi}/${routs.metas.remover}`, parametros).then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }

                            let objetivo = findObjetivoInPrograma(ctnMetaObjetivo.codigoObjetivo.value)

                            objetivo.metas.splice(
                                objetivo.metas.findIndex(obj => obj.pl21_codigo == row.pl21_codigo), 1
                            );

                            ctnMetaObjetivo.table.bootstrapTable('load', objetivo.metas);
                        });
                    }
                });
            }
        };

        ctnMetaObjetivo.table.bootstrapTable({
            locale: 'pt-BR',
            columns: [
                {
                    title: "Meta",
                    field: 'pl21_texto',
                    align: 'left',
                    valign: 'middle',
                    formatter: (value) => {
                        return `<div style="width: 770px"><div class="elipse" title="${value}">${value}</div></div>`;
                    }
                }, {
                    title: "Ações",
                    field: 'acoes',
                    align: 'center',
                    valign: 'middle',
                    width: '130',
                    events: window.eventsMeta,
                    formatter: formatterActionsMeta
                }
            ]
        });

        ctnMetaObjetivo.salvar.addEventListener('click', () => {
            if (ctnMetaObjetivo.meta.value == '') {
                alert('Informe a meta do objetivo para salvar.');
                return;
            }

            const formData = new FormData;
            let codigoMeta = ctnMetaObjetivo.codigo.value;
            formData.append('pl21_codigo', ctnMetaObjetivo.codigo.value);
            formData.append('pl21_objetivosprogramaestrategico', ctnMetaObjetivo.codigoObjetivo.value);
            formData.append('pl21_texto', ctnMetaObjetivo.meta.value);
            formData.append('valores', JSON.stringify(ctnMetaObjetivo.valores.getValores()));

            PHPSession.appendFormData(formData);
            const parametros = {
                body: formData,
                reportMessage: `Aguarde, salvando meta do objetivo.`
            }

            HttpClient.post(`${PHPSession.requestApi}/${routs.metas.salvar}`, parametros).then(response => {
                alert(response.message);
                if (response.error) {
                    return;
                }

                let objetivo = findObjetivoInPrograma(ctnMetaObjetivo.codigoObjetivo.value)

                if (codigoMeta == '') {
                    objetivo.metas.push(response.data);
                } else {
                    let index = objetivo.metas.findIndex(obj => obj.pl21_codigo == codigoMeta);
                    objetivo.metas[index] = response.data;
                }

                ctnMetaObjetivo.table.bootstrapTable('load', objetivo.metas);

                // limpa o formulário
                limpaFormMetaObjetivo();
            });
        });

        const limpaFormMetaObjetivo = () => {
            ctnMetaObjetivo.codigo.value = '';
            ctnMetaObjetivo.meta.value = '';
            ctnMetaObjetivo.valores.reset();
        };

        const findObjetivoInPrograma = (codigoObjetivo) => {
            let indexObjetivo = programaEstrategico.objetivos.findIndex(obj => obj.pl11_codigo == codigoObjetivo);
            return programaEstrategico.objetivos[indexObjetivo];
        };

        /**
         * Botão de retorno
         */
        document.querySelectorAll('.retornar').forEach(el => el.addEventListener('click', event => {
            location.href = `pla4_programaestrategico.php?planejamento=${get.planejamento}`;
        }));

        const formataValor = (value) => {
            let numObj = parseFloat(value);

            return numObj.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        };
        /**
         * --------------------- Inputs aba INDICADORES ---------------------
         */
        const ctnIndicadores = {
            form: document.getElementById('formIndicadores'),
            codigoIndicador: document.getElementById('codigoIndicador'),
            descricao: document.getElementById('descricaoIndicador'),
            pl22_codigo: document.getElementById('pl22_codigo'),
            ano: document.getElementById('anoIndicador'),
            indice: document.getElementById('indiceIndicador'),
            ancora: document.getElementById('ancoraIndicador'),
            salvar: document.getElementById('btnSalvarIndicador'),
            table: jQuery('#tableIndicadores')
        }
        /**
         * --------------------- FUNÇÕES INDICADORES ---------------------
         */
        const carregarDadosIndicadores = () => {
            ctnIndicadores.table.bootstrapTable('load', programaEstrategico.indicadores);
        }
        const lookUpIndicador = new DBLookUp(ctnIndicadores.ancora, ctnIndicadores.codigoIndicador, ctnIndicadores.descricao, {
            'sArquivo': 'func_orcindica.php',
            'sLabel': 'Pesquisar Indicador',
            'sObjetoLookUp': "db_iframe_orcindica"
        });

        const formatterActionsIndicadores = (value, row, index) => {
            return formatterActions(value, row, index).join('');
        }
        const criaTabelaIndicadores = () => {
            const colunasIndicadores = [
                {
                    title: 'Descrição',
                    field: 'indicador.o10_descr',
                    align: 'left',
                    valign: 'center',
                    sortable: true
                },
                {
                    title: 'Ano',
                    field: 'pl22_ano',
                    align: 'center',
                    valign: 'middle',
                    sortable: true
                }, {
                    title: 'Ações',
                    field: 'acoes',
                    align: 'center',
                    valign: 'center',
                    events: window.operateEventsIndicadores,
                    formatter: formatterActionsIndicadores
                }
            ];

            ctnIndicadores.table.bootstrapTable({
                columns: colunasIndicadores,
                uniqueId: "pl22_orcindica",
                locale: 'pt-BR',
                cache: false,
                height: 500,
                pagination: true,
                pageSize: 10,
                pageList: [10, 25, 50, 100, 200, 'All'],
                search: true,
                showButtonText: true,
                class: "table table-sm"
            });

        }
        const validaCamposObrigatoriosIndicador = () => {
            if (ctnIndicadores.codigoIndicador.value == '') {
                alert('Você deve informar o "Código" do indicador.');
                return false;
            }

            const ano = ctnIndicadores.ano.value;
            if (ano == '') {
                alert('Você deve informar o "Ano" do indicador.');
                return false;
            }
            if (ano.length !== 4) {
                alert(`O "Ano" informado deve ser um ano válido`);
                return false;
            }
            if (ctnIndicadores.indice.value == '') {
                alert('Você deve informar o "Índice" do indicador.');
                return false;
            }

            return true;
        }
        const limparCamposIndicadores = () => {
            ctnIndicadores.codigoIndicador.value = '';
            ctnIndicadores.descricao.value = '';
            ctnIndicadores.pl22_codigo.value = '';
            ctnIndicadores.ano.value = '';
            ctnIndicadores.indice.value = '';
        };

        ctnIndicadores.salvar.addEventListener('click', () => {

            if (!validaCamposObrigatoriosIndicador()) {
                return;
            }

            const formData = new FormData(ctnIndicadores.form);
            formData.append('pl9_codigo', programaEstrategico.pl9_codigo);
            formData.append('pl22_codigo', ctnIndicadores.pl22_codigo.value);
            formData.append('pl22_indice', ctnIndicadores.indice.value);
            formData.append('pl22_orcindica', ctnIndicadores.codigoIndicador.value);
            formData.append('pl22_ano', ctnIndicadores.ano.value);

            PHPSession.appendFormData(formData);

            const parametros = {
                body: formData,
                reportMessage: `Aguarde, salvando indicador.`
            }
            HttpClient.post(`${PHPSession.requestApi}/${routs.indicadores.salvar}`, parametros).then(response => {

                alert(response.message);
                if (response.error) {
                    return;
                }

                if (ctnIndicadores.pl22_codigo.value == '') {
                    programaEstrategico.indicadores.push(response.data);
                } else {
                    let codigo = ctnIndicadores.pl22_codigo.value;
                    let index = programaEstrategico.indicadores.findIndex(ind => ind.pl22_codigo == codigo);
                    programaEstrategico.indicadores[index] = response.data;
                }

                limparCamposIndicadores();
                carregarDadosIndicadores();
            });
        });

        window.operateEventsIndicadores = {
            'click .alterar': function (e, value, row, index) {
                ctnIndicadores.pl22_codigo.value = row.pl22_codigo;
                ctnIndicadores.descricao.value = row.indicador.o10_descr;
                ctnIndicadores.codigoIndicador.value = row.pl22_orcindica;
                ctnIndicadores.ano.value = row.pl22_ano;
                ctnIndicadores.indice.value = row.pl22_indice;
            },
            'click .excluir': function (e, value, row, index) {
                alertify.confirm(`Tem certeza que deseja excluir o Indicador?`, (e) => {
                    if (e) {
                        const formData = new FormData;
                        formData.append('pl22_codigo', row.pl22_codigo);
                        PHPSession.appendFormData(formData);

                        const parametros = {
                            body: formData,
                            reportMessage: `Aguarde, excluindo indicador do programa estratégico.`
                        }

                        HttpClient.post(`${PHPSession.requestApi}/${routs.indicadores.remover}`, parametros).then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }

                            programaEstrategico.indicadores.splice(
                                programaEstrategico.indicadores.findIndex(obj => obj.pl22_codigo == row.pl22_codigo), 1
                            );

                            carregarDadosIndicadores();
                        });
                    }
                });
            },
        };
    });


</script>
</body>
