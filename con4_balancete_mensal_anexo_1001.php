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
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>

<div class="container">
    <fieldset>
        <legend>Anexo I - Balancete Financeiro</legend>

        <table class="form-container">


          <tr class="text-left">
             <td style="width: 100px;">
                 <label class="bold" for="competencia">Competência:</label></td>
             <td>
             <?php

             $meses=array( ""  => "Selecione...",
                           "1" => "Janeiro",
                           "2" => "Feveireiro",
                           "3" => "Março",
                           "4" => "Abril",
                           "5" => "Maio",
                           "6" => "Junho",
                           "7" => "Julho",
                           "8" => "Agosto",
                           "9" => "Setembro",
                           "10" => "Outubro",
                           "11" => "Novembro",
                           "12" => "Dezembro");
             db_select("txtMes", $meses, true, 1,"style='width:120px'");
             ?>
            </td>
          </tr>

          <tr>
            <td id="ctnInstituicao" colspan="2" style="font-weight: normal">
                <input type="hidden" name="db_selinstit" id="db_selinstit" value="">
            </td>
          </tr>
        </table>
    </fieldset>
    <button id="btnEmitir" type="button">
        <i class="fas fa-print"></i>  Emitir
    </button>

</div>
</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/session.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script rel="script" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script rel="script" type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script>


const sApiUrl = "<?= ECIDADE_REQUEST_PATH ?>v4/api/financeiro/contabilidade/";
const routs = {
    processar : sApiUrl + "BalancetesMensais/balancete-mensal-anexo1"
};

const competencia = $("txtMes");

var viewInstituicao = new DBViewInstituicao('viewInstituicao', $('ctnInstituicao'));
    viewInstituicao.show();

    btnEmitir.addEventListener('click', () => {

        const formData = new FormData();

        formData.append('competencia', js_formatar(competencia.value, 'd'));

        if (competencia.value == "") {

            alert("Selecione um Mês.");
            return false;
        }

        formData.append('instituicoes', JSON.stringify(viewInstituicao.getInstituicoesSelecionadas()));

        PHPSession.appendFormData(formData);

        HttpClient.post(`${routs.processar}`, {body: formData}).then(response => {

            if (response.error) {
              alert(response.message);
              return;
            }

            console.log(response.data);
            const download = new DBDownload();
                  download.addFile(response.data, "Balancete Mensal Anexo 1 - PDF");
                  download.show();

        });
    });
</script>
