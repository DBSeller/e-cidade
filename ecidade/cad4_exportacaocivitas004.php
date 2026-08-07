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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<div class="container">
    <form>
        <fieldset>
            <legend>Exportação de Civitas</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="dataInicio">Período:</label>
                    </td>
                    <td>
                        <input type="date" name="dataInicio" id="dataInicio"> <strong>à</strong> <input type="date" name="dataFinal" id="dataFinal">
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" value="Exportar" id="exportar">
    </form>
</div>
<script type="text/javascript">
    const dataInicio = new DBInputDate(document.getElementById('dataInicio'));
    const dataFinal = new DBInputDate(document.getElementById('dataFinal'));

    $('exportar').addEventListener('click', () => {
        const data = new FormData();
        data.append('acao', 'exportacao');

        if (dataInicio.value) {
            data.append('dataInicio', dataInicio.getValue().toISOString());
        }

        if (dataFinal.value) {
            data.append('dataFinal', dataFinal.getValue().toISOString());
        }

        HttpClient.post('cad4_exportacaocivitas.RPC.php', {body: data}).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }
            
            const download = new DBDownload();
            download.setWindowLabel('Exportação Civitas');
            download.addGroups('civitas', "Arquivo");
            download.addFile(response.arquivo, 'Clique aqui para baixar o arquivo.', 'civitas');
            download.show();
        });
    });


</script>
