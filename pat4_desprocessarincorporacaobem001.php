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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form id="frm1">
        <div class='subcontainer'>
            <fieldset style="width: 690px;">
                <legend>Desprocessamento de incorporação de bens</legend>
                <table class="form-container">
                    <tr>
                        <td class="field-size2">
                            <label for="codigo_bem"><a href="#" id="ancora_bem" >Bem:</a></label>
                        </td>
                        <td>
                            <input type="text" id="codigo_bem" lang="t52_bem" class="field-size2">
                            <input type="text" id="descricao_bem" lang="t52_descr" disabled="disabled" class="readonly field-size8">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="placa">Placa:</label>
                        </td>
                        <td>
                            <input type="text" id="placa" lang="t52_ident" disabled="disabled" class="readonly field-size3">
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <fieldset >
            <legend>Selecione os materiais/serviços para desincorporar ao bem</legend>
            <div id="ctnGridIncorporar" style="width: 690px;"></div>
        </fieldset>
        <input type="button" value="Processar" id="processar" />
    </form>

</div>
<?php
db_menu();
?>
</body>
<script type="text/javascript">

    var lookUp = new DBLookUp($('ancora_bem'), $('codigo_bem'), $('descricao_bem'), {
        'sArquivo': 'func_bensincorporacao.php',
        'aCamposAdicionais': ['t52_ident'],
        'sLabel': 'Pesquisa de Bens'
    });

    lookUp.setCallBack('onClick', function (campos) {
        $('placa').value = campos[2];
        buscarItensIncorporados($F('codigo_bem'))
    });

    lookUp.setCallBack('onChange', function (erro, campos) {
        $('placa').value = '';
        limpar();
        if (erro) {
            return;
        }
        $('placa').value = campos[2];
        buscarItensIncorporados($F('codigo_bem'));
    });

    var collection = new Collection();
    collection.setId("codigo");

    var gridEstorno = DatagridCollection.create(collection).configure({"order": false, "height": '150'});
    gridEstorno.getGrid().setCheckbox(null);
    gridEstorno.addColumn("descricao_item", {
        label: "Material/Serviço",
        width: '150px'
    }).transform(function (descricao_item) {
        return '<label title="' + descricao_item + '">' + descricao_item + '</label>';
    });

    gridEstorno.addColumn("quantidade", {
        label: "Quantidade",
        align: "right",
        width: '70px'
    });

    gridEstorno.addColumn("vlr_unitario", {
        label: "Valor Unitário",
        align: "right",
        width: '100px'
    }).transform("number");

    gridEstorno.addColumn("vlr_incorporado", {
        label: "Valor Incorporado",
        align: "right",
        width: '100px'
    }).transform("number");

    gridEstorno.addColumn("data_incorporacao", {
        label: "Data",
        align: "center",
        width: '100px'
    }).transform("date");

    gridEstorno.addColumn("reavaliado", {
        label: "Reavaliado",
        align: "center",
        width: '60px'
    }).transform(function (reavaliado, item) {
        var label = item.reavaliado ? 'Sim' : 'Não';
        return '<label >' + label + '</label>';
    });

    gridEstorno.show($('ctnGridIncorporar'));

    function buscarItensIncorporados(codigoBem) {
        var parametros = {exec : 'consultaIncorporacao', codigoBem : codigoBem};

        new AjaxRequest('pat4_incorporacaobem.RPC.php', parametros, function (retorno, erro) {

            if (erro) {
                alert(retorno.message);
                return;
            }

            limpar();
            if (retorno.itens.length == 0) {
                alert('O bem selecionado não possui materiais ou serviços incorporados.');
                return;

            }
            collection.add(retorno.itens);
            gridEstorno.reload();

        }).setMessage('Buscando os materiais/serviços incorporados ao bem, aguarde...').execute();
    }

    function limpar() {
        gridEstorno.clear();
        collection.clear();
    }

    $('processar').addEventListener('click', function () {

        var selecionados = [];
        var linhasGrid = gridEstorno.getGrid().aRows;
        for (var linha of linhasGrid) {
            if (linha.isSelected) {
                var item = linha.itemCollection.build();
                selecionados.push(item);
            }
        }

        if (selecionados.length == 0) {
            alert("Você deve selecionar ao menos um item.");
            return;
        }

        if (!confirm("Confirma o desprocessamento da incorporação dos itens selecionados?")) {
            return;
        }

        var paramentros = {
            exec : 'estornarIncorporacao',
            codigo_bem : $F('codigo_bem'),
            itens_extornar: selecionados
        };

        new AjaxRequest('pat4_incorporacaobem.RPC.php', paramentros, function (retorno, erro) {

            alert(retorno.message);
            if (erro) {
                return;
            }

            document.getElementById('frm1').reset();
            limpar();
        }).setMessage('Buscando os materiais/serviços pendentes de incorporação, aguarde...').execute();

    });
</script>
