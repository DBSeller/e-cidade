<?php

/**
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
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <?php
    db_app::load("windowAux.widget.js");
    ?>
</head>
<style type="text/css">
    #gridgridOcorrencias td, #gridgridStatus td {
        white-space: normal !important;
    }
</style>
    <body class='body-default'>
    <div class="container">
        <?php
        include_once modification("forms/db_frmsituacaocivitas.php");
        ?>
    </div>
    <?php db_menu(); ?>
    <script type='text/javascript'>

        var sUrlRPC = "cad4_situacaocivitasrequisicao001.RPC.php";

        var iHeight = js_round((window.innerHeight/2), 0);
        var iWidth  = 750;
        windowAux = new windowAux('wndApi',
            "",
            iWidth,
            iHeight
        );

        $('consultar').observe("click", function (event) {

            try{

                if (empty($F('dataInicio'))) {
                    throw "O primeiro campo da Data Envio é de preenchimento obrigatório.";
                }

                if (empty($F('dataFim'))) {
                    throw "O segundo campo da Data Envio é de preenchimento obrigatório.";
                }

                consultaDados();

            } catch (erro) {
               alert(erro);
               return false;
            }
        });

        var oCollection  = new Collection().setId('codigo');
        var oGridEnvios = DatagridCollection.create(oCollection)
            .configure({
                'order'  : false,
                'height' : 200
            });

        oGridEnvios.addColumn('data', {
            'label' : 'Data',
            'align' : 'center',
            'width' : '120px'
        });
        oGridEnvios.addColumn("envio", {
            'label' : 'Envio',
            'align' : 'center',
            'width' : '150px'
        });


        oGridEnvios.addColumn("status", {
            'label' : 'Status',
            'align' : 'center',
            'width' : '350px'
        });


        oGridEnvios.addAction('Ocorrências', 'Ocorrências', function (event, registro) {
            consultaOcorrencias(registro);
        });


        oGridEnvios.show($('gridEnvios'));


        function consultaDados() {


            var oParam  = new Object();
            oParam.exec = "consultaDados";

            oParam.aFiltros = new Object();
            oParam.aFiltros.dataInicio = $F('dataInicio');
            oParam.aFiltros.dataFinal  = $F('dataFim');


            js_divCarregando("Consultando os dados.", "msgbox");

            var oAjax = new Ajax.Request(
                sUrlRPC,
                {
                    method    : 'post',
                    parameters: 'json='+js_objectToJson(oParam),
                    onComplete: preencheGrid
                }
            );
        }


        function consultaOcorrencias(registro) {
            var oParam  = new Object();
            oParam.exec = "consultaOcorrencias";

            oParam.aFiltros                     = new Object();
            oParam.aFiltros.idEnvio            = registro.envio;

            js_divCarregando("Consultando os dados do Envio .", "msgbox");

            var oAjax = new Ajax.Request(
                sUrlRPC,
                {
                    method    : 'post',
                    parameters: 'json='+js_objectToJson(oParam),
                    onComplete: mostraOcorrencia
                }
            );
        }


        function mostraOcorrencia(oJson) {
            js_removeObj('msgbox');

            var oRetorno = JSON.parse(oJson.responseText);

            if ( oRetorno.iStatus == 1 ) {

                    var oGrid = new DBGrid("gridOcorrencias");

                oGrid.setHeight(iHeight-100);
                oGrid.setHeader(["Descrição"]);
                oGrid.setCellWidth(["70"]);
                oGrid.setCellAlign(["center", "left", "left"]);

                $(oRetorno.ocorrencias.forEach(function(ocorrencia) {
                    oGrid.addRow([ocorrencia.descricao]);
                }));

                windowAux.setTitle("Ocorrências");
                windowAux.setContent("");
                windowAux.show();
                oGrid.show($('windowwndApi_content'));
                oGrid.setNumRows(oRetorno.ocorrencias.length);

            } else {
                alert( oRetorno.sMessage.urlDecode() );
            }
        }

        function preencheGrid(oJson) {

            oGridEnvios.clear();
            js_removeObj('msgbox');

            var oRetorno = JSON.parse(oJson.responseText);

            if (oRetorno.iStatus == 1) {
                console.log(oGridEnvios.getCollection());
                oGridEnvios.getCollection().add(oRetorno.dados);
                oGridEnvios.reload();
            } else {

                alert(oRetorno.sMessage.urlDecode());
            }
        }

    </script>
    </body>
</html>
