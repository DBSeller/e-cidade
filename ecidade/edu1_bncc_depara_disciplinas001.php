<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/all.min.css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form id="frmDeparaDisciplina" method="post" action="">
        <fieldset>
            <legend>De Para das Disciplinas/Componentes Curriculares</legend>
            <table class="form-container">
                <tr>
                    <td><label for="disciplina_bncc">Disciplina BNCC:</label></td>
                    <td>
                        <select id="disciplina_bncc" name="disciplina_bncc">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend><label for="disciplina_ecidade">Selecione as disciplinas equivalentes</label></legend>
                            <div class="alert alert-warning text-left" role="alert">
                                Segure <kbd>Ctrl + Click Esquerdo do Mouse</kbd><br>para selecionar mais de uma disciplina
                            </div>

                            <select id="disciplina_ecidade" name="disciplina_ecidade" multiple style="height: 300px">
                            </select>
                        </fieldset>
                    </td>
                </tr>
            </table>

        </fieldset>
        <button id="btnSalvar" type="button">
            <i class="fas fa-save"></i>
            Salvar
        </button>
    </form>
</div>
</body>
<script>
    const formulario = document.getElementById('frmDeparaDisciplina'),
        cboDisciplinaBNCC = document.getElementById('disciplina_bncc'),
        cboDisciplinaEcidade = document.getElementById('disciplina_ecidade'),
        btnSalvar = document.getElementById('btnSalvar');

    const limpar = () => {
        const nDisciplinas = cboDisciplinaEcidade.options.length;
        for (var i = 0; i < nDisciplinas; i++) {
            cboDisciplinaEcidade.options[i].selected = false;
        }
    };

    const buscarDisciplinasEquivalentes = (disciplinaBncc) => {
        if (disciplinaBncc === '') {
            return;
        }

        const formData = new FormData();
        formData.append('acao', 'buscarEquivalentes');
        formData.append('disciplina', disciplinaBncc);
        HttpClient.post('edu4_depara_BNCC.RPC.php', {body: formData}).then(response => {

            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            const nDisciplinas = cboDisciplinaEcidade.options.length;
            for (var i = 0; i < nDisciplinas; i++) {
                if (response.equivalencias.in_array(cboDisciplinaEcidade.options[i].value))  {
                    cboDisciplinaEcidade.options[i].selected = true;
                }
            }
        });
    };

    cboDisciplinaBNCC.addEventListener('change', (event) => {
        limpar();
        buscarDisciplinasEquivalentes(event.target.value);
    });

    btnSalvar.addEventListener('click', () => {
        const formData = new FormData(formulario);
        formData.append('acao', 'salvarDeParaDisciplinas');

        for (var i = 0; i < cboDisciplinaEcidade.options.length; i++) {
            if (cboDisciplinaEcidade.options[i].selected) {
                formData.append('disciplinas[]', cboDisciplinaEcidade.options[i].value)
            }
        }

        if (empty(cboDisciplinaBNCC.value)) {
            alert('Selecione uma disciplina da BNCC.');
            return;
        }

        if (empty(cboDisciplinaEcidade.value)) {
            alert('Selecione as disciplinas equivalentes.');
            return;
        }

        HttpClient.post('edu4_depara_BNCC.RPC.php', {body: formData}).then(response => {
            alert(response.mensagem);
            if (response.erro) {
                return;
            }

            cboDisciplinaBNCC.value = '';
            limpar();
        });
    });


    (function () {
        const formData = new FormData();
        formData.append('acao', 'buscarDisciplinas');
        HttpClient.post('edu4_depara_BNCC.RPC.php', {body: formData}).then(response => {
            if (response.erro) {
                return;
            }

            response.disciplinasBncc.map(function (disciplina) {
                cboDisciplinaBNCC.add(new Option(disciplina.nome, disciplina.codigo));
            });

            response.disciplinasEcidade.map(function (disciplina) {
                cboDisciplinaEcidade.add(new Option(disciplina.nome, disciplina.codigo));
            });
        });
    })();

</script>
