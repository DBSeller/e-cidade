<?php

use ECidade\Patrimonial\Material\Repositories\DepositoRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$cldb_usuarios = new cl_db_usuarios();
$sqlUsuario = $cldb_usuarios->sql_query_file(db_getsession("DB_id_usuario"), "id_usuario,nome");
$result_usuarioonline = $cldb_usuarios->sql_record($sqlUsuario);
db_fieldsmemory($result_usuarioonline, 0);

$depositoRepository = new DepositoRepository();
$deposito = $depositoRepository->scopeDepartamento(db_getsession('DB_coddepto'))->first();
if (!is_null($deposito)) {
    $m91_codigo = $deposito->getCodigo();
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/all.min.css">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
</head>
<body class="body-default">
<?php require_once("mat_aviso_deposito.php"); ?>
<div class="container" <?= $isDeposito ?: 'style="display: none"' ?>>
    <fieldset>
        <legend>Transferência de materiais entre depósitos</legend>
        <table class="form-container">
            <tr>
                <td><label for="codigo-usuario" id="ancora-usuario">Usuário: </label></td>
                <td>
                    <input type="text" id="codigo-usuario" value="<?= $id_usuario ?>">
                    <input type="text" id="nome-usuario" value="<?= $nome ?>">
                </td>
            </tr>
            <tr>
                <td><label for="codigo-deposito-origem" id="ancora-deposito-origem">Depósito de Origem: </label></td>
                <td>
                    <input type="text" id="codigo-deposito-origem" value="<?= $deposito->getCodigo() ?>">
                    <input type="text" id="descricao-deposito-origem"
                           value="<?= $deposito->getDepartamento()->getNomeDepartamento() ?>">
                </td>
            </tr>
            <tr>
                <td><label for="codigo-deposito-destino" id="ancora-deposito-destino">Depósito de Destino: </label></td>
                <td>
                    <input type="text" id="codigo-deposito-destino">
                    <input type="text" id="descricao-deposito-destino">
                </td>
            </tr>
            <tr>
                <td><label for="observacao">Observação do Lançamento:</label></td>
                <td>
                    <textarea name="observacao" id="observacao"></textarea>
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset id="ctnTable" style="width: 700px">
        <legend>Materiais</legend>
        <table id="table-materiais"
               class="table table-sm"
               data-height="300"
               data-virtual-scroll="true"
               style="width: 100%;">
        </table>
        <button class="btn btn-light" id="btnNovo" style="margin-top: 10px">
            <i class="fa fa-file" aria-hidden="true"></i>
            Nova transferência
        </button>
        <button class="btn btn-light" id="btnPesquisar" style="margin-top: 10px">
            <i class="fa fa-search" aria-hidden="true"></i>
            Transferências canceladas
        </button>
        <button class="btn btn-light" id="btnTransferir" style="margin-top: 10px">
            <i class="fa fa-share" aria-hidden="true"></i>
            Efetuar transferência
        </button>
    </fieldset>

    <div id="modalMateriais" class="container">
        <fieldset>
            <!--            <legend>Materiais</legend>-->
            <table class="form-container">
                <tr>
                    <td><label for="m60_codmater" id="ancora-material">Código do Material: </label></td>
                    <td>
                        <input type="text" id="m60_codmater">
                        <input type="text" id="m60_descr">
                    </td>
                </tr>
                <tr id="ctnQuantidades" style="display: none;">
                    <td>Quantidade disponível:</td>
                    <td style="display: flex; justify-content: space-around;">
                        <input type="text" class="field-size3 readonly" id="quantidade_disponivel" disabled>
                        <div>
                            <label for="">Quantidade a lançar: </label>
                            <input type="text" class="field-size3" id="quantidade">
                        </div>

                    </td>
                </tr>
            </table>
        </fieldset>
        <div style="width: 600px;">
            <fieldset>
                <legend>Lotes</legend>
                <table id="table-lotes"
                       class="table table-sm"
                       data-height="300"
                       data-virtual-scroll="true"
                       style="width: 100%;">
                </table>
            </fieldset>
        </div>
        <br>
        <button class="btn btn-light" id="btnLancarMaterial">
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
            Lançar
        </button>
    </div>
</div>
<?php db_menu(); ?>
<script type="text/javascript" src="scripts/classes/bootstrapTable/detailFormaterTable.js"></script>

<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<!-- requires bootstrap table -->
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>
<script type="text/javascript">
    $.noConflict();
    jQuery(document).ready(function ($) {
        var tableMateriais = $('#table-materiais');
        var tableLotes = $('#table-lotes');

        const ancoraDepositoOrigem = document.getElementById('ancora-deposito-origem'),
            codigoDepositoOrigem = document.getElementById('codigo-deposito-origem'),
            descricaoDepositoOrigem = document.getElementById('descricao-deposito-origem');

        const ancoraDepositoDestino = document.getElementById('ancora-deposito-destino'),
            codigoDepositoDestino = document.getElementById('codigo-deposito-destino'),
            descricaoDepositoDestino = document.getElementById('descricao-deposito-destino');

        const ancoraUsuario = document.getElementById('ancora-usuario'),
            codigoUsuario = document.getElementById('codigo-usuario'),
            nomeUsuario = document.getElementById('nome-usuario');

        const ancoraMaterial = document.getElementById('ancora-material'),
            codigoMaterial = document.getElementById('m60_codmater'),
            descricaoMaterial = document.getElementById('m60_descr');

        const btnLancar = document.getElementById('btnLancarMaterial');
        const btnTransferir = document.getElementById('btnTransferir');
        const btnPesquisar = document.getElementById('btnPesquisar');
        const btnNovo = document.getElementById('btnNovo');
        const ctnQuantidades = document.getElementById('ctnQuantidades');
        const inputQuantidadeDisponivel = document.getElementById('quantidade_disponivel');
        const inputQuantidade = document.getElementById('quantidade');
        const txtObservacao = document.getElementById('observacao');

        btnLancar.addEventListener('click', () => {
            const dadosLancar = tableLotes.bootstrapTable('getData');
            if (!dadosLancar[0].m71_codlanc) {
                alert(`Não há lotes disponíveis para transferência do ` +
                    `quantitativo a lancar ${inputQuantidade.value}, por favor, ` +
                    `verifique as movimentações do item.`);
                return;
            }
            let lotesLancar = dadosLancar.filter(item => item.rateio !== 0);
            let quantidadeLancar = inputQuantidade.value;
            let totalLancar = lotesLancar.reduce((accumulator, item) => parseFloat(accumulator) + parseFloat(item.rateio), 0);

            if (totalLancar <= 0) {
                alert("Não é possível lançar itens sem Quantidade ou valor negativo!");
                return;
            }

            if (quantidadeLancar != totalLancar &&
                !confirm(`A quantidade informada ${quantidadeLancar} está diferente da ` +
                    `quantidade selecionada nos lotes. ` +
                    `Deseja incluir o material na transferência com a quantidade ${totalLancar}?`)
            ) {
                return;
            }

            const item = {
                codigo_material: lotesLancar[0].m70_codmatmater,
                descricao_material: lotesLancar[0].m60_descr,
                quantidade_total: totalLancar,
                lotes: lotesLancar
            };

            let verificaMaterialLancado = tableMateriais.bootstrapTable('getRowByUniqueId', item.codigo_material);
            if (verificaMaterialLancado) {
                if (!confirm("Item já lançado, deseja sobrescrever?")) {
                    return;
                }
                tableMateriais.bootstrapTable('updateByUniqueId', {
                    id: item.codigo_material,
                    row: item
                });
            } else {
                tableMateriais.bootstrapTable('append', item);
            }
            // atualizaParametrosMaterial();
            hideWindowMateriais();
        });

        const limparFormularioTransferencia = () => {
            codigoDepositoDestino.value = '';
            codigoDepositoDestino.dispatchEvent(new Event('change'));
            txtObservacao.value = '';
            tableMateriais.bootstrapTable("load", []);
        }

        btnTransferir.addEventListener('click', () => {
            if (empty(codigoDepositoDestino.value)) {
                alert("Depósito de Destino não informado!");
                return;
            }
            if (empty(txtObservacao.value)) {
                alert("A observação do lançamento é obrigatória.");
                return;
            }
            let materiasTransferir = tableMateriais.bootstrapTable('getData');
            if (materiasTransferir.length === 0) {
                alert("Nenhum item para transferência, adicionei pelo menos um item.");
                return;
            }

            const formData = new FormData();
            formData.append('acao', 'efetuarTransferencia');
            formData.append('depositoDestino', codigoDepositoDestino.value);
            formData.append('materiais', JSON.stringify(materiasTransferir));
            formData.append('observacao', txtObservacao.value);
            HttpClient.post('mat1_material.RPC.php', {body: formData}).then((response) => {
                alert(response.mensagem);
                if (response.erro) {
                    return;
                }
                limparFormularioTransferencia();
                emitirTermo(response.codigo_matestoqueini);
            });
        });

        btnPesquisar.addEventListener('click', () => {
            let qry = "&chave_m80_codtipo=7";
            qry += "&chave_m80_coddepto=<?=db_getsession("DB_coddepto")?>";
            qry += "&canceladas=true";
            js_OpenJanelaIframe(
                'CurrentWindow.corpo',
                'db_iframe_matestoqueini',
                'func_matestoquetransf.php?funcao_js=parent.js_preenchetransferencia|m83_matestoqueini' + qry,
                'Pesquisa',
                true
            );
            // js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_matestoqueini','func_matestoqueini.php?funcao_js=parent.js_preenche_transferencia|m80_codigo'+qry,'Pesquisa',true);
        });

        btnNovo.addEventListener("click", () => {
            if (confirm("Os dados atuais serão peridos, deseja iniciar uma nova transferência?")) {
                limparFormularioTransferencia();
            }
        });

        const callbackDepositoDestino = (campo1, erro, codigo, descricao) => {
            if (erro) {
                codigoDepositoDestino.value = '';
                descricaoDepositoDestino.value = campo1;
                return;
            }

            if (codigo === codigoDepositoOrigem.value) {
                alert("O depósito de destino não pode ser igual ao depósito de origem");
                codigoDepositoDestino.value = '';
                descricaoDepositoDestino.value = '';
                return;
            }
            codigoDepositoDestino.value = codigo;
            descricaoDepositoDestino.value = descricao;
        }

        const buscarDadosLotes = () => {
            if (parseInt(inputQuantidade.value) > parseInt(inputQuantidadeDisponivel.value)
            ) {
                inputQuantidade.value = inputQuantidadeDisponivel.value;
                alert("Quantidade a lançar não pode ser maior que a quantidade disponível.");
            }
            tableLotes.bootstrapTable('destroy');

            const dataLotes = new FormData();
            dataLotes.append('acao', 'buscarLotes');
            dataLotes.append('codigoMaterial', codigoMaterial.value);
            dataLotes.append('codigoDeposito', codigoDepositoOrigem.value);
            dataLotes.append('quantidade', inputQuantidade.value);
            HttpClient.post('mat1_material.RPC.php', {body: dataLotes}).then(response => {
                if (!response.erro){
                    criarTabelaLotes(response);
                }
            });
        }

        const changeMaterial = () => {
            if (empty(codigoMaterial.value)) {
                ctnQuantidades.style.display = "none";
                inputQuantidade.value = '';
                inputQuantidadeDisponivel.dispatchEvent(new Event('change'));
                inputQuantidadeDisponivel.value = '';
                return;
            }

            tableLotes.bootstrapTable('destroy');

            const formData = new FormData();
            formData.append('acao', 'buscarEstoques');
            formData.append('codigo_material', codigoMaterial.value);
            formData.append('deposito', codigoDepositoOrigem.value);
            HttpClient.post('mat1_material.RPC.php', {body: formData}).then((response) => {
                inputQuantidadeDisponivel.value = 0;
                inputQuantidade.value = 0;
                if (response.estoques.length !== 0) {
                    let estoque = response.estoques.shift();
                    inputQuantidadeDisponivel.value = estoque.quantidade_disponivel;
                    if (estoque.quantidade_disponivel < inputQuantidade.value) {
                        inputQuantidade.value = estoque.quantidade_disponivel;
                    }
                    if (empty(inputQuantidade.value)) {
                        inputQuantidade.value = estoque.quantidade_disponivel;
                    }
                    if (estoque.quantidade_disponivel !== 0) {
                        buscarDadosLotes();
                    }
                }
            });
            ctnQuantidades.style.display = "table-row";
        }

        codigoMaterial.addEventListener('change', () => {
            if (empty(codigoMaterial.value)) {
                ctnQuantidades.style.display = "none";
                changeMaterial();
            }
        });
        inputQuantidade.addEventListener('change', buscarDadosLotes);

        const lookUpUsuario = new DBLookUp(ancoraUsuario, codigoUsuario, nomeUsuario, {
            "sArquivo": "func_db_usuarios.php",
            "sObjetoLookUp": "db_iframe_usuario",
        });
        lookUpUsuario.desabilitar();

        const lookUpDepositoOrigem = new DBLookUp(ancoraDepositoOrigem, codigoDepositoOrigem, descricaoDepositoOrigem, {
            "sArquivo": "func_db_almox.php",
            "sObjetoLookUp": "db_iframe_deposito",
            "sLabel": "Pesquisar Depósitos"
        });
        lookUpDepositoOrigem.desabilitar();

        const lookUpMaterial = new DBLookUp(ancoraMaterial, codigoMaterial, descricaoMaterial, {
            "sArquivo": "func_matmater.php",
            "sObjetoLookUp": "db_iframe_deposito",
            "sLabel": "Pesquisar Material",
            "zIndex": 20000,
            "aParametrosAdicionais": [`codigoAlmoxarifado=${codigoDepositoOrigem.value}`, 'apenasComEstoque=true'],
            "fCallBack": changeMaterial
        });
        // const atualizaParametrosMaterial = () => {
        //     let materiaisLancados = tableMateriais.bootstrapTable('getData');
        //     let materiais = [];
        //
        //     materiaisLancados.map((material) => {
        //         materiais.push(material.codigo_material);
        //     });
        //     let parametros = [`codigoAlmoxarifado=${codigoDepositoOrigem.value}`, 'apenasComEstoque=true'];
        //     if (materiais.length > 0) {
        //         parametros.push(`nosetmaterial=${materiais.join(',')}`);
        //     }
        //     lookUpMaterial.setParametrosAdicionais(parametros);
        // }

        new DBLookUp(ancoraDepositoDestino, codigoDepositoDestino, descricaoDepositoDestino, {
            "sArquivo": "func_db_almox.php",
            "sObjetoLookUp": "db_iframe_deposito",
            "sLabel": "Pesquisar Depósitos",
            "aCamposAdicionais": ["m91_codigo", "descrdepto"],
            "fCallBack": callbackDepositoDestino
        });

        function buttons() {
            return {
                btnAdd: {
                    text: 'Adicionar Item',
                    icon: 'fa-plus',
                    event: function () {
                        windowMateriais.show(0, 0, true);
                    },
                    attributes: {
                        title: 'Clique para adicionar itens a serem transferidos'
                    }
                }
            }
        }

        const formatarLotes = (value, row, index) => {
            const lotes = [];
            value.map((lote) => {
                if (!empty(lote.m77_lote)) {
                    lotes.push(lote.m77_lote);
                }
            });

            return lotes.length > 0 ? lotes.join(', ') : ' - ';
        }

        const atualizaModalParaEdicao = (row) => {
            codigoMaterial.value = row.codigo_material;
            inputQuantidade.value = row.quantidade_total;
            codigoMaterial.dispatchEvent(new Event('change'));

            lookUpMaterial.desabilitar();
            inputQuantidade.disabled = true;
            windowMateriais.show(0, 0, true);
        }

        window.operateEvents = {
            'change .inputQuantidadeLote': function (e, value, row, index) {
                if (parseInt(e.target.value) > parseInt(row.saldo)) {
                    e.target.value = e.target.defaultValue;
                    alert("Valor selecionado é maior que o saldo disponível no lote.");
                    return false;
                }
                if (e.target.value === '') {
                    row.rateio = 0;
                    return;
                }
                row.rateio = parseInt(e.target.value);
            },
            'click .alterar': function (e, value, row, index) {
                atualizaModalParaEdicao(row);
            },
            'click .excluir': function (e, value, row, index) {
                tableMateriais.bootstrapTable('removeByUniqueId', row.codigo_material);
                // atualizaParametrosMaterial();
            }
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
            ].join('')
        }

        tableMateriais.bootstrapTable({
            columns: [
                {
                    title: 'Cód. Material',
                    field: 'codigo_material',
                    align: 'left',
                    valign: 'center',
                    width: 85,
                }, {
                    title: 'Descrição',
                    field: 'descricao_material',
                    align: 'left',
                    valign: 'center',
                }, {
                    title: 'Lotes',
                    field: 'lotes',
                    align: 'left',
                    valign: 'center',
                    formatter: formatarLotes
                }, {
                    title: 'Quantidade',
                    field: 'quantidade_total',
                    align: 'left',
                    valign: 'center',
                    width: 100
                }, {
                    title: 'Ações',
                    field: 'acoes',
                    align: 'center',
                    valign: 'center',
                    width: 70,
                    events: window.operateEvents,
                    formatter: formatterActions,
                }
            ],
            checkbox: false,
            // detailFormatter: detailFormatter,
            uniqueId: "codigo_material",
            locale: 'pt-BR',
            buttons: buttons,
            showButtonText: true,
            class: "table table-sm"
        });

        const modalMateriais = document.getElementById('modalMateriais');
        const hideWindowMateriais = () => {
            if (!!windowMateriais.oDBMask) {
                windowMateriais.oDBMask.destroy();
            }

            codigoMaterial.value = '';
            descricaoMaterial.value = '';
            inputQuantidade.value = '';
            inputQuantidadeDisponivel.value = '';
            inputQuantidadeDisponivel.dispatchEvent(new Event('change'));
            codigoMaterial.dispatchEvent(new Event('change'));

            tableLotes.bootstrapTable('destroy');
            lookUpMaterial.habilitar();
            inputQuantidade.disabled = false;
            windowMateriais.hide();
        }

        var windowMateriais = new windowAux('windowMateriais', 'Lançar Item a Transferir', 700, 600);
        windowMateriais.setContent(modalMateriais);
        windowMateriais.allowCloseWithEsc(true);
        windowMateriais.setShutDownFunction(function () {
            hideWindowMateriais();
        });

        const formatarData = (value, row, index) => {
            if (empty(value)) {
                return null;
            }
            let novaData = new Date(value);
            return novaData.getDateBR();
        }

        const formatarInputQuantidade = (value, row, index) => {

            return `<input type="text"  class="inputQuantidadeLote" value="${value}" />`;
        }

        const criarTabelaLotes = (response) => {
            let lotes = response.itens;

            btnLancar.disabled = false;
            tableLotes.bootstrapTable({
                columns: [
                    {
                        title: 'Cód. Lanc.',
                        field: 'm71_codlanc',
                        align: 'left',
                        valign: 'center',
                        width: 15,
                    }, {
                        title: 'Lote',
                        field: 'm77_lote',
                        align: 'left',
                        valign: 'center',
                    }, {
                        title: 'Validade',
                        field: 'm77_dtvalidade',
                        align: 'left',
                        valign: 'center',
                        formatter: formatarData
                    }, {
                        title: 'Quantidade Lote',
                        field: 'saldo',
                        align: 'left',
                        valign: 'center',
                        width: 130
                    }, {
                        title: 'Quantidade Solicitada',
                        field: 'rateio',
                        align: 'left',
                        valign: 'center',
                        width: 150,
                        formatter: formatarInputQuantidade,
                        events: window.operateEvents
                    }
                ],
                data: lotes,
                uniqueId: "codigo_lancamento",
                locale: 'pt-BR',
                class: "table table-sm",
                onPreBody: (data) => {
                    let codigo = lotes[0].m70_codmatmater;
                    let rowMaterialLancado = tableMateriais.bootstrapTable('getRowByUniqueId', codigo);
                    if (rowMaterialLancado != null) {
                        data.map((lote) => {
                            let loteSelecionado = rowMaterialLancado.lotes.find((itemSelecionado) => {
                                return itemSelecionado.m71_codlanc === lote.m71_codlanc;
                            });
                            if (loteSelecionado != null) {
                                lote.rateio = loteSelecionado.rateio;
                            } else {
                                lote.rateio = 0;
                            }
                        });
                    }
                    return data;
                }
            });
        }

        function emitirTermo(CodigoMatestoqueIni) {
            let sTransferUrl = 'ini=' + CodigoMatestoqueIni + '&fim=' + CodigoMatestoqueIni;
            jan = window.open(
                'mat2_transfermater002.php?' + sTransferUrl,
                '',
                'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0'
            );
            jan.moveTo(0, 0);
        }

        window.retornoDadosTransferencia = (dados) => {
            tableMateriais.bootstrapTable('load', dados.itens);
            codigoDepositoDestino.value = dados.deposito_destino.codigo;
            codigoDepositoDestino.dispatchEvent(new Event("change"));
            txtObservacao.value = dados.observacao;
        }
    });

    async function js_preenchetransferencia(transferencia) {
        db_iframe_matestoqueini.hide();
        const formData = new FormData();
        formData.append('acao', 'buscarDadosTransferencia');
        formData.append('codigo_transferencia', transferencia);
        const response = await HttpClient.post("mat1_material.RPC.php", {body: formData});
        retornoDadosTransferencia(response);
    }
</script>
</body>
</html>
