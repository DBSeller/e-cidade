<?php
/**
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body class="body-container">
<div class="container">
    <fieldset style="width: 400px; margin: 0 auto">
        <legend>Notificação de Recebimento de Recursos Federais</legend>
        <table class="form-container">
            <tr>
                <td><label for="data_inicial">Data inicial: </label></td>
                <td><input type="text" id="data-inicial"></td>
                <td><label for="data_final">Data final: </label></td>
                <td><input type="text" id="data-final"></td>
            </tr>
        </table>
    </fieldset>
    <br />
    <button type="button" id="btn-emitir">
        <i class="fa fa-print"></i>
        Emitir
    </button>
</div>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">
    const inputDataInicial = document.getElementById('data-inicial');
    const inputDataFinal = document.getElementById('data-final');
    const btnEmitir = document.getElementById('btn-emitir');

    const dataInicial = new DBInputDate(inputDataInicial);
    const dataFinal = new DBInputDate(inputDataFinal);

    btnEmitir.addEventListener('click', () => {
        if (empty(dataInicial.__toLocaleDateString())) {
            alert("Informe a Data Inicial");
            return;
        }
        if (empty(dataFinal.__toLocaleDateString())) {
            alert("Informe a Data Final");
            return;
        }
        if (dataFinal.__toLocaleDateString() < dataInicial.__toLocaleDateString()) {
            alert('A data Inicial não pode ser maior que a data final.');
            return;
        }

        let route = 'financeiro/contabilidade/relatorio/notificacao-recebimento-recursos-federais';

        let formData = new FormData();
        PHPSession.appendFormData(formData);
        formData.append('dataInicial', dataInicial.__toLocaleDateString());
        formData.append('dataFinal', dataFinal.__toLocaleDateString());
        HttpClient.post(`${PHPSession.requestApi}/${route}`, {body: formData}).then(response => {
            window.open(response.data.pdf, '', 'scrollbars=1,location=0').moveTo(0, 0);
        });
    });
</script>
</body>
</html>
