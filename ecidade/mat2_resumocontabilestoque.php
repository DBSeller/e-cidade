<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
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
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <style>
        input[type=radio], label {
            cursor: pointer;
        }
    </style>
</head>
<body class="body-default">
<div class="container">
    <fieldset>
        <legend>Resumo contábil de estoque</legend>
        <table class="form-container" style="border-collapse: separate;">
            <tr>
                <td>
                    Período:
                </td>
                <td>
                    <input type="date" id="data_inicial"> até
                    <input type="date" id="data_final">
                </td>
            </tr>
            <tr>
                <td>Tipo de agrupamento:</td>
                <td>
                    <input type="checkbox" name="tipo_agrupamento" id="conta_patrimonial" />
                    <label for="conta_patrimonial">Conta Patrimonial</label>
                    <input type="checkbox" name="tipo_agrupamento" id="grupo" />
                    <label for="grupo">Grupo/Subgrupo</label>
                </td>
            </tr>
            <tr>
                <td>Tipo de impressão:</td>
                <td>
                    <select name="tipo_impressao" id="tipo_impressao" disabled>
                        <option value="0">Analítico</option>
                        <option value="1">Sintético</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Exibir transferências? </td>
                <td>
                    <input type="radio" name="transferencias" value="t" id="transferencias_sim" />
                    <label for="transferencias_sim">Sim</label>
                    <input type="radio" name="transferencias" value="f" id="transferencias_nao" checked />
                    <label for="transferencias_nao">Não</label>
                </td>
            </tr>
            <tr>
                <td>Somente materiais com saldo? </td>
                <td>
                    <input type="radio" name="somente_com_saldo" value="t" id="somente_com_saldo_sim" />
                    <label for="somente_com_saldo_sim">Sim</label>
                    <input type="radio" name="somente_com_saldo" value="f" id="somente_com_saldo_nao" checked />
                    <label for="somente_com_saldo_nao">Não</label>
                </td>
            </tr>
            <tr>
                <td>Ordenar por: </td>
                <td>
                    <select name="ordem" id="ordem">
                        <option value="0">Código do Material</option>
                        <option value="1">Descrição do Material</option>
                    </select>
                </td>
            </tr>
            <tr<?php echo db_getsession('DB_id_usuario') == 1 ? '' : ' style="display:none"'; ?>>
                <td colspan="2" style="text-align: center">
                    <input type="checkbox" name="inconsistencias" id="inconsistencias" />
                    <label for="inconsistencias">Somente itens inconsistentes</label>
                </td>
            </tr>
        </table>
    </fieldset>
    <div id="lancadorContas" hidden></div>
    <div id="lancadorGrupos" hidden></div>
    <div id="lancadorDepositos"></div>
    <button type="button" class="btn btn-light" id="button_emitir">
        <i class="fas fa-print"></i>
        Emitir relatório
    </button>
</div>

<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">
    var urlApi;
    window.addEventListener('load', () => {
        PHPSession.loadData().then(() => {
            urlApi = PHPSession.requestApi;
        });
    });

    const cboTipoImpressao = document.getElementById("tipo_impressao");
    const inputContaPatrimonial = document.getElementById("conta_patrimonial");
    const inputGrupo = document.getElementById("grupo");
    const cboOrdem = document.getElementById("ordem");
    const ctnLancadorContas = document.getElementById("lancadorContas");
    const ctnLancadorGrupos = document.getElementById("lancadorGrupos");

    const inputDataInicial = new DBInputDate(document.getElementById("data_inicial"));
    const inputDataFinal = new DBInputDate(document.getElementById("data_final"));
    const btnEmitir = document.getElementById("button_emitir");

    const retornoPesquisaDeposito = (campo1, campo2, codigo, descricao) => {
        if (empty(codigo) && empty(descricao)) {
            return;
        }
        depositoCodigo.value = codigo;
        depositoDescricao.value = descricao;
    }

    var oLancadorConta = new DBLancador('LancadorConta');
    oLancadorConta.setLabelAncora("Conta: ");
    oLancadorConta.setTextoFieldset("Conta Patrimonial");
    oLancadorConta.setTituloJanela("Pesquisar Conta Patrimonial");
    oLancadorConta.setNomeInstancia("oLancadorConta");
    oLancadorConta.setParametrosPesquisa("func_conplano.php", ["c60_codcon", "c60_descr"]);
    oLancadorConta.setParametro('contasPatrimoniais', 'true');
    oLancadorConta.setGridHeight(150);
    oLancadorConta.show(ctnLancadorContas);
    new DBToogle('flsdLancadorConta', false);

    var oLancadorGrupo = new DBLancador('LancadorGrupo');
    oLancadorGrupo.setLabelAncora("Grupo: ");
    oLancadorGrupo.setTextoFieldset("Grupos");
    oLancadorGrupo.setTituloJanela("Pesquisar Grupo");
    oLancadorGrupo.setNomeInstancia("oLancadorGrupo");
    oLancadorGrupo.setParametrosPesquisa("func_materialestoquegrupo.php", ["m65_sequencial", "db121_descricao"]);
    oLancadorGrupo.setGridHeight(150);
    oLancadorGrupo.show(ctnLancadorGrupos);
    new DBToogle('flsdLancadorGrupo', false);

    var oLancadorDeposito = new DBLancador('LancadorDeposito');
    oLancadorDeposito.setLabelAncora("Depósito: ");
    oLancadorDeposito.setTextoFieldset("Depósitos");
    oLancadorDeposito.setTituloJanela("Pesquisar Depósito");
    oLancadorDeposito.setNomeInstancia("oLancadorDeposito");
    oLancadorDeposito.setParametrosPesquisa(
        "func_db_almox.php",
        ["m91_codigo", "descrdepto"],
        "sDescricaoDepartamento=false"
    );
    oLancadorDeposito.setGridHeight(150);
    oLancadorDeposito.show(document.getElementById("lancadorDepositos"));

    btnEmitir.addEventListener('click', () => {
        if (inputDataInicial.value != null &&
            inputDataFinal.value != null &&
            inputDataInicial.value > inputDataFinal.value) {
            alert('Data inicial não pode ser maior que a data final.');
            return;
        }

        const formData = new FormData();
        formData.append(
            'dataInicial',
            inputDataInicial.__toLocaleDateString() ? js_formatar(inputDataInicial.__toLocaleDateString(), 'd') : ''
        );
        formData.append(
            'dataFinal',
            inputDataFinal.__toLocaleDateString() ? js_formatar(inputDataFinal.__toLocaleDateString(), 'd') : ''
        );
        let depositos = oLancadorDeposito.getRegistros().map((deposito) => {
            return deposito.sCodigo;
        });
        let contas = oLancadorConta.getRegistros().map((conta) => {
            return conta.sCodigo;
        });
        let grupos = oLancadorGrupo.getRegistros().map((grupo) => {
            return grupo.sCodigo;
        });
        formData.append('depositos', depositos.join(','));
        formData.append('contas', contas.join(','));
        formData.append('grupos', grupos.join(','));
        formData.append('transferencias', document.querySelector('input[name="transferencias"]:checked').value);
        formData.append('somente_com_saldo', document.querySelector('input[name="somente_com_saldo"]:checked').value);
        formData.append('inconsistencias', document.getElementById("inconsistencias").checked);
        formData.append('tipo_impressao', cboTipoImpressao.value);
        formData.append('ordem', cboOrdem.value);
        document.querySelectorAll('input[name="tipo_agrupamento"]:checked').forEach((checkbox) => {
            formData.append(checkbox.getAttribute('id'), 'on');
        });
        PHPSession.appendFormData(formData);
        HttpClient.post(`${urlApi}/patrimonial/material/relatorios/resumo-contabil-estoque`, { body: formData })
            .then((response) => {
                if (response.error) {
                    alert(response.message);
                    return;
                }
                window.open(
                    response.data.file,
                    '',
                    'height=' + screen.height + ',width=' + screen.width + 'scrollbars=1,location=0'
                );
            });
    });

    inputContaPatrimonial.addEventListener('change', event => {
        verificarTipoImpressao();
        if (inputContaPatrimonial.checked) {
            ctnLancadorContas.removeAttribute('hidden');
            return;
        }
        oLancadorConta.clearAll();
        ctnLancadorContas.setAttribute('hidden', 'hidden');
    });
    inputGrupo.addEventListener('change', event => {
        verificarTipoImpressao();
        if (inputGrupo.checked) {
            ctnLancadorGrupos.removeAttribute('hidden');
            return;
        }
        oLancadorGrupo.clearAll();
        ctnLancadorGrupos.setAttribute('hidden', 'hidden');
    });
    const verificarTipoImpressao = () => {
        cboTipoImpressao.disabled = false;
        if (!inputContaPatrimonial.checked && !inputGrupo.checked) {
            cboTipoImpressao.value = 0;
            cboTipoImpressao.disabled = true;
        }
    }
</script>
</body>
</html>
