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
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
</head>
<body>
<div class="container">
    <fieldset>
        <legend>Rastreabilidade de Medicamentos</legend>
        <div id="depositos"></div>
        <div id="medicamentos"></div>
        <fieldset class="separator">
            <legend>Outros Filtros</legend>
            <table class="form-container" style="text-align: left; width: 300px;">
                <tr>
                    <td>
                        <label for="estoqueZerado">Estoque zerado:</label>
                    </td>
                    <td>
                        <select id="estoqueZerado">
                            <option value="0">NÃO</option>
                            <option value="1">SIM</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="quebra">Quebra:</label>
                    </td>
                    <td>
                        <select id="quebra">
                            <option value="">NENHUMA</option>
                            <option value="1">MEDICAMENTO</option>
                            <option value="2">DEPÓSITO</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="ordem">Ordem:</label>
                    </td>
                    <td>
                        <select id="ordem">
                            <option value="">ALFABÉTICA</option>
                            <option value="1">NUMÉRICA</option>
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
const selectEstoqueZerado = document.getElementById('estoqueZerado');
const selectQuebra = document.getElementById('quebra');
const selectOrdem = document.getElementById('ordem');

var lancadorDepositos = new DBLancador('lancadorDepositos');
lancadorDepositos.selecionarAposPesquisar = true;
lancadorDepositos.setGridHeight('100px');
lancadorDepositos.setNomeInstancia('lancadorDepositos');
lancadorDepositos.setLabelAncora('Depósito:');
lancadorDepositos.setTextoFieldset('Filtrar Depósitos');
lancadorDepositos.setParametrosPesquisa('func_db_almox.php', ['m91_depto', 'descrdepto'], 'dpto=true');
lancadorDepositos.show(document.getElementById('depositos'));

var lancadorMedicamentos = new DBLancador('lancadorMedicamentos');
lancadorMedicamentos.selecionarAposPesquisar = true;
lancadorMedicamentos.setGridHeight('100px');
lancadorMedicamentos.setNomeInstancia('lancadorMedicamentos');
lancadorMedicamentos.setLabelAncora('Medicamento:');
lancadorMedicamentos.setTextoFieldset('Filtrar Medicamentos');
lancadorMedicamentos.setParametrosPesquisa('func_far_matersaude.php', ['fa01_i_codigo', 'm60_descr'], 'lancador');
lancadorMedicamentos.show(document.getElementById('medicamentos'));

async function imprimir() {
    if (PHPSession.requestApi === undefined) {
        await PHPSession.loadData();
    }
    
    const formData = new FormData();
    formData.append('tipo', 1);
    formData.append('estoqueZerado', selectEstoqueZerado.value);
    formData.append('quebra', selectQuebra.value);
    formData.append('ordem', selectOrdem.value);

    for (let deposito of lancadorDepositos.getRegistros()) {
        formData.append('depositos[]', deposito.sCodigo);
    }
    for (let medicamento of lancadorMedicamentos.getRegistros()) {
        formData.append('materiais[]', medicamento.sCodigo);
    }

    PHPSession.appendFormData(formData);

    let rota = 'patrimonial/material/relatorios/rastreabilidade';
    HttpClient.post(`${PHPSession.requestApi}/${rota}`, { body: formData }).then(response => {
        if (response.error) {
            alert(response.message);
            return;
        }

        window.open(response.data.path, '', 'popup');
    });
}
</script>
</body>