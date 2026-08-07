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
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class='body-default'>
<div class='container' style="width: 1200px;">
    <fieldset>
        <legend>Materiais/Serviços incorporados ao bem</legend>
        <div id="grid"></div>
    </fieldset>
</div>
</body>
<script type="text/javascript">

var collection = new Collection();
collection.setId("codigo");

var gridConsulta = DatagridCollection.create(collection).configure({"order": false, "height": '200'});
gridConsulta.addColumn("empenho", {
    label: "Empenho",
    align: "center",
    width: '133.3px'
});
gridConsulta.addColumn("vlr_empenhado", {
    label: "Valor Empenhado",
    align: "right",
    width: '133.3px'
}).transform('number');

gridConsulta.addColumn("reavaliado", {
    label: "Reavaliado",
    align: "center",
    width: '133.3px'
}).transform(function (reavaliado, item) {
    var label = item.reavaliado == true ? 'Sim' : 'Não';
    return '<label >' + label + '</label>';
});

gridConsulta.addColumn("descricao_item", {
    label: "Material/Serviço",
    width: '133.3px'
}).transform(function (descricao_item) {
    return '<label title="' + descricao_item + '">' + descricao_item + '</label>';
});

gridConsulta.addColumn("quantidade", {
    label: "Quantidade",
    align: "right",
    width: '133.3px'
});

gridConsulta.addColumn("vlr_unitario", {
    label: "Valor Unitário",
    align: "right",
    width: '133.3px'
}).transform("number");

gridConsulta.addColumn("vlr_incorporado", {
    label: "Valor Incorporado",
    align: "right",
    width: '133.3px'
}).transform("number");

gridConsulta.addColumn("data_incorporacao", {
    label: "Data",
    align: "center",
    width: '133.3px'
}).transform("date");

gridConsulta.addColumn("servico", {
    label: "Serviço",
    align: "center",
    width: '133.3px'
}).transform(function (servico, item) {
    var label = item.servico ? 'Sim' : 'Não';
    return '<label >' + label + '</label>';
});

gridConsulta.show($('grid'));

(function () {
    var get = js_urlToObject();
    var parametros = {exec : 'consultaIncorporacao', codigoBem : get.bem};

    new AjaxRequest('pat4_incorporacaobem.RPC.php', parametros, function (retorno, erro) {

        if (erro) {
            alert(retorno.message);
            return;
        }

        gridConsulta.clear();
        collection.add(retorno.itens);
        gridConsulta.reload();

    }).setMessage('Buscando os materiais/serviços incorporados ao bem, aguarde...').execute();
})()
</script>
