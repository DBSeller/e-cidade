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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

?>
<html>
<body class="body-default">
<div class="container">
    <form name="form1" method="post" action="">
        <fieldset>
            <legend>Abertura do Exercício</legend>
            <table>
                <tr>
                    <td>
                        <label class="bold" id="lbl_data" for="data">Data dos Lançamentos:</label>
                    </td>
                    <td>
                        <?php db_inputdata("data", '01', '01', db_getsession("DB_anousu"), true, 'text', 1); ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="processarTeste" type="button" id="processarTeste" value="Processar Abertura"
               onclick="processar()"/>
        <input name="cancelarTeste" type="button" id="cancelarTeste" value="Cancelar Abertura"
               onclick="cancelar()"/>
    </form>
</div>
<?php db_menu(db_getsession("DB_id_usuario"),
    db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),
    db_getsession("DB_instit")); ?>

<script type="text/javascript">

    function processar() {

        var confirmacao = confirm('Você realmente deseja abrir o exercício contábil? ');

        if (!confirmacao){
            return false;
        }
        bloquearBotoesTela(true);
        var oParametros = {
            exec: "processarAbertura",
            encerramento: false,
            data: $F("data"),
        };

        new AjaxRequest('con4_processaaberturaexercicio.RPC.php', oParametros, function (oRetorno, lErro) {
            alert(oRetorno.mensagem.urlDecode());
            bloquearBotoesTela(false);
        }).setMessage("Aguarde, efetuando abertura...").execute();
    }

    function cancelar() {

        var confirmacao = confirm('Você realmente deseja cancelar a abertura do exercício contábil? ');
        if (!confirmacao){
            return false;
        }
        bloquearBotoesTela(true);
        var oParametros = {
            exec: "cancelarAbertura",
            data: $F("data"),
        };

        new AjaxRequest('con4_processaaberturaexercicio.RPC.php', oParametros, function (oRetorno, lErro) {
            alert(oRetorno.mensagem.urlDecode());
            bloquearBotoesTela(false);
        }).setMessage("Aguarde, efetuando cancelamento...").execute();

    }
</script>
</body>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">

        .item-encerramento {
            margin-bottom: 10px !important;
            display: block;
        }
    </style>
</head>
</html>
