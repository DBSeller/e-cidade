<?php
/*
 * E-cidade Software Publico para Gestao Municipal
 * Copyright (C) 2009 DBselller Servicos de Informatica
 * www.dbseller.com.br
 * e-cidade@dbseller.com.br
 *
 * Este programa e software livre; voce pode redistribui-lo e/ou
 * modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 * publicada pela Free Software Foundation; tanto a versao 2 da
 * Licenca como (a seu criterio) qualquer versao mais nova.
 *
 * Este programa e distribuido na expectativa de ser util, mas SEM
 * QUALQUER GARANTIA; sem mesmo a garantia implicita de
 * COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 * PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 * detalhes.
 *
 * Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 * junto com este programa; se nao, escreva para a Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 * 02111-1307, USA.
 *
 * Copia da licenca no diretorio licenca/licenca_en.txt
 * licenca/licenca_pt.txt
 */
use ECidade\Pdf\Pdf;

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sql.php"));

$clrotulo = new rotulocampo();
$clrotulo->label('r14_rubric');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_regist');
$clrotulo->label('r14_quant');
$clrotulo->label('r14_valor');

parse_str($_SERVER['QUERY_STRING']);

$sSqlRubrica = "select rh27_rubric,
                       rh27_descr
                  from rhrubricas
                 where rh27_rubric = '{$rubrica}'
                   and rh27_instit = ".db_getsession("DB_instit");
$rsRubrica = db_query($sSqlRubrica);
db_fieldsmemory($rsRubrica, 0);
if (pg_numrows($rsRubrica) == 0) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Rubrica não cadastrada no período de ' . $mes . ' / ' . $ano);
}

$head2 = "FINANCEIRA POR RUBRICA";
$head3 = "RUBRICA : ".$rh27_rubric." - ".$rh27_descr;
$head4 = "PERÍODO : ".$mes." / ".$ano;
$head5 = "";
$head6 = "";

if ($ponto == 'f') {
    $sArquivo = 'pontofx';
    $sSigla = 'r90_';
    $sCampo = 'r90_datlim as datlim,';
    $head5 = 'PONTO : FIXO';
} elseif ($ponto == 's') {
    $sArquivo = 'pontofs';
    $sSigla = 'r10_';
    $sCampo = 'r10_datlim as datlim,';
    $head5 = 'PONTO : SALÁRIO';
} elseif ($ponto == 'c') {
    $sArquivo = 'pontocom';
    $sSigla = 'r47_';
    $sCampo = '';
    $head5 = 'PONTO : COMPLEMENTAR';
} elseif ($ponto == 'a') {
    $sArquivo = 'pontofa';
    $sSigla = 'r21_';
    $sCampo = '';
    $head5 = 'PONTO : ADIANTAMENTO';
} elseif ($ponto == 'r') {
    $sArquivo = 'pontofr';
    $sSigla = 'r19_';
    $sCampo = '';
    $head5 = 'PONTO : RESCISÃO';
}

if ($recurso == 's') {
    $head6 = 'ALFABÉTICA POR RECURSO';
    $sOrderBy = 'order by rh25_recurso, z01_nome ';
    if ($localtrab == "S") {
        $sOrderBy = 'order by rh55_estrut, rh25_recurso, z01_nome ';
    }
} else {
    if ($ordem == 'a') {
        $head6 = 'ORDEM : ALFABÉTICA ' . strtoupper($tipoordem);
        $sOrderBy = 'order by z01_nome ' . $tipoordem;
        if ($localtrab == "S") {
            $sOrderBy = 'order by rh55_estrut, z01_nome ' . $tipoordem;
        }
    } elseif ($ordem == 'n') {
        $head6 = 'ORDEM : NUMÉRICA ' . strtoupper($tipoordem);
        $sOrderBy = 'order by regist ' . $tipoordem;
        if ($localtrab == "S") {
            $sOrderBy = 'order by rh55_estrut, regist ' . $tipoordem;
        }
    } elseif ($ordem == 'd') {
        $head6 = 'ORDEM : DIGITAÇÃO ';
        $sOrderBy = 'order by ' . $sArquivo . '.oid ';
        if ($localtrab == "S") {
            $sOrderBy = 'order by rh55_estrut, ' . $sArquivo . '.oid ';
        }
    } elseif ($ordem == 'l') {
        $head6 = 'ORDEM : LOTAÇÃO ' . strtoupper($tipoordem);
        $sOrderBy = 'order by lotacao ' . $tipoordem;
        if ($localtrab == "S") {
            $sOrderBy = 'order by rh55_estrut, lotacao ' . $tipoordem;
        }
    } elseif ($ordem == 'v') {
        $head6 = 'ORDEM : VALOR ' . strtoupper($tipoordem);
        $sOrderBy = 'order by valor ' . $tipoordem;
        if ($localtrab == "S") {
            $sOrderBy = 'order by rh55_estrut, valor ' . $tipoordem;
        }
    } elseif ($ordem == 'q') {
        $head6 = 'ORDEM : QUANTIDADE ' . strtoupper($tipoordem);
        $sOrderBy = 'order by quant ' . $tipoordem;
        if ($localtrab == "S") {
            $sOrderBy = 'order by rh55_estrut, quant ' . $tipoordem;
        }
    }
}

$sLeftJoinRhLocalTrab = "";
if ($localtrab == "S") {
    $sCampo .= "rhlocaltrab.rh55_estrut,";
    $sCampo .= "rhlocaltrab.rh55_descr,";

    $sLeftJoinRhLocalTrab  = "left join rhpeslocaltrab  on rhpeslocaltrab.rh56_seqpes = rhpessoalmov.rh02_seqpes ";
    $sLeftJoinRhLocalTrab .= "left join rhlocaltrab     on rhlocaltrab.rh55_codigo = rhpeslocaltrab.rh56_localtrab ";
    $sLeftJoinRhLocalTrab .= "                         and rhlocaltrab.rh55_instit = rhpessoalmov.rh02_instit ";

}

$sWhereLotacoes = "";
if (DBPessoal::utilizaFiltroLotacoesPorUsuario()) {
    $oLotacoesUsuario = DBPessoal::buscaLotacoesPorUsuario();
    $sWhereLotacoes = " and rhpessoalmov.rh02_lota in (".implode(",",$oLotacoesUsuario->aLotacoes).")";
}

$sSqlDados  = "select {$sSigla}rubric as rubric, ";
$sSqlDados .= "       {$sSigla}regist as regist, ";
$sSqlDados .= "       {$sCampo} ";
$sSqlDados .= "       z01_nome, ";
$sSqlDados .= "       round({$sSigla}quant,2) as quant, ";
$sSqlDados .= "       to_number({$sSigla}lotac,'99999') as lotacao, ";
$sSqlDados .= "       rh25_recurso, ";
$sSqlDados .= "       o15_descr, ";
$sSqlDados .= "       r70_codigo, ";
$sSqlDados .= "       round({$sSigla}valor,2) as valor ";
$sSqlDados .= "  from {$sArquivo} ";
$sSqlDados .= "       inner join rhpessoalmov  on rh02_regist = {$sSigla}regist ";
$sSqlDados .= "                               and rh02_anousu = {$sSigla}anousu ";
$sSqlDados .= "                               and rh02_mesusu = {$sSigla}mesusu ";
$sSqlDados .= "                               and rh02_instit = {$sSigla}instit ";
$sSqlDados .= "       inner join rhpessoal on rh01_regist = {$sSigla}regist ";
$sSqlDados .= "       inner join cgm       on z01_numcgm = rh01_numcgm ";
$sSqlDados .= "       inner join rhlota    on r70_codigo = rh02_lota ";
$sSqlDados .= "                           and r70_instit = {$sSigla}instit ";
$sSqlDados .= "        left join (select distinct rh25_codigo, ";
$sSqlDados .= "                          rh25_recurso ";
$sSqlDados .= "                     from rhlotavinc ";
$sSqlDados .= "                    where rh25_anousu = $ano) as rhlotavinc on rh25_codigo = r70_codigo ";
$sSqlDados .= "        left join orctiporec on o15_codigo = rh25_recurso ";
$sSqlDados .= $sLeftJoinRhLocalTrab;
$sSqlDados .= " where {$sSigla}rubric = '{$rh27_rubric}' ";
$sSqlDados .= "   and {$sSigla}anousu = {$ano} ";
$sSqlDados .= "   and {$sSigla}mesusu = {$mes} ";
$sSqlDados .= "   and {$sSigla}instit = ".db_getsession("DB_instit");
$sSqlDados .= $sWhereLotacoes;
$sSqlDados .= "  {$sOrderBy} ";

$rsDados = db_query($sSqlDados);
$iQtdRegistros = pg_num_rows($rsDados);
if ($iQtdRegistros == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de ' . $mes . ' / ' . $ano);
}

$pdf = new Pdf();
$pdf->setExibeBrasao(true);
$pdf->addTitulo($head2,2);
$pdf->addTitulo($head3,3);
$pdf->addTitulo($head4,4);
$pdf->addTitulo($head5,5);
$pdf->addTitulo($head6,6);
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->init(false);
$pdf->setfont('arial', 'b', 8);

$troca = 1;
$alt = 4;
$xvalor = 0;
$xquant = 0;
$total = 0;
$func_c = 0;
$tot_c = 0;
$quebra = 0;
$totq_c = 0;
$xxlocaltrab = null;

for ($x = 0; $x < $iQtdRegistros; $x ++) {

    db_fieldsmemory($rsDados, $x);

    if ($pdf->getY() > $pdf->getH() - 30 || $troca != 0) {
        $pdf->addpage();
        if ($xtotal == 'a') {
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(14, $alt, "Matricula", 1, 0, "C", 1);
            $pdf->cell(60, $alt, "Nome", 1, 0, "C", 1);
            $pdf->cell(15, $alt, 'LOTAÇÃO', 1, 0, "C", 1);
            $pdf->cell(53, $alt, 'RECURSO', 1, 0, "C", 1);
            if ($ponto == 'f' || $ponto == 's') {
                $pdf->cell(12, $alt, 'DATLIM', 1, 0, "C", 1);
            }
            $pdf->cell(12, $alt, 'QUANT', 1, 0, "C", 1);
            $pdf->cell(20, $alt, 'VALOR', 1, 1, "C", 1);
        }
        $troca = 0;
        $pre = 1;
    }

    if ($xtotal == 'a') {

        if ($pre == 1) {
            $pre = 0;
        } else {
            $pre = 1;
        }

        if ($quebra != $rh25_recurso && $recurso == 's') {

            if ($quebra != '') {
                $pdf->ln(1);
                $pdf->cell(156, $alt, 'Total do Recurso  :  ' . $func_c, "T", 0, "L", 0);
                $pdf->cell(12, $alt, db_formatar($totq_c, 'f'), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($tot_c, 'f'), "T", 1, "R", 0);
                $func_c = 0;
                $tot_c = 0;
                $totq_c = 0;
            }
            $pdf->setfont('arial', 'b', 9);
            $pdf->ln(4);
            $pdf->cell(50, $alt, $o15_descr, 0, 1, "L", 1);
            $quebra = $rh25_recurso;
        }

        /*
         * Mostramos os dados do local de trabalho do servidor
         */
        if ($localtrab == "S" && $xxlocaltrab != $rh55_estrut) {
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(43, $alt, 'LOCAL DE TRABALHO : ', 0, 0, "L", 0);
            $sLocalTrabDescricao = $rh55_estrut." - ".$rh55_descr;
            if (empty($rh55_estrut)) {
                $sLocalTrabDescricao = "Não Informado";
            }
            $pdf->cell(140, $alt, $sLocalTrabDescricao, 0, 1, "L", 0);
            $xxlocaltrab = $rh55_estrut;
        }

        $pdf->setfont('arial', '', 7);
        $pdf->cell(14, $alt, $regist, 0, 0, "C", $pre);
        $pdf->cell(60, $alt, $z01_nome, 0, 0, "L", $pre);
        $pdf->cell(15, $alt, $lotacao, 0, 0, "C", $pre);
        $pdf->cell(53, $alt, $o15_descr, 0, 0, "L", $pre);

        if ($ponto == 'f' || $ponto == 's') {
           $pdf->cell(12, $alt, $datlim, 0, 0, "R", $pre);
        }

        $pdf->cell(12, $alt, db_formatar($quant, 'f'), 0, 0, "R", $pre);
        $pdf->cell(20, $alt, db_formatar($valor, 'f'), 0, 1, "R", $pre);
    }

    $tot_c += $valor;
    $totq_c += $quant;
    $xvalor += $valor;
    $xquant += $quant;
    $total += 1;
    $func_c += 1;

}

if ($recurso == 's') {
    $pdf->ln(1);
    $pdf->cell(156, $alt, 'Total do Recurso  :  ' . $func_c, "T", 0, "L", 0);
    $pdf->cell(12, $alt, db_formatar($totq_c, 'f'), "T", 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($tot_c, 'f'), "T", 1, "R", 0);
}

$pdf->setfont('arial', 'b', 8);
$pdf->cell(156, $alt, 'TOTAL  :  ' . $total . '  FUNCIONÁRIOS', "T", 0, "L", 0);
$pdf->cell(12, $alt, db_formatar($xquant, 'f'), "T", 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($xvalor, 'f'), "T", 1, "R", 0);

$pdf->Output();
