<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

$nome = $_GET['nome'];
$numeroCgm = $_GET['numeroCgm'];

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" />
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <style>
        button {
            background-color: #d9d5d5;
            border-radius: 2px; 
            font-family: Arial, Helvetica, sans-serif, verdana;
            font-size: 12px;
            height: 18px;
            border: 1px solid #999999;
            margin-top: 10px;
        }

        p {
            font-size: 15px;
        }

        label {
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div style="text-align:center; margin-top:100px;">
    <p>
        <strong><?php echo $nome ?> não possui CPF/CNPJ cadastrado no CGM</strong><br>
        <strong>portanto não há como definir se é uma PESSOA FÍSICA OU JURÍDICA</strong><br>
    </p>

    <form method="GET">
        <div>
            <label for="tipo-pessoa">Tipo de Pessoa:</label>
            <select id="tipo-pessoa" name="tipo-pessoa">
                <option value="f">Física</option>
                <option value="j">Jurídica</option>
            </select>
        </div>
        <button id="btn-tipo-pessoa" type="button">Selecionar</button>
    </form>
</div>
</body>

<script>
    const btn = document.getElementById('btn-tipo-pessoa');
    const numeroCgm = '<?php echo $numeroCgm ?>';

    btn.addEventListener('click', () => {
        const tipoPessoa = document.getElementById('tipo-pessoa').value;
        window.location.href = `prot1_cadgeralmunic002.php?chavepesquisa=${numeroCgm}&tipoPessoa=${tipoPessoa}`;
    });
</script>
</html>