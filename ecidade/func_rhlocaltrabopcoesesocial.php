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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$sCaminhoArquivo = "./arquivos/esocial/tabelas/{$oGet->sArquivoDados}.json";
if (!file_exists($sCaminhoArquivo)) {
    echo "Arquivo com a coleção de dados não encontrado.";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="ISO8859-1">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" type="text/css" href="estilos.css">
        <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
        <script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
        <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
        <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
        <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
        <script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
        <script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>
        <title>Opções e-Social</title>
    </head>
    <body>
        <div class="container">

            <fieldset>
                <legend id="legend_tabela"></legend>
                <div style="width: 900px;">
                    <table id="data-table" style="width: 100%">
                </div>
            </fieldset>
        </div>
        <script>

            var sDados = "";
            <?php 
                $sDados = file_get_contents($sCaminhoArquivo);
                echo "sDados = {$sDados}";
            ?>
            
            $.noConflict();
            jQuery(document).ready(function($) {
                var tableOpcoes     = jQuery('#data-table');
                const urlParams     = new URLSearchParams(window.location.search);
                const funcRetorno   = urlParams.get('funcao_js');
                const sIdCampo      = urlParams.get('sIdCampo');
                
                window.operateEvents = {
                    'click .selecionar': function(e, value, row, index) {
                        parent.eval(`${funcRetorno}('${sIdCampo}','${row.value}','${row.label}')`);
                    }
                }


                const formatterSelecionar = (value, row, index) => {
                    return `<a class="selecionar">${value}</a>`
                }

                var colunas = [
                    {
                        title:   'Código',
                        field:   'value',
                        align:   'center',
                        valign:  'middle',
                        sortable: false,
                        visible: true,
                        formatter: formatterSelecionar,
                        events:   window.operateEvents
                    },
                    {
                        title:    'Descrição',
                        field:    'label',
                        align:    'left',
                        valign:   'middle',
                        formatter: formatterSelecionar,
                        events:   window.operateEvents,
                        sortable: true
                    }
                ]

                tableOpcoes.createTable = function() {
                    tableOpcoes.bootstrapTable({
                        columns: colunas,
                        locale: 'pt-BR',
                        height: 450,
                        pagination: true,
                        pageSize: 8,
                        pageList: [10, 20, 50, 'All'],
                        search: true,
                        showButtonText: true,
                        class: "table table-sm"
                    })
                }

                tableOpcoes.createTable();
                tableOpcoes.bootstrapTable('load', sDados);
            });
        </script>
    </body>
</html>