<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/materialestoque.model.php"));
?>

<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Informática Ltda</title>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <link rel="stylesheet" type="text/css" href="estilos.css">
</head>
<body>
<div class="container">
    <form name="form1" method="post" action="">
        <fieldset>
            <legend><b>Selecionar Depósitos</b></legend>
            <table class="form-container">
                <tr>
                    <td nowrap="nowrap" colspan=2>
                        <div id="ctnDeposito"></div>
                    </td>
                </tr>
            </table>
            <fieldset>
                <legend><b>Observação</b></legend>
                <tr>
                    <td>
                        <textarea id="obsSaida" type="text" name="obsSaida" rows="3" cols="76" rel="ignore-css"></textarea>
                    </td>
                </tr>
            </fieldset>
        </fieldset>
        <button type="button" class="btn btn-light" id="btn_processar">
            <i class="fas fa-angle-double-right"></i>
            Processar
        </button>
    </form>
</div>
</body>
</html>
<script>
    var oLancadorDeposito = new DBLancador('LancadorDeposito');
    oLancadorDeposito.setLabelAncora("Depósito");
    oLancadorDeposito.setTextoFieldset("Depósito");
    oLancadorDeposito.setTituloJanela("Pesquisar Depósito");
    oLancadorDeposito.setNomeInstancia("oLancadorDeposito");
    oLancadorDeposito.setParametrosPesquisa("func_db_almox.php", ["m91_codigo", "descrdepto"], "sDescricaoDepartamento=false");
    oLancadorDeposito.setGridHeight(150);
    oLancadorDeposito.show($("ctnDeposito"));

    const btnProcessar = document.getElementById("btn_processar");
    const observacao = document.getElementById('obsSaida');

    btnProcessar.addEventListener('click', () => {
        const depositos = [];

        if (oLancadorDeposito.getRegistros() <= 0) {
            alert('Por favor, adicione ao menos um depósito!');
            return false;
        }
        if (observacao.value == '') {
            alert('Por favor, adicione uma observação!');
            return false;
        }
        oLancadorDeposito.getRegistros().each(function(oDadosAlmoxarifado, iIndice) {
            depositos.push(oDadosAlmoxarifado.sCodigo);
        });
        if (confirm('Confirma saída manual de todos itens com estoque para os depósitos selecionados?')) {
            var oParametros           = {};
            oParametros.exec          = "saidaDeposito";
            oParametros.depositos = depositos;
            oParametros.observacao = observacao.value;
            js_divCarregando("Aguarde, efetuando saida","msgBox");
            var oAjax = new Ajax.Request(
                'mat4_requisicaoRPC.php',
                {
                    method    : 'post',
                    parameters: 'json='+Object.toJSON(oParametros),
                    onComplete: js_saidaAtendimento
                }
            );
        }

        function js_saidaAtendimento(oAjax) {
            js_removeObj('msgBox');
            var oRetorno  = JSON.parse(oAjax.responseText);
            if (oRetorno.inconsistencias && confirm('Foram encontradas inconsistências durante a saída manual por depósito, deseja emitir um relatório?')) {
                window.open(
                    oRetorno.relatorio,
                    '',
                    'height=' + screen.height + ',width=' + screen.width + 'scrollbars=1,location=0'
                );
            }
            alert(oRetorno.message.urlDecode().replace(/\\n/g,'\n'));
            document.location.reload(true);
        }
    })
</script>
