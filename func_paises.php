<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="ISO8859-1">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>

    <title>Municípios</title>
</head>
<body>
  <div class="container"> 
  
  <fieldset>
    <legend>Cadastro de Países </legend>
    <div style="width: 900px;">
      <table id="data-table" style="width: 100%">
    </div>
  </fieldset>
  </div>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script> 
<script>
$.noConflict();
jQuery(document).ready(function($) {
  
  var sRPC            = 'func_paises.RPC.php'
  var tablePaises     = jQuery('#data-table');
  const urlParams     = new URLSearchParams(window.location.search);
  const funcRetorno   = urlParams.get('funcao_js');
  const retornaBrasil = urlParams.get('retornaBrasil');

  window.operateEvents = {  
                            'click .selecionar': function(e, value, row, index) { 
                               
                              parent.eval(`${funcRetorno}('${row.codigo}', '${row.codpais}', '${row.descricao}')`);
                              parent.db_iframe_pais.hide();
                            }
                         }   


  const formatterSelecionar = (value, row, index) => {
        
    return `<a class="selecionar">${value}</a>`
  }      

  var colunas = [
          {
            title:   'Código',
            field:   'codigo',
            align:   'center',
            valign:  'middle',
            sortable: false,
            visible: false,
            formatter: formatterSelecionar,
            events:   window.operateEvents
          },
          {
            title:   'Código País E-social',
            field:   'codpais',
            align:   'center',
            valign:  'middle',
            sortable: false,
            formatter: formatterSelecionar,
            events:    window.operateEvents
          },
        {
          title:    'País',
          field:    'descricao',
          align:    'center',
          valign:   'middle',
          formatter: formatterSelecionar,
          events:   window.operateEvents,
          sortable: true          
        }
      ]
      
      tablePaises.createTable = function() {

        tablePaises.bootstrapTable({
          columns: colunas,
          locale: 'pt-BR',
          height: 350,
          pagination: true,
          pageSize: 5,
          pageList: [5, 10, 15, 20, 25, 'All'],
          search: true,
          showButtonText: true,
          class: "table table-sm"          
        })
      }
      
      tablePaises.createTable();
      const formData  = new FormData();
        formData.append('exec', 'buscaPaises');
        formData.append('retornaBrasil', retornaBrasil);
        HttpClient.post(sRPC, {
            body: formData,
            reportMessage: 'Buscando dados ...'
          })
          .then(function(oResponse) {

            if (oResponse.status == 2) {
              tablePaises.bootstrapTable('destroy')
              tablePaises.createTable()
              return alert(oResponse.mensagem);
            }
            
            tablePaises.bootstrapTable('load', oResponse.aPaises);
          });
});      
</script>

</body>
</html>