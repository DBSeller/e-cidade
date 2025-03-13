<?php

require_once modification('fpdf151/pdf.php');
require_once modification('libs/db_stdlib.php');

$clrotulo = new rotulocampo();
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_ender');
$clrotulo->label('z01_compl');
$clrotulo->label('z01_bairro');
$clrotulo->label('z01_munic');
$clrotulo->label('v01_exerc');
$clrotulo->label('dv09_procdiver');
$clrotulo->label('dv09_descr');

//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_SERVER_VARS);

$instit = db_getsession('DB_instit');

$head2 = 'RELATÓRIO DE EXERCÍCIO/PROCEDÊNCIA';
$head3 = 'COBRANÇA ADMINISTRATIVA';
$head6 = '';

$agrupa1 = '';
$agrupa2 = '';

if (isset($exerc)) {
    $exercicios = ' and dv05_exerc in ('.str_replace('-', ',', $exerc).') ';
    $selexercicios = ' and dv05_exerc > '.str_replace('-', ',', $exerc).' ';
    $anos = str_replace('-', ', ', $exerc);
    $head4 = 'Exercicíos Selecionados: '.$anos;
} else {
    $exercicios = '';
}

// Liga Debug
$DB_DEBUG = false;

//$xdata = ;
$sqlTermoini = '';

$head5 = 'Cáculo na data :'.db_formatar($xdata, 'd');

$sqlParcelamentosCorrigidos = " create temp table w_parcelamentos_corrigidos as                        \n";
$sqlParcelamentosCorrigidos .= " select v07_parcel, v07_numpre,                                        \n";
if ('c' == $tiporel) {
    $sqlParcelamentosCorrigidos .= " k22_numcgm as k00_numcgm,                                         \n";
}
$sqlParcelamentosCorrigidos .= "         k00_descr,                                                    \n";
$sqlParcelamentosCorrigidos .= "         round(sum(k22_vlrhis ),2) as k22_vlrhis,                      \n";
$sqlParcelamentosCorrigidos .= "         round(sum(k22_vlrcor ),2) as k22_vlrcor,                      \n";
$sqlParcelamentosCorrigidos .= "         round(sum(k22_juros  ),2) as k22_juros,                       \n";
$sqlParcelamentosCorrigidos .= "         round(sum(k22_multa  ),2) as k22_multa,                       \n";
$sqlParcelamentosCorrigidos .= "         round(sum(valor_total),2) as valor_total                      \n";
$sqlParcelamentosCorrigidos .= "    from (                                                             \n";
$sqlParcelamentosCorrigidos .= "           select v07_parcel, v07_numpre,                              \n";

if ('c' == $tiporel) {
    $sqlParcelamentosCorrigidos .= "           k22_numcgm,                                             \n";
}

$sqlParcelamentosCorrigidos .= "                  k00_descr as k00_descr,                              \n";
$sqlParcelamentosCorrigidos .= "                  k22_vlrhis as k22_vlrhis,                            \n";
$sqlParcelamentosCorrigidos .= "                  k22_vlrcor as k22_vlrcor,                            \n";
$sqlParcelamentosCorrigidos .= "                  k22_juros as k22_juros,                              \n";
$sqlParcelamentosCorrigidos .= "                  k22_multa as k22_multa,                              \n";
$sqlParcelamentosCorrigidos .= "                  ( k22_vlrcor+k22_juros+k22_multa ) as valor_total    \n";
$sqlParcelamentosCorrigidos .= "              from termo                                               \n";
$sqlParcelamentosCorrigidos .= "                   inner join debitos   on k22_data   = '$xdata'       \n";
$sqlParcelamentosCorrigidos .= "                                       and k22_numpre = v07_numpre     \n";
$sqlParcelamentosCorrigidos .= "                                       and k22_instit = $instit        \n";
$sqlParcelamentosCorrigidos .= "                   inner join arretipo  on k00_tipo   = k22_tipo       \n";
$sqlParcelamentosCorrigidos .= '             where v07_instit = '.db_getsession('DB_instit')."         \n";
$sqlParcelamentosCorrigidos .= "               and k22_data   = '$xdata'                               \n";
//$sqlParcelamentosCorrigidos .= "               and k03_tipo not in (15, 18) ";
$sqlParcelamentosCorrigidos .= "        ) as parcelamentos                                             \n";
$sqlParcelamentosCorrigidos .= "  group by v07_parcel, v07_numpre,                                     \n";
if ('c' == $tiporel) {
    $sqlParcelamentosCorrigidos .= "  k22_numcgm,                                                      \n";
}
$sqlParcelamentosCorrigidos .= "    k00_descr                                                          \n";

if (!$DB_DEBUG) {
    db_query($sqlParcelamentosCorrigidos); // cria tabela temporaria
    db_query('create index w_parcel_corrigidos_1_in on w_parcelamentos_corrigidos(v07_parcel)');
} else {
    $sqlParcelamentosCorrigidos .= ';<br><br>';
    $sqlParcelamentosCorrigidos .= 'create index w_parcel_corrigidos_1_in on w_parcelamentos_corrigidos(v07_parcel);';
    $sqlParcelamentosCorrigidos .= '<br><br>';
}

$sql = '';

if ($DB_DEBUG) {
    $sql .= " create table w_debitos_numpre as                                                                     \n";
}

// 1º cobrança administrativa
$sql .= " select dv05_exerc,                                                                                       \n";

if ($DB_DEBUG) {
    $sql .= "        numpre,                                                                                       \n";
}

$sql .= "        k00_descr,                                                                                        \n";
$sql .= "        dv09_procdiver,                                                                                      \n";
$sql .= "        dv09_descr,                                                                                        \n";
$sql .= "        v03_tributaria,                                                                                   \n";

if ('c' == $tiporel) {
    $sql .= "        k00_numcgm,                                                                                   \n";
}

$sql .= "        round(sum(k22_vlrhis),2)  as k22_vlrhis,                                                          \n";
$sql .= "        round(sum(k22_vlrcor),2)  as k22_vlrcor,                                                          \n";
$sql .= "        round(sum(k22_juros),2)   as k22_juros,                                                           \n";
$sql .= "        round(sum(k22_multa),2)   as k22_multa,                                                           \n";
$sql .= "        round(sum(valor_total),2) as valor_total                                                          \n";
$sql .= "   from ( select dv05_exerc,                                                                              \n";

if ($DB_DEBUG) {
    $sql .= "                 k22_numpre as numpre,                                                                \n";
}

$sql .= "                 k00_descr,                                                                               \n";
$sql .= "                 dv09_procdiver,                                                                             \n";
$sql .= "                 dv09_descr,                                                                               \n";
$sql .= "                 v07_descricao as v03_tributaria,                                                         \n";

if ('c' == $tiporel) {
    $sql .= "                 k22_numcgm as k00_numcgm,                                                            \n";
}

$sql .= "                 round(sum(k22_vlrhis),2) as k22_vlrhis,                                                  \n";
$sql .= "                 round(sum(k22_vlrcor),2) as k22_vlrcor,                                                  \n";
$sql .= "                 round(sum(k22_juros),2)  as k22_juros,                                                   \n";
$sql .= "                 round(sum(k22_multa),2)  as k22_multa,                                                   \n";
$sql .= "                 round(sum(k22_vlrcor+k22_juros+k22_multa),2) as valor_total                              \n";
$sql .= "            from debitos                                                                                  \n";
$sql .= "                 inner join diversos   on dv05_numpre = k22_numpre                                        \n";
$sql .= '                                      and dv05_instit = '.db_getsession('DB_instit')."                    \n";
$sql .= "                 inner join procdiver  on dv09_procdiver = dv05_procdiver                                 \n";
$sql .= "                 inner join proced     on v03_codigo        = dv09_proced                                 \n";
$sql .= "                 inner join tipoproced on v07_sequencial    = v03_tributaria                              \n";
$sql .= "                 inner join arretipo   on arretipo.k00_tipo = k22_tipo                                    \n";
$sql .= "           where k22_tipo = 13                                                                            \n";

$sql .= "             and k22_data = '".$xdata."'  and k22_instit = $instit                                        \n";
$sql .= "           group by dv05_exerc,                                                                           \n";

if ($DB_DEBUG) {
    $sql .= "                    k22_numpre,                                                                       \n";
}

$sql .= "                    k00_descr,                                                                            \n";
$sql .= "                    dv09_procdiver,                                                                          \n";
$sql .= "                    dv09_descr,                                                                            \n";
$sql .= "                    v03_tributaria,                                                                       \n";
$sql .= "                    v07_descricao                                                                         \n";
if ('c' == $tiporel) {
    $sql .= "           , k22_numcgm                                                                               \n";
}

// 2º parcelamentos e reparcelamentos de divida
$sql .= " \n";
$sql .= "          union all                                                                                       \n";
$sql .= " \n";

$sql .= " select dv05_exerc,                                                                                       \n";

if ($DB_DEBUG) {
    $sql .= "                 k22_numpre as numpre,                                                                \n";
}

$sql .= "                 arretipo.k00_descr,                                                                      \n";
$sql .= "                 dv09_procdiver,                                                                             \n";
$sql .= "                 dv09_descr,                                                                               \n";
$sql .= "                 v07_descricao as v03_tributaria,                                                         \n";

if ('c' == $tiporel) {
    $sql .= "                 k22_numcgm as k00_numcgm,                                                            \n";
}

$sql .= "                 round(sum(debitos.k22_vlrhis),2) as k22_vlrhis,                                          \n";
$sql .= "                 round(sum(debitos.k22_vlrcor),2) as k22_vlrcor,                                          \n";
$sql .= "                 round(sum(debitos.k22_juros),2)  as k22_juros,                                           \n";
$sql .= "                 round(sum(debitos.k22_multa),2)  as k22_multa,                                           \n";
$sql .= "                 round(sum(debitos.k22_vlrcor+debitos.k22_juros+debitos.k22_multa),2) as valor_total      \n";
$sql .= "            from debitos                                                                                  \n";
$sql .= "                 inner join termo      on v07_numpre = k22_numpre                                         \n";
$sql .= "                                      and v07_instit = 1                                                  \n";
$sql .= "                 inner join termodiver on dv10_parcel = v07_parcel                                        \n";
$sql .= "                 inner join diversos   on dv10_coddiver = dv05_coddiver                                   \n";
$sql .= '                                      and dv05_instit = '.db_getsession('DB_instit')."                    \n";
$sql .= "                 inner join procdiver  on dv09_procdiver = dv05_procdiver                                 \n";
$sql .= "                 inner join proced     on v03_codigo        = dv09_proced                                 \n";
$sql .= "                 inner join tipoproced on v07_sequencial    = v03_tributaria                              \n";
$sql .= "                 inner join arretipo   on arretipo.k00_tipo = k22_tipo                                    \n";

// Parcelamentos na Debitos
$sql .= "                 inner join w_parcelamentos_corrigidos a on a.v07_parcel = termo.v07_parcel               \n";
$sql .= "           where k22_tipo = 14                                                                            \n";
$sql .= "             and k22_data = '".$xdata."'  and k22_instit = $instit                                        \n";
$sql .= "           group by dv05_exerc,                                                                           \n";

if ($DB_DEBUG) {
    $sql .= "                    k22_numpre,                                                                       \n";
}

$sql .= "                    arretipo.k00_descr,                                                                   \n";
$sql .= "                    dv09_procdiver,                                                                          \n";
$sql .= "                    dv09_descr,                                                                            \n";
$sql .= "                    v03_tributaria,                                                                       \n";
$sql .= "                    v07_descricao                                                                         \n";
if ('c' == $tiporel) {
    $sql .= "           , k22_numcgm                                                                               \n";
}

$sql .= " ) as posdivexerc                                                                                         \n";
$sql .= " where 1 = 1                                                                                              \n";
if ('' != $procedencias) {
    $sql .= " and dv09_procdiver in ($procedencias)                                                                   \n";
}

$sql .= " $exercicios                                                                                              \n";

$sql .= "  group by dv05_exerc,                                                                                    \n";
if ($DB_DEBUG) {
    $sql .= "           numpre,                                                                                    \n";
}
$sql .= "           k00_descr,                                                                                     \n";
$sql .= "           dv09_procdiver,                                                                                   \n";
$sql .= "           dv09_descr,                                                                                     \n";
$sql .= "           v03_tributaria                                                                                 \n";
if ('c' == $tiporel) {
    $sql .= "          , k00_numcgm                                                                                \n";
}

$sql .= "  order by dv05_exerc,                                                                                    \n";
$sql .= "           k00_descr,                                                                                     \n";
$sql .= "           dv09_procdiver,                                                                                   \n";
$sql .= "           dv09_descr,                                                                                     \n";
$sql .= "           v03_tributaria                                                                                 \n";

if ($DB_DEBUG) {
    $strDebug = "begin; <br> $sqlTermoini <br><br>$sqlParcelamentosCorrigidos <br><br>$sql";
    die('<pre>'.$strDebug);
}

$result = db_query($sql) or die("FALHA: <br>$sql");

if (0 == pg_numrows($result)) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cobranças em aberto. ('.$exerc.').');
}

if (1 == $tipo) {
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(220);
    $pdf->SetFont('Arial', 'B', 7);

    $pag = 1;
    $totalreg = 0;
    $totalhis = 0;
    $totalcor = 0;
    $totaljur = 0;
    $totalmul = 0;
    $totalval = 0;
    $tot_trib = 0;
    $tot_nao_trib = 0;
    $int_ano = 0;
    $int_ano2 = 0;
    $str_tipo = '';

    for ($x = 0; $x < pg_numrows($result); ++$x) {
        db_fieldsmemory($result, $x);
        if (($pdf->gety() > $pdf->h - 30) || 1 == $pag) {
            $pdf->addpage(('c' == $tiporel ? 'L' : ''));
            $pag = 0;
        }
        if ($int_ano != $dv05_exerc) {
            if (0 != $int_ano && $int_ano != $dv05_exerc) {
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(15, 7, '', 'T', 0, 'C', 0);
                $pdf->Cell(15, 7, '', 'T', 0, 'C', 0);
                $pdf->Cell(60, 7, '', 'T', 0, 'L', 0);
                if ('c' == $tiporel) {
                    $pdf->Cell(10, 7, '', 'T', 0, 'L', 0);
                    $pdf->Cell(80, 7, '', 'T', 0, 'L', 0);
                }
                $pdf->cell(20, 7, db_formatar($totalhis, 'f'), 'T', 0, 'R', 0);
                $pdf->cell(20, 7, db_formatar($totalcor, 'f'), 'T', 0, 'R', 0);
                $pdf->cell(20, 7, db_formatar($totaljur, 'f'), 'T', 0, 'R', 0);
                $pdf->cell(20, 7, db_formatar($totalmul, 'f'), 'T', 0, 'R', 0);
                $pdf->cell(20, 7, db_formatar($totalval, 'f'), 'T', 1, 'R', 0);

                $pdf->cell(75, 5, 'COBRANÇA ADMINISTRATIVA NÃO TRIBUTÁRIA:', 1, 0, 'R', 0);
                $pdf->cell(20, 5, db_formatar($tot_nao_trib, 'f'), 1, 1, 'R', 0);
                $pdf->cell(75, 5, 'COBRANÇA ADMINISTRATIVA:', 1, 0, 'R', 0);
                $pdf->cell(20, 5, db_formatar($tot_trib, 'f'), 1, 1, 'R', 0);
                $pdf->Cell(15, 7, '', 'T', 1, 'C', 0);

                $totalhis = 0;
                $totalcor = 0;
                $totaljur = 0;
                $totalmul = 0;
                $totalval = 0;
                $tot_trib = 0;
                $tot_nao_trib = 0;
            }
            $int_ano = $dv05_exerc;
            $pdf->Cell(80, 5, 'valores referentes ao ano de '.$dv05_exerc, 0, 1, 'C', 1);
            $pdf->Cell(15, 5, '', 1, 0, 'C', 1);
            $pdf->Cell(15, 5, 'Cód. Proced', 1, 0, 'C', 1);
            $pdf->Cell(60, 5, $RLdv09_descr, 1, 0, 'C', 1);
            if ('c' == $tiporel) {
                $pdf->cell(10, 5, 'CGM', 1, 0, 'R', 1);
                $pdf->cell(80, 5, 'NOME', 1, 0, 'R', 1);
            }
            $pdf->cell(20, 5, 'Historico', 1, 0, 'R', 1);
            $pdf->cell(20, 5, 'Corrigido', 1, 0, 'R', 1);
            $pdf->cell(20, 5, 'Juros', 1, 0, 'R', 1);
            $pdf->cell(20, 5, 'Multa', 1, 0, 'R', 1);
            $pdf->cell(20, 5, 'Total', 1, 1, 'R', 1);
        }

        if ($str_tipo != $k00_descr || $int_ano2 != $dv05_exerc) {
            $int_ano2 = $dv05_exerc;
            $pdf->Cell(15, 4, '', 'T', 0, 'C', 0);
            $pdf->Cell(50, 4, $k00_descr, 1, 1, 'C', 1);
            $str_tipo = $k00_descr;
        }

        $pdf->Cell(15, 5, $v03_tributaria, 0, 0, 'L', 0);
        $pdf->Cell(15, 5, $dv09_procdiver, 0, 0, 'R', 0);
        $pdf->Cell(60, 5, substr($dv09_descr,0,40), 0, 0, 'L', 0);
        if ('c' == $tiporel) {
            $sqlnome = "select z01_nome from cgm where z01_numcgm = $k00_numcgm";
            $resultnome = db_query($sqlnome) or die($sqlnome);
            if (pg_numrows($resultnome) > 0) {
                db_fieldsmemory($resultnome, 0);
            } else {
                $z01_nome = '';
            }
            $pdf->Cell(10, 5, $k00_numcgm, 0, 0, 'L', 0);
            $pdf->Cell(80, 5, $z01_nome, 0, 0, 'L', 0);
        }
        $pdf->cell(20, 5, db_formatar($k22_vlrhis, 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($k22_vlrcor, 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($k22_juros, 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($k22_multa, 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($valor_total, 'f'), 0, 1, 'R', 0);
        ++$totalreg;
        $totalhis += $k22_vlrhis;
        $totalcor += $k22_vlrcor;
        $totaljur += $k22_juros;
        $totalmul += $k22_multa;
        $totalval += $valor_total;

        $int_arr = 0;
        $boo_encontro = false;
        if ($totalreg > 1) {
            for ($int_arr = 0; $int_arr < count($arr_proced); ++$int_arr) {
                if ($arr_proced[$int_arr][0] == $dv09_procdiver.' - '.$dv09_descr) {
                    $boo_encontro = true;
                    break;
                }
            }
        }
        if ((false == $boo_encontro and $int_arr > 0) or 1 == $totalreg) {
            if ($totalreg > 1) {
                $int_arr = count($arr_proced);
            }
            $arr_proced[$int_arr][0] = '';
            $arr_proced[$int_arr][1] = '';
            $arr_proced[$int_arr][2] = 0;
            $arr_proced[$int_arr][3] = 0;
            $arr_proced[$int_arr][4] = 0;
            $arr_proced[$int_arr][5] = 0;
            $arr_proced[$int_arr][6] = 0;
            $arr_proced[$int_arr][7] = 0;
            $arr_proced[$int_arr][8] = 0;
        }
        $arr_proced[$int_arr][0] = $dv09_procdiver.' - '.$dv09_descr;
        $arr_proced[$int_arr][1] = $v03_tributaria;
        $arr_proced[$int_arr][2] += $k22_vlrhis;
        $arr_proced[$int_arr][3] += $k22_vlrcor;
        $arr_proced[$int_arr][4] += $k22_juros;
        $arr_proced[$int_arr][5] += $k22_multa;
        $arr_proced[$int_arr][6] += $valor_total;
        $arr_proced[$int_arr][7] += 0;
        $arr_proced[$int_arr][8] += 0;

        if ('T' == substr($v03_tributaria, 0, 1)) {
            $tot_trib += $valor_total;
            $arr_proced[$int_arr][7] += $valor_total;
        } else {
            $tot_nao_trib += $valor_total;
            $arr_proced[$int_arr][8] += $valor_total;
        }
    }

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(15, 7, '', 'T', 0, 'C', 0);
    $pdf->Cell(15, 7, '', 'T', 0, 'C', 0);
    $pdf->Cell(60, 7, '', 'T', 0, 'L', 0);
    if ('c' == $tiporel) {
        $pdf->Cell(10, 7, '', 'T', 0, 'L', 0);
        $pdf->Cell(80, 7, '', 'T', 0, 'L', 0);
    }
    $pdf->cell(20, 7, db_formatar($totalhis, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 7, db_formatar($totalcor, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 7, db_formatar($totaljur, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 7, db_formatar($totalmul, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 7, db_formatar($totalval, 'f'), 'T', 1, 'R', 0);

    $pdf->cell(75, 5, 'COBRANÇA ADMINISTRATIVA NÃO TRIBUTÁRIA:', 1, 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($tot_nao_trib, 'f'), 1, 1, 'R', 0);
    $pdf->cell(75, 5, 'COBRANÇA ADMINISTRATIVA TRIBUTÁRIA:', 1, 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($tot_trib, 'f'), 1, 1, 'R', 0);
    $pdf->Cell(15, 7, '', 'T', 1, 'C', 0);

    $pdf->addpage(('c' == $tiporel ? 'L' : ''));
    $pdf->Cell(190, 5, 'RESUMO', 0, 1, 'C', 0);
    $pdf->Cell(25, 5, '', 1, 0, 'C', 1);
    $pdf->Cell(60, 5, $RLdv09_descr, 1, 0, 'C', 1);
    $pdf->cell(20, 5, 'Historico', 1, 0, 'R', 1);
    $pdf->cell(20, 5, 'Corrigido', 1, 0, 'R', 1);
    $pdf->cell(20, 5, 'Juros', 1, 0, 'R', 1);
    $pdf->cell(20, 5, 'Multa', 1, 0, 'R', 1);
    $pdf->cell(20, 5, 'Total', 1, 1, 'R', 1);

    $totalhis1 = 0;
    $totalcor1 = 0;
    $totaljur1 = 0;
    $totalmul1 = 0;
    $totalval1 = 0;

    $totalhis2 = 0;
    $totalcor2 = 0;
    $totaljur2 = 0;
    $totalmul2 = 0;
    $totalval2 = 0;

    $tot_trib = 0;
    $tot_nao_trib = 0;

    for ($int_arr = 0; $int_arr < count($arr_proced); ++$int_arr) {
        $pdf->Cell(25, 5, $arr_proced[$int_arr][1], 0, 0, 'L', 0);
        $pdf->Cell(60, 5, substr($arr_proced[$int_arr][0], 0, 40), 0, 0, 'L', 0);
        $pdf->cell(20, 5, db_formatar($arr_proced[$int_arr][2], 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($arr_proced[$int_arr][3], 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($arr_proced[$int_arr][4], 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($arr_proced[$int_arr][5], 'f'), 0, 0, 'R', 0);
        $pdf->cell(20, 5, db_formatar($arr_proced[$int_arr][6], 'f'), 0, 1, 'R', 0);

        if ('T' == substr($arr_proced[$int_arr][1], 0, 1)) {
            $totalhis1 += $arr_proced[$int_arr][2];
            $totalcor1 += $arr_proced[$int_arr][3];
            $totaljur1 += $arr_proced[$int_arr][4];
            $totalmul1 += $arr_proced[$int_arr][5];
            $totalval1 += $arr_proced[$int_arr][6];
        } else {
            $totalhis2 += $arr_proced[$int_arr][2];
            $totalcor2 += $arr_proced[$int_arr][3];
            $totaljur2 += $arr_proced[$int_arr][4];
            $totalmul2 += $arr_proced[$int_arr][5];
            $totalval2 += $arr_proced[$int_arr][6];
        }
        $tot_trib += $arr_proced[$int_arr][7];
        $tot_nao_trib += $arr_proced[$int_arr][8];
    }
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(85, 5, 'COBRANÇA ADMINISTRATIVA TRIBUTÁRIA:', 'T', 0, 'L', 0);
    $pdf->cell(20, 5, db_formatar($totalhis1, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalcor1, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totaljur1, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalmul1, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalval1, 'f'), 'T', 1, 'R', 0);

    $pdf->Cell(85, 5, 'COBRANÇA ADMINISTRATIVA NÃO TRIBUTÁRIA:', 0, 0, 'L', 0);
    $pdf->cell(20, 5, db_formatar($totalhis2, 'f'), 0, 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalcor2, 'f'), 0, 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totaljur2, 'f'), 0, 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalmul2, 'f'), 0, 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalval2, 'f'), 0, 1, 'R', 0);

    $pdf->Cell(85, 5, 'TOTAL:', 'T', 0, 'L', 0);
    $pdf->cell(20, 5, db_formatar($totalhis1 + $totalhis2, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalcor1 + $totalcor2, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totaljur1 + $totaljur2, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalmul1 + $totalmul2, 'f'), 'T', 0, 'R', 0);
    $pdf->cell(20, 5, db_formatar($totalval1 + $totalval2, 'f'), 'T', 1, 'R', 0);

    $pdf->Output();
} else {
    $nomedoarquivo = '/tmp/cobranca_exercicio_procedencia_'.date('Y-m-d_His', db_getsession('DB_datausu')).'.txt';

    $erro = false;
    $descricao_erro = false;
    set_time_limit(0);
    $clabre_arquivo = new cl_abre_arquivo($nomedoarquivo);

    if (false != $clabre_arquivo->arquivo) {
        fputs($clabre_arquivo->arquivo, 'exercicio;');
        fputs($clabre_arquivo->arquivo, 'descricao_tipo_debito;');
        fputs($clabre_arquivo->arquivo, 'codigo_procedencia;');
        fputs($clabre_arquivo->arquivo, 'descricao_procedencia;');
        fputs($clabre_arquivo->arquivo, 'categoria;');
        if ('c' == $tiporel) {
            fputs($clabre_arquivo->arquivo, 'cgm;');
        }
        fputs($clabre_arquivo->arquivo, 'valor_historico;');
        fputs($clabre_arquivo->arquivo, 'valor_corrigido;');
        fputs($clabre_arquivo->arquivo, 'valor_juros;');
        fputs($clabre_arquivo->arquivo, 'valor_multa;');
        fputs($clabre_arquivo->arquivo, 'valor_total;');
        fputs($clabre_arquivo->arquivo, "\n");
    }

    for ($x = 0; $x < pg_numrows($result); ++$x) {
        db_fieldsmemory($result, $x);

        fputs($clabre_arquivo->arquivo, trim($dv05_exerc).';');
        fputs($clabre_arquivo->arquivo, trim($k00_descr).';');
        fputs($clabre_arquivo->arquivo, trim($dv09_procdiver).';');
        fputs($clabre_arquivo->arquivo, trim($dv09_descr).';');
        fputs($clabre_arquivo->arquivo, trim($v03_tributaria).';');

        if ('c' == $tiporel) {
            $sqlnome = "select z01_nome from cgm where z01_numcgm = $k00_numcgm";
            $resultnome = db_query($sqlnome) or die($sqlnome);
            if (pg_numrows($resultnome) > 0) {
                db_fieldsmemory($resultnome, 0);
            } else {
                $z01_nome = '';
            }

            fputs($clabre_arquivo->arquivo, trim($k00_numcgm).';');
            fputs($clabre_arquivo->arquivo, trim($z01_nome).';');
        }

        fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_vlrhis, 'f')).';');
        fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_vlrcor, 'f')).';');
        fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_juros, 'f')).';');
        fputs($clabre_arquivo->arquivo, trim(db_formatar($k22_multa, 'f')).';');
        fputs($clabre_arquivo->arquivo, trim(db_formatar($valor_total, 'f')).';');

        fputs($clabre_arquivo->arquivo, "\n");
    }

    $descricao_erro = "Arquivo $nomedoarquivo gerado com sucesso.";

    fclose($clabre_arquivo->arquivo);

    if (isset($local) or 1 == 1) {
        echo "<script>jan = window.open('db_download.php?arquivo=".$clabre_arquivo->nomearq."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
        echo 'jan.moveTo(0,0);</script>';
    }

    db_msgbox($descricao_erro);
}
