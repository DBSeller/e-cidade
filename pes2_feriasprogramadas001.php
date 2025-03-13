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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_gerfcom_classe.php");

$rotulo = new rotulocampo;
$rotulo->label("r44_selec");
$rotulo->label("r44_descr");

$ano = DBPessoal::getAnoFolha();
$mes = DBPessoal::getMesFolha();
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewTipoFiltrosFolha.js"></script>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="estilos/grid.style.css">
    <style type="text/css">
        td {
            padding: 5px;
        }
    </style>
</head>
<body>
<form class="container">
    <fieldset>
        <legend>Férias Programadas</legend>
        <table class="form-container">
            <tr>
                <td nowrap title="Ano / Mês de competência">
                    <label for="ano">Ano / Mês de competência:</label>
                </td title="Ano / Mês de competência">
                <td>
                    <?php db_input('ano', 4, 1, true, 'text', 2); ?>
                    <?php db_input('mes', 2, 1, true, 'text', 2); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="r44_selec" id="lblSelecao">Seleção:</label>
                </td>
                <td>
                    <?php db_input('r44_selec', null, 1, true, 'text', 2); ?>
                    <?php db_input('r44_descr', null, 0, true, 'text', 3); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="servidorAfastado">Servidores Afastados:</label>
                </td>
                <td>
                    <?php
                    $opcoes = array("Não", "Sim");
                    db_select("servidorAfastado", $opcoes, true, 1);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" id="containnerTipoFiltrosFolha"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <fieldset>
                        <legend>Visualização</legend>
                        <table class="form-container">
                            <tr>
                                <td width="27%">
                                    <label for="quebrarPagina">Quebrar Página:</label>
                                </td>
                                <td>
                                    <?php
                                    $opcoes = array("Não", "Por Local de Trabalho");
                                    db_select("quebrarPagina", $opcoes, true, 1);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="tipoOrdem">Tipo de ordem:</label>
                                </td>
                                <td>
                                    <?php
                                    $opcoes = array(
                                      "Alfabética (Local de Trabalho)",
                                      "Numérica",
                                      "Período aquisitivo inicial",
                                      "Período aquisitivo final"
                                    );
                                    db_select("tipoOrdem", $opcoes, true, 1);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="formato">Formato:</label>
                                </td>
                                <td>
                                    <?php
                                    $opcoes = array("PDF", "CSV");
                                    db_select("formato", $opcoes, true, 1);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </fieldset>
    <input type="button" id="emitir" value="Emitir">
</form>

<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"),
  db_getsession("DB_instit"));
?>
</body>
</html>
<script type="application/javascript">
	new DBLookUp($('lblSelecao'), $('r44_selec'), $('r44_descr'), {
		sArquivo: "func_selecao.php",
		sObjetoLookUp: 'func_selecao',
		sLabel: "Seleção"
	});

	var filtrosFolha = new DBViewFormularioFolha.DBViewTipoFiltrosFolha(<?php echo db_getsession("DB_instit")?>);
	filtrosFolha.aTipos = [0, 1, 2, 3, 4, 5];
	filtrosFolha.sInstancia = 'filtrosFolha';
	filtrosFolha.show($('containnerTipoFiltrosFolha'));

	$('emitir').observe('click', () => {
		if (!validarFormulario()) {
			return false;
		}

        let tipoFiltro = $F('oCboTipoFiltro');
		let query = {};
		query.iAno = $F('ano');
		query.iMes = $F('mes');
		query.iSelecao = $F('r44_selec');
		query.iTipoRelatorio = $F('oCboTipoRelatorio');
		query.iTipoFiltro = $F('oCboTipoFiltro');
        query.iFormato    = $F('formato');
        query.iQuebraPagina    = $F('quebrarPagina');
        query.iQuebraPagina    = $F('quebrarPagina');
        query.iTipoOrdem    = $F('tipoOrdem');
        query.iServidorAfastado  = $F('servidorAfastado');


		/**
		 * Verifica se o tipo escolhido foi intervalo
		 */
		if (tipoFiltro == 1) {
			query.iIntervaloInicial = $F('InputIntervaloInicial');
			query.iIntervaloFinal = $F('InputIntervaloFinal');
		}

		/**
		 * Verifica se o tipo escolhido foi sele??o
		 */
		if (tipoFiltro == 2) {
			let selecionados = [];
			let tipoFiltros = filtrosFolha.getLancadorAtivo().getRegistros();

			/**
			 * Percorre os itens selecionados no lancador
			 */
			tipoFiltros.each(function (oFiltro, iIndice) {
				selecionados[iIndice] = oFiltro.sCodigo;
			});

			query.iRegistros = selecionados;
		}

		query.sOrdem = $F('tipoOrdem');

		let oJanela = window.open(
			'pes2_feriasprogramadas002.php?json=' + Object.toJSON(query),
			'',
			'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 '
		);

		oJanela.moveTo(0, 0);
	});

	function validarFormulario() {
		if ($F('ano') === '' || $F('mes') === '') {
			alert('Por favor, informe Ano/Mês para a emissão do relatório.');
			return false;
		}

		if ($F('ano') <= 0) {
			alert('Ano da folha informado é inválido.');
			return false;
		}

		if ($F('mes') <= 0 || $F('mes') > 12) {
			alert('Mês da folha informado é inválido.');
			return false;
		}


		let tipoRelatorio = $F('oCboTipoRelatorio');
		let tipoFiltro = $F('oCboTipoFiltro');

		if (tipoRelatorio != 0 && tipoFiltro == 2) {

			let lancadorSelecionado = filtrosFolha.getLancadorAtivo().getRegistros();
			if (lancadorSelecionado.length === 0) {

				alert('Por Favor, realize pelo menos o lançamento de 1 registro.');
				return false
			}
		}

		if (tipoRelatorio != 0 && tipoFiltro == 1) {

			if ($F('InputIntervaloInicial') == '' || $F('InputIntervaloFinal') == '') {

				alert('Por favor, informe o intervalo para geração do relatório.');
				return false;
			}
		}

		return true;
	}

</script>