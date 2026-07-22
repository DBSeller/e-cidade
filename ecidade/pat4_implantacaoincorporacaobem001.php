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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body class='body-default'>
<div class='container'>
    <form>
        <fieldset>
            <legend>Implantação da Incorporação de Bens</legend>
            <table class="form-container">
                <tr>
                    <td>Implantado:</td>
                    <td>
                        <select id="implantacao">
                            <option selected value="N">Não</option>
                            <option value="S">Sim</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" id="salvar" name="salvar" value="Salvar" disabled="disabled" />
    </form>
</div>
<?php
db_menu();
?>
</body>
<script type="text/javascript">
(function () {
    new AjaxRequest('pat4_incorporacaobem.RPC.php', {exec: 'buscarConfiguracao'}, function (retorno, erro) {
        if (erro) {
            alert(retorno.message);
            return;
        }

        valorImplantacao(retorno.utiliza);
    }).setMessage('Buscando configuração, aguarde...').execute();
})();

$('implantacao').addEventListener('change', function () {
    $('salvar').setAttribute('disabled', 'disabled');

    if ($F('implantacao') == 'S') {
        $('salvar').removeAttribute('disabled');
    }
});

$('salvar').addEventListener('click', function() {
    if (!confirm('Tem certeza que deseja implantar a incorporação de bens?\nEsse procedimento é irreversível.')){
        return;
    }

    new AjaxRequest('pat4_incorporacaobem.RPC.php', {exec: 'implantarIncorporacao'}, function (retorno, erro) {
        alert(retorno.message);
        if (erro) {
            return;
        }

        valorImplantacao('S');
        $('salvar').setAttribute('disabled', 'disabled');
    }).setMessage('Buscando configuração, aguarde...').execute();
});

function valorImplantacao(situacao) {
    $('implantacao').value = situacao;

    if (situacao === 'S') {
        $('implantacao').setAttribute('disabled', 'disabled');
    }
}
</script>
</html>