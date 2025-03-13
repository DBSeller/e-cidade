<?php
/*
 *  E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));

parse_str($_SERVER['QUERY_STRING']);

if ($unidade == '') {
    $unidade = 0;
}
if ($orgao == '') {
    $orgao = 0;
}

$aWhere = array();
$aWhere[] = " o41_anousu  = {$ano}";
if (!empty($orgao)) {
    $aWhere[] = " o41_orgao = {$orgao}";
}
if (!empty($unidade)) {
    $aWhere[] = " o41_unidade = {$unidade}";
}
$sWhere = implode(" and ", $aWhere);

$sql_orgao = "
select 
    o40_orgao, o40_descr, o41_unidade, o41_descr, 
    sum(o58_valor) as total_por_unidade,
    sum(case when substr(o56_elemento,3,1) = '1' then o58_valor else 0 end ) as total_unidade_pessoal,
    sum(case when substr(o56_elemento,3,1) = '2' then o58_valor else 0 end ) as total_unidade_juros,
    sum(case when substr(o56_elemento,3,1) = '3' then o58_valor else 0 end ) as total_unidade_despesas,
    sum(case when substr(o56_elemento,3,1) = '4' then o58_valor else 0 end ) as total_unidade_investimentos,
    sum(case when substr(o56_elemento,3,1) = '5' then o58_valor else 0 end ) as total_unidade_inversoes,
    sum(case when substr(o56_elemento,3,1) = '6' then o58_valor else 0 end ) as total_unidade_amortizacao
from orcorgao 
inner join orcunidade on o40_orgao = o41_orgao and o40_anousu = o41_anousu
inner join orcdotacao on o41_orgao = o58_orgao and o41_unidade = o58_unidade and o40_anousu = o58_anousu
inner join orcelemento on o58_codele   = o56_codele and o58_anousu   = o56_anousu
where {$sWhere}
group by o40_orgao, o40_descr, o41_unidade, o41_descr, o40_orgao, o41_unidade
order by  o40_orgao,o41_unidade 
";

$res_orgao = pg_exec($sql_orgao);

$head1 = "\nPROGRAMA DE TRABALHO";
$head2 = "\nEXERCÍCIO : $ano";

$pdf = new Pdf();
$pdf->init(false);
$pdf->exibeHeader(true, \Fpdf\Pdf::HEADER_DEFAULT);
$pdf->setExibeBrasao(true);

$pdf->addTitulo($head1, 1);
$pdf->addTitulo($head2, 2);

if (isset($mostra_rodape) && $mostra_rodape == "N") {
    $pdf->imprime_rodape = false;
    $pdf->imprime_numero_pagina = false;
}

$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$troca = 1;

for ($o = 0; $o < pg_numrows($res_orgao); $o++) {
    db_fieldsmemory($res_orgao, $o);
    /////inicio funcao
    $imprimirTotal = false;
    $sql_funcao = "
    select
        o58_funcao,
        o52_descr,
        sum(o58_valor) as total_por_funcao,
        sum(case when substr(o56_elemento,3,1) = '1' then o58_valor else 0 end ) as total_funcao_pessoal,
        sum(case when substr(o56_elemento,3,1) = '2' then o58_valor else 0 end ) as total_funcao_juros,
        sum(case when substr(o56_elemento,3,1) = '3' then o58_valor else 0 end ) as total_funcao_despesas,
        sum(case when substr(o56_elemento,3,1) = '4' then o58_valor else 0 end ) as total_funcao_investimentos,
        sum(case when substr(o56_elemento,3,1) = '5' then o58_valor else 0 end ) as total_funcao_inversoes,
        sum(case when substr(o56_elemento,3,1) = '6' then o58_valor else 0 end ) as total_funcao_amortizacao
    from orcdotacao
    inner join orcfuncao on o58_funcao = o52_funcao
    inner join orcelemento on o58_codele = o56_codele
        and o58_anousu = o56_anousu    
    where o58_anousu  = $ano and o58_orgao   = $o40_orgao and o58_unidade = $o41_unidade
    group by o58_funcao, o52_descr
    having sum(o58_valor) > 0
    order by o58_funcao, o52_descr
    ";

    $result_funcao = pg_exec($sql_funcao);
    $pdf->ln(2);
    $troca = 1;

    $alt = 6;
    for ($x1 = 0; $x1 < pg_numrows($result_funcao); $x1++) {
        db_fieldsmemory($result_funcao, $x1);
        $imprimirTotal = true;
        if ($pdf->getY() > $pdf->getH() - 35 || $troca != 0) {
            $pdf->AddPage("L");
            $alt = 4;
            $pdf->setfont('arial', 'b', 9);
            $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".00 - $o40_descr", 0, 1, "L", 0);
            $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".".str_pad($o41_unidade, 2, '0', STR_PAD_LEFT)." - $o41_descr ", 0, 1, "L", 0);
            $pdf->cell(0, 1, "", "B", 1);
            $pdf->ln(2);
            $borda = "LRT";
            $alt = 3;

            $pdf->setfont('arial', 'b', 7);
            $pdf->cell(97, $alt, "E S P E C I F I C A Ç Ã O", $borda, 0, "C", 0);
            $pdf->cell(8, $alt, "ESF", $borda, 0, "C", 0);
            $pdf->cell(11, $alt, "FNT", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "TOTAL", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "PESSOAL E", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "JUROS E", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "OUTRAS", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "INVESTIMENTOS", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "INVERSÕES", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "AMORTIZAÇÃO", $borda, 1, "C", 0);

            $borda = "LR";
            $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "ENCARGOS", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "ENCARGOS DA", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "DESPESAS", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "FINANCEIRAS", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "DÍVIDA", $borda, 1, "C", 0);

            $borda = "LRB";
            $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "SOCIAIS", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "DÍVIDA", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "CORRENTES", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
            $pdf->cell(23, $alt, "", $borda, 1, "C", 0);
            $alt = 6;
            $troca = 0;
        }

        $pdf->setfont('arial', 'b', 7);
        $pdf->cell(97, $alt, str_pad($o58_funcao, 2, '0', STR_PAD_LEFT)." - ".$o52_descr, 'LR', 0, "L", 0);
        $pdf->cell(8, $alt, "", 'LR', 0, "C", 0);
        $pdf->cell(11, $alt, "", 'LR', 0, "C", 0);
        $pdf->cell(23, $alt, db_formatar($total_por_funcao, 'f'), 'LR', 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_funcao_pessoal, 'f'), 'LR', 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_funcao_juros, 'f'), 'LR', 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_funcao_despesas, 'f'), 'LR', 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_funcao_investimentos, 'f'), 'LR', 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_funcao_inversoes, 'f'), 'LR', 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_funcao_amortizacao, 'f'), 'LR', 1, "R", 0);
        $pdf->cell(97, 1, "", 'LR', 0, "C", 0);
        $pdf->cell(180, 0.5, "", 1, 1, "C", 1);

        $sql_subfuncao = "
        select
            o58_subfuncao,
            o53_descr,
            sum(o58_valor) as total_por_subfuncao,
            sum(case when substr(o56_elemento,3,1) = '1' then o58_valor else 0 end ) as total_subfuncao_pessoal,
            sum(case when substr(o56_elemento,3,1) = '2' then o58_valor else 0 end ) as total_subfuncao_juros,
            sum(case when substr(o56_elemento,3,1) = '3' then o58_valor else 0 end ) as total_subfuncao_despesas,
            sum(case when substr(o56_elemento,3,1) = '4' then o58_valor else 0 end ) as total_subfuncao_investimentos,
            sum(case when substr(o56_elemento,3,1) = '5' then o58_valor else 0 end ) as total_subfuncao_inversoes,
            sum(case when substr(o56_elemento,3,1) = '6' then o58_valor else 0 end ) as total_subfuncao_amortizacao                
        from orcdotacao
        inner join orcfuncao on o58_funcao = o52_funcao
        inner join orcsubfuncao on o58_subfuncao = o53_subfuncao
        inner join orcelemento on o58_codele = o56_codele and o58_anousu = o56_anousu
        where o58_anousu = $ano
        and o58_orgao = $o40_orgao
        and o58_unidade = $o41_unidade
        and o58_funcao = $o58_funcao
        group by o58_subfuncao, o53_descr       
        having sum(o58_valor) > 0
        order by o58_subfuncao, o53_descr       
        ";
        $result_subfuncao = pg_exec($sql_subfuncao);
        $xxnum1 = pg_numrows($result_subfuncao);
        
        for ($x2 = 0; $x2 < pg_numrows($result_subfuncao); $x2++) {
            db_fieldsmemory($result_subfuncao, $x2);
            if ($pdf->getY() > $pdf->getH() - 35 || $troca != 0) {
                $pdf->AddPage("L");
                $alt = 4;
                $pdf->setfont('arial', 'b', 9);
                $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".00 - $o40_descr", 0, 1, "L", 0);
                $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".".str_pad($o41_unidade, 2, '0', STR_PAD_LEFT)." - $o41_descr ", 0, 1, "L", 0);
                $pdf->cell(0, 1, "", "B", 1);
                $pdf->ln(2);
                $borda = "LRT";
                $alt = 3;
                $borda = "LRT";

                $pdf->setfont('arial', 'b', 7);
                $pdf->cell(97, $alt, "E S P E C I F I C A Ç Ã O", $borda, 0, "C", 0);
                $pdf->cell(8, $alt, "ESF", $borda, 0, "C", 0);
                $pdf->cell(11, $alt, "FNT", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "TOTAL", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "PESSOAL E", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "JUROS E", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "OUTRAS", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "INVESTIMENTOS", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "INVERSÕES", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "AMORTIZAÇÃO", $borda, 1, "C", 0);

                $borda = "LR";
                $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "ENCARGOS", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "ENCARGOS DA", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "DESPESAS", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "FINANCEIRAS", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "DÍVIDA", $borda, 1, "C", 0);

                $borda = "LRB";
                $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "SOCIAIS", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "DÍVIDA", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "CORRENTES", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                $pdf->cell(23, $alt, "", $borda, 1, "C", 0);
                $alt = 6;
                $troca = 0;
            }

            $pdf->setfont('arial', 'b', 7);
            $pdf->cell(97, $alt, "   ".str_pad($o58_subfuncao, 3, '0', STR_PAD_LEFT)." - ".$o53_descr, 'LR', 0, "L", 0);
            $pdf->cell(8, $alt, "", 'LR', 0, "C", 0);
            $pdf->cell(11, $alt, "", 'LR', 0, "C", 0);
            $pdf->cell(23, $alt, db_formatar($total_por_subfuncao, 'f'), 0, 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_subfuncao_pessoal, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_subfuncao_juros, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_subfuncao_despesas, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_subfuncao_investimentos, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_subfuncao_inversoes, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_subfuncao_amortizacao, 'f'), 'LR', 1, "R", 0);
            $pdf->cell(97, 1, "", 'LR', 0, "C", 0);
            $pdf->cell(180, 0.5, '', 'BLR', 1, "C", 0);

            $sql_programa = "
            select
                o58_programa,
                o54_descr,
                o54_finali,
                sum(o58_valor) as total_por_programa,
                sum(case when substr(o56_elemento,3,1) = '1' then o58_valor else 0 end ) as total_programa_pessoal,
                sum(case when substr(o56_elemento,3,1) = '2' then o58_valor else 0 end ) as total_programa_juros,
                sum(case when substr(o56_elemento,3,1) = '3' then o58_valor else 0 end ) as total_programa_despesas,
                sum(case when substr(o56_elemento,3,1) = '4' then o58_valor else 0 end ) as total_programa_investimentos,
                sum(case when substr(o56_elemento,3,1) = '5' then o58_valor else 0 end ) as total_programa_inversoes,
                sum(case when substr(o56_elemento,3,1) = '6' then o58_valor else 0 end ) as total_programa_amortizacao   
            from orcdotacao
            inner join orcfuncao    on o58_funcao     = o52_funcao
            inner join orcsubfuncao on o58_subfuncao  = o53_subfuncao
            inner join orcprograma  on o58_programa   = o54_programa
                and o58_anousu = o54_anousu
            inner join orcelemento  on o58_codele = o56_codele
                and o58_anousu = o56_anousu
            where o58_anousu    = $ano
            and o58_orgao     = $o40_orgao
            and o58_unidade   = $o41_unidade
            and o58_funcao    = $o58_funcao
            and o58_subfuncao = $o58_subfuncao
            group by o58_programa, o54_descr, o54_finali
            having sum(o58_valor) > 0
            order by o58_programa, o54_descr, o54_finali
            ";
            $result_programa = pg_exec($sql_programa);
            $xxnum2 = pg_numrows($result_programa);
          
            for ($x3 = 0; $x3 < pg_numrows($result_programa); $x3++) {
                db_fieldsmemory($result_programa, $x3);
                if ($pdf->getY() > $pdf->getH() - 35 || $troca != 0) {
                    $pdf->AddPage("L");
                    $alt = 4;
                    $pdf->setfont('arial', 'b', 9);
                    $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".00 - $o40_descr", 0, 1, "L", 0);
                    $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".".str_pad($o41_unidade, 2, '0', STR_PAD_LEFT)." - $o41_descr ", 0, 1, "L", 0);
                    $pdf->cell(0, 1, "", "B", 1);
                    $pdf->ln(2);
                    $borda = "LRT";
                    $alt = 3;
                    $borda = "LRT";
  
                    $pdf->setfont('arial', 'b', 7);
                    $pdf->cell(97, $alt, "E S P E C I F I C A Ç Ã O", $borda, 0, "C", 0);
                    $pdf->cell(8, $alt, "ESF", $borda, 0, "C", 0);
                    $pdf->cell(11, $alt, "FNT", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "TOTAL", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "PESSOAL E", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "JUROS E", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "OUTRAS", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "INVESTIMENTOS", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "INVERSÕES", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "AMORTIZAÇÃO", $borda, 1, "C", 0);
  
                    $borda = "LR";
                    $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "ENCARGOS", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "ENCARGOS DA", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "DESPESAS", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "FINANCEIRAS", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "DÍVIDA", $borda, 1, "C", 0);
  
                    $borda = "LRB";
                    $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "SOCIAIS", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "DÍVIDA", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "CORRENTES", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                    $pdf->cell(23, $alt, "", $borda, 1, "C", 0);
                    $alt = 6;
                    $troca = 0;
                }
  
                $pdf->setfont('arial', 'b', 7);
                $pdf->cell(97, $alt, '      '.str_pad($o58_programa, 3, '0', STR_PAD_LEFT)." - ".$o54_descr, 'LR', 0, "L", 0);
                $pdf->cell(8, $alt, "", 'LR', 0, "C", 0);
                $pdf->cell(11, $alt, "", 'LR', 0, "C", 0);
                $pdf->setfont('arial', '', 7);
                $pdf->cell(23, $alt, db_formatar($total_por_programa, 'f'), 'LR', 0, "R", 0);
                $pdf->cell(23, $alt, db_formatar($total_programa_pessoal, 'f'), 'LR', 0, "R", 0);
                $pdf->cell(23, $alt, db_formatar($total_programa_juros, 'f'), 'LR', 0, "R", 0);
                $pdf->cell(23, $alt, db_formatar($total_programa_despesas, 'f'), 'LR', 0, "R", 0);
                $pdf->cell(23, $alt, db_formatar($total_programa_investimentos, 'f'), 'L', 0, "R", 0);
                $pdf->cell(23, $alt, db_formatar($total_programa_inversoes, 'f'), 'LR', 0, "R", 0);
                $pdf->cell(23, $alt, db_formatar($total_programa_amortizacao, 'f'), 'LR', 1, "R", 0);
  
                $sql_projativ = "
                select
                    o58_projativ,
                    coalesce((select descricao 
                            from plugins.orcprojativdescricao 
                            where anousu = o55_anousu 
                            and orcprojativ = o55_projativ),o55_descr) as o55_descr,
                    o55_finali,
                    sum(o58_valor) as total_por_projativ,
                    sum(case when substr(o56_elemento,3,1) = '1' then o58_valor else 0 end ) as total_projativ_pessoal,
                    sum(case when substr(o56_elemento,3,1) = '2' then o58_valor else 0 end ) as total_projativ_juros,
                    sum(case when substr(o56_elemento,3,1) = '3' then o58_valor else 0 end ) as total_projativ_despesas,
                    sum(case when substr(o56_elemento,3,1) = '4' then o58_valor else 0 end ) as total_projativ_investimentos,
                    sum(case when substr(o56_elemento,3,1) = '5' then o58_valor else 0 end ) as total_projativ_inversoes,
                    sum(case when substr(o56_elemento,3,1) = '6' then o58_valor else 0 end ) as total_projativ_amortizacao        
                from orcdotacao
                inner join orcfuncao on o58_funcao = o52_funcao
                inner join orcsubfuncao on o58_subfuncao = o53_subfuncao
                inner join orcprograma  on o58_programa = o54_programa
                    and o58_anousu = o54_anousu
                inner join orcprojativ  on o58_projativ   = o55_projativ
                    and o58_anousu = o55_anousu
                inner join orcelemento on o58_codele = o56_codele
                    and o58_anousu = o56_anousu
                where o58_anousu = $ano
                and o58_orgao = $o40_orgao
                and o58_unidade = $o41_unidade
                and o58_funcao = $o58_funcao
                and o58_subfuncao = $o58_subfuncao
                and o58_programa = $o58_programa
                group by o58_projativ, o55_projativ, o55_anousu, o55_descr, o55_finali
                having sum(o58_valor) > 0
                order by o58_projativ, o55_descr, o55_finali
                ";
                $result_projativ = pg_exec($sql_projativ);
                $xxnum3 = pg_numrows($result_projativ);
            
                for ($x4 = 0; $x4 < pg_numrows($result_projativ); $x4++) {
                    db_fieldsmemory($result_projativ, $x4);
                    if ($pdf->getY() > $pdf->getH() - 35 || $troca != 0) {
                        $pdf->AddPage("L");
                        $alt = 4;
                        $pdf->setfont('arial', 'b', 9);
                        $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".00 - $o40_descr", 0, 1, "L", 0);
                        $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".".str_pad($o41_unidade, 2, '0', STR_PAD_LEFT)." - $o41_descr ", 0, 1, "L", 0);
                        $pdf->cell(0, 1, "", "B", 1);
                        $pdf->ln(2);
                        $alt = 3;
                        $borda = "LRT";
            
                        $pdf->setfont('arial', 'b', 7);
                        $pdf->cell(97, $alt, "E S P E C I F I C A Ç Ã O", $borda, 0, "C", 0);
                        $pdf->cell(8, $alt, "ESF", $borda, 0, "C", 0);
                        $pdf->cell(11, $alt, "FNT", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "TOTAL", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "PESSOAL E", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "JUROS E", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "OUTRAS", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "INVESTIMENTOS", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "INVERSÕES", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "AMORTIZAÇÃO", $borda, 1, "C", 0);
                
                        $borda = "LR";
                        $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "ENCARGOS", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "ENCARGOS DA", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "DESPESAS", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "FINANCEIRAS", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "DÍVIDA", $borda, 1, "C", 0);
                
                        $borda = "LRB";
                        $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "SOCIAIS", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "DÍVIDA", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "CORRENTES", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                        $pdf->cell(23, $alt, "", $borda, 1, "C", 0);
                        $alt = 6;
                        $troca = 0;
                    }
            
                    $pdf->setfont('arial', 'b', 7);

                    $iPosX = $pdf->getX()+97;
                    $iPosY = $pdf->getY();
                    $sDescricaoProjAtiv = '         '.str_pad($o58_funcao, 2, '0', STR_PAD_LEFT).".".str_pad($o58_subfuncao, 2, '0', STR_PAD_LEFT).".".str_pad($o58_programa, 3, '0', STR_PAD_LEFT).".".str_pad($o58_projativ, 3, '0', STR_PAD_LEFT)." - ".$o55_descr;
                    $pdf->multicell(97, 4, $sDescricaoProjAtiv, 'LR', "L", 0);
                    $pdf->cell(1, 4, "", 'L', 0, "C", 1);
                    $pdf->setY($iPosY);
                    $pdf->setX($iPosX);

                    $pdf->cell(8, $alt, "", 'LR', 0, "C", 0);
                    $pdf->cell(11, $alt, "", 'LR', 0, "C", 0);
                    $pdf->setfont('arial', '', 7);
                    $pdf->cell(23, $alt, db_formatar($total_por_projativ, 'f'), 'LR', 0, "R", 0);
                    $pdf->cell(23, $alt, db_formatar($total_projativ_pessoal, 'f'), 'LR', 0, "R", 0);
                    $pdf->cell(23, $alt, db_formatar($total_projativ_juros, 'f'), 'LR', 0, "R", 0);
                    $pdf->cell(23, $alt, db_formatar($total_projativ_despesas, 'f'), 'LR', 0, "R", 0);
                    $pdf->cell(23, $alt, db_formatar($total_projativ_investimentos, 'f'), 'LR', 0, "R", 0);
                    $pdf->cell(23, $alt, db_formatar($total_projativ_inversoes, 'f'), 'LR', 0, "R", 0);
                    $pdf->cell(23, $alt, db_formatar($total_projativ_amortizacao, 'f'), 'LR', 1, "R", 0);

                    $pdf->setfont('arial', '', 5);
                    $pdf->multicell(277, 5, $o55_finali, 'LR', "L", 0);
            
                    $sql_recurso = "
                    select
                        o15_recurso,
                        case when o58_esferaorcamentaria = 1 then 'F' else 'S' end as o58_esferaorcamentaria,
                        sum(o58_valor) as total_por_recurso,
                        sum(case when substr(o56_elemento,3,1) = '1' then o58_valor else 0 end ) as total_recurso_pessoal,
                        sum(case when substr(o56_elemento,3,1) = '2' then o58_valor else 0 end ) as total_recurso_juros,
                        sum(case when substr(o56_elemento,3,1) = '3' then o58_valor else 0 end ) as total_recurso_despesas,
                        sum(case when substr(o56_elemento,3,1) = '4' then o58_valor else 0 end ) as total_recurso_investimentos,
                        sum(case when substr(o56_elemento,3,1) = '5' then o58_valor else 0 end ) as total_recurso_inversoes,
                        sum(case when substr(o56_elemento,3,1) = '6' then o58_valor else 0 end ) as total_recurso_amortizacao     
                    from orcdotacao
                        inner join orcfuncao on o58_funcao = o52_funcao
                        inner join orcsubfuncao on o58_subfuncao = o53_subfuncao
                        inner join orcprograma on o58_programa = o54_programa
                            and o58_anousu = o54_anousu
                        inner join orcprojativ on o58_projativ = o55_projativ
                            and o58_anousu = o55_anousu
                        inner join orcelemento on o58_codele = o56_codele
                            and o58_anousu = o56_anousu
                        inner join orctiporec on o15_codigo = o58_codigo 
                    where o58_anousu = $ano
                        and o58_orgao = $o40_orgao
                        and o58_unidade = $o41_unidade
                        and o58_funcao = $o58_funcao
                        and o58_subfuncao = $o58_subfuncao
                        and o58_programa = $o58_programa
                        and o58_projativ = $o58_projativ
                    group by o15_recurso, o58_esferaorcamentaria
                    having sum(o58_valor) > 0
                    order by o58_esferaorcamentaria, o15_recurso 
                    ";
                    $result_recurso = pg_exec($sql_recurso);
                    $xxnum4 = pg_numrows($result_recurso);
               
                    for ($x5 = 0; $x5 < pg_numrows($result_recurso); $x5++) {
                        db_fieldsmemory($result_recurso, $x5);
                        if ($pdf->getY() > $pdf->getH() - 35 || $troca != 0) {
                              $pdf->AddPage("L");
                              $alt = 4;
                              $pdf->setfont('arial', 'b', 9);
                              $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".00 - $o40_descr", 0, 1, "L", 0);
                              $pdf->cell(50, $alt, str_pad($o40_orgao, 2, '0', STR_PAD_LEFT).".".str_pad($o41_unidade, 2, '0', STR_PAD_LEFT)." - $o41_descr ", 0, 1, "L", 0);
                              $pdf->cell(0, 1, "", "B", 1);
                              $pdf->ln(2);
                              $alt = 3;
                              $borda = "LRT";
                        
                              $pdf->setfont('arial', 'b', 7);
                              $pdf->cell(97, $alt, "E S P E C I F I C A Ç Ã O", $borda, 0, "C", 0);
                              $pdf->cell(8, $alt, "ESF", $borda, 0, "C", 0);
                              $pdf->cell(11, $alt, "FNT", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "TOTAL", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "PESSOAL E", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "JUROS E", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "OUTRAS", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "INVESTIMENTOS", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "INVERSÕES", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "AMORTIZAÇÃO", $borda, 1, "C", 0);
                        
                              $borda = "LR";
                              $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "ENCARGOS", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "ENCARGOS DA", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "DESPESAS", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "FINANCEIRAS", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "DÍVIDA", $borda, 1, "C", 0);
                        
                              $borda = "LRB";
                              $pdf->cell(97, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(8, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(11, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "SOCIAIS", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "DÍVIDA", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "CORRENTES", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "", $borda, 0, "C", 0);
                              $pdf->cell(23, $alt, "", $borda, 1, "C", 0);
                              $alt = 6;
                              $troca = 0;
                        }
               
                         $pdf->setfont('arial', 'b', 7);
                         $pdf->cell(97, $alt, '', 'LR', 0, "L", 0);
                         $pdf->cell(8, $alt, $o58_esferaorcamentaria, 'LR', 0, "C", 0);
                         //$pdf->setfont('arial', 'b', 6);
                         $pdf->cell(11, $alt, str_pad($o15_recurso, 3, '0', STR_PAD_LEFT), 'LR', 0, "C", 0);
                         $pdf->setfont('arial', '', 7);
                         $pdf->cell(23, $alt, db_formatar($total_por_recurso, 'f'), 'LR', 0, "R", 1);
                         $pdf->cell(23, $alt, db_formatar($total_recurso_pessoal, 'f'), 'LR', 0, "R", 1);
                         $pdf->cell(23, $alt, db_formatar($total_recurso_juros, 'f'), 'LR', 0, "R", 1);
                         $pdf->cell(23, $alt, db_formatar($total_recurso_despesas, 'f'), 'LR', 0, "R", 1);
                         $pdf->cell(23, $alt, db_formatar($total_recurso_investimentos, 'f'), 'LR', 0, "R", 1);
                         $pdf->cell(23, $alt, db_formatar($total_recurso_inversoes, 'f'), 'LR', 0, "R", 1);
                         $pdf->cell(23, $alt, db_formatar($total_recurso_amortizacao, 'f'), 'LR', 1, "R", 1);
                    }
                }
            }
        }
    }

    if ($imprimirTotal) {
        $alt = 4;
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(97, $alt, 'T O T A L    G E R A L', 1, 0, "L", 0);
        $pdf->cell(8, $alt, '', 1, 0, "C", 0);
        $pdf->cell(11, $alt, '', 1, 0, "C", 0);
        $pdf->cell(23, $alt, db_formatar($total_por_unidade, 'f'), 1, 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_unidade_pessoal, 'f'), 1, 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_unidade_juros, 'f'), 1, 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_unidade_despesas, 'f'), 1, 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_unidade_investimentos, 'f'), 1, 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_unidade_inversoes, 'f'), 1, 0, "R", 0);
        $pdf->cell(23, $alt, db_formatar($total_unidade_amortizacao, 'f'), 1, 1, "R", 0);
        

        
        $sql_recurso1 = "
        select
            case when o58_esferaorcamentaria = 1 then 'FISCAL' else 'SEGURIDADE' end as o58_esferaorcamentaria1,
            sum(o58_valor) as total,
            sum(case when substr(o56_elemento,3,1) = '1' then o58_valor else 0 end ) as total_recurso_pessoal1,
            sum(case when substr(o56_elemento,3,1) = '2' then o58_valor else 0 end ) as total_recurso_juros1,
            sum(case when substr(o56_elemento,3,1) = '3' then o58_valor else 0 end ) as total_recurso_despesas1,
            sum(case when substr(o56_elemento,3,1) = '4' then o58_valor else 0 end ) as total_recurso_investimentos1,
            sum(case when substr(o56_elemento,3,1) = '5' then o58_valor else 0 end ) as total_recurso_inversoes1,
            sum(case when substr(o56_elemento,3,1) = '6' then o58_valor else 0 end ) as total_recurso_amortizacao1     
        from orcdotacao
        inner join orcfuncao on o58_funcao = o52_funcao
        inner join orcsubfuncao on o58_subfuncao = o53_subfuncao
        inner join orcprograma on o58_programa = o54_programa
            and o58_anousu = o54_anousu
        inner join orcprojativ on o58_projativ = o55_projativ
            and o58_anousu = o55_anousu
        inner join orcelemento on o58_codele = o56_codele
            and o58_anousu = o56_anousu
        where o58_anousu = $ano and o58_orgao     = $o40_orgao and o58_unidade = $o41_unidade
        group by o58_esferaorcamentaria
        having sum(o58_valor) > 0
        order by o58_esferaorcamentaria      
        ";
        
        $result_recurso1 = pg_exec($sql_recurso1);
        $xxnum5 = pg_numrows($result_recurso1);
            
        for ($x6 = 0; $x6 < pg_numrows($result_recurso1); $x6++) {
            db_fieldsmemory($result_recurso1, $x6);
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(97, $alt, $o58_esferaorcamentaria1, 'LR', 0, "L", 0);
            $pdf->cell(8, $alt, '', 'LR', 0, "C", 0);
            $pdf->cell(11, $alt, '', 'LR', 0, "C", 0);
            $pdf->cell(23, $alt, db_formatar($total, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_recurso_pessoal1, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_recurso_juros1, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_recurso_despesas1, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_recurso_investimentos1, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_recurso_inversoes1, 'f'), 'LR', 0, "R", 0);
            $pdf->cell(23, $alt, db_formatar($total_recurso_amortizacao1, 'f'), 'LR', 1, "R", 0);
        }
        $pdf->cell(278, 0.1, '', 1, 1, "R", 0);
    }
}
$pdf->Output();
