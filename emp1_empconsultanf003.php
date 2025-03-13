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

use ECidade\Financeiro\Empenho\Mapper\TiposNotasParaiba;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_empnotaele_classe.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

$clempnotaele = new cl_empnotaele();
$oGet = db_utils::postMemory($_GET);
$sCampos = "
    e69_numero,e69_codnota, e69_dtnota,e69_dtrecebe,e69_dtservidor,e70_valor,m72_codordem,
    case when
       e04_numeroprocesso is null then
       e03_numeroprocesso
      end as e04_numeroprocesso,
    e69_outrosdados
";

$sWhere = "";
if (trim($oGet->e69_codnota) != '') {
    $sWhere .= "e69_codnota = " . $oGet->e69_codnota;
}
$e69_codnota = $oGet->e69_codnota;

$sSqlNota = $clempnotaele->sql_query_nf(null, null, $sCampos, null, $sWhere);
$rsNota = db_query($sSqlNota);

$tipoNota = '';
$numeroSerie = '';
$chave = '';

$e69_numero = '';
$e69_dtnota = '';
$e69_dtservidor = '';
$e69_dtrecebe = '';
$e70_valor = '';
$m72_codordem = '';
if (pg_num_rows($rsNota) > 0) {
    $oNota = db_utils::fieldsMemory($rsNota, 0);

    if (isParaiba() && !empty($oNota->e69_outrosdados)) {
        $outrosDados = json_decode($oNota->e69_outrosdados);
        $tipo = (new TiposNotasParaiba())->getTipoByID($outrosDados->tipo_nota);

        $tipoNota = sprintf('%s - %s', $tipo['id'], $tipo['label']);
        $numeroSerie = $outrosDados->serie_nota;
        $chave = $outrosDados->chave_nota;
    }
    $e69_numero = $oNota->e69_numero;
    $e69_dtnota = db_formatar($oNota->e69_dtnota, 'd');
    $e69_dtservidor = db_formatar($oNota->e69_dtservidor, 'd');
    $e69_dtrecebe = db_formatar($oNota->e69_dtrecebe, 'd');
    $e70_valor = trim(db_formatar($oNota->e70_valor, 'f'));
    $m72_codordem = $oNota->m72_codordem;
}

?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js,
             classes/infoLancamentoContabil.classe.js,messageboard.widget.js");
    db_app::load("estilos.css, grid.style.css,tab.style.css");
    ?>

</head>
<body>

<form name="form1" method="post">
    <table style="width: 100%">
        <tr>
            <td>
                <fieldset>
                    <legend><b>Dados da Nota</b></legend>
                    <table>
                        <tr>
                            <td><b>Número:</b> </td>
                            <td class="field-size3" style="background-color: #FFFFFF;" align="right">
                                <?= $e69_numero; ?>
                            </td>
                            <td onclick="js_consultaOrdemCompra(<?= $m72_codordem; ?>);"
                                style="cursor: pointer;">
                                <a href="#"><b>Ordem Compra:</b></a>
                            </td>
                            <td class="field-size3"  style="background-color: #FFFFFF; text-align: right;">
                                <?= $m72_codordem; ?>
                            </td>
                        </tr>
                        <?php if (isParaiba()): ?>
                            <tr>
                                <td><strong>Tipo Nota:</strong></td>
                                <td colspan="3" style="background-color: #FFFFFF;">
                                    <?= $tipoNota ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Chave:</strong></td>
                                <td colspan="3" style="background-color: #FFFFFF;">
                                    <?= $chave ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Número de Série:</strong></td>
                                <td colspan="3" style="background-color: #FFFFFF;">
                                    <?= $numeroSerie ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td><b>Data:</b></td>
                            <td style="background-color: #FFFFFF; text-align: right">
                                <?= $e69_dtnota;?>
                            </td>
                            <td><b>Data Entrada:</b></td>
                            <td style="background-color: #FFFFFF;width: 60px; text-align: right;">
                                <?= $e69_dtservidor;?>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Data Entrega:</b></td>
                            <td style="background-color: #FFFFFF;width: 60px; text-align: right;">
                                <?= $e69_dtrecebe;?>
                            </td>
                            <td><b>Valor:</b></td>
                            <td style="background-color: #FFFFFF; text-align: right;">
                                <?= $e70_valor; ?>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Processo Administrativo:</strong></td>
                            <td colspan="3" style="background-color: #FFFFFF;width: 60px; text-align: right">
                                <?= $e04_numeroprocesso ?>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>

    <fieldset>
        <legend><b>Outras Informações</b></legend>
        <?php
        $oTabDetalhes = new verticalTab("detalhesemp", 300);
        $oTabDetalhes->add("detalhamento", "Detalhamento", "func_empconsultanf001.php?e69_codnota={$e69_codnota}&exec=detalhamento");
        $oTabDetalhes->add("lancamentos", "Lançamentos", "func_conlancam002.php?e69_codnota={$e69_codnota}&chavepesquisa='nota'");
        $oTabDetalhes->add("retencoes", "Retenções", "func_empconsultanf001.php?e69_codnota={$e69_codnota}&exec=retencoes");
        $oTabDetalhes->add("itens", "Ítens", "func_empconsultanf001.php?e69_codnota={$e69_codnota}&exec=itens");
        $oTabDetalhes->add("pit", "PIT", "func_empconsultanf001.php?e69_codnota={$e69_codnota}&exec=pit");
        $oTabDetalhes->show();
        ?>

    </fieldset>

</form>
</body>
</html>
<script>
    function js_infoLancamento(iLancamento) {
        var oLancamentoInfo = new infoLancamentoContabil(iLancamento);
    }

    function js_consultaOrdemCompra(iCodigoOrdem) {

        var sQuery = '';
        sQuery += 'm51_codordem=' + iCodigoOrdem;

        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_ordemcompra',
            'com3_ordemdecompra002.php?' + sQuery,
            'Dados da ordem de compra', true);

    }

</script>
