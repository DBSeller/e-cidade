<?php

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_cgm_campos_obrigatorios_classe.php"));
include(modification("dbforms/db_funcoes.php"));

$oCgmCamposObrigatorios = new \cl_cgmcamposobrigatorios();

$oPostgres = db_query($oCgmCamposObrigatorios->sql_query_file(null, null, 'p73_label'));

$htmlPessoaFisica = $htmlPessoaJuridica = '';

while ($row = pg_fetch_assoc($oPostgres)) {
    $checked = $row['p73_obrigatorio'] == 'f' ? '' : 'checked';
    $label = $row['p73_label'];
    $htmlId = $row['p73_html_id'];

    if ($row['p73_tipo_pessoa'] === 'fisica') {
        $htmlPessoaFisica .= "
            <div>
                <label><input name='{$htmlId}-fisica' type='checkbox' {$checked}> <span><b>{$label}</b></span></label>
            </div>
        ";
        
        continue;
    }

    $htmlPessoaJuridica .= "
        <div>
            <label><input name='{$htmlId}-juridica' type='checkbox' {$checked}> <span><b>{$label}</b></span></label>
        </div>
    ";
}

?>

<html>
<style>
    .font-style {
        font-family: Arial, Helvetica, serif, sans-serif, verdana;
        font-size: 12px;
        color: #000000;
    }
    
    .checkboxes input {
        vertical-align: middle;
    }
    
    .checkboxes label span {
        vertical-align: middle;
    }

    form {
        display:table;
        margin:0 auto;
    }

    button {
        cursor: pointer;
        background-color: #d9d5d5;
        border-radius: 2px;
        font-family: Arial, Helvetica, sans-serif, verdana;
        font-size: 12px;
        height: 18px;
        border: 1px solid #999999;
        margin-top: 10px;
    }
</style>
<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>
<div class="font-style" style="margin-top: 50px;">
    <form id="cgm-form"class="checkboxes">
        <fieldset style="padding:19px;">
            <legend style="font-size: 12px;" ><b>Campos obrigatórios cadastro CGM</b></legend>
            
            <fieldset style="display:inline-block; vertical-align:middle;">
                <legend style="font-size: 12px;" ><b>Cadastro pessoa física</b></legend>
                <?php echo $htmlPessoaFisica ?>
            </fieldset>
            
            <fieldset style="display:inline-block; vertical-align:middle; margin-left:20px;">
                <legend style="font-size: 12px;" ><b>Cadastro pessoa jurídica</b></legend>
                <?php echo $htmlPessoaJuridica ?>
            </fieldset>

        </fieldset>
    </form>
    <div style="text-align:center;">
        <button onclick="salvaCamposObrigatorios()">Salvar</button>
        <button onclick="limpaCampos()">Limpar</button>
    </div>
</div>

<script>

function salvaCamposObrigatorios()
{
    const formData = new FormData(document.getElementById('cgm-form'));
    formData.append('exec', 'salvaCamposObrigatorios');

    HttpClient.post('pro1_protparametrocgm.RPC.php', {body: formData}).then(response => {
        if (response.erro) {
            return alert(response.mensagem);
        }

        alert('Alteração realiazada com sucesso.');
    });
}

function limpaCampos()
{
    var inputs = document.querySelectorAll("input[type='checkbox']");

    for (var i = 0; i < inputs.length; i++) {
        inputs[i].checked = false;
    }
}

</script>

</body>
</html>