<?php


require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta_plugin.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form id="formAprovacao">
        <fieldset>
            <legend>Controle de aprovação</legend>
            <table class="form-container">
                <tr>
                    <td><label for="tipoPlano">Tipo de Plano:</label></td>
                    <td>
                        <select id="tipoPlano" name="pl2_tipo">
                            <option value="">Selecione</option>
                            <option value="PPA">PPA</option>
                            <option value="LDO">LDO</option>
                            <option value="LOA">LOA</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="plano">Plano:</label></td>
                    <td>
                        <select id="plano" name="pl2_codigo">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="situacaoAtual">Situação atual:</label></td>
                    <td><input type="text" id="situacaoAtual" class="readonly field-size7 bold"></td>
                </tr>
                <tr>
                    <td><label for="situacaoAtualizar">Situação:</label></td>
                    <td>
                        <select id="situacaoAtualizar" name="pl1_codigo">
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button type="button" id="btnSalvar" disabled>
            <i class="far fa-save"></i>
            Salvar
        </button>
    </form>
</div>

<?php db_menu() ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<script type="text/javascript">

    const routs = {
        planos: "financeiro/planejamento/consulta/planos",
        getSituacoes: "financeiro/planejamento/consulta/situacoes/movimentar/plano",
        atualizar: "financeiro/planejamento/status-planejamento/situacao"

    }

    let planos = [];

    const dados = {
        form: document.getElementById('formAprovacao'),
        tipoPlano: document.getElementById('tipoPlano'),
        plano: document.getElementById('plano'),
        situacaoAtual: document.getElementById('situacaoAtual'),
        situacaoAtualizar: document.getElementById('situacaoAtualizar'),
        btnSalvar: document.getElementById('btnSalvar'),
    }

    dados.tipoPlano.addEventListener('change', (e) => {
        planos = [];
        dados.situacaoAtual.value = '';
        dados.situacaoAtualizar.length = 0;
        dados.situacaoAtualizar.add(new Option('Selecione', ''));
        dados.btnSalvar.setAttribute('disabled', 'disabled');

        if (dados.tipoPlano.value === '') {
            dados.form.reset();
            return;
        }

        HttpClient.get(`${PHPSession.requestApi}/${routs.planos}/${dados.tipoPlano.value}`).then(response => {
            dados.plano.length = 0;
            dados.plano.add(new Option('Selecione', ''));
            if (response.error) {
                alert(response.message);
            }

            response.data.map(plano => {
                dados.plano.add(new Option(plano.pl2_titulo, plano.pl2_codigo));
                planos.push(plano);
            });
        });
    });

    dados.plano.addEventListener('change', () => {

        if (dados.plano.value == '') {
            dados.situacaoAtual.value = '';
            dados.situacaoAtualizar.length = 0;

            return;
        }

        planos.map(plano => {
            if (plano.pl2_codigo == dados.plano.value) {
                dados.situacaoAtual.value = plano.status.pl1_descricao;
            }
        });


        HttpClient.get(`${PHPSession.requestApi}/${routs.getSituacoes}/${dados.plano.value}`).then(response => {

            dados.situacaoAtualizar.length = 0;
            dados.situacaoAtualizar.add(new Option('Selecione', ''));

            if (response.error) {
                alert(response.message);
                return;
            }

            response.data.map(situacao => {
                dados.situacaoAtualizar.add(new Option(situacao.pl1_descricao, situacao.pl1_codigo));
            });

            dados.btnSalvar.removeAttribute('disabled');
        });
    });


    dados.btnSalvar.addEventListener('click', () => {

        if (dados.situacaoAtualizar.value === '') {
            alert('Selecione para a qual deseja atualizar.');
            return;
        }

        let plano = dados.plano.options[dados.plano.selectedIndex].innerHTML;
        let descricao = dados.situacaoAtualizar.options[dados.situacaoAtualizar.selectedIndex].innerHTML;


        let msg = `Confirma a atualização do Plano de Governo ${plano} de ${dados.situacaoAtual.value} para ${descricao}?`;
        msg += `\nEssa alteração pode ser irreversível.`;

        alertify.confirm(msg, confirma => {
            if (confirma) {
                const formData = new FormData(dados.form);
                PHPSession.appendFormData(formData);

                const parametros = {
                    body: formData,
                    reportMessage: `Aguarde, atualizando situação do plano ${plano}.`
                }

                HttpClient.post(`${PHPSession.requestApi}/${routs.atualizar}`, parametros).then(response => {
                    alert(response.message);
                    if (response.error) {
                        return;
                    }
                    dados.form.reset();
                });
            }
        });
    });

</script>
</body>
</html>

