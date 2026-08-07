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
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form name="form1">
        <fieldset>
            <legend>Manutenção das Informações Complementares</legend>
            <table >
                <tr>
                    <td><label for="estrutural"><b>Estrutural:</b></label></td>
                    <td>
                        <input type="text" name="estrutural" id="estrutural" maxlength="15" />
                    </td>
                    <td>
                        <input type="button" value="Pesquisar" id="pesquisar" />
                    </td>
                </tr>
            </table>
            <fieldset class="separator">
                <legend>Contas</legend>
                <div id="ctnContas" style="width: 800px;"></div>
            </fieldset>

            <fieldset class="separator">
                <legend>Informações Complementares</legend>
                <div id="gridInfoComplementares"></div>
            </fieldset>
        </fieldset>
        <input type="button" id="processar" value="Processar" >
    </form>
</div>
<?php
db_menu();
?>
<script type="text/javascript">
let ano = <?=DB_getsession('DB_anousu');?>;
//@todo colocar o nome do RPC
var RPC = 'con4_informacoescomplementares.RPC.php';

var collectionContas = new Collection().setId("codigo");
var gridContas = new DatagridCollection(collectionContas).configure({
    order    : false,
    height   : 200
});

gridContas.getGrid().setCheckbox(0);
gridContas.addColumn("estrutural", {
    label : "Estrutural",
    align : "left",
    width : "25%"
});
gridContas.addColumn("descricao", {
    label : "Descrição",
    align : "left",
    width : "75%"
});
gridContas.show($('ctnContas'));


var collectionInfoComplementares = new Collection().setId("codigo");
var gridInfoComplementares = new DatagridCollection(collectionInfoComplementares).configure({
    order    : false,
    height   : 175
});

gridInfoComplementares.getGrid().setCheckbox(0);
gridInfoComplementares.addColumn("descricao", {
    label : "Descrição",
    align : "left",
    width : "100%"
});
gridInfoComplementares.show($('gridInfoComplementares'));


(function () {
    // carrega as informacoes complementares
    new AjaxRequest(RPC, {exec:'getInformacoesComplementares'}, function(retorno, erro){
        if (erro) {
            alert(retorno.message);
            return;
        }
        for (var inf of retorno.informacoes_complementares) {

            inf.descricao = "["+inf.sigla+"] - "+inf.descricao;
            collectionInfoComplementares.add(inf);
        }
        gridInfoComplementares.reload();
    }).setMessage("Buscando Informações Complementares.").execute();
})();


function getItensSelecionados(grid) {
    var contas = [];
    var linhasGrid = grid.getGrid().aRows;

    for ( var linha of linhasGrid) {
        if (linha.isSelected ) {
            contas .push( linha.itemCollection.codigo );
        }
    }
    return contas;
}

$('processar').addEventListener('click', function () {

    // pega as linhas das contas selecionadas
    var contas = getItensSelecionados(gridContas);
    // pega as linhas das informações complementares selecionadas
    var informacoes_complementares = getItensSelecionados(gridInfoComplementares);

    // validar se tem alguma conta selecionada
    if (contas.length == 0) {
        alert("Selecione ao menos uma Conta.");
        return;
    }

    // validar se tem informações complementares selecionada
    if (informacoes_complementares.length == 0) {
        alert("Selecione ao menos uma Informação Complementar.");
        return;
    }
    var totalAtributos = 6;
    var mensagem =  "É permitido a seleção de no máximo 6(seis) informações complementares";
    if (ano >= 2020) {
        totalAtributos = 7;
        mensagem =  "É permitido a seleção de no máximo 7(sete) informações complementares";
    }
    // validar se foram marcadas mais que 4 informações complementares
    if (informacoes_complementares.length > totalAtributos) {
        alert(mensagem);
        return;
    }

    if (!confirm('As contas selecionadas serão vinculadas as informações complementares abaixo.\nQualquer configuração anterior será perdida.\nDeseja confirmar a ação?')) {
      return;
    }

    var parametros = {
        'exec':'salvar',
        'contas': contas,
        'informacoes_complementares': informacoes_complementares
    };
    new AjaxRequest(RPC, parametros, function (retorno, erro) {

        alert(retorno.message);
        if (erro) {
            return;
        }


    }).setMessage("Configurando contas selecionadas com as informações complementáres.").execute();
});

$('pesquisar').addEventListener('click', function () {

    if ($F('estrutural') == '') {
        alert('Informe o estrutural da conta.');
        return;
    }
    collectionContas.clear();
    new AjaxRequest(RPC, {'exec' : 'getContas', 'estrutural': $F('estrutural') }, function (retorno, erro) {

        if (erro) {
            alert(retorno.message);
            return;
        }

        for (var conta of retorno.contas) {
            collectionContas.add(conta);
        }
        gridContas.reload();


    }).setMessage("Aguarde, buscando contas.").execute();
})

</script>
</body>
</html>



