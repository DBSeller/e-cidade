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
use ECidade\Pdf\Pdf;

include(modification("libs/db_stdlib.php"));
include(modification("libs/db_conecta.php"));

db_postmemory($_POST);
$oDaofar_retiradaitens = new cl_far_retiradaitens();

function novoCabecalho(Pdf $oPdf, $sLabel1, $sLabel2)
{
    $lCor = true;
    $oPdf->setfillcolor(223);
    $oPdf->setfont('arial', 'B', 9);
    $iTam = 5;

    $oPdf->cell(16, $iTam, 'Data', 1, 0, 'C', $lCor);
    $oPdf->cell(29, $iTam, 'Tipo de Retirada', 1, 0, 'C', $lCor);
    $oPdf->cell(10, $iTam, $sLabel1, 1, 0, 'C', $lCor);
    $oPdf->cell(98, $iTam, $sLabel2, 1, 0, 'C', $lCor);
    $oPdf->cell(10, $iTam, 'Qtde.', 1, 0, 'C', $lCor);
    $oPdf->cell(15, $iTam, 'Lote', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Val. do Lote', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Requisição', 1, 0, 'C', $lCor);
    $oPdf->cell(40, $iTam, 'Motivo da Devolução', 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, 'Usuário', 1, 1, 'C', $lCor);
}

function novaQuebra(Pdf $oPdf, $sLabel1, $sCampo1, $sLabel2, $sCampo2, $sCartaoSus, $iTipo)
{
    $lCor = true;
    $oPdf->setfillcolor(243);
    $oPdf->setfont('arial', 'B', 9);
    $iTam = 8;
    $iHeigth = 248;

    $oPdf->cell(30, $iTam, "{$sLabel1}: {$sCampo1}", 'LBT', 0, 'L', $lCor);
    if ($iTipo == 1) { // Quebra por CGS
        $oPdf->cell(55, $iTam, "CNS: {$sCartaoSus}", 'BT', 0, 'L', $lCor);
        $iHeigth = 193;
    }
    $oPdf->cell($iHeigth, $iTam, "{$sLabel2}: {$sCampo2}", 'RBT', 1, 'L', $lCor);
}

function novaLinha(
    Pdf $oPdf,
    $dData,
    $sCampo1,
    $sCampo2,
    $iQuantidade,
    $iLote,
    $dValidade,
    $iRequisicao,
    $sLogin,
    $sTipo,
    $sMotivo,
    $iTipo
) {

    // Devolução
    $lCor = false;
    if ($iTipo == 2) {
        $oPdf->setfillcolor(243);
        $lCor = true;
    }

    $oPdf->setfont('arial', '', 8);
    $iTam = 5;

    $oPdf->cell(16, $iTam, $dData, 1, 0, 'C', $lCor);
    $oPdf->cell(29, $iTam, $sTipo, 1, 0, 'L', $lCor);
    $oPdf->cell(10, $iTam, $sCampo1, 1, 0, 'C', $lCor);
    $oPdf->cell(98, $iTam, substr($sCampo2, 0, 52), 1, 0, 'L', $lCor);
    $oPdf->cell(10, $iTam, $iQuantidade, 1, 0, 'C', $lCor);
    $oPdf->cell(15, $iTam, $iLote, 1, 0, 'L', $lCor);
    $oPdf->cell(20, $iTam, $dValidade, 1, 0, 'C', $lCor);
    $oPdf->cell(20, $iTam, $iRequisicao, 1, 0, 'C', $lCor);
    $oPdf->cell(40, $iTam, substr($sMotivo, 0, 24), 1, 0, 'L', $lCor);
    $oPdf->cell(20, $iTam, $sLogin, 1, 1, 'L', $lCor);
}

function novoTotal(Pdf $oPdf, $iTotalRetirado, $iTotalDevolvido, $sUnidade)
{
    $lCor = false;
    $oPdf->setfont('arial', 'B', 8);
    $iTam = 5;

    $oPdf->cell(60, $iTam, "TOTAL RETIRADO: {$iTotalRetirado} {$sUnidade}(S)", 'LBT', 0, 'L', $lCor);
    $oPdf->cell(103, $iTam, "TOTAL DEVOLVIDO: {$iTotalDevolvido} {$sUnidade}(S)", 'BT', 0, 'L', $lCor);
}

function cabecalhoTotalGeral(Pdf $oPdf, $lTitulo = false)
{
    $lCor = true;
    $oPdf->setfillcolor(223);
    $iTam = 5;

    if ($lTitulo) {
        $oPdf->setfont('arial', 'B', 11);
        $oPdf->cell(278, 8, 'Total Retirado por Medicamento', 0, 1, 'C', false);
    }

    $oPdf->setfont('arial', 'B', 9);

    $oPdf->cell(20, $iTam, 'Código', 1, 0, 'L', $lCor);
    $oPdf->cell(186, $iTam, 'Medicamento', 1, 0, 'L', $lCor);
    $oPdf->cell(26, $iTam, 'Qtde. Retirada', 1, 0, 'L', $lCor);
    $oPdf->cell(26, $iTam, 'Qtde. Devolvida', 1, 0, 'L', $lCor);
    $oPdf->cell(20, $iTam, 'Unidade', 1, 1, 'L', $lCor);
}

function novaLinhaTotalGeral(Pdf $oPdf, $iCodigo, $sMedicamento, $iQuantidadeRetirada, $iQuantidadeDevolvida, $sUnidade)
{
    $lCor = false;
    $oPdf->setfont('arial', '', 8);
    $iTam = 5;

    $oPdf->cell(20, $iTam, $iCodigo, 1, 0, 'L', $lCor);
    $oPdf->cell(186, $iTam, $sMedicamento, 1, 0, 'L', $lCor);
    $oPdf->cell(26, $iTam, $iQuantidadeRetirada, 1, 0, 'L', $lCor);
    $oPdf->cell(26, $iTam, $iQuantidadeDevolvida, 1, 0, 'L', $lCor);
    $oPdf->cell(20, $iTam, $sUnidade, 1, 1, 'L', $lCor);
}

function formataData($dData, $iTipo = 1)
{
    if (empty($dData)) {
        return '';
    }

    if ($iTipo == 1) {
        $dData = explode('/', $dData);
        $dData = $dData[2] . '-' . $dData[1] . '-' . $dData[0];
        return $dData;
    }

    $dData = explode('-', $dData);
    $dData = @$dData[2] . '/' . @$dData[1] . '/' . @$dData[0];
    return $dData;
}

if (isset($datas)) {
    $dDatas = explode(',', $datas);
    $dDataIni = formataData($dDatas[0]);
    $dDataFim = formataData($dDatas[1]);
}

$sWherePeriodo1 = '';
$sWherePeriodo2 = '';
$lChaveSoCgs = true; // se foi passado apenas o CGS

if (isset($datas)) {
    $sWherePeriodo1 = " fa04_d_data between '$dDataIni' and '$dDataFim'";
    $sWherePeriodo2 = " fa22_d_data between '$dDataIni' and '$dDataFim'";
    $lChaveSoCgs = false;
}

$sWhereCgs = '';
if (isset($iCgs) && !$lChaveSoCgs) {
    $sWhereCgs = " and z01_i_cgsund in ($iCgs) ";
} elseif (isset($iCgs) && $lChaveSoCgs) {
    $sWhereCgs = " z01_i_cgsund = $iCgs ";
}

$sWhereMedicamentos = '';
if (isset($medicamentos)) {
    $sWhereMedicamentos = " and fa01_i_codigo in ($medicamentos) ";
}

$sWhereDepartamentos = '';
if (isset($departamentos)) {
    $sWhereDepartamentos = "and m70_coddepto in ($departamentos)";
}

if (isset($medicamentos) && !isset($iCgs)) {
    $iTipo = 2; // quebra por medicamento
    $sOrderBy = ' fa04_d_data desc, z01_v_nome asc, fa01_i_codigo, z01_i_cgsund, fa06_i_codigo, tipo ';
} else {
    $iTipo = 1; // quebra por CGS
    $sOrderBy = ' fa04_d_data desc, z01_v_nome asc, z01_i_cgsund, fa01_i_codigo, fa06_i_codigo, tipo ';
}

$sWhereTipoReceita = '';
if (isset($tipoDeReceita) && $tipoDeReceita != "") {
    $sWhereTipoReceita = "and fa03_i_codigo = {$tipoDeReceita}";
}

$sCampos = ' z01_i_cgsund, z01_v_nome, fa01_i_codigo, m60_descr, m61_descr, fa07_i_matrequi, ';
$sCampos .= ' coalesce(fa09_f_quant, fa06_f_quant) as fa09_f_quant, ';
$sCampos .= ' case when fa04_i_codigo is null then fa22_i_codigo else fa04_i_codigo end as id, ';
$sCampos .= ' fa04_d_data, m77_lote, m77_dtvalidade, login, tipo, fa23_c_motivo, ';
$sCampos .= " case when fa04_tiporetirada = 1 and tipo = 1 then ";
$sCampos .= "   'Normal' ";
$sCampos .= " else case when fa04_tiporetirada = 2 and tipo = 1 then ";
$sCampos .= "        'Não padronizada' ";
$sCampos .= "      else case when fa23_i_cancelamento = 2 and tipo = 2 then ";
$sCampos .= "             'Devolução' ";
$sCampos .= "           else case when fa23_i_cancelamento = 1 and fa04_tiporetirada = 1 and tipo = 2 then ";
$sCampos .= "                  'Cancelamento' ";
$sCampos .= "                else ";
$sCampos .= "                  'Cancelamento N. P.' ";
$sCampos .= "                end ";
$sCampos .= "           end ";
$sCampos .= "      end ";
$sCampos .= " end as stipo, ";
$sCampos .= ' fa23_i_quantidade,fa22_d_data, fa03_c_descr ';

//Query principal do relatorio
$sSql = $oDaofar_retiradaitens->sql_query_historicoretiradasdevolucoes(
    null,
    $sCampos,
    $sOrderBy,
    $sWherePeriodo1 . $sWhereCgs . $sWhereMedicamentos . $sWhereDepartamentos . $sWhereTipoReceita,
    $sWherePeriodo2 . $sWhereCgs . $sWhereMedicamentos . $sWhereDepartamentos . $sWhereTipoReceita,
    '',
    $sWhereDepartamentos
);
$sCamposTotal = ' fa01_i_codigo,';
$sCamposTotal .= ' m60_descr,';
$sCamposTotal .= ' m61_descr,';
$sCamposTotal .= ' sum(coalesce(fa09_f_quant, fa06_f_quant)) as fa09_f_quant, ';
$sCamposTotal .= ' sum(fa23_i_quantidade) as fa23_i_quantidade ';

$sOrderByTotal = ' fa01_i_codigo ';
$sGroupByTotal = ' fa01_i_codigo, m60_descr, m61_descr ';

$sSqlTotal = $oDaofar_retiradaitens->sql_query_historicoretiradasdevolucoes(
    null,
    $sCamposTotal,
    $sOrderByTotal,
    $sWherePeriodo1 . $sWhereCgs . $sWhereMedicamentos . $sWhereDepartamentos . $sWhereTipoReceita,
    $sWherePeriodo2 . $sWhereCgs . $sWhereMedicamentos . $sWhereDepartamentos . $sWhereTipoReceita,
    $sGroupByTotal,
    $sWhereDepartamentos
);

$rs = $oDaofar_retiradaitens->sql_record($sSql);
$iLinhas = $oDaofar_retiradaitens->numrows;

if ($iLinhas == 0) {
    ?>
    <table width='100%'>
        <tr>
            <td align='center'>
                <font color='#FF0000' face='arial'>
                    <b>Nenhum registro encontrado.<br>
                        <input type='button' value='Fechar' onclick='window.close()'>
                    </b>
                </font>
            </td>
        </tr>
    </table>
    <?php
    exit;
}

$rsTotal = $oDaofar_retiradaitens->sql_record($sSqlTotal);
$iLinhasTotal = $oDaofar_retiradaitens->numrows;

$oPdf = new Pdf();
$oPdf->init(false);
$oPdf->AliasNbPages();

$oPdf->addTitulo('Histórico da Retirada de Medicamentos');
$oPdf->addTitulo('');

if (!$lChaveSoCgs) {
    $oPdf->addTitulo('Período:');
    $oPdf->addTitulo('    ' . $dDatas[0] . ' a ' . $dDatas[1]);
} else {
    $oPdf->addTitulo('Paciente:');
    $oPdf->addTitulo('    ' . $iCgs . ' - ' . $sNome);
}

if (isset($descricaoReceita) && $descricaoReceita != '') {
    $oPdf->addTitulo('');
    $oPdf->addTitulo('Tipo de Receita: ' . $descricaoReceita);
}

$iCont_pacientes = 1;
$iCont_medicamentos = 1;
$iCont_linhas_na_pagina = 0;
$nome2 = '';
$medicamento2 = '';

if ($iTipo == 1) { // quebra por CGS
    $sCampo1 = 'z01_i_cgsund';
    $sCampo2 = 'z01_v_nome';
    $sCampo3 = 'fa01_i_codigo';
    $sCampo4 = 'm60_descr';
    $sLabel1 = 'CGS';
    $sLabel2 = 'Nome';
    $sLabel3 = 'Cód.';
    $sLabel4 = 'Medicamento';
} else { // quebra por medicamento
    $sCampo1 = 'fa01_i_codigo';
    $sCampo2 = 'm60_descr';
    $sCampo3 = 'z01_i_cgsund';
    $sCampo4 = 'z01_v_nome';
    $sLabel1 = 'Código';
    $sLabel2 = 'Medicamento';
    $sLabel3 = 'CGS';
    $sLabel4 = 'Nome';
}

if ($somenteTotalizadores === "false") {
    $oPdf->Addpage('L'); // P retrato
    novoCabecalho($oPdf, $sLabel3, $sLabel4);
    $oDados = db_utils::fieldsmemory($rs, 0);

    /* codigo que identifica quando é feita uma nova quebra, pois recebe o código do campo que vai determinar a quebra e
       quando este código mudar, significa que deve ser feita uma quebra */
    $iCodigoQuebra = -1;
    $iCodigoTotal = $oDados->{$sCampo3};
    $idRetirada = $oDados->id;
    $iTotalRetirado = 0;
    $iTotalDevolvido = 0;
    $iPaciente = '';
    $iTotalPacientes = 0;
    $receita = $oDados->fa03_c_descr;

    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {
        $oDados = db_utils::fieldsmemory($rs, $iCont);
        $oCgs = new Cgs($oDados->z01_i_cgsund);
        $sCartaoSus = $oCgs->getCartaoSusAtivo();

        if ($iCodigoTotal != $oDados->{$sCampo3} || $idRetirada != $oDados->id) { // novo total retirado
            novoTotal($oPdf, $iTotalRetirado, $iTotalDevolvido, $oDados->m61_descr);
            $iTotalRetirado = 0;
            $iTotalDevolvido = 0;
            $iCodigoTotal = $oDados->$sCampo3;
            $idRetirada = $oDados->id;
            $oPdf->setfont('arial', '', 7);
            $oPdf->cell(115, 5, $receita, 'RBT', 1, 'L');
            $receita = $oDados->fa03_c_descr;
        }

        if ($oPdf->getY() > $oPdf->getH() - 30) {
            $oPdf->Addpage('L'); // L deitado
            novoCabecalho($oPdf, $sLabel3, $sLabel4);
        }

        if ($iCodigoQuebra != $oDados->{$sCampo1}) { // novo paciente ou medicamento, dependendo do tipo de quebra
            novaQuebra($oPdf, $sLabel1, $oDados->{$sCampo1}, $sLabel2, $oDados->{$sCampo2}, $sCartaoSus, $iTipo);
            $iCodigoQuebra = $oDados->{$sCampo1};
        }

        if ($oDados->tipo == 2) {
            $oDados->fa04_d_data = $oDados->fa22_d_data;
            $oDados->fa09_f_quant = $oDados->fa23_i_quantidade;
        }

        novaLinha(
            $oPdf,
            formataData($oDados->fa04_d_data, 2),
            $oDados->{$sCampo3},
            $oDados->{$sCampo4},
            $oDados->fa09_f_quant,
            $oDados->m77_lote,
            formataData($oDados->m77_dtvalidade, 2),
            $oDados->fa07_i_matrequi,
            $oDados->login,
            $oDados->stipo,
            $oDados->fa23_c_motivo,
            $oDados->tipo
        );

        // retirada
        if ($oDados->tipo == 1) {
            $iTotalRetirado += $oDados->fa09_f_quant;
        } else { //devolução
            $iTotalDevolvido += $oDados->fa09_f_quant;
        }

        if ($iPaciente != $oDados->z01_i_cgsund) {
            $iPaciente = $oDados->z01_i_cgsund;
            $iTotalPacientes++;
        }
    }

    novoTotal($oPdf, $iTotalRetirado, $iTotalDevolvido, $oDados->m61_descr);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(115, 5, $receita, 'RBT', 1, 'L');
    $oPdf->setfont('arial', 'B', 8);
    $oPdf->cell(60, 5, "TOTAL PACIENTES ATENDIDOS: $iTotalPacientes", 'LBTR', 1, 'L', true);
}


// impressão do total geral
$oPdf->Addpage('L'); // L deitado
cabecalhoTotalGeral($oPdf, true);

for ($iCont = 0; $iCont < $iLinhasTotal; $iCont++) {
    $oDados = db_utils::fieldsmemory($rsTotal, $iCont);
    novaLinhaTotalGeral(
        $oPdf,
        $oDados->fa01_i_codigo,
        $oDados->m60_descr,
        $oDados->fa09_f_quant,
        $oDados->fa23_i_quantidade,
        $oDados->m61_descr
    );

    if ($oPdf->getY() > $oPdf->getH() - 30) {
        $oPdf->Addpage('L'); // P retrato
        cabecalhoTotalGeral($oPdf);
    }
}

$oPdf->Output();
