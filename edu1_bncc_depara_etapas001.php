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
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form id="frmDeparaEtapa" method="post" action="">
        <fieldset>
            <legend>De Para das Etapas</legend>
            <table class="form-container">
                <tr>
                    <td><label for="ensino_bncc">Ensino BNCC:</label></td>
                    <td>
                        <select id="ensino_bncc" name="ensino_bncc">
                            <option value="">Selecione</option>
                            <option value="EF">Ensino Fundamental</option>
                            <option value="EM">Ensino Médio</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="etapa_bncc">Etapa BNCC:</label></td>
                    <td>
                        <select id="etapa_bncc" name="etapa_bncc">
                            <option value="">Selecione a Etapa da BNCC</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <fieldset>
                            <legend><label for="etapa_ecidade">Selecione as etapas equivalentes</label></legend>
                            <div class="alert alert-warning text-left" role="alert">
                                Segure <kbd>Ctrl + Click Esquerdo do Mouse</kbd><br>para selecionar mais de uma etapa
                            </div>

                            <select id="etapa_ecidade" name="etapa_ecidade" multiple style="height: 300px">
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
    const formulario = document.getElementById('frmDeparaEtapa'),
        cboEnsinoBNCC = document.getElementById('ensino_bncc'),
        cboEtapasBNCC = document.getElementById('etapa_bncc'),
        cboEtapasEcidade = document.getElementById('etapa_ecidade'),
        btnSalvar = document.getElementById('btnSalvar');

    const limparEtapasEcidade = () => {
        // cboEtapasEcidade.options.length = 0;
        cboEtapasEcidade.innerHTML = '';
    };
    const limpar = () => {
        cboEtapasBNCC.options.length = 0;
        limparEtapasEcidade();
    };

    const buscarEtapas = (ensino) => {
        if (ensino === '') {
            return;
        }

        const formData = new FormData(formulario);
        formData.append('acao', 'buscarEtapasBNCC');
        formData.append('ensino', ensino);
        HttpClient.post('edu4_depara_BNCC.RPC.php', {body: formData}).then(response => {

            if (response.erro) {
                alert(response.mensagem);
                return;
            }
            cboEtapasBNCC.options.length = 0;
            cboEtapasBNCC.add(new Option('Selecione a Etapa da BNCC', ''));
            response.etapasBNCC.map(function (etapa) {
                cboEtapasBNCC.add(new Option(etapa.etapa, etapa.codigo))
            });
        });
    };

    const buscarEtapasEcidade = (ensino, codigo_etapa) => {
        if (codigo_etapa === '') {
            return;
        }

        const formData = new FormData(formulario);
        formData.append('acao', 'buscarEtapasEcidade');
        formData.append('ensino', ensino);
        formData.append('codigoEtapa', codigo_etapa);
        HttpClient.post('edu4_depara_BNCC.RPC.php', {body: formData}).then(response => {

            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            limparEtapasEcidade();
            response.ensinos.forEach(function (ensino) {
                const group = document.createElement('optgroup');
                group.label = ensino.ensino;
                ensino.etapas.map(function (etapa) {
                    const option = new Option(etapa.etapa, etapa.codigo_etapa);
                    if (etapa.equivalente) {
                        option.setAttribute('selected', 'selected');
                    }
                    group.appendChild(option);
                });

                cboEtapasEcidade.add(group)
            });
        });
    };

    cboEnsinoBNCC.addEventListener('change', (event) => {
        limpar();
        buscarEtapas(event.target.value);
    });

    cboEtapasBNCC.addEventListener('change', (event) => {
        limparEtapasEcidade();
        buscarEtapasEcidade(cboEnsinoBNCC.value, event.target.value);
    });

    btnSalvar.addEventListener('click', () => {
        const formData = new FormData(formulario);
        formData.append('acao', 'salvarDeParaEtapas');

        for (var i = 0; i < cboEtapasEcidade.options.length; i++) {
            const option = cboEtapasEcidade.options[i];
            if (option.selected) {
                formData.append('etapas[]', option.value)
            }
        }

        if (empty(cboEtapasEcidade.value)) {
            alert('Selecione as etapas equivalentes.');
            return;
        }

        HttpClient.post('edu4_depara_BNCC.RPC.php', {body: formData}).then(response => {

            alert(response.mensagem);
            if (response.erro) {
                return;
            }
            cboEtapasBNCC.value = '';
            limparEtapasEcidade();
        });
    });

</script>
