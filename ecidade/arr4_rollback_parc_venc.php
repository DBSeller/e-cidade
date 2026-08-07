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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_arretipo_classe.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libpostgres.php"));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" href="estilos.css" rel="stylesheet">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>
<body>
</body>
<div class="container">
    <form name="form1" action="" method="post">
        <fieldset style="width: 400px; height: 75px;">
            <legend><b>Reversão de Parcelamento</b></legend>
            <br>
            <table>
                <tr>
                    <td style="text-align: right">
                        <label><b>Número do Parcelamento:</b></label> &nbsp;
                    </td>
                    <td>
                        <input type="text" id="parcelamento" class="field-size3">
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<div class="container">
    <button id='btnBuscar'>
        <i class="fas fa-search"></i>
            Buscar
    </button>
</div>
&nbsp;
<div class="subcontainer" style="width: 1000px;">
    <fieldset>
        <legend>Parcelas</legend>
        <table id="data-table-parcelamentos"
            class="table table-sm">
        </table>
    </fieldset>
</div>
&nbsp;
<div class="container">
    <button id='btnProcessar'>
            Processar
    </button>
</div>
<?php db_menu(); ?>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script>
$.noConflict();
const routes = {
    buscar: 'tributario/arrecadacao/controle-parcelamentos-vencidos/buscar',
    processar: 'tributario/arrecadacao/controle-parcelamentos-vencidos/processar'
};

const parcelamento = document.getElementById('parcelamento');
const btnBuscar = document.getElementById('btnBuscar');
const tabelaParcelamentos = jQuery('#data-table-parcelamentos');
const btnProcessar = document.getElementById('btnProcessar');

btnBuscar.addEventListener('click', () => {
    if(!validaCampos()) {
        return;
    }

    let numParcelamento = parcelamento.value;

    HttpClient.get(`${PHPSession.requestApi}/${routes.buscar}/${numParcelamento}`).then(response => {
        if (response.error) {
            alert(response.message);
            return;
        }
        console.log(response.data);
        tabelaParcelamentos.bootstrapTable('load', response.data);
    });

});

jQuery(document).ready(jQuery => {
    tabelaParcelamentos.bootstrapTable({
        height: 300,
        columns:[
            {
                field: 'v07_parcel',
                title: 'Parcelamento',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar51_numpre',
                title: 'Numpre',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar51_numpar',
                title: 'Numpar',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar51_receit',
                title: 'Receita',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar51_dtvenc',
                title: 'Data Venc. Original',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar51_novadtvenc',
                title: 'Data Venc. Atual',
                align: 'center',
                width: 50,
            },
            {
                field: 'ar51_id_agendamento',
                title: 'Processamento Parc.',
                align: 'center',
                width: 70,
            },
            {
                field: 'ar51_dtproc',
                title: 'Data de processamento',
                align: 'center',
                width: 50,
            }
        ]
    });
});

btnProcessar.addEventListener('click', () => {
    if(!validaCampos()) {
        return;
    }

    let numParcelamento = parcelamento.value;

    HttpClient.post(`${PHPSession.requestApi}/${routes.processar}/${numParcelamento}`).then(response => {
        if (response.error) {
            alert(response.message);
            return false;
        }

        tabelaParcelamentos.bootstrapTable('load', response.data);
        return alert('Reversão concluída com sucesso');
    });

});

function validaCampos()
{
    if (parcelamento.value == '') {
        alert('Digite o número do parcelamento');
        return false;
    }

    return true;
}

</script>
</body>
</html>
