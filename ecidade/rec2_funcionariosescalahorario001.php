<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript"
            src="scripts/classes/recursoshumanos/Efetividade/PeriodoEfetividade.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <title>DBSeller Sistemas Integrados</title>
</head>
<body class="body-default">
<div class="container">
    <form>
        <fieldset>
            <legend>Funcionários Por Escala de Horário</legend>

            <table class="form-container">
                <tr>
                    <td>
                        <label for="periodoInicio">Período:</label>
                    </td>
                    <td id="linhaPeriodoEfetividade" colspan="2" class="field-size-max"></td>
                </tr>
                <tr >
                    <td>
                        <label for="formato">Formato:</label>
                    </td>
                    <td>
                        <?php
                        $opcoes = array("PDF", "CSV");
                        db_select("formato", $opcoes, true, 1, "style='width:144px'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td >
                        <label for="quebrarPagina">Quebrar Página:</label>
                    </td>
                    <td>
                        <?php
                        $opcoes = array("Não", "Por Local de Trabalho");
                        db_select("quebrarPagina", $opcoes, true, 1, "style='width:144px'");
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="tipoFiltro">Filtrar por:</label>
                    </td>
                    <td colspan="2" class="field-size-max">
                        <select id="tipoFiltro" style="width: 145px;">
                            <option value="1" selected>Seleção</option>
                            <option value="2">Matrícula</option>
                            <option value="3">Local de Trabalho</option>
                        </select>
                    </td>
                </tr>

                <tr id="filtroSelecao" >
                    <td id="selecao" colspan="3"></td>
                </tr>

                <tr id="filtroMatricula" style="display: none;">
                    <td id="matricula" colspan="3"></td>
                </tr>

                <tr id="filtroLocais" style="display: none;">
                    <td id="locais" colspan="3"></td>
                </tr>
            </table>

        </fieldset>

        <input id="gerar" type="button" value="Gerar Relatório"/>
    </form>

    <?php db_menu(); ?>

</div>
</body>
<script>


    var tipoFiltro = $('tipoFiltro');


    var oPeriodoEfetividade = new PeriodoEfetividade();

    oPeriodoEfetividade.__initDataSugerida(<?=DBPessoal::getCompetenciaFolha()->getAno()?>,<?=DBPessoal::getCompetenciaFolha()->getMes() - 1?>);

    oPeriodoEfetividade.show($('linhaPeriodoEfetividade'));

    var lancadorMatriculas = new DBLancador('lancadorMatriculas');
    lancadorMatriculas.setLabelAncora('Matrícula:');
    lancadorMatriculas.setNomeInstancia('lancadorMatriculas');
    lancadorMatriculas.setTituloJanela('Pesquisa de Matrícula');
    lancadorMatriculas.setParametrosPesquisa('func_rhpessoal.php', ['rh01_regist', 'z01_nome']);
    lancadorMatriculas.setTextoFieldset('Matrículas');
    lancadorMatriculas.setGridHeight(150);
    lancadorMatriculas.adicionarItensPrimeiraPosicao(true);

    lancadorMatriculas.setCallbackBotao(function () {
        $('txtCodigolancadorMatriculas').focus();
    });

    lancadorMatriculas.show($('matricula'));


    var lancadorSelecao = new DBLancador('lancadorSelecao');
    lancadorSelecao.setLabelAncora('Seleção:');
    lancadorSelecao.setNomeInstancia('lancadorSelecao');
    lancadorSelecao.setTituloJanela('Pesquisa de Seleção');
    lancadorSelecao.setParametrosPesquisa('func_selecao.php', ['r44_selec', 'r44_descr']);
    lancadorSelecao.setTextoFieldset('Seleção');
    lancadorSelecao.setGridHeight(150);
    lancadorSelecao.show($('selecao'));


    var oLancadorLocais = new DBLancador('oLancadorLocais');
    oLancadorLocais.setLabelAncora('Locais de trabalho:');
    oLancadorLocais.setNomeInstancia('oLancadorLocais');
    oLancadorLocais.setTituloJanela('Pesquisa de Locais de Trabalho');
    oLancadorLocais.setParametrosPesquisa('func_rhlocaltrab.php', ['rh55_codigo', 'rh55_descr']);
    oLancadorLocais.setTextoFieldset('Adicionar Registros');
    oLancadorLocais.setGridHeight(150);
    oLancadorLocais.show($('locais'));


    tipoFiltro.observe('change', function () {

        /**
         * Filtrar por Seleção
         */
        if (tipoFiltro.value === '1') {


            $('filtroMatricula').setStyle({'display': 'none'});
            $('filtroLocais').setStyle({'display': 'none'});
            $('filtroSelecao').setStyle({'display': ''});

            lancadorMatriculas.clearAll();
            oLancadorLocais.clearAll();
        }

        /**
         * Filtrar por Matrícula
         */
        if (tipoFiltro.value === '2') {

            $('filtroSelecao').setStyle({'display': 'none'});
            $('filtroLocais').setStyle({'display': 'none'});
            $('filtroMatricula').setStyle({'display': ''});

            $('r44_selec').value = '';
            $('r44_descr').value = '';

            $('rh55_codigo').value = '';
            $('rh55_descr').value = '';
        }

        /**
         * Filtrar por Local de Trabalho
         */
        if (tipoFiltro.value === '3') {

            $('filtroSelecao').setStyle({'display': 'none'});
            $('filtroMatricula').setStyle({'display': 'none'});
            $('filtroLocais').setStyle({'display': ''});

            $('r44_selec').value = '';
            $('r44_descr').value = '';

            lancadorMatriculas.clearAll();
        }
    });

    $('gerar').observe('click', function () {

        if (oPeriodoEfetividade.getDataInicio() === null || oPeriodoEfetividade.getDataFim() === null) {

            alert('Período de datas não informado.');
            return false;
        }

        var matriculas = [];
        var formato = $F('formato');
        var selecao = [];
        var selecaoDesc = [];
        var localTrabalho = [];

        if (tipoFiltro.value === '1') {
            matriculas = [];
            localTrabalho=  [];
            lancadorSelecao.getRegistros().each(function (oSelecao) {
                selecao.push(oSelecao.sCodigo);
                selecaoDesc.push(oSelecao.sDescricao);
            });
        }

        if (tipoFiltro.value === '2') {

            selecao = [];
            selecaoDesc = [];

            localTrabalho=  [];
            lancadorMatriculas.getRegistros().each(function (matricula) {
                matriculas.push(matricula.sCodigo);
            });
        }

        if (tipoFiltro.value === '3') {

            selecao = [];
            selecaoDesc = [];
            matriculas = [];
            oLancadorLocais.getRegistros().each(function (oLocal) {
                localTrabalho.push(oLocal.sCodigo);
            });
        }


        var emissaoRelatorio = new EmissaoRelatorio(
            'rec2_funcionariosescalahorario002.php',
            {
                'dataInicial': oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataInicio()),
                'dataFinal': oPeriodoEfetividade.getDataFormatada(oPeriodoEfetividade.getDataFim()),
                'selecao': selecao,
                'selecaodesc' :selecaoDesc,
                'matriculas': matriculas,
                'localTrabalho': localTrabalho,
                'filtro': tipoFiltro.value,
                'formato' : formato,
                'quebrarPagina': $F('quebrarPagina')
            }
        );

        emissaoRelatorio.open();
    });
</script>
</html>
