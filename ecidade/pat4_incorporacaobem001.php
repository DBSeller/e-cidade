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
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript"
            src="scripts/classes/patrimonio/FiltroItensIncorporacao.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form>
        <fieldset>
            <legend>Incorporação de bens</legend>
            <table class="form-container">
                <tr>
                    <td class="field-size2">
                        <label for="codigo_bem"><a href="#" id="ancora_bem">Bem:</a></label>
                    </td>
                    <td>
                        <input type="text" id="codigo_bem" lang="t52_bem" class="field-size2">
                        <input type="text" id="descricao_bem" lang="t52_descr" disabled="disabled"
                               class="readonly field-size8">
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
        <input type="button" id="incorporar" name="incorporar" value="Selecionar Itens"/>
        <br/>
        <br/>
        <fieldset class="subcontainer">
            <legend>Materiais/Serviços adicionados para incorporar o bem</legend>
            <div id="ctnGridIncorporar" style="width: 500px;"></div>
        </fieldset>

        <input type="button" value="Realizar Incorporação" id="salvar"/>
    </form>
</div>
<?php
db_menu();
?>
</body>
<script type="text/javascript">

    document.getElementById('codigo_bem').addEventListener('change', function() {
        document.getElementById('placa').value = '';
    });

    var lookUp = new DBLookUp($('ancora_bem'), $('codigo_bem'), $('descricao_bem'), {
        'sArquivo': 'func_bensincorporacao.php',
        'aCamposAdicionais': ['t52_ident'],
      'sLabel': 'Pesquisa de Bem',
    });

    lookUp.setCallBack('onClick', function (campos) {
        $('placa').value = campos[2];
    });

    lookUp.setCallBack('onChange', function (erro, campos) {
        $('placa').value = '';
        if (erro) {
            return;
        }
        $('placa').value = campos[2];
    });

    var collectionIncorporar = new Collection();
    collectionIncorporar.setId("codigo");

    var gridIncorporar = DatagridCollection.create(collectionIncorporar).configure({"order": false, "height": '200'});
    gridIncorporar.addColumn("descricao", {
        label: "Material/Serviço",
        align: "left",
        width: '65%'
    }).transform(function (descricao_bem, itemCollection) {
        return '<label title="' + descricao_bem + '">' + descricao_bem + '</label>';
    });
    gridIncorporar.addColumn("quantidade", {
        label: "Valor Total",
        align: "right",
        width: '20%'
    }).transform(function (quantidade, itemCollection) {
        var vlrTotal = itemCollection.valorUnitario * itemCollection.quantidade;
        return js_formatar(vlrTotal, 'f');
    });

    gridIncorporar.addAction('Excluir', 'Remove o Material ou serviço da lista de incorporação.', function (e, item) {
        collectionIncorporar.remove(item.codigo);
        gridIncorporar.reload();
    });

    gridIncorporar.show($('ctnGridIncorporar'));

    function preencheGrid(dados) {
        collectionIncorporar.add(dados);
        gridIncorporar.reload();
    }

    $('incorporar').addEventListener('click', function () {
        new FiltroItensIncorporacao(preencheGrid);
    });

    $('salvar').addEventListener('click', function () {

        if (!validarForm()) {
            return;
        }

        var bens_incorporar = [];
        collectionIncorporar.get().forEach(function (itemCollection) {
            bens_incorporar.push(itemCollection.build());
        });

        var paramentros = {
            exec: 'incorporarBens',
            codigo_bem: $F('codigo_bem'),
            bem: $F('descricao_bem'),
            bens_incorporar: bens_incorporar,
            reavaliar: false
        };

        new AjaxRequest('pat4_incorporacaobem.RPC.php', paramentros, function (retorno, erro) {

            alert(retorno.message);
            if (erro) {
                return;
            }

            limpar();
        }).setMessage('Buscando os materiais/serviços pendentes de incorporação, aguarde...').execute();
    });

    function validarForm() {

        if (empty($F('codigo_bem'))) {
            alert('Informe o bem para o qual deseja incorporar.');
            return false;
        }

        if (collectionIncorporar.get().length == 0) {
            alert('Não foi informado nenhum material/serviço para incorporar ao bem.');
            return false;
        }

        return true;
    }

    function limpar() {
        $('placa').value = '';
        $('codigo_bem').value = '';
        $('descricao_bem').value = '';
        collectionIncorporar.clear();
        gridIncorporar.clear();
        gridIncorporar.reload();
    }
</script>
