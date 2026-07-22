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
require_once(modification("classes/db_folha_classe.php"));
require_once(modification("classes/db_selecao_classe.php"));
require_once(modification("classes/db_gerfsal_classe.php"));
require_once(modification("classes/db_gerfadi_classe.php"));
require_once(modification("classes/db_gerffer_classe.php"));
require_once(modification("classes/db_gerfres_classe.php"));
require_once(modification("classes/db_gerfs13_classe.php"));
require_once(modification("classes/db_gerfcom_classe.php"));
require_once(modification("classes/db_gerffx_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
db_postmemory($_POST);
$clfolha = new cl_folha;
$clselecao = new cl_selecao;
$clgerfsal = new cl_gerfsal;
$clgerfadi = new cl_gerfadi;
$clgerffer = new cl_gerffer;
$clgerfres = new cl_gerfres;
$clgerfs13 = new cl_gerfs13;
$clgerfcom = new cl_gerfcom;
$clgerffx = new cl_gerffx;
$clrotulo = new rotulocampo;
$clrotulo->label('r90_valor');
$clrotulo->label('r48_semest');
$db_opcao = 1;
$db_botao = true;

$anoFolha = DBPessoal::getAnoFolha();
$mesFolha = DBPessoal::getMesFolha();
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">

    <form name="form1" method="post" action="">
        <fieldset>
            <legend>Relatório de Retenções e Consignações</legend>

            <table class="form-container">
                <tr>
                    <td nowrap colspan="2">
                        <?php
                        db_input("folhaselecion", 3, 0, true, 'hidden', 3);
                        $arr_pontosgerfs_inicial = array();
                        $arr_pontosgerfs_final = array();
                        $arr_pontos = array(
                            "0" => "Salário",
                            "1" => "Adiantamento",
                            "2" => "Férias",
                            "3" => "Rescisão",
                            "4" => "Saldo do 13o",
                            "5" => "Complementar"
                        );
                        if (isset($objeto1)) {
                            foreach ($objeto1 as $index) {
                                $arr_pontosgerfs_inicial[$index] = $arr_pontos[$index];
                            }
                        } else {
                            $arr_pontosgerfs_inicial = $arr_pontos;
                        }
                        if (isset($objeto2)) {
                            foreach ($objeto2 as $index) {
                                $arr_pontosgerfs_final[$index] = $arr_pontos[$index];
                            }
                        }
                        db_multiploselect("valor", "descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 6, 250, "", "", true);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><strong>Totaliza por Recurso:</strong>
                    </td>
                    <td>
                        <?php
                        $arr_tipo = array("s" => "Sim", "n" => "Não");
                        db_select('totaliza', $arr_tipo, true, 4);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Ano/Mês:</td>
                    <td>
                        <input type="text" class="field-size1" name='anoFolha' id="anoFolha" value="<?= $anoFolha ?>"> /
                        <input type="text" class="field-size1" name='mesFolha' id="mesFolha" value="<?= $mesFolha ?>">
                    </td>
                </tr>
            </table>
            <div id="lancadorRubricas"></div>
            <div id="lancadorRecursos"></div>
        </fieldset>
        <input name="incluir" type="button" id="db_opcao" onclick="js_enviardados();" value="Gerar">
    </form>

</div>
<?php
db_menu();
?>
</body>
</html>
<script>

    const selectTipoFolha = document.getElementById('objeto2');
    const inputTotaliza = document.getElementById('totaliza');
    const inputAno = document.getElementById('anoFolha');
    const inputMes = document.getElementById('mesFolha');

    const cntLancadorRubricas = document.getElementById('lancadorRubricas');
    var lancadorRubricas = new DBLancador("lancadorRubricas");
    lancadorRubricas.iGridHeight = 100;
    lancadorRubricas.sTextoFieldset = 'Filtrar Recurso(s)';
    lancadorRubricas.setLabelAncora("Rubrica:");
    lancadorRubricas.setNomeInstancia("lancadorRubricas");
    lancadorRubricas.setHabilitado(true);
    lancadorRubricas.selecionarAposPesquisar = true;
    lancadorRubricas.setParametrosPesquisa("func_rhrubricas.php", ["rh27_rubric", "rh27_descr"]);
    lancadorRubricas.show(cntLancadorRubricas);

    const cntLancadorRecurso = document.getElementById('lancadorRecursos');
    var lancadorRecurso = new DBLancador("lancadorRecurso");
    lancadorRecurso.iGridHeight = 100;
    lancadorRecurso.sTextoFieldset = 'Filtrar Recurso(s)';
    lancadorRecurso.setLabelAncora("Recurso:");
    lancadorRecurso.setNomeInstancia("lancadorRecurso");
    lancadorRecurso.setHabilitado(true);
    lancadorRecurso.selecionarAposPesquisar = true;
    lancadorRecurso.setParametrosPesquisa("func_fonterecurso.php", ["o15_recurso", "o15_descr"], 'db_instit=1');
    lancadorRecurso.show(cntLancadorRecurso);

    function js_enviardados() {
        if (inputAno.value == "") {
            alert("Informe o ano a ser pesquisado.");
            inputAno.focus();
            return;
        }
        if (inputMes.value == "") {
            alert("Informe o mês a ser pesquisado.");
            inputMes.focus();
            return;
        }

        if (selectTipoFolha.options.length === 0) {
            alert("Selecione ao menos um tipo de Ponto.")
        }

        let rubricas = []
        lancadorRubricas.getRegistros().map(rubrica => {
            rubricas.push(rubrica.sCodigo)
        });

        let recursos = []
        lancadorRecurso.getRegistros().map(recurso => {
            recursos.push(recurso.sCodigo)
        });

        let pontos = []
        for (let option of selectTipoFolha.options) {
            pontos.push(option.value);
        }

        let filtro = `?ano=${inputAno.value}&mes=${inputMes.value}&totaliza=${inputTotaliza.value}`;
        filtro += '&pontos=' + pontos.join(',');
        filtro += '&rubricas=' + JSON.stringify(rubricas);
        filtro += '&recursos=' + JSON.stringify(recursos);

        jan = window.open('pes2_retconsig002.php' + filtro, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0');
        jan.moveTo(0, 0);
    }
</script>
