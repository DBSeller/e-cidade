<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form id="form-upload" method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>
                Relatório de Liquidação/Agrupamento
            </legend>
            <div class="text-left" style="margin-top: 5px; margin-bottom: 5px; display: flex">
                <div></div>
                <label for="exercicio" class="bold" style="margin-top: 3px; padding: 0 2px">Competencia: </label>
                <div>
                    <input id="exercicio" name="exercicio">
                </div>
                <span style="margin-top: 4px; padding: 0 3px">até</span>
                <div>
                    <input id="exercicio2" name="exercicio2">
                </div>
            </div>
        </fieldset>
        <button type="button" class="btn btn-light" id="btnrelatorio" onclick="gerarRelatorio()">
            Gerar relatório
        </button>
    </form>
</div>

<?php db_menu() ?>

<script>
    const datainicio = new DBInputDate(document.getElementById('exercicio'));
    const datafinal = new DBInputDate(document.getElementById('exercicio2'));

    function gerarRelatorio(){
        if(datainicio.getValue() == null || datafinal.getValue() == null){
            alert("É necessário informar o período.")
            return;
        }
        if(datainicio.__toLocaleDateString() > datafinal.__toLocaleDateString()){
            alert("A primeira data não pode ser maior que a segunda.")
            return;
        }

        const jan = window.open('emp2_relempenholiquidacao002.php?dataini='
            + datainicio.__toLocaleDateString()
            + '&datafim=' + datafinal.__toLocaleDateString());
        jan.moveTo(0,0);
    }

</script>
