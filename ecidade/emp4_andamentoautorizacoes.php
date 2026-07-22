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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script src="scripts/classes/http/http.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
    .th-inner {color:white}
</style>
</head>

<body>

<div class="container">
    <div class='card' style="width:40%;margin: 0 auto">
        <div class='card-body'>
           <div class='row'>
              <div class='col-md-12'>
                  <label><b>Período de:</b></label>
                  <?php
                     db_inputdata('dataInicial', @$dia, @$mes, @$ano, true, 'text', 1, "");
                    ?>
                  <label><b>Período ate:</b></label>
                  <?php
                     db_inputdata('dataFinal', @$dia, @$mes, @$ano, true, 'text', 1, "");
                    ?>
              </div>
            </div>

             <div class='row'>
                 <div class='col-md-12'>
                     <label><b>Status:</b></label>
                     <?php

                        $clAndamentoEmppreAutorizacaoStatus = new cl_andamentoemppreautorizacaostatus;
                        $sqlAndamentoAutorizacaoStatus = $clAndamentoEmppreAutorizacaoStatus->sql_query_file(
                            $id = null,
                            $campos = '*',
                            $ordem = 'id'
                        );
                        $result = db_query($sqlAndamentoAutorizacaoStatus);
                        $todos = [
                            "0",
                            "TODOS"
                        ];
                        db_selectrecord("statusFiltro", $result, false, 1, " ", "", "", $todos, "", "");
                        ?>
                 </div>
             </div>
        </div>
    </div>

  <div style="margin-top: 10px;">
     <button id="pesquisar" type="button" class="btn btn-outline-primary" onClick="jsPesquisar()"> 
         <i class="fas fa-search"></i> 
         Pesquisar 
    </button>
  </div>

</div>

<div class="container">
    <div class="row">
        <div class = "col-md-12">
            <legend>Andamento das autorizações</legend>
            <table id="data-table"
                   class="table table-sm"
                   data-height="250"
                   data-virtual-scroll="true"
                   style="width: 100%;">
                   <thead>
                       <tr>
                           <th data-field="andamento_id" data-visible="false"></th>
                           <th data-field="e54_autori">Id Autorizacao</th>
                           <th data-field="e54_anousu">Ano</th>
                           <th data-field="e54_valor" data-formatter="valorFormatter">Valor</th>
                           <th data-field="z01_nome">CGM</th>
                           <th data-field="status_descricao">Status</th>
                           <th data-field="status_id" data-visible="false"></th>
                           <th data-field="data" data-formatter="dtFormatter">Data</th>
                           <th data-field="button" data-formatter="buttonFormatter" data-events="operateEvents">
                               Ação
                           </th>
                       </tr>
                   </thead>
            </table>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="andamentoModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="andamentoModalLabel"><b>Andamento de autorizações</b></h5>
      </div>
      <div class="modal-body">
         <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <label><b>Id da autorização:</b></label>
                        <?php
                            db_input("empautoriza_id", 10, 3, true, 'text', 3)
                        ?>
                       <?php
                            db_input("andamento_id", 10, 3, true, 'hidden', 3)
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label><b>Status:</b></label>
                        <?php
                            $db_opcao_textearea = 1;
        
                        if ($modo_andamento == 'consulta') {
                            $db_opcao_textearea = 5;
                        }
                            
                            $clAndamentoEmppreAutorizacaoStatus = new cl_andamentoemppreautorizacaostatus;
                            $sqlAndamentoAutorizacaoStatus = $clAndamentoEmppreAutorizacaoStatus->sql_query_file(
                                $id = null,
                                $campos = '*',
                                $ordem = 'id'
                            );
                            $result = db_query($sqlAndamentoAutorizacaoStatus);
                            db_selectrecord("status_id", $result, false, 1, " ", "", "", "", "", "");

                            ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <labeL><b>Observação:</b></labeL>
                        <?php
                            db_textarea('observacao', 3, 54, 'observacao', true, 'text', $db_opcao_textearea, "")
                        ?>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <?php if ($modo_andamento == 'gerencial') : ?>
            <button type="button" class="btn btn-primary" onClick="jsProcessar()">Processar</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detalhes -->
<div class="modal fade" id="andamentoDetalhesModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="andamentoDetalhesModalLabel"><b>Detalhamento do Andamento</b></h5>
      </div>
      <div class="modal-body">
         <div class="row">
            <div class="col-md-12">
                <table id="data-table-detalhes"
                    class="table table-sm"
                    data-height="250"
                    data-virtual-scroll="true"
                    style="width: 100%;">
                    <thead>
                        <tr>
                            <th data-field="andamento_id" data-visible="false"></th>
                            <th data-field="e54_autori">Id Autorizacao</th>
                            <th data-field="e54_anousu">Ano</th>
                            <th data-field="e54_valor" data-formatter="valorFormatter">Valor</th>
                            <th data-field="z01_nome">CGM</th>
                            <th data-field="status_descricao">Status</th>
                            <th data-field="status_id" data-visible="false"></th>
                            <th data-field="data" data-formatter="dtFormatter">Data</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<?php db_menu();?>


</body>
</html>

<!-- requires bootstrap table -->
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>
<link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
<link type="text/css" href="assets/bootstrap-4.5.3/css/bootstrap.min.css" rel="stylesheet">

<script>

jsPesquisar();

window.operateEvents = {
    'click .buttonFormat': function (e, value, row, index) {
        
        let andamentoPropriedades = row;
        $('#andamento_id').val(row.andamento_id);  
        $('#empautoriza_id').val(row.e54_autori);    
        $('#status_id').val(row.status_id);
        $('#observacao').val(row.observacao);
        $('#andamentoModal').modal('show');
    },
    'click .buttonAndamento': function (e, value, row, index) {
        
        const formData = new FormData();
        formData.append('acao', 'obterDetalhamentoAndamento');
        formData.append('empautoriza', row.e54_autori);

        HttpClient.post("emp4_emppreautoriza.RPC.php", {body: formData}).then(response => {
            tableDetalhes.bootstrapTable('load', response.andamento_detalhes);
            $('#andamentoDetalhesModal').modal('show');
        });
    }
}

let table = $('#data-table');
let tableDetalhes = $('#data-table-detalhes');

table.bootstrapTable({
    uniqueId: "pl9_codigo",
    locale: 'pt-BR',
    cache: false,
    height: 450,
    search: true,
    showButtonText: true,
    class: "table table-sm"
});

tableDetalhes.bootstrapTable({
    uniqueId: "pl9_codigo",
    locale: 'pt-BR',
    cache: false,
    height: 400,
    search: false,
    showButtonText: true,
    class: "table table-sm"
});

function buttonFormatter(value, row, index) {
    
    <?php
    $btnIcone = '<i class="fa fa-edit"></i>';
    $btnTitle = 'Editar';
    if ($modo_andamento == 'consulta') {
        $btnTitle = 'Ver Detalhes';
        $btnIcone = '<i class="fa fa-search"></i>';
    }
    ?>

    return [
      '<a class="buttonFormat" href="javascript:void(0)" title="<?= $btnTitle ?>">',
      '<?= $btnIcone ?>',
      '</a>  ',
      '&nbsp;<a class="buttonAndamento" href="javascript:void(0)" title="Ver Andamento">',
      '<i class="fa fa-file"></i>',
      '</a>  '
    ].join('')
}

function dtFormatter(value, row, index) {
    return js_formatar(value, 'd');
}

function valorFormatter(value, row, index) {
    return js_formatar(value, 'f');
}


function jsPesquisar(){
    
    const formData = new FormData();

    formData.append('acao', 'ListaAutorizacoes');
    formData.append('dataInicial', $('#dataInicial' ).val());
    formData.append('dataFinal', $('#dataFinal' ).val());
    formData.append('status', $('#statusFiltro' ).val());
    formData.append('modo', '<?= $modo_andamento ?>');

    HttpClient.post("emp4_emppreautoriza.RPC.php", {body: formData}).then(response => {

        table.bootstrapTable('load', response.andamentos);
    });

}

function jsProcessar() {

    const formData = new FormData();

    formData.append('acao', 'AtualizarAndamento');
    formData.append('empautoriza_id', $('#empautoriza_id' ).val());
    formData.append('status_id', $('#status_id' ).val());
    formData.append('observacao', $('#observacao' ).val());
    formData.append('idAndamento', $('#andamento_id' ).val());

    HttpClient.post("emp4_emppreautoriza.RPC.php", {body: formData}).then(response => {
      alert(response.mensagem); 
      jsPesquisar();
      $('#andamentoModal').modal('hide');
    });
}

jQuery(document).ready(jQuery => {
    <?php if ($modo_andamento == 'consulta') : ?>
        jQuery('#status_id').attr('disabled', 'disabled');
    <?php endif; ?>
});
</script>