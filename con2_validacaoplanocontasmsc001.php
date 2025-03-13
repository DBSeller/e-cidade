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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
?>

<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="" quiv="Expires" CONTENT="0">
        <link href="estilos.css" rel="stylesheet" type="text/css">
        <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>

    </head>
    <body class="body-default">
        <div class="container">
            <form>
                <fieldset>
                    <legend>Validação Plano de Contas MSC</legend>

                    <table class="form-container">
                        <tr>
                            <td>
                                <label for="ano">Ano:</label>
                            </td>
                            <td>
                                <input type ='text' id='ano' maxlength="4" />
                            </td>
                        </tr>
                    </table>
                </fieldset>

                <input type="button" value="Processar" id="processar" name="processar" />
            </form>
        </div>
        <?php db_menu();?>
    </body>
    <script type="text/javascript">

        new DBInputInteger($('ano'));

        $('processar').addEventListener('click', function() {

            if(!$F('ano')) {
                alert('Por favor informe um ano!');
                return;
            }

            var url  = 'con2_validacaoplanocontasmsc002.php?ano=' + $F('ano');
            window.open(url, '', 'scrollbars=1,location=0');
        });

    </script>
</html>
