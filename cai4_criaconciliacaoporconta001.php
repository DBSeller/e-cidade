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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_saltes_classe.php"));
require_once(modification("classes/db_corrente_classe.php"));


$clsaltes   = new cl_saltes;
$clcorrente = new cl_corrente;
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$db_opcao = 1;
$db_botao = true;
$borda = 0;

db_app::load("scripts.js, prototype.js, strings.js");
?>

<!doctype html>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Expires" CONTENT="0">
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>

</head>


<form id="formulario" method="post">

<div class="container" style="width: 450px; margin-top: 10px;">

  <fieldset>
    <legend> Criar Conciliação Por Conta</legend>

    <table class="form-container">
        <tr>
            <td>Conta:</td>
            <td> <input type="text" id='conta' name='conta'class="field-size2" />  </td>
        </tr>
        <tr>
            <td>Data:</td>
            <td><input id='data' name='data' class="field-size2" /></td>
        </tr>
    </table>

  </fieldset>

  <button type="button" id="btnProcessar" name="btnProcessar" class="btn btn-light" >
    <i class="far fa-save"></i>
    Processar
  </button>
</div>


</form>

<?php
db_menu();
?>
</body>
</html>


<script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/session.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<!-- requires bootstrap table -->
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/popper.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-4.5.3/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table-export.min.js"></script>


<script>


const sApiUrl = "<?= ECIDADE_REQUEST_PATH ?>v4/api/financeiro/tesouraria/";
const routs = {

    processarPorReduzido : sApiUrl + 'processar-implantacao-por-conta'

};

const formData = new FormData();

$.noConflict();
jQuery(document).ready(function ($) {

    const conta = document.getElementById('conta');
    const data = document.getElementById('data');

    inputData = new DBInputDate(data)

    const validaProcessamento = () => {

        if (conta.value == '' || data.value == '') {

          alert("Selecionar Conta e Data.");
          return false;
        }
        return true;
    }

    btnProcessar.addEventListener('click', () => {

         if (!validaProcessamento()) {
                return false;
         }

         let aDados = [

               oDados = {
                   "conta" : conta.value,
                   "data" : data.value
               }
            ]

         formData.append('linhasContas', JSON.stringify(aDados));
         PHPSession.appendFormData(formData);

         HttpClient.post(`${routs.processarPorReduzido}`, {body: formData}).then(response => {

            alert(response.message);

         });

  });


});

</script>
