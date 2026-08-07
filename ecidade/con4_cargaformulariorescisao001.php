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

$oRotulo = new rotulocampo();
$oRotulo->label("h12_assent");
$oRotulo->label("h12_descr");
$oRotulo->label("eso10_sequencial");
$oRotulo->label("eso10_descricao");
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
        <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">


    </head>
    <body class="body-default">
        <div class="container" style="width: 800px;">
            <form id="frmCargaRescisao">
                <fieldset>
                    <legend>Carga de Dados de Desligamento</legend>
                   <fieldset class="separator">
                       <legend>Filtros</legend>
                       <table>
                           <tr>
                               <td class="bold">
                                   <label for="dataInicio">Data de Início:</label>
                               </td>
                               <td>
                                   <input id="dataInicio" type="text" readonly/>
                               </td>
                               <td class="bold">
                                   <label for="dataFinal">Data de Término:</label>
                               </td>
                               <td>
                                   <input id="dataFinal" type="text" />
                               </td>
                           </tr>
                       </table>
                    </fieldset>
                    <input type="button" id="btnPesquisar" value="Pesquisar">
                    <fieldset class="separator">
                        <legend>Desligamentos sem envio no período</legend>
                        <div id="gridDesligamentosContainer">

                        </div>
                    </fieldset>
                </fieldset>
                <input type="button" id="btnEnviar" value="Processar Carga">
            </form>
        </div>
    </body>
</html>
<?php db_menu();?>
<script>

    var dataInicio = DBInputDate.create($('dataInicio'));
    dataInicio.setReadOnly(true);
    var dataFinal = DBInputDate.create($('dataFinal'));
    const RPC = "eso4_cargaformulariosrescisao.RPC.php";

    collectionRescisoes = new Collection().setId('codigo_rescisao');
    gridRescisoes       = DatagridCollection.create(collectionRescisoes).configure("order", false);

    gridRescisoes.addColumn("codigo_rescisao",     {label: "codigo_rescisao", align: "center", width: "80px"});
    gridRescisoes.addColumn("matricula",          {label: "Matrícula", align: "center", width: "80px"});
    gridRescisoes.addColumn("nome",             {label: "Nome", align: "left", width: "200px"});
    gridRescisoes.addColumn("data_desligamento",   {label: "Desligamento em", align: "center", width: "100px"}).transform('date');
    gridRescisoes.addColumn("dias_atraso", {label: "Dias em aberto", align: "right", width: "100px"});
    gridRescisoes.addColumn("total_rubricas", {label: "Qtde. Rubricas", align: "right", width: "70px"});
    gridRescisoes.getGrid().setCheckbox(0);
    gridRescisoes.aColumnsDisplayed.push(1);
    gridRescisoes.show($('gridDesligamentosContainer'));

    (function(){
        AjaxRequest.create(
            'eso4_configuracaoenvio.RPC.php',
            {"exec" : "getConfiguracao"},
            function (retorno, erro) {

                if (erro) {

                    alert(retorno.mensagem);
                    return false;
                }
                dataInicio.value = js_formatar(retorno.arquivo.s2229.data_envio, 'd');
                dataFinal.value = '<?php echo date('d/m/Y', db_getsession('DB_datausu')) ?>';

            }
        ).execute();
    })();

    $('btnPesquisar').observe('click', function(){

        if (dataInicio.value == '' || dataFinal.value == null) {
            alert('Ambas as datas devem ser informadas.');
            return false;
        }

        var parametros = {
            'exec'       : 'getRescisoes',
            'dataInicio' : dataInicio.value,
            'dataFim': dataFinal.value,
        };

        AjaxRequest.create(
            RPC,
            parametros,
            function (retorno, erro) {

                if (erro) {

                    alert(retorno.mensagem);
                    return false;
                }

                collectionRescisoes.clear();
                for (rescisao of retorno.rescisoes) {
                    collectionRescisoes.add(rescisao);
                }
                gridRescisoes.reload();
            }
        ).execute('Aguarde, pesquisando rescisões');

    });


    $('btnEnviar').observe('click', function(){

        var matriculas = [];

        if (!confirm('Confirma o processamento da carga das matrículas selecionadas?')) {
            return;
        }
        var registros = gridRescisoes.getGrid().getSelection();
        if (registros.length == 0) {
            alert('Selecione ao menos uma rescisão.');
            return false;
        }
        var parametros = {
            'exec': 'processar',
            'rescisoes' : []
        }

        for (rescisao of registros) {
           parametros.rescisoes.push(rescisao[0]);
           matriculas.push(rescisao[2]);
        }

        var matriculaInconsistente = false,
            error = false;

        AjaxRequest.create(
            RPC,
            {
                exec: 'validarEnvioServidor',
                matriculas : matriculas
            },
            function (retorno, erro) {
                if (retorno.matriculas.length > 0) {
                    matriculaInconsistente = true;
                    matriculas = retorno.matriculas.join(', ');
                }

                if (retorno.mensagem) {
                    error = true;
                    alert(retorno.mensagem);
                }
            }
        )
            .asynchronous(false)
            .execute('Aguarde, validando matrículas');

        if (error) {
            return false;
        }

        if (matriculaInconsistente && !confirm("As matrículas " + matriculas + " não foram enviadas no evento de conferência do servidor ao eSocial. É necessário enviá-las antes do evento de desligamento.\nDeseja prosseguir?")) {
            return false;
        }

        AjaxRequest.create(
            RPC,
            parametros,
            function (retorno, erro) {

                alert(retorno.mensagem);
                if (erro) {
                    return false;
                }

                if (retorno.file) {
                    var oDownload = new DBDownload();
                    oDownload.setHelpMessage('Clique no link abaixo para fazer download do relatório.');
                    oDownload.addFile(retorno.file, 'Relatório de Inconsistências.csv');
                    oDownload.show();
                }

                $('btnPesquisar').click();
            }
        ).execute('Aguarde, pesquisando rescisões');

    });
</script>
