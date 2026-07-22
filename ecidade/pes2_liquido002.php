<?php
/*
 * E-cidade Software Publico para Gestao Municipal
 * Copyright (C) 2009 DBSeller Servicos de Informatica
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
require_once (modification("libs/db_utils.php"));

parse_str($_SERVER['QUERY_STRING']);
db_postmemory($_SERVER);

if (! isset($faixa1) || (empty($faixa1) && $faixa1 != 0) || ! isset($faixa2) || (empty($faixa2) && $faixa2 != 0)) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Informe valores inicial/final corretamente na faixa.');
}

if ($tipo_faixa == 'l') {
    $where = " where r38_liq between $faixa1 and $faixa2";
    $head2 = "RELATORIO POR FAIXA DE LIQUIDOS";
} elseif ($tipo_faixa == 'p') {
    $where = " where r38_prov between $faixa1 and $faixa2";
    $head2 = "RELATORIO POR FAIXA DE BRUTO";
} else {
    $where = " where r38_desc between $faixa1 and $faixa2";
    $head2 = "RELATORIO POR FAIXA DE DESCONTO";
}

if (isset($lotaci) && trim($lotaci) != "" && isset($lotacf) && trim($lotacf) != "") {
    // Se for por intervalos e vier lotação inicial e final
    $where .= " and r70_estrut between '" . $lotaci . "' and '" . $lotacf . "' ";
} else if (isset($lotaci) && trim($lotaci) != "") {
    // Se for por intervalos e vier somente lotação inicial
    $where .= " and r70_estrut >= '" . $lotaci . "' ";
} else if (isset($lotacf) && trim($lotacf) != "") {
    // Se for por intervalos e vier somente lotação final
    $where .= " and r70_estrut <= '" . $lotacf . "' ";
} else if (isset($faixa_lotac) && $faixa_lotac != '') {
    $faixa_lotac = str_replace(",", "','", $faixa_lotac);
    $where .= " and r70_estrut in ('" . $faixa_lotac . "') ";
}

if (isset($regisi) && trim($regisi) != "" && isset($regisf) && trim($regisf) != "") {
    // Se for por intervalos e vier lotação inicial e final
    $where .= " and r38_regist between '" . $regisi . "' and '" . $regisf . "' ";
} else if (isset($regisi) && trim($regisi) != "") {
    // Se for por intervalos e vier somente lotação inicial
    $where .= " and r38_regist >= '" . $regisi . "' ";
} else if (isset($regisf) && trim($regisf) != "") {
    // Se for por intervalos e vier somente lotação final
    $where .= " and r38_regist <= '" . $regisf . "' ";
} else if (isset($faixa_regis) && $faixa_regis != '') {
    $faixa_regis = str_replace(",", "','", $faixa_regis);
    $where .= " and r38_regist in ('$faixa_regis') ";
}

if (isset($selecao) && !empty($selecao)) {
    $oSelecao = new Selecao($selecao);
    $sqlServidores = " select array_to_string(array_accum(rh02_regist),',') as servidores
                         from folha 
                              inner join rhpessoal    on rh01_regist = r38_regist
                              inner join rhpessoalmov on rh01_regist = rh02_regist
                                                     and rh02_anousu = {$ano}
                                                     and rh02_mesusu = {$mes}
                                                     and rh02_instit = " . db_getsession("DB_instit") . "
                               left join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit = rhpessoalmov.rh02_instit
                               left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                               left join rhpescargo on rhpescargo.rh20_seqpes = rhpessoalmov.rh02_seqpes
                               left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                                                    and rhpespadrao.rh03_anousu = rhpessoalmov.rh02_anousu
                                                    and rhpespadrao.rh03_mesusu = rhpessoalmov.rh02_mesusu
                               left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                        where " . $oSelecao->getWhere();
    $rsServidores = db_query($sqlServidores);
    if (pg_num_rows($rsServidores) == 0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Nenhuma informação encontrada para a seleção selecionada.');
    }
    
    $listaServidores =  db_utils::fieldsMemory($rsServidores, 0)->servidores;
    $where .= "and r38_regist in ({$listaServidores})";
}

if ($asc == 'a') {
    $xasc = ' asc';
    $dasc = ' ASCENDENTE';
} else {
    $xasc = ' desc';
    $dasc = ' DESCENDENTE';
}

if ($ordem == 'a') {
    $xordem = ' order by r38_nome ';
    $dordem = 'ALFABETICA';
} elseif ($ordem == 'n') {
    $xordem = ' order by r38_regist ';
    $dordem = 'NUMERICA';
} elseif ($ordem == 'l') {
    $xordem = ' order by r38_liq ';
    $dordem = 'LIQUIDO';
} elseif ($ordem == 'p') {
    $xordem = ' order by r38_prov ';
    $dordem = 'PROVENTO';
} else {
    $xordem = ' order by r38_desc ';
    $dordem = 'DESCONTO';
}
$head6 = 'ORDEM : ' . $dordem . $dasc;

$limit = "";
if ($qtd != "") {
    $limit = "limit $qtd";
    $head9 = "Quantidade de Registros : {$qtd}";
}
$head4 = "PERIODO : " . $mes . " / " . $ano;
$head8 = 'FAIXA : DE ' . db_formatar($faixa1, 'f') . ' ATE ' . db_formatar($faixa2, 'f');

$troca = 1;
$alt = 4;
$total_func = 0;
$total_prov = 0;
$total_desc = 0;
$total_liq = 0;
$lotacao = 0;

$pdf = new pdf();

$pdf->addTitulo($head2, 2);
$pdf->addTitulo($head4, 4);
$pdf->addTitulo($head6, 6);
$pdf->addTitulo($head8, 8);

$pdf->AliasNbPages();
$pdf->setfillcolor(235);

$pdf->setExibeBrasao(true);
$pdf->init(false);

$pdf->setfont('arial', 'b', 8);

if ($func_lota != 'l') {

    $sql = "select folha.*, 
                   r70_estrut,
                   r70_descr 
              from folha
                   inner join rhpessoalmov on rh02_regist = r38_regist
   	                                      and rh02_anousu = $ano
                                          and rh02_mesusu = $mes
                                          and rh02_instit = " . db_getsession("DB_instit") . "
    	           inner join rhlota       on r70_codigo  = rh02_lota
                                          and r70_instit  = " . db_getsession("DB_instit") . "
      	  $where
          $xordem $xasc $limit
          ";

    $result = db_query($sql);
    if (! $result || pg_num_rows($result) == 0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Verifique se foi gerado a folha em disco');
    }

    for ($x = 0; $x < pg_numrows($result); $x ++) {
        db_fieldsmemory($result, $x);
        if ($pdf->gety() > $pdf->getH() - 30 || $troca != 0) {
            $pdf->addpage();
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(15, $alt, 'MATRIC', 1, 0, "C", 1);
            $pdf->cell(70, $alt, 'NOME DO FUNCIONARIO', 1, 0, "C", 1);

            if (strpos("#" . $colunas, "p") + 0 > 0) {
                $pdf->cell(30, $alt, 'PROVENTOS', 1, 0, "C", 1);
            }
            if (strpos("#" . $colunas, "d") + 0 > 0) {
                $pdf->cell(30, $alt, 'DESCONTOS', 1, 0, "C", 1);
            }
            if (strpos("#" . $colunas, "l") + 0 > 0) {
                $pdf->cell(30, $alt, 'LIQUIDO', 1, 0, "C", 1);
            }
            $pdf->ln(4);
            $troca = 0;
            $pre = 1;
        }
        if ($pre == 1) {
            $pre = 0;
        } else {
            $pre = 1;
        }
        $pdf->setfont('arial', '', 7);
        $pdf->cell(15, $alt, $r38_regist, 0, 0, "C", $pre);
        $pdf->cell(70, $alt, $r38_nome, 0, 0, "L", $pre);
        if (strpos("#" . $colunas, "p") + 0 > 0) {
            $pdf->cell(30, $alt, db_formatar($r38_prov, 'f'), 0, 0, "R", $pre);
        }
        if (strpos("#" . $colunas, "d") + 0 > 0) {
            $pdf->cell(30, $alt, db_formatar($r38_desc, 'f'), 0, 0, "R", $pre);
        }
        if (strpos("#" . $colunas, "l") + 0 > 0) {
            $pdf->cell(30, $alt, db_formatar($r38_liq, 'f'), 0, 0, "R", $pre);
        }
        $pdf->ln(4);
        $total_func += 1;
        $total_prov += $r38_prov;
        $total_desc += $r38_desc;
        $total_liq += $r38_liq;
    }
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(85, $alt, 'TOTAL GERAL  :  ' . $total_func . '   FUNCIONARIOS', "T", 0, "C", 0);
    if (strpos("#" . $colunas, "p") + 0 > 0) {
        $pdf->cell(30, $alt, db_formatar($total_prov, 'f'), "T", 0, "R", 0);
    }
    if (strpos("#" . $colunas, "d") + 0 > 0) {
        $pdf->cell(30, $alt, db_formatar($total_desc, 'f'), "T", 0, "R", 0);
    }
    if (strpos("#" . $colunas, "l") + 0 > 0) {
        $pdf->cell(30, $alt, db_formatar($total_liq, 'f'), "T", 1, "R", 0);
    }
} else {

    $campos = '';
    $virg = '';
    if (strpos("#" . $colunas, "p") + 0 > 0) {
        $campos = $campos . $virg . 'sum(r38_prov) as r38_prov ';
        $virg = ', ';
    }
    if (strpos("#" . $colunas, "d") + 0 > 0) {
        $campos = $campos . $virg . 'sum(r38_desc) as r38_desc ';
        $virg = ', ';
    }
    if (strpos("#" . $colunas, "l") + 0 > 0) {
        $campos = $campos . $virg . 'sum(r38_liq) as r38_liq ';
        $virg = ', ';
    }
    $sql1 = "select r70_codigo,r70_estrut,r70_descr,count(r38_regist) as func,$campos
               from folha
                    inner join rhpessoalmov on rh02_regist = r38_regist
	                                       and rh02_anousu = $ano
                  			               and rh02_mesusu = $mes
                                           and rh02_instit = " . db_getsession("DB_instit") . "
	                inner join rhlota       on r70_codigo  = rh02_lota
                                           and r70_instit  = " . db_getsession("DB_instit") . "
	                {$where}
              group by r70_codigo,r70_estrut,r70_descr
			  order by r70_estrut";

    $result1 = db_query($sql1);

    for ($xx = 0; $xx < pg_numrows($result1); $xx ++) {
        
        db_fieldsmemory($result1, $xx);

        if ($xx == 0) {
          $pdf->addTitulo("POR LOTACAO", 9);
          $pdf->addpage("L");
        }
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(15, $alt, 'CODIGO', 1, 0, "C", 1);
        $pdf->cell(50, $alt, 'DESCRICAO DA LOTACAO', 1, 0, "C", 1);
        $pdf->cell(30, $alt, 'FUNCIONARIOS', 1, 0, "C", 1);
        if (strpos("#" . $colunas, "p") + 0 > 0) {
            $pdf->cell(30, $alt, 'PROVENTOS', 1, 0, "C", 1);
        }
        if (strpos("#" . $colunas, "d") + 0 > 0) {
            $pdf->cell(30, $alt, 'DESCONTOS', 1, 0, "C", 1);
        }
        if (strpos("#" . $colunas, "l") + 0 > 0) {
            $pdf->cell(30, $alt, 'LIQUIDO', 1, 0, "C", 1);
        }
        $pdf->ln(4);
        $troca = 0;
        
        /*
         * Informacoes da lotacao
         */
        $pdf->setfont('arial', '', 7);
        $pdf->cell(15, $alt, $r70_estrut, 0, 0, "C", 0);
        $pdf->cell(50, $alt, $r70_descr, 0, 0, "L", 0);
        $pdf->cell(30, $alt, db_formatar($func, 'f'), 0, 0, "R", 0);
        if (strpos("#" . $colunas, "p") + 0 > 0) {
            $pdf->cell(30, $alt, db_formatar($r38_prov, 'f'), 0, 0, "R", 0);
            $total_prov += $r38_prov;
        }
        if (strpos("#" . $colunas, "d") + 0 > 0) {
            $pdf->cell(30, $alt, db_formatar($r38_desc, 'f'), 0, 0, "R", 0);
            $total_desc += $r38_desc;
        }
        if (strpos("#" . $colunas, "l") + 0 > 0) {
            $pdf->cell(30, $alt, db_formatar($r38_liq, 'f'), 0, 0, "R", 0);
            $total_liq += $r38_liq;
        }
        $pdf->cell(1, 4, '', 0, 1);
        /*
         * Informacao das matriculas da lotacao
         */
        $sqlMatriculas = "select r38_regist, 
                                 r38_nome,
                                 rh02_funcao,
                                 rh37_descr, 
                                 r38_prov,
                                 r38_desc,
                                 r38_liq
                            from folha
                                 inner join rhpessoalmov on rh02_regist = r38_regist
	                                                    and rh02_anousu = $ano
                  	             		                and rh02_mesusu = $mes
                                                        and rh02_instit = " . db_getsession("DB_instit") . "
	                             inner join rhlota       on r70_codigo  = rh02_lota
                                                        and r70_instit  = " . db_getsession("DB_instit") . "
                                  left join rhfuncao     on rh37_funcao = rh02_funcao
                                                        and rh37_instit = " . db_getsession("DB_instit") . "
	                             {$where} and rhlota.r70_codigo = {$r70_codigo}
                                 {$xordem} {$xasc}";
        $rsMatriculas = db_query($sqlMatriculas);
        $iQtdMatriculas = pg_num_rows($rsMatriculas);
        for ($i = 0; $i < $iQtdMatriculas; $i++) {
          $oDadosMatricula = db_utils::fieldsMemory($rsMatriculas, $i);
          
          if ($pdf->gety() > $pdf->geth() - 18|| $i == 0) {
             $pdf->setfont('arial', 'b', 8);
             $pdf->cell(15, $alt, 'MATRIC', 1, 0, "C", 1);
             $pdf->cell(80, $alt, 'NOME DO FUNCIONARIO', 1, 0, "C", 1);
             $pdf->cell(20, $alt, 'COD. CARGO', 1, 0, "C", 1);
             $pdf->cell(70, $alt, 'DESCR. CARGO', 1, 0, "C", 1);
             if (strpos("#" . $colunas, "p") + 0 > 0) {
                 $pdf->cell(30, $alt, 'PROVENTOS', 1, 0, "C", 1);
             }
             if (strpos("#" . $colunas, "d") + 0 > 0) {
                 $pdf->cell(30, $alt, 'DESCONTOS', 1, 0, "C", 1);
             }
             if (strpos("#" . $colunas, "l") + 0 > 0) {
                 $pdf->cell(30, $alt, 'LIQUIDO', 1, 0, "C", 1);
             }
             $pdf->cell(1, 4, '', 0, 1);
             
          }
          
          $pdf->setfont('arial', '', 7);
          $pdf->cell(15, $alt, $oDadosMatricula->r38_regist, 0, 0, "C", 0);
          $pdf->cell(80, $alt, $oDadosMatricula->r38_nome, 0, 0, "L", 0);
          $pdf->cell(20, $alt, $oDadosMatricula->rh02_funcao, 0, 0, "C", 0);
          $pdf->cell(70, $alt, $oDadosMatricula->rh37_descr, 0, 0, "L", 0);
          if (strpos("#" . $colunas, "p") + 0 > 0) {
              $pdf->cell(30, $alt, db_formatar($oDadosMatricula->r38_prov, 'f'), 0, 0, "R", 0);
          }
          if (strpos("#" . $colunas, "d") + 0 > 0) {
              $pdf->cell(30, $alt, db_formatar($oDadosMatricula->r38_desc, 'f'), 0, 0, "R", 0);
          }
          if (strpos("#" . $colunas, "l") + 0 > 0) {
              $pdf->cell(30, $alt, db_formatar($oDadosMatricula->r38_liq, 'f'), 0, 0, "R", 0);
          }
          $pdf->cell(1, 4, '', 0, 1);
          
        }
        
        $pdf->ln(4);
        $total_func += $func;
    }
    
    //Totalizadores
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(65, $alt, '', "T", 0, "C", 0);
    $pdf->cell(30, $alt, db_formatar($total_func, 'f'), "T", 0, "R", 0);

    if (strpos("#" . $colunas, "p") + 0 > 0) {
        $pdf->cell(30, $alt, db_formatar($total_prov, 'f'), "T", 0, "R", 0);
    }
    if (strpos("#" . $colunas, "d") + 0 > 0) {
        $pdf->cell(30, $alt, db_formatar($total_desc, 'f'), "T", 0, "R", 0);
    }
    if (strpos("#" . $colunas, "l") + 0 > 0) {
        $pdf->cell(30, $alt, db_formatar($total_liq, 'f'), "T", 0, "R", 0);
    }
}

$pdf->Output();
   
