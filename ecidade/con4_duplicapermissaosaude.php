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
</head>
<body>
    <div class="container">
        <fieldset>
            <legend>Duplicar Permissão de Menus da Saúde</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="ano-origem">Ano Origem: </label>
                    </td>
                    <td>
                        <input type="text" id="ano-origem" class="field-size1" value="<?= $_SESSION['DB_anousu'] + 1 ?>">
                        <label for="ano-destino">Ano Destino: </label>
                        <input type="text" id="ano-destino" class="field-size1 readonly" readonly value="<?= $_SESSION['DB_anousu'] ?>">
                    </td>
                </tr>
            </table>
        </fieldset>
        <button onclick="processar()">
            <i class="fas fa-cog"></i>
            Processar
        </button>
    </div>
    <?php db_menu(); ?>
</body>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script>
const inputAnoOrigem = document.getElementById('ano-origem');
const inputAnoDestino = document.getElementById('ano-destino');

inputAnoOrigem.addEventListener('change', () => {
    inputAnoDestino.value = Number(inputAnoOrigem.value) - 1;
});

async function processar() {
    if (inputAnoOrigem.value == '') {
        alert('Informe o ano de Origem.')
        return;
    }
    if (inputAnoDestino.value == '') {
        inputAnoDestino.value = Number(inputAnoOrigem.value) - 1;
    }

    if (PHPSession.requestApi == undefined) {
        await PHPSession.loadData();
    }

    const route = `${PHPSession.requestApi}/configuracao/menu/permissoes/saude/duplicar`;
    const formData = new FormData();
    formData.append('anoOrigem', inputAnoOrigem.value);
    formData.append('anoDestino', inputAnoDestino.value);

    PHPSession.appendFormData(formData);

    const response = await HttpClient.post(route, { body: formData });
    alert(response.message);
}
</script>
