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
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
</head>
<body>
<form id="formulario" method="post">
<div class="alert alert-primary text-left" role="alert">
    Selecione as linhas que deseja efetuar os lançamentos contábeis.
</div>
    <div class="subcontainer" style="width: 80%">
        <fieldset>
            <legend>Registros não processados</legend>
            <table id="data-table"
                   class="table table-sm"
                   data-height="550"
                   data-virtual-scroll="true"
                   data-show-columns="true"

                   style="width: 100%;">
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


<script type="text/javascript">
    const routs = {
        get: 'financeiro/tesouraria/processar',
        processar: 'financeiro/tesouraria/processar',
        inconsistente: 'financeiro/tesouraria/inconsistente'
    };

    const btnProcessar = document.getElementById('btnProcessar');

    $.noConflict();
    jQuery(document).ready(function ($) {
        const formatterData = (value) => {
            return js_formatar(value, 'd');
        }

        const formatterValor = (value) => {
            return js_formatar(value, 'f');
        }

        const formatterCheckbox = (v, data) => {
            if (data.operacoesrealizadastef_id == null) {
                return {
                    disabled: true
                };
            }
            return;
        }

        const formatterActions = (v, data) => {
            if (data.operacoesrealizadastef_id == null) {
                return `
                <a class="info" href="javascript:void(0)"
                   title="Registro Inconsistente: Operação inicial não encontrada">
                    <i class="fas fa-info-circle"></i>
                </a> &nbsp
                <!--<a class="inconsistente" href="javascript:void(0)" title="Marcar como visto">
                    <i class="fas fa-check-square"></i>
                </a>-->`;
            }
            return '-';
        }

        const registrarInconsistente = {
            'click .inconsistente': (e, v, data) => {
                const formData = new FormData();

                formData.append('id', data.id);

                HttpClient.post(`${PHPSession.requestApi}/${routs.inconsistente}`, {body: formData}).then(response => {
                    if (response.error) {
                        alert(response.message);
                        return;
                    }
                });
                table.bootstrapTable('removeByUniqueId', data.id);
            }
        }

        const estiloLinha = (data) => {
            if (data.operacoesrealizadastef_id == null) {
                return {
                    classes: 'form-error'
                };
            }
            return {};
        }

        const colunas = [
            {
                checkbox: true,
                formatter: formatterCheckbox
            },
            {
                title: 'Autorização',
                field: 'numero_autorizacao',
                halign: 'center',
                align: 'left'
            },
            {
                title: 'CV',
                field: 'numero_cv',
                halign: 'center',
                align: 'left',
            },
            {
                title: 'Cartão',
                field: 'cartao',
                halign: 'center',
                align: 'left',
                visible: false
            },
            {
                title: 'Data Venda',
                field: 'data_venda',
                halign: 'center',
                align: 'left',
                formatter: formatterData
            },
            {
                title: 'Data Vencimento',
                field: 'data_vencimento',
                halign: 'center',
                align: 'left',
                visible: false,
                formatter: formatterData
            },
            {
                title: 'Parcela',
                field: 'parcela',
                halign: 'center',
                align: 'left',
                visible: false
            },
            {
                title: 'T. Parcelas',
                field: 'total_parcelas',
                halign: 'center',
                align: 'left',
                visible: false
            },
            {
                title: 'Vlr. Original',
                field: 'valor_original',
                halign: 'center',
                align: 'right',
                switchable : false,
                formatter: formatterValor
            },
            {
                title: 'Vlr. Bruto',
                field: 'valor_bruto',
                halign: 'center',
                align: 'right',
                switchable : false,
                formatter: formatterValor
            },
            {
                title: 'Vlr. Descontos',
                field: 'valor_descontos',
                halign: 'center',
                align: 'right',
                switchable : false,
                formatter: formatterValor
            },
            {
                title: 'Vlr. Líquido',
                field: 'valor_liquido',
                halign: 'center',
                align: 'right',
                switchable : false,
                formatter: formatterValor
            },
            {
                title: 'Ações',
                halign: 'center',
                align: 'center',
                switchable : false,
                formatter: formatterActions,
                events: registrarInconsistente
            }
        ];

        var table = $('#data-table');
        table.bootstrapTable({
            columns: colunas,
            uniqueId: "id",
            locale: 'pt-BR',
            cache: false,
            pagination: true,
            pageSize: 15,
            pageList: [10, 25, 50, 100, 200, 'All'],
            search: true,
            class: "table table-sm",
            rowStyle: estiloLinha
        });

        const buscarLinhasProcessar = () => {
            table.bootstrapTable('removeAll');
            HttpClient.get(`${PHPSession.requestApi}/${routs.get}`).then((response) => {
                if (response.error) {
                    alert(response.message);
                    return;
                }
                table.bootstrapTable('load', response.data);
            });
        }

        PHPSession.loadData().then(() => {
            buscarLinhasProcessar();
        });

        btnProcessar.addEventListener('click', () => {
            const formData = new FormData();
            table.bootstrapTable('getSelections').each(selecao => {
                formData.append('linhasTef[]', selecao.id);
            });

            PHPSession.appendFormData(formData);
            HttpClient.post(`${PHPSession.requestApi}/${routs.processar}`, {body: formData}).then((response) => {

                alert(response.message);
                if (response.error) {
                    return;
                }

                buscarLinhasProcessar();
            });
        });
    });
</script>
