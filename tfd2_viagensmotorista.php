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
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body>
<div class="container">
    <fieldset>
        <legend>Viagens por Motorista</legend>
        <div id="lancadorMotorista"></div>
        <fieldset class="separator">
            <legend>Período</legend>
            <table>
                <tr> 
                  <td>
                     <label class='bold'>De:</label> &nbsp;
                  </td>
                  <td>
                     <input id="periodo-inicio"> &nbsp;
                  </td>
                  <td>
                     <label class='bold'>Até:</label> &nbsp;
                  </td>
                  <td>
                     <input id="periodo-fim">
                  </td>
               </tr>
            </table>
        </fieldset>
        <fieldset class="separator">
            <legend>Parâmetros Adicionais</legend>
            <table style="text-align: left;">
                <tr>
                    <td>
                        <a id="destino" class="bold" href="">Destino:</a>
                    </td>
                    <td>
                        <input type="text" id="tf03_i_codigo" class="field-size2">
                        <input type="text" id="tf03_c_descr" class="field-size9 readonly" readonly>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="bold">Ordem:</label>
                    </td>
                    <td>
                        <input type="radio" name="ordem" id="radio-data" value='1' checked>
                        <label for="radio-data">Data</label>
                        <input type="radio" name="ordem" id="radio-veiculo" value='2'>
                        <label for="radio-veiculo">Veiculo</label>
                        <input type="radio" name="ordem" id="radio-destino" value='3'>
                        <label for="radio-destino">Destino</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="bold">Tipo:</label>
                    </td>
                    <td>
                        <input type="radio" name="tipo" id="radio-pdf" value='1' checked>
                        <label for="radio-pdf">PDF</label>
                        <input type="radio" name="tipo" id="radio-csv" value='2'>
                        <label for="radio-csv">CSV</label>
                    </td>
                </tr>
            </table>
        </fieldset>
    </fieldset>
    <button type="button" id="btnImprimir" onClick="imprimir();">
         <i class="fas fa-print"></i>
         Imprimir
    </button>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script>
const route = 'saude/tfd/relatorio/viagens-por-motorista';

const data = new Date();
document.getElementById('periodo-fim').value = data.toLocaleDateString('pt-BR');
const periodoInicial = new DBInputDate(document.getElementById('periodo-inicio'));
const periodoFinal = new DBInputDate(document.getElementById('periodo-fim'));
const destino = {
    ancora: document.getElementById('destino'),
    id: document.getElementById('tf03_i_codigo'),
    descricao: document.getElementById('tf03_c_descr')
};
const radioOrdem = document.getElementsByName('ordem');

var lancadorMotorista = new DBLancador('lancadorMotorista');
lancadorMotorista.iGridHeight = 100;
lancadorMotorista.selecionarAposPesquisar = true;
lancadorMotorista.setNomeInstancia('lancadorMotorista');
lancadorMotorista.setLabelAncora('Motorista:');
lancadorMotorista.setTextoFieldset('Filtrar Motoristas');
lancadorMotorista.setParametrosPesquisa('func_veicmotoristasalt.php', ['ve05_codigo', 'z01_nome']);
lancadorMotorista.show(document.getElementById('lancadorMotorista'));

const lookUpDestino = new DBLookUp(destino.ancora, destino.id, destino.descricao, {
    'sArquivo': 'func_tfd_destino.php',
    'sObjetoLookUp': 'db_iframe_tfd_destino',
    'sLabel': 'Pesquisar Destinos'
});

async function imprimir()
{
    if (!validaCampos()) {
        return false;
    }

    await PHPSession.loadData();

    const formData = new FormData();

    for (let motorista of lancadorMotorista.getRegistros()) {
        formData.append('motoristas[]', motorista.sCodigo);
    }
    formData.append('periodoInicial', js_formatar(periodoInicial.__toLocaleDateString(), 'd'));
    formData.append('periodoFinal', js_formatar(periodoFinal.__toLocaleDateString(), 'd'));
    formData.append('ordem', document.querySelector('input[name="ordem"]:checked').value);
    formData.append('tipo', document.querySelector('input[name="tipo"]:checked').value);
    if (destino.id.value != '') {
        formData.append('destino', destino.id.value);
    }

    PHPSession.appendFormData(formData);

    let response = await HttpClient.post(`${PHPSession.requestApi}/${route}`, {body: formData});

    if (response.error) {
        alert(response.message);
        return false;
    }

    const download = new DBDownload();
    download.addFile(response.data.path, `${response.data.name}`);
    download.show();
}

function validaCampos()
{
    if (empty(periodoInicial.__toLocaleDateString()) || empty(periodoFinal.__toLocaleDateString())) {
        alert('Informe o período!');
        return false;
    }

    if (periodoInicial.__toLocaleDateString() > periodoFinal.__toLocaleDateString()) {
        alert('O periodo inical não pode ser maior que o período final!');
        return false;
    }

    return true;
}
</script>
</body>
</html>