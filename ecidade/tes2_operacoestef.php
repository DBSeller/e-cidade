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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
</head>
<body>
<div class="alert alert-primary text-left" role="alert">
    Informe o período para imprimir a lista de operações processadas do TEF.
</div>
    <div class="container">
        <fieldset>
            <legend>Filtre o Período</legend>
            <table>
                <tr>
                    <td>
                        <label class='bold'>De:</label> &nbsp;
                    </td>
                    <td>
                        <input id="data-inicio"> &nbsp;
                    </td>
                    <td>
                        <label class='bold'>Até:</label> &nbsp;
                    </td>
                    <td>
                        <input id="data-fim">
                    </td>
                </tr>
            </table>
        </fieldset>
        <button type="button" id="btnGerar">
            <i class="fas fa-print"></i>
            Gerar
        </button>
    </div>
<?php
db_menu();
?>
</body>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script>
    const rota = 'financeiro/tesouraria/relatorio/tef';
    const btnGerar = document.getElementById('btnGerar');
    const dataInicial = new DBInputDate(document.getElementById('data-inicio'));
    const dataFinal = new DBInputDate(document.getElementById('data-fim'));

    const dataHoje = new Date();

    dataInicial.setValue(`${dataHoje.getUTCFullYear()}-01-01`);
    dataFinal.setValue(dataHoje.toLocaleString());

    const validarData = () => {
        try {
            if (empty(dataInicial.__toLocaleDateString()) || empty(dataFinal.__toLocaleDateString())) {
                throw 'Necessário informar o período para realizar a consulta!';
            }

            if (dataInicial.value.getUTCFullYear() != dataFinal.value.getUTCFullYear()) {
                throw 'As datas devem estar dentro do mesmo exercício.';
            }

            if (js_comparadata(dataInicial.inputElement.value, dataFinal.inputElement.value, '>')) {
                throw 'Data de inicio deve ser menor que a data final.';
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    }

    btnGerar.addEventListener('click', () => {
        const formData = new FormData();

        if(!validarData()) {
            return;
        }

        formData.append('dataInicial', js_formatar(dataInicial.__toLocaleDateString(), 'd'));
        formData.append('dataFinal', js_formatar(dataFinal.__toLocaleDateString(), 'd'));
        PHPSession.appendFormData(formData);

        PHPSession.loadData().then(() => {
            HttpClient.post(`${PHPSession.requestApi}/${rota}`, {body: formData}).then(response => {
                if (response.error) {
                    return alert(response.message);
                }

                const download = new DBDownload();
                download.addFile(response.data.pdf, "Lista de Movimentações em Operações - PDF");
                download.addFile(response.data.csv, "Lista de Movimentações em Operações - CSV");
                download.show();
            });
        });
    });
</script>
</html>
