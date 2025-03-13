<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta_plugin.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form id="frmFiltros">

        <fieldset>
            <legend>Aluno Infrequente - FICAI</legend>
            <fieldset class="separator">
                <legend>Selecione o aluno</legend>
                <table class="form-container">
                    <tr>
                        <td><a id="ancora_aluno" href="#">Aluno:</a></td>
                        <td>
                            <input type="text" value="" id="codigo_aluno" name="codigo_aluno" lang="ed47_i_codigo"
                                   class="field-size2"/>
                            <input type="text" id="descricao_aluno" name="descricao_aluno" lang="ed47_v_nome"
                                   class="readonly field-size8"/>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="separator">
                <legend>Preenchimento da FICAI</legend>
                <fieldset>
                    <legend><label for="procedimento_escola">Procedimentos adotados pela escola</label></legend>
                    <textarea id="procedimento_escola" rows="5" cols="70"></textarea>
                </fieldset>
                <fieldset>
                    <legend><label for="observacao_aluno">Observação acerca do aluno</label></legend>
                    <textarea id="observacao_aluno" rows="5" cols="70"></textarea>
                </fieldset>
                <div class="text-left">
                    <label class="bold" for="data_encaminhamento">Data de encaminhamento para Conselho Tutelar: </label>
                    <input type="text" name="data_encaminhamento" id="data_encaminhamento">
                </div>
            </fieldset>
        </fieldset>

        <button type="button" id="btnImprimir" name="btnImprimir">
            <i class="fas fa-print"></i>
            <label>Imprimir</label>
        </button>
    </form>
</div>
<?php
db_menu();
?>
<script type="text/javascript">
    const ancoraAluno = document.getElementById('ancora_aluno');
    const codigoAluno = document.getElementById('codigo_aluno');
    const labelAluno = document.getElementById('descricao_aluno');

    const procedimentoEscola = document.getElementById(('procedimento_escola'));
    const observacaoAluno = document.getElementById(('observacao_aluno'));
    const data = new DBInputDate(document.getElementById('data_encaminhamento'));

    const lookUpAluno = new DBLookUp(ancoraAluno, codigoAluno, labelAluno, {
        'sArquivo': 'func_aluno_matriculado.php',
        'sLabel': 'Pesquisar Alunos Matrículados na Escola',
        'sObjetoLookUp': "db_iframe_aluno"
    });

    $('btnImprimir').addEventListener('click', function (){
        if (codigoAluno.value == '') {
            alert("Aluno não informado.");
            return false;
        }

        const dados =  btoa(JSON.stringify({
            "procedimentoEscola": procedimentoEscola.value.urlEncode(),
            "observacaoAluno": observacaoAluno.value.urlEncode(),
            "data": $F('data_encaminhamento')
        }));

        var url = `edu2_ficai002.php?aluno=${codigoAluno.value}&dados=${dados}`;
        window.open(url, '', 'scrollbars=1,location=0');
    });

</script>
