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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/financeiro/PesquisaLinhaPacto.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">

    <fieldset>
        <legend><b>Consulta de saldo do plano orçamentário</b></legend>
        <table>
            <tr>
                <td><b><label for="dataInicial">Data Inicial:</label></b></td>
                <td><input id="dataInicial"/></td>
                <td><b><label for="dataFinal">Data Final:</label></b></td>
                <td><input id="dataFinal"/></td>
            </tr>
            <tr>
                <td>
                    <label id="lblPrograma" for="o54_programa"><b>Programa:</b></label>
                </td>
                <td colspan="3">
                    <?php
                    db_input('o54_programa', 10, 1, true, 'text', 1);
                    db_input('o54_descr', 50, 1, true, 'text', 3);
                    ?>
                </td>
            </tr>

            <tr>
                <td>
                    <label id="lblAcao" for="o55_projativ"><b>Ação:</b></label>
                </td>
                <td colspan="3">
                    <?php
                    db_input('o55_projativ', 10, 1, true, 'text', 1);
                    db_input('o55_descr', 50, 1, true, 'text', 3);
                    ?>
                </td>
            </tr>

            <tr>
                <td>
                    <label id="lblDotacao" for="o58_coddot"><b>Dotação:</b></label>
                </td>
                <td colspan="3">
                    <?php
                    db_input('o58_coddot', 10, 1, true, 'text', 1);
                    db_input('dl_estrutural', 50, 1, true, 'text', 3);
                    ?>
                </td>
            </tr>

            <tr>
                <td>
                    <label id="lblLinhaPacto" for="c07_sequencial"><b>Linhas de Pacto:</b></label>
                </td>
                <td colspan="3">
                    <?php
                    db_input('c07_sequencial', 10, 1, true, 'text', 1);
                    db_input('c07_titulo', 50, 1, true, 'text', 3);
                    ?>
                </td>
            </tr>


        </table>
    </fieldset>
    <p>
        <input type="button" id="btnPesquisar" value="Pesquisar" onclick="pesquisar()"/>
    </p>
</div>

<div class="container" style="width: 100%">
    <fieldset>
        <legend class="bold">Resultado da Pesquisa</legend>
        <div id="ctnResultadoPesquisa"></div>
    </fieldset>
</div>
</body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
    db_getsession("DB_instit"));
?>

<script>

    var dataAtual = new Date();
    mesAtual = (dataAtual.getMonth() + 1);

    var dataInicial = new DBInputDate($('dataInicial'));
    dataInicial.setValue('01/01/' + dataAtual.getFullYear());
    var dataFinal = new DBInputDate($('dataFinal'));
    dataFinal.setValue(dataAtual.getDate() + '/' + mesAtual + '/' + dataAtual.getFullYear());

    var lookupPrograma = new DBLookUp($('lblPrograma'), $('o54_programa'), $('o54_descr'), {sArquivo: 'func_orcprograma.php'});
    var lookupAcao = new DBLookUp($('lblAcao'), $('o55_projativ'), $('o55_descr'), {sArquivo: 'func_orcprojativ.php'});
    var lookupDotacao = new DBLookUp($('lblDotacao'), $('o58_coddot'), $('dl_estrutural'), {sArquivo: 'func_orcdotacao.php'});
    var lookupLinhasDePacto = new DBLookUp($('lblLinhaPacto'), $('c07_sequencial'), $('c07_titulo'), {sArquivo: 'func_linhaspacto.php'});

    function pesquisar() {

        var parametro = {
            exec: 'pesquisar',
            data_inicial: document.querySelector('#dataInicial').value,
            data_final: document.querySelector('#dataFinal').value,
            programa: document.querySelector('#o54_programa').value,
            acao: document.querySelector('#o55_projativ').value,
            dotacao: document.querySelector('#o58_coddot').value,
            linha_pacto: document.querySelector('#c07_sequencial').value
        };

        var camposObrigatorios = [];

        if (parametro.data_inicial.replace(/\//g, '').trim() == '') {
            camposObrigatorios.push('data inicial');
        }
        if (parametro.data_final.replace(/\//g, '').trim() == '') {
            camposObrigatorios.push('data final');
        }

        if (camposObrigatorios.length > 0) {

            var ligacao = camposObrigatorios.length === 1 ? 'é' : 'são';
            alert("Campo(s) " + camposObrigatorios.join(', ') + " " + ligacao + " de preenchimento obrigatório.");
            return false;
        }

        AjaxRequest.create(
            'orc3_planoorcamentarioRPC.php',
            parametro,
            function (response, erro) {

                gridPesquisa.clearAll(true);
                if (erro) {

                    alert(response.mensagem);
                    return false;
                }

                response.planos_orcamentarios.each(
                    function (dados) {

                        var codigoLinhaPacto = dados.linha_pacto;
                        var codigoAcao = dados.acao;
                        gridPesquisa.addRow(
                            [
                                dados.codigo_dotacao,
                                dados.estrutural_dotacao,
                                dados.descricao_plano,
                                "<a href='#' onclick='abrirConsulta(" + codigoLinhaPacto + ", " + codigoAcao + ", \""+dados.descricao_linha+"\")'>" + dados.descricao_linha + "</a>",
                                js_formatar(dados.valor_previsto, 'f'),
                                js_formatar(dados.valor_alterado_remanejado, 'f'),
                                js_formatar(dados.valor_realizado, 'f'),
                                js_formatar(dados.saldo_final, 'f')
                            ]
                        );

                    }
                );
                gridPesquisa.renderRows();
            }
        ).setMessage('Aguarde, pesquisando informações...').execute();
    }

    var gridPesquisa = new DBGrid('gridPesquisa');
    gridPesquisa.nameInstance = 'gridPesquisa';
    gridPesquisa.setHeader(['Código', 'Estrutural', 'Plano Orçamentário', 'Linha de Pacto', 'Previsto', 'Alterado / Remanejado', 'Realizado', 'Saldo']);
    gridPesquisa.setCellAlign(['center', 'center', 'left', 'left', 'right', 'right', 'right', 'right']);
    gridPesquisa.setCellWidth(['5%', '15%', '20%', '20%', '8%', '8%', '8%', '8%', '8%']);
    gridPesquisa.setHeight(250);
    gridPesquisa.show($('ctnResultadoPesquisa'));


    function abrirConsulta(codigoLinha, codigoAcao, descricaoLinha) {
        new PesquisaLinhaPacto(codigoLinha, codigoAcao, descricaoLinha);
    }


</script>

