<?php
/*
 *  E-cidade Software Publico para Gestao Municipal
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

parse_str($_SERVER['QUERY_STRING']);

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));

$instituicaoSessao = db_getsession("DB_instit");
$iAnoUsoSessao      = db_getsession("DB_anousu");

define('APURACAO_TOTAL_RENDIMENTOS', 1);

if (!isset($cgm) || !isset($data_inicio) || !isset($data_final) || !isset($apuracao)) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Filtros necessários não informados.');
    exit;
}

// Retenções
$sWhereRetencoes = "corrente.k12_data between '{$data_inicio}' and '{$data_final}'";
$sWhereRetencoes .= " and e60_numcgm = $cgm";
$sWhereRetencoes .= " and e23_recolhido is true";
$sWhereRetencoes .= " and corrente.k12_estorn is false and e21_retencaotipocalc in (1,2)";
$sCampoOrdenarRetencoes = "EXTRACT(MONTH from corrente.k12_data)";
$groupByRetencoes = "GROUP BY EXTRACT(MONTH from corrente.k12_data)";

$sqlApenasRetencoes  = "SELECT ";
$sqlApenasRetencoes .= "    case ";
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '01' then 'JAN'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '02' then 'FEV'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '03' then 'MAR'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '04' then 'ABR'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '05' then 'MAI'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '06' then 'JUN'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '07' then 'JUL'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '08' then 'AGO'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '09' then 'SET'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '10' then 'OUT'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '11' then 'NOV'" ;
$sqlApenasRetencoes .= "        when EXTRACT(MONTH from corrente.k12_data) = '12' then 'DEZ' " ;
$sqlApenasRetencoes .= "    end as mes, ";
$sqlApenasRetencoes .= "    'Total de rendimentos pagos no mês' as descricao, ";
$sqlApenasRetencoes .= "    sum(e53_valor) as valor, ";
$sqlApenasRetencoes .= "    sum(e23_valorretencao) as e23_valorretencao ";
$sqlApenasRetencoes .= "FROM retencaoreceitas ";
$sqlApenasRetencoes .= "INNER JOIN retencaopagordem on e20_sequencial = e23_retencaopagordem ";
$sqlApenasRetencoes .= "INNER JOIN pagordem on e50_codord = e20_pagordem ";
$sqlApenasRetencoes .= "LEFT JOIN pagordemconta on e49_codord = e20_pagordem ";
$sqlApenasRetencoes .= "INNER JOIN pagordemele on e50_codord = e53_codord ";
$sqlApenasRetencoes .= "INNER JOIN empempenho empempenho2 on empempenho2.e60_numemp = e50_numemp ";
$sqlApenasRetencoes .= "INNER JOIN orcdotacao on e60_coddot = o58_coddot and empempenho2.e60_anousu = o58_anousu ";
$sqlApenasRetencoes .= "INNER JOIN orctiporec on o15_codigo = o58_codigo ";
$sqlApenasRetencoes .= "INNER JOIN cgm on empempenho2.e60_numcgm = cgm.z01_numcgm ";
$sqlApenasRetencoes .= "LEFT JOIN cgm cgmordem on e49_numcgm = cgmordem.z01_numcgm ";
$sqlApenasRetencoes .= "INNER JOIN pagordemnota on e71_codord = e50_codord and e71_anulado is false ";
$sqlApenasRetencoes .= "INNER JOIN empnota on e71_codnota = e69_codnota ";
$sqlApenasRetencoes .= "INNER JOIN retencaotiporec on e21_sequencial = e23_retencaotiporec ";
$sqlApenasRetencoes .= "INNER JOIN tabrec on e21_receita = k02_codigo ";
$sqlApenasRetencoes .= "LEFT JOIN retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial ";
$sqlApenasRetencoes .= "LEFT JOIN slipretencaoreceitas on k206_retencaoreceitas = e23_sequencial ";
$sqlApenasRetencoes .= "LEFT JOIN corgrupocorrente on k105_sequencial = e47_corgrupocorrente ";
$sqlApenasRetencoes .= "LEFT JOIN corrente on k105_id = corrente.k12_id and k105_autent = corrente.k12_autent 
                            and k105_data = corrente.k12_data and corrente.k12_instit = $instituicaoSessao ";
$sqlApenasRetencoes .= "LEFT JOIN conplanoreduz on corrente.k12_conta = c61_reduz and c61_anousu = $iAnoUsoSessao ";
$sqlApenasRetencoes .= "LEFT JOIN conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
$sqlApenasRetencoes .= "WHERE $sWhereRetencoes ";
$sqlApenasRetencoes .= "$groupByRetencoes ";
$sqlApenasRetencoes .= "ORDER BY $sCampoOrdenarRetencoes ";

// Todos os Rendimentos
$whereRendimentosPagos = "coremp.k12_data between '{$data_inicio}' and '{$data_final}'";
$whereRendimentosPagos .= " and empempenho.e60_numcgm = $cgm ";
$groupByRendimentosPagos = "GROUP BY EXTRACT(MONTH from k12_data)";
$campoOrdenarRendimentosPagos = "ORDER BY EXTRACT(MONTH from k12_data)";

$whereRetencoesLeft = "corrente.k12_data between '{$data_inicio}' and '{$data_final}' and e60_numcgm = $cgm ";
$whereRetencoesLeft .= "and e23_recolhido is true and corrente.k12_estorn is false ";

$sqlTodosOsRendimentos = "with rendimentos_cgm_pagos as (";
$sqlTodosOsRendimentos .= " SELECT ";
$sqlTodosOsRendimentos .= "       coremp.k12_data, ";
$sqlTodosOsRendimentos .= "       empempenho.e60_numcgm, empempenho.e60_numemp, pagordem.e50_codord, k12_valor, ";
$sqlTodosOsRendimentos .= "       k12_codord ";
$sqlTodosOsRendimentos .= " FROM coremp ";
$sqlTodosOsRendimentos .= " INNER JOIN empempenho on e60_numemp = k12_empen and e60_instit = $instituicaoSessao ";
$sqlTodosOsRendimentos .= " INNER JOIN orcdotacao on e60_coddot = o58_coddot and e60_anousu = o58_anousu ";
$sqlTodosOsRendimentos .= " INNER JOIN pagordem on e50_codord = k12_codord ";
$sqlTodosOsRendimentos .= " LEFT JOIN pagordemconta on e50_codord = e49_codord ";
$sqlTodosOsRendimentos .= " INNER JOIN corrente on corrente.k12_id = coremp.k12_id ";
$sqlTodosOsRendimentos .= "        and corrente.k12_data = coremp.k12_data ";
$sqlTodosOsRendimentos .= "        and corrente.k12_autent = coremp.k12_autent ";
$sqlTodosOsRendimentos .= " INNER JOIN cgm on cgm.z01_numcgm = e60_numcgm ";
$sqlTodosOsRendimentos .= " LEFT JOIN cgm cgmordem on cgmordem.z01_numcgm = e49_numcgm ";
$sqlTodosOsRendimentos .= " INNER JOIN saltes on saltes.k13_conta = corrente.k12_conta ";
$sqlTodosOsRendimentos .= " LEFT JOIN corgrupocorrente on k105_id = corrente.k12_id and k105_data = corrente.k12_data ";
$sqlTodosOsRendimentos .= "        and k105_autent = corrente.k12_autent ";
$sqlTodosOsRendimentos .= " LEFT JOIN corgrupotipo on k106_sequencial = k105_corgrupotipo ";
$sqlTodosOsRendimentos .= " WHERE $whereRendimentosPagos ";
$sqlTodosOsRendimentos .= " ORDER BY coremp.k12_data ";
$sqlTodosOsRendimentos .= ") ";
$sqlTodosOsRendimentos .= "SELECT ";
$sqlTodosOsRendimentos .= "    case ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '01' then 'JAN' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '02' then 'FEV' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '03' then 'MAR' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '04' then 'ABR' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '05' then 'MAI' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '06' then 'JUN' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '07' then 'JUL' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '08' then 'AGO' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '09' then 'SET' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '10' then 'OUT' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '11' then 'NOV' ";
$sqlTodosOsRendimentos .= "        when EXTRACT(MONTH from k12_data) = '12' then 'DEZ' ";
$sqlTodosOsRendimentos .= "        end as mes, ";
$sqlTodosOsRendimentos .= "    'Total de rendimentos pagos no mês' as descricao, ";
$sqlTodosOsRendimentos .= "    case ";
$sqlTodosOsRendimentos .= "     when sum(e53_valor) is null then sum(k12_valor) else sum(e53_valor) ";
$sqlTodosOsRendimentos .= "    end as valor, ";
$sqlTodosOsRendimentos .= "    sum(e23_valorretencao) as e23_valorretencao ";
$sqlTodosOsRendimentos .= "FROM ";
$sqlTodosOsRendimentos .= "rendimentos_cgm_pagos ";
$sqlTodosOsRendimentos .= "LEFT JOIN (";
$sqlTodosOsRendimentos .= "     SELECT ";
$sqlTodosOsRendimentos .= "          e53_valor, e23_valorretencao, empempenho2.e60_numcgm, ";
$sqlTodosOsRendimentos .= "          empempenho2.e60_numemp, e50_codord as ord ";
$sqlTodosOsRendimentos .= "     FROM retencaoreceitas ";
$sqlTodosOsRendimentos .= "     INNER JOIN retencaopagordem on e20_sequencial = e23_retencaopagordem ";
$sqlTodosOsRendimentos .= "     INNER JOIN pagordem on e50_codord = e20_pagordem ";
$sqlTodosOsRendimentos .= "     LEFT JOIN pagordemconta on e49_codord = e20_pagordem ";
$sqlTodosOsRendimentos .= "     INNER JOIN pagordemele on e50_codord = e53_codord ";
$sqlTodosOsRendimentos .= "     INNER JOIN empempenho empempenho2 on empempenho2.e60_numemp = e50_numemp ";
$sqlTodosOsRendimentos .= "     INNER JOIN orcdotacao on e60_coddot = o58_coddot ";
$sqlTodosOsRendimentos .= "          and empempenho2.e60_anousu = o58_anousu ";
$sqlTodosOsRendimentos .= "     INNER JOIN orctiporec on o15_codigo = o58_codigo ";
$sqlTodosOsRendimentos .= "     INNER JOIN cgm on empempenho2.e60_numcgm = cgm.z01_numcgm ";
$sqlTodosOsRendimentos .= "     LEFT JOIN cgm cgmordem on e49_numcgm = cgmordem.z01_numcgm ";
$sqlTodosOsRendimentos .= "     INNER JOIN pagordemnota on e71_codord = e50_codord and e71_anulado is false ";
$sqlTodosOsRendimentos .= "     INNER JOIN empnota on e71_codnota = e69_codnota ";
$sqlTodosOsRendimentos .= "     INNER JOIN retencaotiporec on e21_sequencial = e23_retencaotiporec ";
$sqlTodosOsRendimentos .= "     INNER JOIN tabrec on e21_receita = k02_codigo ";
$sqlTodosOsRendimentos .= "     LEFT JOIN retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial ";
$sqlTodosOsRendimentos .= "     LEFT JOIN slipretencaoreceitas on k206_retencaoreceitas = e23_sequencial ";
$sqlTodosOsRendimentos .= "     LEFT JOIN corgrupocorrente on k105_sequencial = e47_corgrupocorrente ";
$sqlTodosOsRendimentos .= "     LEFT JOIN corrente on k105_id = corrente.k12_id ";
$sqlTodosOsRendimentos .= "          and k105_autent = corrente.k12_autent and k105_data = corrente.k12_data ";
$sqlTodosOsRendimentos .= "          and corrente.k12_instit = $instituicaoSessao ";
$sqlTodosOsRendimentos .= "     LEFT JOIN conplanoreduz on corrente.k12_conta = c61_reduz ";
$sqlTodosOsRendimentos .= "          and c61_anousu = $iAnoUsoSessao ";
$sqlTodosOsRendimentos .= "     LEFT JOIN conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu ";
$sqlTodosOsRendimentos .= "     WHERE $whereRetencoesLeft ";
$sqlTodosOsRendimentos .= ") retencoes ON retencoes.e60_numcgm = rendimentos_cgm_pagos.e60_numcgm ";
$sqlTodosOsRendimentos .= "     and retencoes.e60_numemp = rendimentos_cgm_pagos.e60_numemp ";
$sqlTodosOsRendimentos .= "     and retencoes.ord = rendimentos_cgm_pagos.e50_codord ";
$sqlTodosOsRendimentos .= "$groupByRendimentosPagos ";
$sqlTodosOsRendimentos .= "$campoOrdenarRendimentosPagos ";

$sql = $sqlApenasRetencoes;
if ($apuracao == APURACAO_TOTAL_RENDIMENTOS) {
    $sql = $sqlTodosOsRendimentos;
}

$result = db_query($sql);
if (pg_num_rows($result) == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum resultado encontrado.');
    exit;
}

$head1 = "\nCOMPROVANTE DE RENDIMENTOS PAGOS OU CREDITADOS ";
$head1 .= "E DE RETENÇÃO DE IMPOSTO DE RENDA NA FONTE - PESSOA JURÍDICA";
$head2 = "Sintético";
$head3 = "Período: ".db_formatar($data_inicio, 'd').' a '.db_formatar($data_final, 'd');
$head4 = $apuracao == "2" ? "Apenas Rendimentos com Retenção" : "Todos os Rendimentos";
$head4 = "Apuração dos Pagamentos: ".$head4;

$sSqlCgm  = "select z01_numcgm, z01_nome, z01_cgccpf ";
$sSqlCgm .= "from cgm ";
$sSqlCgm .= "where z01_numcgm = $cgm ";

$rsCgm = db_query($sSqlCgm) or die($sSqlCgm);
$oCgm  = db_utils::fieldsMemory($rsCgm, 0);

$sqlDadosInt = "select nomeinst, db21_compl, cgc ";
$sqlDadosInt .= "from db_config where codigo = ".db_getsession("DB_instit");

$rsDadosInst = db_query($sqlDadosInt) or die($sqlDadosInt);
$dadosInst  = db_utils::fieldsMemory($rsDadosInst, 0);

$pdf = new Pdf();
$pdf->init(false);
$pdf->exibeHeader(true, \Fpdf\Pdf::HEADER_DEFAULT);
$pdf->setExibeBrasao(true);

$pdf->addTitulo($head1, 1);
$pdf->addTitulo($head2, 2);
$pdf->addTitulo($head3, 3);
$pdf->addTitulo($head4, 4);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(240);
$pdf->SetFont('Arial', '', 8);

$largura = ( $pdf->w ) / 2;
$larguraUtil = ($pdf->w - $pdf->rMargin - $pdf->lMargin);
$larguraUtilMetade = $larguraUtil / 2 ;
$x = $pdf->getX();

$cpfCnpj = strlen($oCgm->z01_cgccpf) == 11
            ? db_formatar($oCgm->z01_cgccpf, 'CPF')
            : db_formatar($oCgm->z01_cgccpf, 'cnpj');

$pdf->Cell(30, 6, "1. FONTE PAGADORA", 0, 1, "L");
$pdf->SetFont('Arial', '', 7);
$y = $pdf->getY();
$pdf->SetXY($x, $y);

$pdf->MultiCell($larguraUtilMetade, 4, 'NOME EMPRESARIAL'."\n\n".substr($oCgm->z01_nome, 0, 62), 1, "L", 0);

$pdf->SetXY(105.1, $y);
$pdf->MultiCell($larguraUtilMetade, 4, 'CNPJ'."\n\n".$cpfCnpj, 1, "L", 0);

$pdf->ln();
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(30, 6, "2. PESSOA JURÍDICA BENEFICIÁRIA DOS RENDIMENTOS", 0, 1, "L");
$pdf->SetFont('Arial', '', 7);
$y = $pdf->getY();
$pdf->SetXY($x, $y);
$pdf->MultiCell($larguraUtilMetade, 4, 'NOME EMPRESARIAL'."\n\n".$dadosInst->nomeinst, 1, "L", 0);
$pdf->SetXY(105.1, $y);
$pdf->MultiCell($larguraUtilMetade, 4, 'CNPJ'."\n\n".db_formatar($dadosInst->cgc, 'cnpj'), 1, "L", 0);

$pdf->ln();
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6, "3. RENDIMENTO E IMPOSTO RETIDO NA FONTE", 0, 1, "L");
$pdf->cell(0, 0.5, '', "B", 1, "C", 0);
$pdf->ln(3);
$pdf->SetFont('Arial', 'B', 7);
$pdf->cell(17, 5, "MÊS", 1, 0, "C", 1);
$pdf->cell(122, 5, "DESCRIÇÃO", 1, 0, "C", 1);
$pdf->cell(25, 5, "VALOR(R$) ", 1, 0, "R", 1);
$pdf->cell(26, 5, "VALOR RETIDO(R$)", 1, 0, "R", 1);
$pdf->ln();

// TEM VINTE
while ($dados = pg_fetch_object($result)) {
    $pdf->SetFont('Arial', '', 7);
    $pdf->cell(17, 5, $dados->mes, 1, 0, "C", 0);
    $pdf->cell(122, 5, substr(ucfirst(mb_strtolower($dados->descricao, 'iso-8859-1')), 0, 40), 1, 0, "L", 0);
    $pdf->cell(25, 5, db_formatar($dados->valor, 'f'), 1, 0, "R", 0);
    $pdf->cell(26, 5, db_formatar($dados->e23_valorretencao, 'f'), 1, 0, "R", 0);
    $pdf->ln();
}

$pdf->ln(8);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(30, 6, "4. INFORMAÇÕES COMPLEMENTARES", 0, 1, "L");
$posicaoX = $pdf->GetX();

$pdf->SetFont('Arial', '', 7);
if (strlen($informacoes_complementares) > 0) {
    $pdf->MultiCell($larguraUtil, 4, utf8_decode($informacoes_complementares), 1, "L", 0);
} else {
    $pdf->MultiCell($larguraUtil, 4, "\n\n\n\n", 1, "L", 0);
}

$sec =  "______________________________"."\n"."Secretário(a)";
$cont =  "______________________________"."\n"."Contador(a)";

$pdf->ln(20);
$pos = $pdf->GetY();
  
$pdf->setX($posicaoX+20);
$pdf->multicell(50, 4, ucwords(strtolower($sec)), 0, "C", 0, 0);

$pdf->setXY(100, $pos);
$pdf->multicell(50, 4, ucwords(strtolower($cont)), 0, "C", 0, 0);

$pdf->setXY(150, $pos + 15);
$pdf->multicell($largura, 4, 'Emitido em '.date('d/m/Y'), 0, "C", 0, 0);

$pdf->Output();
