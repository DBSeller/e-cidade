<?php
/**
 *
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <?php
        db_app::load("scripts.js, strings.js, prototype.js");
        db_app::load("estilos.css, grid.style.css");
        db_app::load('widgets/DBDownload.widget.js, AjaxRequest.js');
        ?>
    </head>
    <body style="background-color: #CCCCCC; margin-top:30px">
        <div class="container">
            <form enctype="multipart/form-data" name="vinculaRecursos" id="vinculaRecursos">
                <fieldset style="width: 600px;">
                    <legend class="bold">Arquivo com o cadastro de transações</legend>
                    <table style="width: 100%;">
                        <tr>
                            <td><b>Selecione o arquivo: </b></td>
                            <td>
                                <input type="file" id="arquivo" name="arquivo" style="height: 25px;"/>
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <p>
                    <input type="button" id="btnImportar" value="Importar"/>
                </p>
            </form>
        </div>
        <?php
        db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
        ?>
    </body>
</html>

<script>
    var fonteRpc =  "con4_importatransacoes.RPC.php";

    $('btnImportar').observe('click', function() {
        if ($F('arquivo') == '') {
            alert("Campo Arquivo é de preenchimento obrigatório.");
            return false;
        }

        var sMensagem = "Confirma a importação do arquivo?";

        if (!confirm(sMensagem)) {
            return false;
        }

        var oParametros = { exec: "importarArquivo" };

        new AjaxRequest(
            fonteRpc,
            oParametros,
            function(oRetorno, lErro) {
                alert(oRetorno.sMessage.urlDecode());
                $('arquivo').value = '';
            }
        ).addFileInput($('arquivo'))
        .setMessage('Aguarde, efetuando o upload do arquivo...')
        .execute();
    });

</script>
