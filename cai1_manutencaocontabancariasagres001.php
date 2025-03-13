<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<div class="container">
    <button onclick="buscar()"> Buscar contas </button>
</div>
<div>
    <table id="data-table"
           class="table table-sm"
           data-height="250"
           data-virtual-scroll="true"
           style="width: 100%;">

    </table>
</div>

<?php db_menu() ?>

<script type="text/javascript" src="scripts/classes/http/http.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>

<!-- requires bootstrap table -->
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>

<script>
$.noConflict();

const colunas = [
    {
        field: 'k13_conta',
        title: 'Código da Conta',
        sortable: true,
    },
    {
        field: 'k13_dtimplantacao',
        title: 'Data da Implantação',
        sortable: true,

    },
    {
        field: 'k13_descr',
        title: 'Descrição',
        sortable: true,

    },
    {
        field: 'k13_reduz',
        title: 'Reduzido',
        sortable: true,
    },
    {
        field: 'o15_recurso',
        title: 'Recurso',
        sortable: true,
    },
    {
        field: 'o15_loaespecificacao',
        title: 'Especificação',
        sortable: true,
    },
    {
        field: 'k13_saldo',
        title: 'Saldo',
        sortable: true,

    },
    {
        field: 'k13_outrosdados',
        title: 'Outros Dados',
        formatter: (a,b) => {
            return selectFormatter(a,b)
        }
    }]


const table = jQuery('#data-table');
    table.bootstrapTable({
        uniqueId: "k13_conta",
        locale: 'pt-BR',
        cache: false,
        height: 450,
        search: true,
        pagination: true,
        showButtonText: true,
        columns: colunas
    });

    function buscar(){
        const formData = new FormData();
        PHPSession.appendFormData(formData);
        HttpClient.post(`${PHPSession.requestApi}/financeiro/tesouraria/contatesouraria/buscar`,{body: formData}).then(response => {
            table.bootstrapTable('load', response.data);
        });
    }

    function selectFormatter(a,object){
        if(JSON.parse(object.k13_outrosdados).conta_ativa == 'true'){
            var conta_ativa = `<option value="conta_ativa.true" selected>SIM</option>
            <option value="conta_ativa.false">NÃO</option>`
        }else{
            var conta_ativa = `<option value="conta_ativa.true">SIM</option>
            <option value="conta_ativa.false" selected>NÃO</option>`
        }
        if(JSON.parse(object.k13_outrosdados).enviada_sagres == 'true'){
            var enviada_sagres = `<option value="enviada_sagres.true" selected>SIM</option>
            <option value="enviada_sagres.false">NÃO</option>`
        }else{
            var enviada_sagres = `<option value="enviada_sagres.true">SIM</option>
            <option value="enviada_sagres.false" selected>NÃO</option>`
        }
        return `<table>
                    <tr>
                        <td>
                            <label>Conta ativa: </label>
                        </td>
                        <td>
                            <select onchange="atualizaOutrosDados(${object.k13_conta},this.value)" name="select">
                            ${conta_ativa}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Enviada Sagres:</label>
                        </td>
                        <td>
                            <select onchange="atualizaOutrosDados(${object.k13_conta},this.value)" name="select" align="right">
                            ${enviada_sagres}
                            </select>
                        </td>
                    </tr>
                </table>`


    }

    function atualizaOutrosDados(data,data2){
        const formData = new FormData();
        formData.append('conta', data);
        formData.append('changed', data2);
        PHPSession.appendFormData(formData);
        HttpClient.post(`${PHPSession.requestApi}/financeiro/tesouraria/contatesouraria/alterar`, {body: formData});
    }
</script>
</body>
