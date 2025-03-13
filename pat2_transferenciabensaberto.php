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
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>

</head>
<body>
<div class="container">
    <fieldset>
        <legend>Transferência em aberto</legend>
        <div id="lancadorMotorista"></div>
        <fieldset class="separator">
            <legend>Período</legend>
            <table>
                <tr>
                    <td>
                        <label class='bold'>De:</label> &nbsp;
                    </td>
                    <td>
                        <input id="dataInicial"> &nbsp;
                    </td>
                    <td>
                        <label class='bold'>Até:</label> &nbsp;
                    </td>
                    <td>
                        <input id="dataFinal">
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
    const route = 'patrimonial/patrimonio/relatorio/transferencia-bens-aberto';

    const dataInicial = new DBInputDate(document.getElementById('dataInicial'));
    const dataFinal = new DBInputDate(document.getElementById('dataFinal'));

    async function imprimir()
    {
        if (!validaCampos()) {
            return false;
        }

        await PHPSession.loadData();

        const formData = new FormData();

        formData.append('dataInicial', js_formatar(dataInicial.__toLocaleDateString(), 'd'));
        formData.append('dataFinal', js_formatar(dataFinal.__toLocaleDateString(), 'd'));

        PHPSession.appendFormData(formData);

        let response = await HttpClient.post(`${PHPSession.requestApi}/${route}`, {body: formData});

        if (response.error) {
            alert(response.message);
            return false;
        }


        window.open(response.data.path);

    }

    function validaCampos()
    {
        if (empty(dataInicial.__toLocaleDateString()) || empty(dataFinal.__toLocaleDateString())) {
            alert('Informe o período!');
            return false;
        }

        if (dataInicial.__toLocaleDateString() > dataFinal.__toLocaleDateString()) {
            alert('O periodo inical não pode ser maior que o período final!');
            return false;
        }
        return true;
    }
</script>
</body>
</html>
