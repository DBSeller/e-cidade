<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_app.utils.php');
require_once modification('dbforms/db_funcoes.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="estilos.css" rel="stylesheet">
    <link type="text/css" href="grid.style.css" rel="styleshet">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
</head>
<body>
<div class="container">
    <fieldset>
        <legend>Controle Demanda Reprimida</legend>
        <div id="departamentos"></div>
        <div id="medicamentos"></div>
        <div id="pacientes"></div>
        <fieldset class="separator">
            <legend>Período</legend>
            <table class="form-container">
                <tr><td colspan="2"></td></tr>
                <tr>
                    <td>
                        <label for="periodo-inicio">De: </label>
                    </td>
                    <td>
                        <input id="periodo-inicio">
                        <label for="periodo-fim"> Até: </label>
                        <input id="periodo-fim">
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset class="separator">
            <legend>Parâmetros Adicionais</legend>
            <table>
                <tr>
                    <td>
                        <label class="bold" for="somente-totalizadores">Somente Totalizadores: </label>
                    </td>
                    <td>
                        <select id="somente-totalizadores" class="field-size2">
                            <option value="0">NÃO</option>
                            <option value="1">SIM</option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                    <td style="text-align: right;">
                        <label class="bold" for="exibe-observacao">Exibe Observação: </label>
                        <select id="exibe-observacao" class="field-size2">
                            <option value="0">NÃO</option>
                            <option value="1">SIM</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        <label class="bold" for="ordem">Ordem: </label>
                    </td>
                    <td colspan="3" style="text-align: left;">
                        <select id="ordem" class="field-size2">
                            <option value="">Data</option>
                            <option value="1">Medicamento</option>
                            <option value="2">Paciente</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <button onclick="imprimir();">
        <i class="fas fa-print"></i>
        Imprimir
    </button>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script>
document.getElementById('periodo-fim').value = (new Date()).toLocaleDateString('pt-BR');
const periodoInicial = new DBInputDate(document.getElementById('periodo-inicio'));
const periodoFinal = new DBInputDate(document.getElementById('periodo-fim'));

const somenteTotalizadores = document.getElementById('somente-totalizadores');
const exibeObservacao = document.getElementById('exibe-observacao');
const ordem = document.getElementById('ordem');
exibeObservacao.disabled = true;
exibeObservacao.classList.add('readonly');

var lancadorPacientes = new DBLancador('lancadorPacientes');
lancadorPacientes.selecionarAposPesquisar = true;
lancadorPacientes.setGridHeight('80px');
lancadorPacientes.setNomeInstancia('lancadorPacientes');
lancadorPacientes.setLabelAncora('Paciente:');
lancadorPacientes.setTextoFieldset('Filtrar Pacientes');
lancadorPacientes.setParametrosPesquisa('func_cgs_und.php', ['z01_i_cgsund', 'z01_v_nome']);
lancadorPacientes.setCallbackBotao(() => {
    exibeObservacao.disabled = false;
    exibeObservacao.classList.remove('readonly');
});
lancadorPacientes.setCallbackRemover(() => {
    if (lancadorPacientes.getRegistros().length == 0) {
        exibeObservacao.disabled = true;
        exibeObservacao.classList.add('readonly');
    }
});
lancadorPacientes.show(document.getElementById('pacientes'));

var lancadorMedicamentos = new DBLancador('lancadorMedicamentos');
lancadorMedicamentos.selecionarAposPesquisar = true;
lancadorMedicamentos.setGridHeight('80px');
lancadorMedicamentos.setNomeInstancia('lancadorMedicamentos');
lancadorMedicamentos.setLabelAncora('Medicamento:');
lancadorMedicamentos.setTextoFieldset('Filtrar Medicamentos');
lancadorMedicamentos.setParametrosPesquisa('func_far_matersaude.php', ['fa01_i_codigo', 'm60_descr'], 'lancador');
lancadorMedicamentos.show(document.getElementById('medicamentos'));

var lancadorDepartamentos = new DBLancador('lancadorDepartamentos');
lancadorDepartamentos.selecionarAposPesquisar = true;
lancadorDepartamentos.setGridHeight('80px');
lancadorDepartamentos.setNomeInstancia('lancadorDepartamentos');
lancadorDepartamentos.setLabelAncora('Departamento:');
lancadorDepartamentos.setTextoFieldset('Filtrar Departamentos');
lancadorDepartamentos.setParametrosPesquisa('func_unidades.php', ['sd02_i_codigo', 'descrdepto']);
lancadorDepartamentos.show(document.getElementById('departamentos'));

async function imprimir() {
    if (!validaCampos()) {
        return;
    }

    if (PHPSession.requestApi === undefined) {
        await PHPSession.loadData();
    }

    const formData = getFormData();

    PHPSession.appendFormData(formData);

    let rota = 'saude/farmacia/relatorio/demanda-reprimida';
    HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
        if (response.error) {
            alert(response.message);
            return;
        }

        window.open(response.data.path, 'relatorio-demanda-reprimida', 'popup');
    });
}

function validaCampos() {
    if (empty(periodoInicial.__toLocaleDateString()) || empty(periodoFinal.__toLocaleDateString())) {
        alert('Informe o período!');
        return false;
    }
    if (js_comparadata(periodoInicial.__toLocaleDateString(), periodoFinal.__toLocaleDateString(), '>')) {
        alert('O período inicial não pode ser maior que o período final!');
        return false;
    }

    return true;
}

function getFormData() {
    const formData = new FormData();

    for (let paciente of lancadorPacientes.getRegistros()) {
        formData.append('pacientes[]', paciente.sCodigo);
    }
    for (let medicamento of lancadorMedicamentos.getRegistros()) {
        formData.append('medicamentos[]', medicamento.sCodigo);
    }
    for (let departamento of lancadorDepartamentos.getRegistros()) {
        formData.append('departamentos[]', departamento.sCodigo);
        formData.append('txtDepartamentos[]', departamento.sDescricao);
    }
    formData.append('periodoInicial', js_formatar(periodoInicial.__toLocaleDateString(), 'd'));
    formData.append('periodoFinal', js_formatar(periodoFinal.__toLocaleDateString(), 'd'));
    formData.append('somenteTotal', somenteTotalizadores.value);
    formData.append('exibeObservacao', exibeObservacao.value);
    formData.append('ordem', ordem.value);

    return formData;
}
</script>
</body>
</html>