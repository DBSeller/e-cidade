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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
    db_app::load("widgets/windowAux.widget.js,messageboard.widget.js");
    db_app::load("estilos.css, grid.style.css,tab.style.css");
    ?>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <style>
        .tabela_taxas {
            width: 100%;
            border-collapse: collapse;
        }

        .tabela_cabecalho {
            background-color: #a5a5a5;
        }

        .zebra_claro {
            background-color: #ffffff;
        }
        .zebra_escuro {
            background-color: #EFEFEF;
        }
    </style>
</head>
<body>
    <div class="container" id="retornoTaxas" style="width: 800px;"></div>
</body>
</html>
<script type="text/javascript">
    var get = js_urlToObject();

    (function () {
        var parametros = {'sExecucao': 'buscaTaxasProcesso', 'processo': get.v70_sequencial};
        new AjaxRequest('jur4_manutencaotaxacusta.RPC.php', parametros, function (retorno, erro) {

            if (erro) {
                alert(retorno.sMensagem);
                return;
            }

            if (retorno.partilhas.length == 0) {

                $('retornoTaxas').innerHTML = "<h1 style='color:#ff3019'>Sem taxas lançadas para o processo.</h1>";
                return;
            }
            montaTabela(retorno.partilhas);


        }).setMessage('Buscando taxas processuais.').execute();
    })();

    function montaTabela(partilhas) {
        var fieldset = createFieldSet();
        var table = createHeaderTable();

        createCells(table, partilhas);
        fieldset.appendChild(table);

        document.getElementById("retornoTaxas").appendChild(fieldset);
    }

    function createHeaderTable() {
        var table = document.createElement("table");
        table.addClassName('tabela_taxas');
        table.border = 1;

        var row = table.insertRow();
        row.addClassName('tabela_cabecalho');
        row.insertCell(0).outerHTML = '<th>Status</th>';
        row.insertCell(1).outerHTML = '<th>Data</th>';
        row.insertCell(2).outerHTML = '<th>Observação</th>';
        row.insertCell(3).outerHTML = '<th>Taxa</th>';
        row.insertCell(4).outerHTML = '<th>Valor</th>';

        return table;
    }

    function createCells(table, partilhas) {
        var linha = 1;
        for (var partilha of partilhas) {
            var rowspan = partilha.taxas.length;
            var row = table.insertRow();

            var cor = 'zebra_claro';

            console.log(linha, '-',(linha % 2));
            if ( linha % 2 === 0) {
                cor = 'zebra_escuro';
            }
            row.addClassName(cor);
            createCell(row, partilha.status, rowspan);
            createCell(row, partilha.data, rowspan);
            createCell(row, partilha.observacao, rowspan);


            for (var taxa of partilha.taxas) {
                var row2 = row;
                if (taxa.id !== partilha.taxas[0].id) {
                    row2 = table.insertRow();
                }

                row2.addClassName(cor);
                createCell(row2, taxa.descricao);
                createCell(row2, taxa.valor);
            }
            linha ++;
        }
    }

    function createCell(row, text, rowspan) {
        var cell = row.insertCell();
        if ( rowspan != undefined ) {
            cell.rowSpan = rowspan;
        }
        cell.innerHTML = text;

    }

    function createFieldSet() {
        var fieldset = document.createElement("fieldset");
        var legend = document.createElement("legend");
        legend.innerHTML = 'Taxas/Custas';
        fieldset.appendChild(legend);

        return fieldset;
    }
</script>
