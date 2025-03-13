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
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
</head>
<body>
<div id='ctnAbas'></div>
<div id='ctnAbaEmissao' class='subcontainer'>
    <fieldset>
        <legend>Balancete da receita pro complemento</legend>
        <form name="formulario" id="formulario">
            <table class="form-container">
                <tr class="text-left">
                    <td><label class="bold" for="natureza">Natureza da Receita:</label></td>
                    <td colspan="3">
                        <input type="text" name="natureza" id="natureza" class="field-size4" >
                    </td>
                </tr>
                <tr>
                    <td><label class="bold" for="nivel_agrupar">Tipo de Agrupamento das Deduções: </label></td>
                    <td>
                        <select id="nivel_agrupar" name="nivel_agrupar">
                            <option value="0">Lista Deduções Grupo 9</option>
                            <option value="2">Deduções no Mesmo Grupo</option>
                        </select>
                    </td>
                </tr>
                <td><label for="apenasComMovimentacao">Mostrar apenas receitas com movimentação:</label></td>
                <td>
                    <select name="apenasComMovimentacao" id="apenasComMovimentacao">
                        <option value="1" checked>Sim</option>
                        <option value="0">Não</option>
                    </select>
                </td>
                <tr>
                    <td id="ctnInstituicao" colspan="4" style="font-weight: normal">
                        <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
                    </td>
                </tr>
            </table>
            <fieldset class="separator">
                <legend>Saldo por datas</legend>
                <table class="form-container">
                    <tr>
                        <td>Data Inicial:</td>
                        <td><input id="dataInicio" name="dataInicio" type="text"/></td>
                        <td>Data Final:</td>
                        <td><input id="dataFinal" name="dataFinal" type="text"/></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </fieldset>
    <button id="emitir" type="button">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</div>
<div id='ctnAbaRecursos' class='container' style="display: none">
    <fieldset>
        <legend>Selecione os recursos na grid abaixo se quiser filtrar um ou mais recurso</legend>
        <div id="cntGridRecursos" style="width: 800px"></div>
    </fieldset>
</div>

</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>

<script type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
<script type="text/javascript" src="scripts/classes/DBViewFiltroRecursos.classe.js"></script>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
<script>

    const inputNatureza = document.getElementById('natureza');
    const inputDataInicio = new DBInputDate(document.getElementById('dataInicio'));
    const inputDataFinal = new DBInputDate(document.getElementById('dataFinal'));
    const dataHoje = new Date();

    inputDataInicio.setValue(`${dataHoje.getUTCFullYear()}-01-01`);
    inputDataFinal.setValue(dataHoje.toLocaleString());

    $('dataInicio').observe('blur', () => {
        buscarRecursos();
    });

    const ctnAbaRecursos = document.getElementById('ctnAbaRecursos');

    const routs = {
        recursos: 'financeiro/orcamento/recursos',
        relatorio: 'financeiro/contabilidade/relatorio/balancete-receita-por-complemento',
    };

    inputNatureza.addEventListener('input', (e) => {
        var expr = new RegExp("[^0-9,]+");
        if (inputNatureza.value.match(expr)) {
            if (inputNatureza.value != '') {
                inputNatureza.disabled = true;
                alert("Natureza da Receita deve ser preenchido somente com números e vírgulas!");
                inputNatureza.disabled = false;
                inputNatureza.value = '';
                inputNatureza.focus();
                return false;
            }
        }
    })

    // Objetos para controle das Abas
    const dBAba = new DBAbas($('ctnAbas'));
    const abaRelatorio = dBAba.adicionarAba("Relatório", document.getElementById('ctnAbaEmissao'));
    const abaRecursos = dBAba.adicionarAba("Recursos", ctnAbaRecursos);

    const cntGridRecursos = document.getElementById('cntGridRecursos');
    const cntGridUnidade = document.getElementById('cntGridUnidade');

    var viewInstituicao = new DBViewInstituicao('viewInstituicao', document.getElementById('ctnInstituicao'));
    viewInstituicao.iHeight = 150;
    viewInstituicao.show();


    var collectionRecursos = new Collection().setId("o15_codigo");
    var gridRecursos = new DatagridCollection(collectionRecursos).configure({
        order: false,
        height: 400
    });

    gridRecursos.getGrid().setCheckbox(0);
    gridRecursos.addColumn("descricao_recurso", {
        label: "Recurso",
        align: "left",
        width: "47%"
    });
    gridRecursos.addColumn("descricao_complemento", {
        label: "Complemento",
        align: "left",
        width: "47%"
    });

    gridRecursos.show(cntGridRecursos);

    const buscarRecursos = () => {

        let dataFinal = js_formatar(inputDataInicio.inputElement.value, 'd');
        HttpClient.get(`${PHPSession.requestApi}/${routs.recursos}/${dataFinal}`).then(response => {

            let dados = response.data.map((recurso) => {
                recurso.descricao_recurso = `${recurso.o15_recurso} - ${recurso.o15_descr}`;
                recurso.descricao_complemento = `${recurso.complemento.codigo} - ${recurso.complemento.descricao}`;
                return recurso;
            });
            collectionRecursos.add(dados);
            gridRecursos.reload();
        });
    };

    const buscarUnidades = () => {
        const formData = new FormData();
        PHPSession.appendFormData(formData);

        collectionRecursos.clear();

        HttpClient.get(`${PHPSession.requestApi}/${routs.recursos}`).then(response => {

            let dados = response.data.map((recurso) => {
                recurso.descricao_recurso = `${recurso.o15_recurso} - ${recurso.o15_descr}`;
                recurso.descricao_complemento = `${recurso.complemento.codigo} - ${recurso.complemento.descricao}`;
                return recurso;
            });
            collectionRecursos.add(dados);
            gridRecursos.reload();
        });
    };

    PHPSession.loadData().then(() => {
        ctnAbaRecursos.style.display = '';
        buscarRecursos();
    });


    const validarInputs = () => {
        try {
            if (inputNatureza.value !== '') {
                let codigo = Number(inputNatureza.value.substr(0, 1))
                if (![4, 9].includes(codigo)) {
                    throw 'O código da natureza deve começar com 4 ou 9.';
                }
            }

            if (inputDataInicio.value.getUTCFullYear() != inputDataFinal.value.getUTCFullYear()) {
                throw 'As datas devem estar dentro do mesmo exercício.';
            }

            if (js_comparadata(inputDataInicio.inputElement.value, inputDataFinal.inputElement.value, '>')) {
                throw 'Data de inicio deve ser menor que a data final.';
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    }

    document.getElementById('emitir').addEventListener('click', () => {

        if (!validarInputs()) {
            return
        }

        const formData = new FormData(document.getElementById('formulario'));

        formData.append('instituicoes', JSON.stringify(viewInstituicao.getInstituicoesSelecionadas()));

        gridRecursos.getGrid().aRows.each((linha) => {
            if (linha.isSelected) {
                formData.append('recursos[]', linha.itemCollection.o15_codigo);
            }
        });

        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routs.relatorio}`, {body: formData}).then((response) => {
            if (response.error) {
                alert(response.message);
                return;
            }

            const download = new DBDownload();
            download.addFile(response.data.pdf, "Balancete da Receita");
            download.show();
        });
    });
</script>
