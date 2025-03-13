<?php

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
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <style>
        input[type="text"] {
            height: 30px;
        }

        td > a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body class="body-default">

<div id='ctnAbas'></div>

<button style="top: 3px; right: 15px; position: fixed" rel='ignore-css' class="retornar" type="button">
    <i class="fas fa-arrow-left"></i>
    Retornar
</button>

<div id="abaPlanoGovernamental" class="container">
    <form id="frmPlanoGoverno">
        <fieldset>
            <legend>Plano de Governo</legend>
            <fieldset class="separator">
                <legend>Dados</legend>
                <table class="form-container">
                    <tr>
                        <td><label for="anoInicial">Ano Inicial:</label></td>
                        <td>
                            <input type="text" id="anoInicial" name="pl2_ano_inicial" class="field-size2"
                                   maxlength="4" oninput="js_ValidaCampos(this, 1, 'Ano', 'f', 'f', event)">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="anoFinal">Ano Final:</label></td>
                        <td>
                            <input type="text" id="anoFinal" name="pl2_ano_final" class="field-size2 readonly"
                                   readonly>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="tituloPlano">Título:</label></td>
                        <td><input type="text" id="tituloPlano" name="pl2_titulo" class="field-size8"></td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="separator">
                <legend><label for="ementa">Ementa</label></legend>
                <textarea id="ementa" name="pl2_ementa" rows="3" cols="30"></textarea>
            </fieldset>
            <fieldset class="separator">
                <legend>Configuração</legend>
                <table class="form-container">
                    <tr>
                        <td><label for="composicao">Composição: </label></td>
                        <td>
                            <select id="composicao" name="pl2_composicao">
                                <option value="1">Sem área de resultado</option>
                                <option value="2">Apenas com área de resultado</option>
                                <option value="3">Com área de resultado e objetivo estratégico</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="baseCalculo">Base de Cálculo:</label></td>
                        <td>
                            <select id="baseCalculo" name="pl2_base_calculo">
                                <option value="1">Previsão Atualizada</option>
                                <option value="2">Realizado e Reestimado</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="linhaBaseDespesa" style="display: none">
                        <td><label for="baseDespesa">Base da Despesa:</label></td>
                        <td>
                            <select id="baseDespesa" name="pl2_base_despesa">
                                <option value="">Selecione</option>
                                <option value="1">Empenhado</option>
                                <option value="2">Liquidado</option>
                                <option value="3">Pago</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>

        </fieldset>
        <input type="hidden" id="codigoPlano" name="pl2_codigo">
        <input type="hidden" id="codigoPlanoPai" name="pl2_codigo_pai">
        <input type="hidden" id="tipoPlano" name="pl2_tipo">
        <button type="button" id="btnSalvarPlanejamento">
            <i class="far fa-save"></i>
            Salvar
        </button>
    </form>
</div>

<div style="display: none;" id="abaIdentidadeOrganizacional">
    <form id="formIdentidadeOrganizacional" class="container">
        <fieldset>
            <legend>Missão</legend>
            <textarea rows="4" cols="100" id="missao" name="pl2_missao"></textarea>
        </fieldset>
        <fieldset>
            <legend>Visão</legend>
            <textarea rows="4" cols="100" id="visao" name="pl2_visao"></textarea>
        </fieldset>
        <fieldset>
            <legend>Valores</legend>
            <textarea rows="4" cols="100" id="valores" name="pl2_valores"></textarea>
        </fieldset>
        <button type="button" id="btnSalvarIdentidade">
            <i class="far fa-save"></i>
            Salvar
        </button>
    </form>
</div>

<div style="display: none;" id="abaComissao">
    <form id="frmComissao" class="container">

        <div style="width: 650px;" id="ctnLancadorCgm"></div>

        <button type="button" id="btnSalvarComissao">
            <i class="far fa-save"></i>
            Salvar
        </button>
    </form>
</div>

<div style="display: none;" id="abaAreaResultado">
    <form id="formArea" class="container">
        <fieldset>
            <legend>Informe a Área de resultado</legend>
            <div style="text-align: left;">
                <table class="form-container">
                    <tr>
                        <td class="field-size2"><label for="tituloArea" class="bold">Título:</label></td>
                        <td><input type="text" id="tituloArea" name="pl4_titulo" class="field-size-max"></td>
                    </tr>
                </table>
            </div>
            <fieldset class="separator">
                <legend>Contextualização</legend>
                <textarea rows="4" cols="100" id="contextualizacaoArea" name="pl4_contextualizacao"></textarea>
            </fieldset>
        </fieldset>
        <input type="hidden" id="codigoArea" name="pl4_codigo">
        <button type="button" id="btnSalvarArea">
            <i class="far fa-save"></i>
            Salvar
        </button>
        <fieldset>
            <legend>Áreas de Resultado Cadastradas</legend>
            <table id="data-table-area"
                   class="table table-sm"
                   data-height="250"
                   data-virtual-scroll="true"

                   style="width: 100%;">
            </table>
        </fieldset>
    </form>
</div>

<div id="modalArea" style="display: none; width: 1300px;">
    <form id="formObjetivo" class="container">
        <div style="text-align: left;">
            <h3>Área de Resultado: <span id="area"></span></h3>
        </div>
        <fieldset>
            <legend>Informe os Objetivos da área de resultado</legend>


            <div style="text-align: left;">
                <table class="form-container">
                    <tr>
                        <td class="field-size2"><label for="tituloObjetivo">Título:</label></td>
                        <td>
                            <input type="text" id="tituloObjetivo" name="pl5_titulo" class="field-size-max">
                        </td>
                    </tr>
                </table>
            </div>
            <fieldset class="separator">
                <legend><label for="contextualizacaoObjetivo">Contextualização</label></legend>
                <textarea rows="3" cols="100" id="contextualizacaoObjetivo" name="pl5_contextualizacao"></textarea>
            </fieldset>
            <fieldset class="separator">
                <legend><label for="fonteObjetivo">Fonte</label></legend>
                <textarea rows="3" cols="100" id="fonteObjetivo" name="pl5_fonte"></textarea>
            </fieldset>
        </fieldset>
        <input type="hidden" id="codigoObjetivo" name="pl5_codigo">
        <input type="hidden" id="codigoVinculoArea" name="pl5_arearesultado">
        <button type="button" id="btnSalvarObjetivo">
            <i class="far fa-save"></i>
            Salvar
        </button>
        <fieldset>
            <legend>Objetivos Estratégicos Cadastrados</legend>
            <table id="data-table-objetivo"
                   class="table table-sm"
                   data-height="250"
                   data-virtual-scroll="true"

                   style="width: 100%;">
            </table>
        </fieldset>
    </form>
</div>

<?php db_menu() ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<script type="text/javascript" src='scripts/widgets/Collection.widget.js'></script>
<script type="text/javascript">

    const get = js_urlToObject();
    var planejamento = {};

    const collectionArea = new Collection().setId("pl4_codigo");

    const routs = {
        "comissao": "financeiro/planejamento/comissao",
        "identidade": "financeiro/planejamento/identidade-organizacional",
        "area": "financeiro/planejamento/area-resultado/salvar",
        "remove_area": "financeiro/planejamento/area-resultado/excluir",
        "objetivo": "financeiro/planejamento/objetivo-estrategico",
        "remove_objetivo": "financeiro/planejamento/objetivo-estrategico/excluir",
        "ldo": "financeiro/planejamento/ldo",
        "loa": "financeiro/planejamento/loa",
        "ppa": "financeiro/planejamento/ppa",
        "buscaPlano": "financeiro/planejamento/consulta/plano"
    }

    // Abas
    const cntPlanoGovernamental = document.getElementById('abaPlanoGovernamental')
    const cntIdentidadeOrganizacional = document.getElementById('abaIdentidadeOrganizacional')
    const cntComissao = document.getElementById('abaComissao')
    const cntAreaResultado = document.getElementById('abaAreaResultado')

    const ctnAbas = new DBAbas(document.getElementById('ctnAbas'));
    const abaPlanoGovernamental = ctnAbas.adicionarAba("Plano de Governo", cntPlanoGovernamental);
    const abaIdentidadeOrganizacional = ctnAbas.adicionarAba("Identidade Organizacional", cntIdentidadeOrganizacional);
    const abaComissao = ctnAbas.adicionarAba("Comissão", cntComissao);
    const abaAreaResultado = ctnAbas.adicionarAba("Área de Resultado", cntAreaResultado);
    abaIdentidadeOrganizacional.bloquear();
    abaComissao.bloquear();
    abaAreaResultado.bloquear();
    abaAreaResultado.bloquear();

    // elementos form plano governo
    const planoGoverno = {
        frm : document.getElementById('frmPlanoGoverno'),
        codigo : document.getElementById('codigoPlano'),
        codigoPai : document.getElementById('codigoPlanoPai'),
        tipo : document.getElementById('tipoPlano'),
        anoInicial : document.getElementById('anoInicial'),
        anoFinal : document.getElementById('anoFinal'),
        tituloPlano : document.getElementById('tituloPlano'),
        ementa : document.getElementById('ementa'),
        composicao : document.getElementById('composicao'),
        baseCalculo : document.getElementById('baseCalculo'),
        baseDespesa : document.getElementById('baseDespesa'),
        linhaBaseDespesa : document.getElementById('linhaBaseDespesa'),
        btnSalvarPlanejamento : document.getElementById('btnSalvarPlanejamento')
    };

    // elementos da aba identidade organizacional
    const identidade = {
        frm : document.getElementById('formIdentidadeOrganizacional'),
        missao : document.getElementById('missao'),
        visao : document.getElementById('visao'),
        valores : document.getElementById('valores'),
        btnSalvar : document.getElementById('btnSalvarIdentidade')
    };

    // elementos da aba comissão
    const frmComissao = document.getElementById('frmComissao');
    const btnSalvarComissao = document.getElementById('btnSalvarComissao');
    // não alterar para const a instancia do DBLancador
    var lancadorCgm = new DBLancador("lancadorCgm");
    lancadorCgm.iGridHeight = 180;
    lancadorCgm.sTextoFieldset = 'CGM(s)';
    lancadorCgm.selecionarAposPesquisar = true;
    lancadorCgm.setLabelAncora("CGM:");
    lancadorCgm.setNomeInstancia("lancadorCgm");
    lancadorCgm.setHabilitado(true);
    lancadorCgm.setParametrosPesquisa("func_nome.php", ["z01_numcgm", "z01_nome"]);

    // elementos da aba area
    const areaResultado = {
        frm : document.getElementById('formArea'),
        codigo : document.getElementById('codigoArea'),
        titulo : document.getElementById('tituloArea'),
        contextualizacao : document.getElementById('contextualizacaoArea'),
        btnSalvar : document.getElementById('btnSalvarArea')
    };

    /**
     * Campos Modal Objetivo Estratégico
     */
    const objetivoEstrategico = {
        modal : document.getElementById('modalArea'),
        form :  document.getElementById('formObjetivo'),
        tituloArea : document.getElementById('area'),
        codigoArea : document.getElementById('codigoVinculoArea'),
        titulo : document.getElementById('tituloObjetivo'),
        contextualizacao : document.getElementById('contextualizacaoObjetivo'),
        fonte : document.getElementById('fonteObjetivo'),
        codigo : document.getElementById('codigoObjetivo'),
        btnSalvar : document.getElementById('btnSalvarObjetivo'),
    };

    /**
     * Botão de retorno
     */
    document.querySelectorAll('.retornar').forEach(el => el.addEventListener('click', event => {
        location.href = `pla1_planejamento001.php?tipo=${get.tipo}`;
    }));

    const fechaModal = () => {
        if (!!windowObjetivo.oDBMask) {
            windowObjetivo.oDBMask.destroy();
        }
        windowObjetivo.hide();
    }

    const windowObjetivo = new windowAux('windowObjetivo', 'Manutenção dos Objetivos Estratégicos', 1300, 800);
    windowObjetivo.setContent(objetivoEstrategico.modal);
    windowObjetivo.setShutDownFunction(() => {
        fechaModal()
    });

    const inicializa = () => {

        planoGoverno.tipo.value = get.tipo;
        planoGoverno.anoInicial.addEventListener('change', () => {
            let anosIncrementar = 3;
            switch (get.tipo) {
                case 'LDO':
                    anosIncrementar = 2;
                    break;
                case 'LOA':
                    anosIncrementar = 0;
                    break;
            }

            if (planoGoverno.anoInicial.value === '') {
                planoGoverno.anoFinal.value = '';
            } else {
                planoGoverno.anoFinal.value = Number(planoGoverno.anoInicial.value) + anosIncrementar;
            }
        });

        planoGoverno.baseCalculo.addEventListener('change', (e) => {
            planoGoverno.linhaBaseDespesa.style.display = 'none';
            planoGoverno.baseDespesa.value = '';
            if (e.target.value == 2) {
                planoGoverno.linhaBaseDespesa.style.display = 'table-row';
            }
        });

        lancadorCgm.show(document.getElementById('ctnLancadorCgm'));
        cntIdentidadeOrganizacional.style.display = '';
        cntComissao.style.display = '';
        cntAreaResultado.style.display = '';
    };

    $.noConflict();
    jQuery(document).ready(function (jQuery) {

        inicializa();

        PHPSession.loadData().then(() => {
            if (get.codigo) {
                HttpClient.get(`${PHPSession.requestApi}/${routs.buscaPlano}/${get.codigo}`).then(response => {
                    preencheFormularios(response.data);
                    liberaAbas();
                });
            }
        });

        const preencheFormularios = (data) => {

            planejamento = data;

            planoGoverno.codigo.value = data.pl2_codigo;
            planoGoverno.codigoPai.value = data.pl2_codigo_pai;
            planoGoverno.anoInicial.value = data.pl2_ano_inicial;
            planoGoverno.anoFinal.value = data.pl2_ano_final;
            planoGoverno.tituloPlano.value = data.pl2_titulo;
            planoGoverno.ementa.value = data.pl2_ementa;
            planoGoverno.composicao.value = data.pl2_composicao;
            planoGoverno.baseCalculo.value = data.pl2_base_calculo;
            planoGoverno.baseCalculo.dispatchEvent(new Event('change'));
            planoGoverno.baseDespesa.value = data.pl2_base_despesa;

            identidade.missao.value = data.pl2_missao;
            identidade.visao.value = data.pl2_visao;
            identidade.valores.value = data.pl2_valores;

            estadoAbaResultado(data.pl2_composicao != 1);

            let comissoes = data.comissoes.map(comissao => {
                return [comissao.pl3_cgm, comissao.cgm.z01_nome]
            });
            lancadorCgm.carregarRegistros(comissoes);

            collectionArea.add(data.areas_resultado);
            tableArea.bootstrapTable('load', collectionArea.build());

            if (get.tipo != 'PPA') {
                planoGoverno.anoInicial.readonly = true;
                planoGoverno.composicao.readonly = true;
                planoGoverno.baseCalculo.readonly = true;
                planoGoverno.baseDespesa.readonly = true;
                planoGoverno.anoInicial.className += ' readonly';
                planoGoverno.composicao.className += ' readonly';
                planoGoverno.baseCalculo.className += ' readonly';
                planoGoverno.baseDespesa.className += ' readonly';
            }
        };

        planoGoverno.composicao.addEventListener('change', () => {

            let msg = `O ${get.tipo} que esta alterando, já possui programas vínculado. `;
            msg += 'Ao alterar todos os vínculos serão excluídos. Confirma essa ação?';

            if (planoGoverno.codigo.value !== '' && planejamento.areas_resultado.length > 0) {

                let hasPrograma = false;
                if (planejamento.pl2_composicao == 2) {
                    hasPrograma = planejamento.areas_resultado.filter(area => {
                        return area.programas.length > 0;
                    }).length > 0;
                } else {
                    hasPrograma = planejamento.areas_resultado.filter(area => {
                        return area.objetivos_estrategicos.filter(objetivo => {
                            return objetivo.programas.length > 0;
                        }).length > 0;
                    }).length > 0;
                }

                if (hasPrograma) {
                    alertify.confirm(msg, confirma => {
                        if (!confirma) {
                            planoGoverno.composicao.value = planejamento.pl2_composicao;
                        } else {
                            collectionArea.clear();
                            tableArea.bootstrapTable('load', []);
                            planejamento.areas_resultado = [];
                        }
                    });
                } else {
                    collectionArea.clear();
                    tableArea.bootstrapTable('load', []);
                    planejamento.areas_resultado = [];
                }
            }
        });

        planoGoverno.btnSalvarPlanejamento.addEventListener('click', () => {

            if (!validaFormPlanoGoverno()) {
                return;
            }

            const rota = getRoutePlano();
            const formData = new FormData(planoGoverno.frm);
            PHPSession.appendFormData(formData);
            HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then((response) => {
                if (response.error) {
                    alert(response.message);
                    return;
                }

                planejamento = response.data;
                planoGoverno.codigo.value = response.data.pl2_codigo;
                abaPlanoGovernamental.setVisibilidade(false);
                abaIdentidadeOrganizacional.setVisibilidade(true);

                liberaAbas();

                estadoAbaResultado(response.data.pl2_composicao != 1);
            });
        });

        /**
         * Libera as abas
         */
        const liberaAbas = () => {
            abaIdentidadeOrganizacional.desbloquear();
            abaComissao.desbloquear();
            abaAreaResultado.desbloquear();
            estadoAbaResultado(planejamento.pl2_composicao != 1);
        };

        /**
         * @param {boolean} liberar
         */
        const estadoAbaResultado = (liberar) => {
            abaAreaResultado.bloquear();
            if (liberar) {
                abaAreaResultado.desbloquear()
            }
        };

        const getRoutePlano = () => {
            switch (get.tipo) {
                case 'PPA':
                    return routs.ppa;
                case 'LDO':
                    return routs.ldo;
                case 'LOA':
                    return routs.loa;
                default:
                    throw 'Rota não mapeada';
            }
        };

        /**
         * Realiza as validações dos inputs do formulário de plano de governo
         */
        const validaFormPlanoGoverno = () => {

            if (planoGoverno.anoInicial.value.length < 4) {
                alert("Ano inicial deve ter no mínimo 4 caracteres.");
                return false;
            }

            if (planoGoverno.baseCalculo.value == 2 && planoGoverno.baseDespesa.value == '') {
                alert('Quando selecionado "Base de Cálculo" igual a "Realizado e Reestimado", você deve selecionar "Base de Despesa".');
                return false;
            }

            return true;
        };

        identidade.btnSalvar.addEventListener('click', () => {

            const formData = new FormData(identidade.frm);
            formData.append('pl2_codigo', planoGoverno.codigo.value);
            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${routs.identidade}`, {body: formData}).then((response) => {
                alert(response.message);
                if (response.error) {
                    return;
                }

                abaIdentidadeOrganizacional.setVisibilidade(false);
                abaComissao.setVisibilidade(true);
            });
        });

        btnSalvarComissao.addEventListener('click', () => {

            if (lancadorCgm.getRegistros().length === 0) {
                alert('Informe ao menos um CGM se deseja criar uma comissão.');
                return;
            }

            const formData = new FormData();
            formData.append('pl2_codigo', planoGoverno.codigo.value);

            lancadorCgm.getRegistros().map(cgm => {
                formData.append('cgms[]', cgm.sCodigo);
            });

            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${routs.comissao}`, {body: formData}).then((response) => {
                alert(response.message);
                if (response.error) {
                    return;
                }
                abaIdentidadeOrganizacional.setVisibilidade(false);
                abaComissao.setVisibilidade(true);
            });
        });

        const actionsTable = () => {
            return [
                '<a class="alterar" href="javascript:void(0)" title="Alterar">',
                '  <i class="fa fa-edit"></i>',
                '</a>',
                '&nbsp; &nbsp; ',
                '<a class="excluir" href="javascript:void(0)" title="Excluir">',
                '  <i class="fas fa-trash-alt"></i>',
                '</a>'
            ];
        }

        const actionsArea = (value, row, index) => {
            let actions = actionsTable();
            actions.push('&nbsp; &nbsp; ');

            if (planejamento.pl2_composicao === 3) {
                actions.push('<a class="adicionarObjetivo" href="javascript:void(0)" title="Adicionar Objetivo">');
                actions.push('  <i class="fas fa-list-ul"></i>');
                actions.push('</a>');
            }
            return actions.join('');
        }

        const actionsObjetivo = (value, row, index) => {
            return actionsTable().join('');
        }

        window.eventsArea = {
            'click .alterar': function (e, value, row, index) {
                areaResultado.codigo.value = row.pl4_codigo;
                areaResultado.titulo.value = row.pl4_titulo;
                areaResultado.contextualizacao.value = row.pl4_contextualizacao;
            },
            'click .excluir': function (e, value, row, index) {
                alertify.confirm(`Tem certeza que deseja excluir a Área de Resultado?`, (e) => {
                    if (e) {
                        const formData = new FormData;
                        formData.append('pl4_codigo', row.pl4_codigo);
                        PHPSession.appendFormData(formData);

                        HttpClient.post(`${PHPSession.requestApi}/${routs.remove_area}`, {body: formData})
                        .then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }

                            collectionArea.remove(row.pl4_codigo);
                            tableArea.bootstrapTable('remove', {
                                field: 'pl4_codigo',
                                values: [row.pl4_codigo]
                            });
                        });
                    }
                });
            },
            'click .adicionarObjetivo': function (e, value, row, index) {
                objetivoEstrategico.tituloArea.innerHTML = row.pl4_titulo;
                objetivoEstrategico.codigoArea.value = row.pl4_codigo;
                objetivoEstrategico.modal.style.display = '';

                tableObjetivo.bootstrapTable('load', row.objetivos_estrategicos);
                windowObjetivo.show(0, 0, true);
            }
        };

        window.eventsObjetivo = {
            'click .alterar': function (e, value, row, index) {
                objetivoEstrategico.codigo.value = row.pl5_codigo;
                objetivoEstrategico.codigoArea.value = row.pl5_arearesultado;
                objetivoEstrategico.titulo.value = row.pl5_titulo;
                objetivoEstrategico.contextualizacao.value = row.pl5_contextualizacao;
                objetivoEstrategico.fonte.value = row.pl5_fonte;
            },
            'click .excluir': function (e, value, row, index) {
                alertify.confirm(`Tem certeza que deseja excluir o Objetivo Estratégico?`, (e) => {
                    if (e) {
                        const formData = new FormData();
                        formData.append('pl5_codigo', row.pl5_codigo);
                        PHPSession.appendFormData(formData);

                        HttpClient.post(`${PHPSession.requestApi}/${routs.remove_objetivo}`, {body: formData})
                        .then(response => {
                            alert(response.message);
                            if (response.error) {
                                return;
                            }

                            let area = collectionArea.get(row.pl5_arearesultado).build();
                            area.objetivos_estrategicos.splice(
                                area.objetivos_estrategicos.findIndex(obj => obj.pl5_codigo == row.pl5_codigo), 1
                            );

                            tableObjetivo.bootstrapTable('load', area);
                            collectionArea.add(area);
                        });
                    }
                });
            },
        };

        const tableArea = jQuery('#data-table-area');
        tableArea.bootstrapTable({
            locale: 'pt-BR',
            columns: [
                {
                    title: "ID",
                    field: 'id',
                    visible: false,
                    formatter: (value, row) => {
                        return row.pl4_codigo;
                    }
                },
                {
                    title: "Título",
                    field: 'pl4_titulo',
                    align: 'left',
                    valign: 'middle',
                    formatter: (value) => {
                        return `<div style="width: 290px"><div class="elipse" title="${value}">${value}</div></div>`;
                    }
                },
                {
                    title: "Contextualização",
                    field: 'pl4_contextualizacao',
                    align: 'left',
                    valign: 'middle',
                    formatter: (value) => {
                        return `<div style="width: 500px"><div class="elipse" title="${value}">${value}</div></div>`;
                    }
                },
                {
                    title: "Ações",
                    field: 'acoes',
                    align: 'center',
                    valign: 'middle',
                    width: '130',
                    events: window.eventsArea,
                    formatter: actionsArea
                }
            ],
        });

        areaResultado.btnSalvar.addEventListener('click', () => {
            if (tituloArea.value === '') {
                alert('Título da Área é obrigatório.');
                return;
            }

            const formData = new FormData(areaResultado.frm);
            formData.append('pl2_codigo', planoGoverno.codigo.value);
            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${routs.area}`, {body: formData}).then(response => {
                alert(response.message);
                if (response.error) {
                    return;
                }
                areaResultado.frm.reset();
                codigoArea.value = '';
                planejamento.areas_resultado = response.data;
                collectionArea.add(response.data)
                tableArea.bootstrapTable('load', collectionArea.build());
            });
        });

        const tableObjetivo = jQuery('#data-table-objetivo');
        tableObjetivo.bootstrapTable({
            locale: 'pt-BR',
            columns: [
                {
                    title: "Título",
                    field: 'pl5_titulo',
                    align: 'left',
                    valign: 'middle',
                    formatter: (value) => {
                        return `<div style="width: 290px"><div class="elipse" title="${value}">${value}</div></div>`;
                    }
                },
                {
                    title: "Contextualização",
                    field: 'pl5_contextualizacao',
                    align: 'left',
                    valign: 'middle',
                    formatter: (value) => {
                        return `<div style="width: 500px"><div class="elipse" title="${value}">${value}</div></div>`;
                    }
                },
                {
                    title: "Ações",
                    field: 'acoes',
                    align: 'center',
                    valign: 'middle',
                    width: '130',
                    events: window.eventsObjetivo,
                    formatter: actionsObjetivo
                }
            ]
        });

        objetivoEstrategico.btnSalvar.addEventListener('click', () => {
            if (objetivoEstrategico.titulo.value === '') {
                alert('Você deve informar o "Título" do Objetivo Estratégico.');
                return
            }

            if (objetivoEstrategico.contextualizacao.value === '') {
                alert('Você deve informar a "Contextualização" do Objetivo Estratégico.');
                return
            }

            const formData = new FormData(objetivoEstrategico.form);
            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${routs.objetivo}`, {body: formData}).then(response => {
                alert(response.message);
                if (response.error) {
                    return;
                }
                objetivoEstrategico.form.reset();
                objetivoEstrategico.codigo.value = '';
                tableObjetivo.bootstrapTable('load', response.data);

                let area = collectionArea.get(objetivoEstrategico.codigoArea.value);
                area.objetivos_estrategicos = response.data

                tableArea.bootstrapTable('load', collectionArea.build());
            });
        });
    });
</script>
</body>
</html>
