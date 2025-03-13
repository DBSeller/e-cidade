<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');

?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
    <title>DBSeller Informática Ltda</title>
    <style>
        .DBJanelaIframe {
            top: 60px;
            width: 100%;
            height: 90%;
        }
    </style>
</head>
<body class="body-default">
<?php
require_once modification('forms/db_frm_teto_orcamentario.php');
db_menu();
?>
<script rel="script" type="text/javascript">
    document.querySelector('#pesquisar').dispatchEvent(new Event('click'));
    document.querySelector('#pesquisar').style = {display: ''};
    document.querySelector('#limpar').style = {display: 'none'};
</script>
</body>
</html>
