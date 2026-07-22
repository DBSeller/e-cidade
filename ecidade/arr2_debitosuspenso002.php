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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_utils.php"));

$oGet = db_utils::postmemory($_GET);

switch ($oGet->selordem) {
    case "d":
        $orderby = "order by ar18_data, k00_numcgm, k00_matric, k00_inscr, k00_numpre, k00_numpar, k00_receit";
        $headOrdem = "ORDENADO POR DATA";
        break;
    case "c":
        $orderby = "order by k00_numcgm, k00_matric, k00_inscr, k00_numpre, k00_numpar, ar18_data, k00_receit";
        $headOrdem = "ORDENADO POR CGM";
        break;
    case "m":
        $orderby = "order by k00_matric, k00_numcgm, k00_inscr, k00_numpre, k00_numpar, ar18_data, k00_receit";
        $headOrdem = "ORDENADO POR MATRÍCULA";
        break;
    case "i":
        $orderby = "order by k00_inscr, k00_numcgm, k00_matric, k00_numpre, k00_numpar, ar18_data, k00_receit";
        $headOrdem = "ORDENADO POR INSCRIÇÃO";
        break;
}

$where = "";
if ($oGet->z01_numcgm) {

    $sInnerArrenumcgm = " inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre ";
    $where = " and arrenumcgm.k00_numcgm = " . $oGet->z01_numcgm;
}

if ($oGet->j01_matric) {
    $where .= "and k00_matric = " . $oGet->j01_matric;
}
if ($oGet->q02_inscr) {
    $where .= "and k00_inscr  = " . $oGet->q02_inscr;
}

if ($oGet->anulada == "s") {
    $where .= " and exists (select 1 
                                           from suspensaofinaliza
                              where ar19_suspensao = ar18_sequencial
                                and ar19_data <=  '{$oGet->dataf}') ";
} else if ($oGet->anulada == "n") {
    $where .= " and not exists (select 1 
                             from suspensaofinaliza
                            where ar19_suspensao = ar18_sequencial
                                and ar19_data <=  '{$oGet->dataf}') ";
}

if (isset($oGet->aTipoDebito)  && $oGet->aTipoDebito != "") {

    $where .= "and arretipo.k00_tipo in ($oGet->aTipoDebito) ";
}

$aCabecalho       = array();
$alt              = 5;
$fonte            = 8;
$iList            = 1;
$codSuspensao        = null;
$aCabecalho       = array();
$aTotalMatric     = array();
$aTotalInscr      = array();
$aTotalCgm        = array();
$aTotalExerc      = array();
$aTotalOrigem     = array();
$iNroMatric       = 0;
$iTotalParcial    = 0;
$iNroInscr        = 0;
$iNroCgm          = 0;
$SubTotalVlrHist  = 0;
$SubTotalVlrCorr  = 0;
$SubTotalVlrMulta = 0;
$SubTotalVlrJuros = 0;
$SubTotal         = 0;
$GeralVlrHist     = 0;
$GeralVlrCorr     = 0;
$GeralVlrMulta    = 0;
$GeralVlrJuros    = 0;
$GeralTotal       = 0;

$head2 = "RELATÓRIO DE DÉBITOS SUSPENSOS";
$head3 = "DE " . db_formatar($oGet->datai, "d") . " À " . db_formatar($oGet->dataf, "d");
$head4 = $headOrdem;
($oGet->seltipo == "c" ? $head5 = "TIPO: COMPLETO" : $head5 = "TIPO: RESUMIDO");

// select distinct
// arresusp.k00_suspensao, 
// (case when arrematric.k00_numpre = arresusp.k00_numpre then 'M' ||'-'||arrematric.k00_matric
//       when arreinscr.k00_numpre = arresusp.k00_numpre then 'I' ||'-'||arreinscr.k00_inscr
//       when arrenumcgm.k00_numpre = arresusp.k00_numpre then 'C' ||'-'||arrenumcgm.k00_numcgm
//                    end) as ORIGEM,
// (case when j01_numcgm is not null and m.z01_numcgm = j01_numcgm then m.z01_nome
//       when q02_numcgm is not null and i.z01_numcgm = q02_numcgm then i.z01_nome
//       when c.z01_numcgm = arrenumcgm.k00_numcgm and j01_numcgm is null 
//                                                 and q02_numcgm is null then C.z01_nome
//                    end) as NOME,
// sum(arresusp.k00_valor) as vlr_hist,
// sum(arresusp.k00_vlrcor) as vlr_cor,
// sum(arresusp.k00_vlrjur )as vlr_jur,
// sum(arresusp.k00_vlrmul) as vlr_mul,
// arresusp.k00_tipo as debito,
// arretipo.k00_descr as desc_tipo,
// ar18_usuario as usuario_inclusao,
// ar18_data as dt_incusao,
// a.nome as nome_us_inc,
// ar18_obs,
// ar19_data as dt_finaliza,
// ar19_id_usuario as usuario_finaliza,
// b.nome as nome_us_fin


// from arresusp 
// inner join suspensao on arresusp.k00_suspensao = ar18_sequencial
// inner join db_usuarios a on a.id_usuario = ar18_usuario 
// inner join arretipo on arresusp.k00_tipo = arretipo.k00_tipo
// left join arrematric on arrematric.k00_numpre = arresusp.k00_numpre
// left join iptubase on arrematric.k00_matric = j01_matric
// left join cgm m on m.z01_numcgm = j01_numcgm
// left join arreinscr on arreinscr.k00_numpre = arresusp.k00_numpre
// left join issbase on arreinscr.k00_inscr = q02_inscr
// left join cgm i on i.z01_numcgm = q02_numcgm
// left join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre
// left join cgm c on c.z01_numcgm = arrenumcgm.k00_numcgm
// left join suspensaofinaliza on ar19_suspensao = ar18_sequencial
// left join db_usuarios b on b.id_usuario = ar19_id_usuario 
// where ar18_data between $dtini and $dtfin
// group by
// arresusp.k00_suspensao, 
// arrematric.k00_numpre,
// arresusp.k00_numpre,
// arrematric.k00_matric,
// arreinscr.k00_numpre,
// arreinscr.k00_inscr,
// arrenumcgm.k00_numpre,
// arrenumcgm.k00_numcgm,
// j01_numcgm,
// m.z01_numcgm,
// m.z01_nome,
// q02_numcgm,
// i.z01_numcgm,
// i.z01_nome,
// c.z01_numcgm,
// c.z01_nome,
// arresusp.k00_tipo,
// arretipo.k00_descr,
// ar18_usuario,
// ar18_data,
// a.nome,
// ar18_obs,
// ar19_data,
// ar19_id_usuario,
// b.nome

$sqlSuspensao  = "  select  distinct                                                                            ";
$sqlSuspensao .= "          arresusp.k00_numcgm,                                                                         ";
$sqlSuspensao .= "          z01_nome,                                                                           ";
$sqlSuspensao .= "          extract(year from k00_dtoper) as k30_dtoper,                                        ";
$sqlSuspensao .= "          k00_matric,                                                                         ";
$sqlSuspensao .= "          k00_inscr,                                                                          ";
$sqlSuspensao .= "          ar18_data,                                                                           ";
$sqlSuspensao .= "          arresusp.k00_valor,                                                                          ";
$sqlSuspensao .= "          arresusp.k00_vlrcor,                                                                        ";
$sqlSuspensao .= "          arresusp.k00_vlrmul,                                                                          ";
$sqlSuspensao .= "          arresusp.k00_vlrjur,                                                                       ";
$sqlSuspensao .= "          arresusp.k00_dtvenc,                                                                         ";
$sqlSuspensao .= "          (arresusp.k00_vlrcor+arresusp.k00_vlrmul+arresusp.k00_vlrjur) as total,                                      ";
$sqlSuspensao .= "          arresusp.k00_numpre,                                                                         ";
$sqlSuspensao .= "          arresusp.k00_numpar,                                                                         ";
$sqlSuspensao .= "          arresusp.k00_receit,                                                                         ";
$sqlSuspensao .= "          k02_estorc,                                                                          ";
$sqlSuspensao .= "          o57_descr,                                                                          ";
$sqlSuspensao .= "          k02_descr,                                                                          ";
$sqlSuspensao .= "          ar18_sequencial,                                                                         ";
$sqlSuspensao .= "          ar18_obs,                                                                            ";
$sqlSuspensao .= "          k03_tipo,                                                                           ";
$sqlSuspensao .= "          k00_descr,                                                                          ";
$sqlSuspensao .= "          case 
                            when exists (select 1 
                                           from suspensaofinaliza
                              where ar19_suspensao = ar18_sequencial
                                and ar19_data <=  '{$oGet->dataf}')
                            then
                              't'::boolean
                            else
                              'f'::boolean
                            end as anulado,                                                                        ";
$sqlSuspensao .= "          login,                                                                              ";
$sqlSuspensao .= "          ( select ar19_data
                               from suspensaofinaliza
                              where ar19_suspensao = ar18_sequencial
                                and ar19_data <=  '{$oGet->dataf}'
                              order by ar19_data desc limit 1) as ar19_data                 ";
$sqlSuspensao .= "    from arresusp                                                                           ";
$sqlSuspensao .= "         inner join suspensao         on k00_suspensao            = ar18_sequencial              ";
$sqlSuspensao .= "                                     and ar18_instit            = " . db_getsession('DB_instit');

if ($oGet->z01_numcgm) {
    $sqlSuspensao .= $sInnerArrenumcgm;
}

$sqlSuspensao .= "         inner join arretipo           on arretipo.k00_tipo     = arresusp.k00_tipo                    ";
$sqlSuspensao .= "         inner join tabrec             on k02_codigo            = k00_receit                  ";
$sqlSuspensao .= "         inner join cgm                on z01_numcgm            = k00_numcgm                  ";
$sqlSuspensao .= "         inner join db_usuarios        on id_usuario            = ar18_usuario                 ";
$sqlSuspensao .= "         left  join taborc             on taborc.k02_codigo     = tabrec.k02_codigo           ";
$sqlSuspensao .= "                                      and taborc.k02_anousu     = " . db_getsession("DB_anousu");
$sqlSuspensao .= "           left join orcreceita        on taborc.k02_anousu = o70_anousu                      ";
$sqlSuspensao .= "                                      and k02_codrec = o70_codrec                             ";
$sqlSuspensao .= "           left join orcfontes         on o57_anousu = o70_anousu                      ";
$sqlSuspensao .= "                                      and o57_codfon = o70_codfon                             ";
$sqlSuspensao .= "         left  join arreinscr          on arreinscr.k00_numpre  = arresusp.k00_numpre                  ";
$sqlSuspensao .= "         left  join arrematric         on arrematric.k00_numpre = arresusp.k00_numpre                  ";
$sqlSuspensao .= "   where ar18_data between '" . $oGet->datai . "' and '" . $oGet->dataf . "'                           ";

$sqlSuspensao .= " $where   ";
$sqlSuspensao .= " $orderby ";

$rsSuspensao    = db_query($sqlSuspensao) or die($sqlSuspensao);
$iRowsSuspensao = pg_num_rows($rsSuspensao);

$aResumos = array();
$aAgrupaResumo['estrutural']  = 'k02_estorc';
$aAgrupaResumo['receita']     = 'k00_receit';
$aAgrupaResumo['tipo_debito'] = 'k03_tipo';

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage("L");

if ($iRowsSuspensao == 0) {

    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
    exit;
}

for ($iInd = 0; $iInd < $iRowsSuspensao; $iInd++) {

    $oSuspensao = db_utils::fieldsMemory($rsSuspensao, $iInd);

    foreach ($aAgrupaResumo as $sDescrAgrupa => $sCampo) {

        if ($sDescrAgrupa == 'proced') {
            $sDescricao = $oSuspensao->v03_descr;
        } else if ($sDescrAgrupa == 'estrutural') {
            $sDescricao = $oSuspensao->o57_descr;
        } else if ($sDescrAgrupa == 'receita') {
            $sDescricao = $oSuspensao->k02_descr;
        } else {
            $sDescricao = $oSuspensao->k00_descr;
        }
        
        
        if (isset($aResumos[$sDescrAgrupa][$oSuspensao->$sCampo])) {
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nVlrHist'] += $oSuspensao->k00_valor;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nVlrCorr'] += $oSuspensao->k00_vlrcor;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nMulta']   += $oSuspensao->k00_vlrmul;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nJuros']   += $oSuspensao->k00_vlrjur;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nTotal']   += $oSuspensao->total;
        } else {
            // die("{$oSuspensao->k00_vlrcor} - {$oSuspensao->k00_vlrmul} - {$oSuspensao->k00_vlrjur}");
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['sDescricao'] = $sDescricao;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nVlrHist']   = $oSuspensao->k00_valor;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nVlrCorr']   = $oSuspensao->k00_vlrcor;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nMulta']     = $oSuspensao->k00_vlrmul;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nJuros']     = $oSuspensao->k00_vlrjur;
            $aResumos[$sDescrAgrupa][$oSuspensao->$sCampo]['nTotal']     = $oSuspensao->total;
        }
    }

    if ($oGet->seltipo == "c") {

        if (in_array(array($oSuspensao->k00_numcgm, $oSuspensao->k00_matric, $oSuspensao->k00_inscr), $aCabecalho)) {
            $lImprimeCab = false;
            $lImprimeSubTotal = false;
        } else {
            $aCabecalho[0] = array($oSuspensao->k00_numcgm, $oSuspensao->k00_matric, $oSuspensao->k00_inscr);
            $lImprimeCab = true;
            $lImprimeSubTotal = true;
        }

        if ($oGet->selhist == "s" &&  $codSuspensao != $oSuspensao->ar18_sequencial && $iInd != 0) {
            $pdf->setfont('arial', 'i', 7);
            $pdf->cell(280, $alt, "Histórico : " . $obsSuspensao, 0, 1, "L", ($iList == 0 ? $iList = 1 : $iList = 0));
            $pdf->setfont('arial', '', $fonte);
        }

        if ($lImprimeSubTotal == true && $iInd != 0) {

            $pdf->ln(2);
            $pdf->setfont('arial', 'b', $fonte);
            $pdf->cell(20, $alt, "TOTAL : ", 0, 0, "L", 0);
            $pdf->cell(25, $alt, db_formatar($SubTotalVlrHist, "f"), 0, 0, "R", 0);
            $pdf->cell(25, $alt, db_formatar($SubTotalVlrCorr, "f"), 0, 0, "R", 0);
            $pdf->cell(25, $alt, db_formatar($SubTotalVlrMulta, "f"), 0, 0, "R", 0);
            $pdf->cell(25, $alt, db_formatar($SubTotalVlrJuros, "f"), 0, 0, "R", 0);
            $pdf->cell(25, $alt, db_formatar($SubTotal, "f"), 0, 1, "R", 0);

            $pdf->cell(30, $alt, "TOTAL DE REGISTROS:  ", 0, 0, "L", 0);
            $pdf->cell(25, $alt, $iTotalParcial, 0, 0, "C", 0);
            $pdf->setfont('arial', '', $fonte);
            $pdf->ln();

            $iTotalParcial = 0;

            if (trim($oSuspensao->k00_inscr) != "") {
                if (isset($aTotalOrigem["INSCRIÇÃO"])) {
                    $aTotalOrigem["INSCRIÇÃO"]['valor'] += $SubTotalVlrHist;
                    $aTotalOrigem["INSCRIÇÃO"]['vlrcor'] += $SubTotalVlrCorr;
                    $aTotalOrigem["INSCRIÇÃO"]['multa'] += $SubTotalVlrMulta;
                    $aTotalOrigem["INSCRIÇÃO"]['juros'] += $SubTotalVlrJuros;
                    $aTotalOrigem["INSCRIÇÃO"]['total'] += $SubTotal;
                } else {
                    $aTotalOrigem["INSCRIÇÃO"]['valor']  = $SubTotalVlrHist;
                    $aTotalOrigem["INSCRIÇÃO"]['vlrcor']  = $SubTotalVlrCorr;
                    $aTotalOrigem["INSCRIÇÃO"]['multa']  = $SubTotalVlrMulta;
                    $aTotalOrigem["INSCRIÇÃO"]['juros']  = $SubTotalVlrJuros;
                    $aTotalOrigem["INSCRIÇÃO"]['total']  = $SubTotal;
                }
            } else if (trim($oSuspensao->k00_matric) != "") {
                if (isset($aTotalOrigem["MATRÍCULA"])) {
                    $aTotalOrigem["MATRÍCULA"]['valor'] += $SubTotalVlrHist;
                    $aTotalOrigem["MATRÍCULA"]['vlrcor'] += $SubTotalVlrCorr;
                    $aTotalOrigem["MATRÍCULA"]['multa'] += $SubTotalVlrMulta;
                    $aTotalOrigem["MATRÍCULA"]['juros'] += $SubTotalVlrJuros;
                    $aTotalOrigem["MATRÍCULA"]['total'] += $SubTotal;
                } else {
                    $aTotalOrigem["MATRÍCULA"]['valor']  = $SubTotalVlrHist;
                    $aTotalOrigem["MATRÍCULA"]['vlrcor']  = $SubTotalVlrCorr;
                    $aTotalOrigem["MATRÍCULA"]['multa']  = $SubTotalVlrMulta;
                    $aTotalOrigem["MATRÍCULA"]['juros']  = $SubTotalVlrJuros;
                    $aTotalOrigem["MATRÍCULA"]['total']  = $SubTotal;
                }
            } else {
                if (isset($aTotalOrigem["CGM"])) {
                    $aTotalOrigem["CGM"]['valor'] += $SubTotalVlrHist;
                    $aTotalOrigem["CGM"]['vlrcor'] += $SubTotalVlrCorr;
                    $aTotalOrigem["CGM"]['multa'] += $SubTotalVlrMulta;
                    $aTotalOrigem["CGM"]['juros'] += $SubTotalVlrJuros;
                    $aTotalOrigem["CGM"]['total'] += $SubTotal;
                } else {
                    $aTotalOrigem["CGM"]['valor']  = $SubTotalVlrHist;
                    $aTotalOrigem["CGM"]['vlrcor']  = $SubTotalVlrCorr;
                    $aTotalOrigem["CGM"]['multa']  = $SubTotalVlrMulta;
                    $aTotalOrigem["CGM"]['juros']  = $SubTotalVlrJuros;
                    $aTotalOrigem["CGM"]['total']  = $SubTotal;
                }
            }

            $SubTotalVlrHist     = 0;
            $SubTotalVlrCorr     = 0;
            $SubTotalVlrMulta  = 0;
            $SubTotalVlrJuros  = 0;
            $SubTotal                   = 0;

            if ($iInd == $iRowsSuspensao) {

                $pdf->ln();
                $pdf->setfont('arial', 'b', 8);
                $pdf->cell(20, $alt, "GERAL : ", 0, 0, "L", 1);
                $pdf->cell(25, $alt, db_formatar($GeralVlrHist, "f"), 0, 0, "R", 1);
                $pdf->cell(25, $alt, db_formatar($GeralVlrCorr, "f"), 0, 0, "R", 1);
                $pdf->cell(25, $alt, db_formatar($GeralVlrMulta, "f"), 0, 0, "R", 1);
                $pdf->cell(25, $alt, db_formatar($GeralVlrJuros, "f"), 0, 0, "R", 1);
                $pdf->cell(25, $alt, db_formatar($GeralTotal, "f"), 0, 1, "R", 1);
                $pdf->cell(50, $alt, "TOTAL GERAL DE REGISTROS: ", 0, 0, "L", 0);
                $pdf->cell(10, $alt, $iRowsSuspensao, 0, 0, "C", 0);
                $pdf->ln();

                $pdf->cell(60, $alt, "", "T", 0, "C", 0);
                $pdf->cell(140, $alt, "TOTAL POR EXERCÍCIO", "T", 0, "C", 0);
                $pdf->cell(0, $alt, "", "T", 1, "C", 0);
                $pdf->cell(80, $alt, "", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Exercício", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Vlr Hist", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Vlr Corr", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Multa", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Juros", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Total", "T", 0, "C", 1);
                $pdf->cell(0, $alt, "", "T", 1, "C", 1);
                $pdf->ln(4);
                $pdf->setfont('arial', '', 8);

                $ValorExerc  = 0;
                $VlrCorExerc = 0;
                $MultaExerc  = 0;
                $JurosExerc  = 0;
                $TotalExerc  = 0;

                foreach ($aTotalExerc as $key => $aExercicio) {

                    $pdf->cell(80, $alt, "", 0, 0, "C", 0);
                    $pdf->cell(20, $alt, strtoupper($key), 0, 0, "C", 0);
                    $pdf->cell(20, $alt, db_formatar($aExercicio['valor'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aExercicio['vlrcor'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aExercicio['multa'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aExercicio['juros'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aExercicio['total'], "f"), 0, 1, "R", 0);

                    $ValorExerc  += $aExercicio['valor'];
                    $VlrCorExerc += $aExercicio['vlrcor'];
                    $MultaExerc  += $aExercicio['multa'];
                    $JurosExerc  += $aExercicio['juros'];
                    $TotalExerc  += $aExercicio['total'];


                    if ($pdf->gety() > $pdf->h - 30) {

                        $pdf->setfont('arial', 'b', 8);
                        $pdf->cell(60, $alt, "", "T", 0, "C", 0);
                        $pdf->cell(140, $alt, "TOTAL POR EXERCÍCIO", "T", 0, "C", 0);
                        $pdf->cell(0, $alt, "", "T", 1, "C", 0);
                        $pdf->cell(80, $alt, "", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Exercício", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Vlr Hist", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Vlr Corr", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Multa", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Juros", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Total", "T", 0, "C", 1);
                        $pdf->cell(0, $alt, "", "T", 1, "C", 1);
                        $pdf->ln(2);
                        $pdf->setfont('arial', '', 8);
                    }
                }
                $pdf->setx(55);
                $pdf->cell(55, $alt, "", 0, 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($ValorExerc, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($VlrCorExerc, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($MultaExerc, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($JurosExerc, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($TotalExerc, "f"), "T", 1, "R", 0);

                $pdf->ln();
                $pdf->setfont('arial', 'b', 8);
                $pdf->cell(60, $alt, "", "T", 0, "C", 0);
                $pdf->cell(140, $alt, "TOTAL POR ORIGEM", "T", 0, "C", 0);
                $pdf->cell(0, $alt, "", "T", 1, "C", 0);
                $pdf->cell(50, $alt, "", "T", 0, "C", 1);
                $pdf->cell(50, $alt, "Origem", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Vlr Hist", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Vlr Corr", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Multa", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Juros", "T", 0, "C", 1);
                $pdf->cell(20, $alt, "Total", "T", 0, "C", 1);
                $pdf->cell(0, $alt, "", "T", 1, "C", 1);
                $pdf->ln(2);
                $pdf->setfont('arial', '', 8);

                $ValorOrig  = 0;
                $VlrCorOrig = 0;
                $MultaOrig  = 0;
                $JurosOrig  = 0;
                $TotalOrig  = 0;

                foreach ($aTotalOrigem as $key => $aOrigem) {
                    $pdf->cell(50, $alt, "", 0, 0, "C", 0);
                    $pdf->cell(50, $alt, strtoupper($key), 0, 0, "L", 0);
                    $pdf->cell(20, $alt, db_formatar($aOrigem['valor'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aOrigem['vlrcor'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aOrigem['multa'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aOrigem['juros'], "f"), 0, 0, "R", 0);
                    $pdf->cell(20, $alt, db_formatar($aOrigem['total'], "f"), 0, 1, "R", 0);

                    $ValorOrig  += $aOrigem['valor'];
                    $VlrCorOrig += $aOrigem['vlrcor'];
                    $MultaOrig  += $aOrigem['multa'];
                    $JurosOrig  += $aOrigem['juros'];
                    $TotalOrig  += $aOrigem['total'];

                    if ($pdf->gety() > $pdf->h - 30) {
                        $pdf->addpage("L");
                        $pdf->setfont('arial', 'b', 8);
                        $pdf->cell(60, $alt, "", "T", 0, "C", 0);
                        $pdf->cell(140, $alt, "TOTAL POR ORIGEM", "T", 0, "C", 0);
                        $pdf->cell(0, $alt, "", "T", 1, "C", 0);
                        $pdf->cell(50, $alt, "", "T", 0, "C", 1);
                        $pdf->cell(50, $alt, "Origem", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Vlr Hist", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Vlr Corr", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Multa", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Juros", "T", 0, "C", 1);
                        $pdf->cell(20, $alt, "Total", "T", 0, "C", 1);
                        $pdf->cell(0, $alt, "", "T", 1, "C", 1);
                        $pdf->ln(2);
                        $pdf->setfont('arial', '', 8);
                    }
                }

                $pdf->setx(55);
                $pdf->cell(55, "", 0, 1, "R", 0);
                $pdf->cell(20, $alt, db_formatar($ValorOrig, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($VlrCorOrig, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($MultaOrig, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($JurosOrig, "f"), "T", 0, "R", 0);
                $pdf->cell(20, $alt, db_formatar($TotalOrig, "f"), "T", 1, "R", 0);

                $pdf->setfont('arial', '', $fonte);
                $pdf->ln();
                continue;
            }
        }

        if ($lImprimeCab == true) {

            $pdf->ln();
            $pdf->setfont('arial', 'b', $fonte);
            $pdf->cell(20, $alt, "CGM", 1, 0, "C", 1);
            $pdf->cell(80, $alt, "Nome/ Razão Social", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Matrícula", 1, 0, "C", 1);
            $pdf->cell(30, $alt, "Inscrição", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Dt Suspensao", 1, 1, "C", 1);

            $pdf->setfont('arial', '', $fonte);
            $pdf->cell(20, $alt, $oSuspensao->k00_numcgm, 0, 0, "C", 0);
            $pdf->cell(80, $alt, $oSuspensao->z01_nome, 0, 0, "L", 0);
            $pdf->cell(20, $alt, $oSuspensao->k00_matric, 0, 0, "C", 0);
            $pdf->cell(30, $alt, $oSuspensao->k00_inscr, 0, 0, "C", 0);
            $pdf->cell(20, $alt, db_formatar($oSuspensao->ar18_data, "d"), 0, 1, "C", 0);
        }

        if ($pdf->gety() > $pdf->h - 30 || $lImprimeCab == true) {
            if ($pdf->gety() > $pdf->h - 30) {
                $pdf->addpage("L");
            }
            $pdf->setfont('arial', 'b', $fonte);
            $pdf->cell(20, $alt, "Dt Venc", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Vlr Hist", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Vlr Corr", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Multa", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Juros", 1, 0, "C", 1);
            $pdf->cell(25, $alt, "Total", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Numpre", 1, 0, "C", 1);
            $pdf->cell(15, $alt, "Numpar", 1, 0, "C", 1);
            $pdf->cell(15, $alt, "Receita", 1, 0, "C", 1);
            $pdf->cell(25, $alt, "Login", 1, 0, "C", 1);
            $pdf->cell(20, $alt, "Anulada", 1, 1, "C", 1);
            $iList = 1;
        }

        ($iList == 0 ? $iList = 1 : $iList = 0);

        $pdf->setfont('arial', '', $fonte);
        $pdf->cell(20, $alt, db_formatar($oSuspensao->k00_dtvenc, "d"), 0, 0, "C", $iList);
        $pdf->cell(20, $alt, db_formatar($oSuspensao->k00_valor, "f"), 0, 0, "R", $iList);
        $pdf->cell(20, $alt, db_formatar($oSuspensao->k00_vlrcor, "f"), 0, 0, "R", $iList);
        $pdf->cell(20, $alt, db_formatar($oSuspensao->k00_vlrmul, "f"), 0, 0, "R", $iList);
        $pdf->cell(20, $alt, db_formatar($oSuspensao->k00_vlrjur, "f"), 0, 0, "R", $iList);
        $pdf->cell(25, $alt, db_formatar($oSuspensao->total, "f"), 0, 0, "R", $iList);
        $pdf->cell(20, $alt, $oSuspensao->k00_numpre, 0, 0, "C", $iList);
        $pdf->cell(15, $alt, $oSuspensao->k00_numpar, 0, 0, "C", $iList);
        $pdf->cell(15, $alt, $oSuspensao->k00_receit, 0, 0, "C", $iList);
        $pdf->cell(25, $alt, $oSuspensao->login, 0, 0, "C", $iList);

        // echo "<pre>"; 
        // var_dump($oSuspensao);

        // die();
        $pdf->cell(20, $alt, ($oSuspensao->anulado == "f" ? "Não" : db_formatar($oSuspensao->ar19_data, 'd')), 0, 1, "C", $iList);
        $iTotalParcial++;
    }

    if ($iInd == $iRowsSuspensao) {
        continue;
    }

    $SubTotalVlrHist        += $oSuspensao->k00_valor;
    $SubTotalVlrCorr        += $oSuspensao->k00_vlrcor;
    $SubTotalVlrMulta       += $oSuspensao->k00_vlrmul;
    $SubTotalVlrJuros       += $oSuspensao->k00_vlrjur;
    $SubTotal               += $oSuspensao->total;

    $GeralVlrHist           += $oSuspensao->k00_valor;
    $GeralVlrCorr           += $oSuspensao->k00_vlrcor;
    $GeralVlrMulta          += $oSuspensao->k00_vlrmul;
    $GeralVlrJuros          += $oSuspensao->k00_vlrjur;
    $GeralTotal             += $oSuspensao->total;

    if (isset($aTotalExerc[$oSuspensao->k00_dtoper])) {

        $aTotalExerc[$oSuspensao->k00_dtoper]['valor']  += $oSuspensao->k00_valor;
        $aTotalExerc[$oSuspensao->k00_dtoper]['vlrcor'] += $oSuspensao->k00_vlrcor;
        $aTotalExerc[$oSuspensao->k00_dtoper]['multa']  += $oSuspensao->k00_vlrmul;
        $aTotalExerc[$oSuspensao->k00_dtoper]['juros']  += $oSuspensao->k00_vlrjur;
        $aTotalExerc[$oSuspensao->k00_dtoper]['total']  += $oSuspensao->total;
    } else {
        $aTotalExerc[$oSuspensao->k00_dtoper]['valor']  = $oSuspensao->k00_valor;
        $aTotalExerc[$oSuspensao->k00_dtoper]['vlrcor'] = $oSuspensao->k00_vlrcor;
        $aTotalExerc[$oSuspensao->k00_dtoper]['multa']  = $oSuspensao->k00_vlrmul;
        $aTotalExerc[$oSuspensao->k00_dtoper]['juros']  = $oSuspensao->k00_vlrjur;
        $aTotalExerc[$oSuspensao->k00_dtoper]['total']  = $oSuspensao->total;
    }

    if (!in_array($oSuspensao->k00_matric, $aTotalMatric) && $oSuspensao->k00_matric != "") {
        $aTotalMatric[$iNroMatric] = $oSuspensao->k00_matric;
        $iNroMatric++;
    }
    if (!in_array($oSuspensao->k00_inscr, $aTotalInscr) && $oSuspensao->k00_inscr != "") {
        $aTotalInscr[$iNroInscr] = $oSuspensao->k00_inscr;
        $iNroInscr++;
    }
    if (!in_array($oSuspensao->k00_numcgm, $aTotalCgm) && $oSuspensao->k00_matric == "" && $oSuspensao->k00_inscr == "") {
        $aTotalCgm[$iNroCgm] = $oSuspensao->k00_numcgm;
        $iNroCgm++;
    }

    if ($codSuspensao != $oSuspensao->ar18_sequencial) {
        $obsSuspensao = $oSuspensao->ar18_obs;
        $codSuspensao = $oSuspensao->ar18_sequencial;
    }
}

if ($iRowsSuspensao > 0 &&  $oGet->seltipo == "c") {
    $pdf->cell(280, $alt, "Histórico : " . $obsSuspensao, 0, 1, "L", ($iList == 0 ? $iList = 1 : $iList = 0));
}

$iNroReg = $iRowsSuspensao;

if ($oGet->seltipo == "c") {
    $pdf->addpage("L");
}

$pdf->sety(35);
$pdf->setfont('arial', 'b', $fonte);
$pdf->cell(30, $alt, "TOTAL DE REGISTROS  : ", 0, 1, "L", 0);
$pdf->cell(30, $alt, "TOTAL DE MATRÍCULAS : ", 0, 1, "L", 0);
$pdf->cell(30, $alt, "TOTAL DE INSCRIÇÕES : ", 0, 1, "L", 0);
$pdf->cell(30, $alt, "TOTAL SOMENTE CGM   : ", 0, 1, "L", 0);
$pdf->ln();

$pdf->sety(35);
$pdf->setfont('arial', '', $fonte);
$pdf->cell(45, $alt, $iNroReg, 0, 1, "R", 0);
$pdf->cell(45, $alt, $iNroMatric, 0, 1, "R", 0);
$pdf->cell(45, $alt, $iNroInscr, 0, 1, "R", 0);
$pdf->cell(45, $alt, $iNroCgm, 0, 1, "R", 0);
$pdf->ln(2);

$pdf->setfont('arial', 'b', $fonte);
$pdf->cell(60, $alt, "", "T", 0, "C", 0);
$pdf->cell(140, $alt, "TOTAL DE DÉBITOS SUSPENSOS", "T", 0, "C", 0);
$pdf->cell(0, $alt, "", "T", 1, "C", 0);
$pdf->cell(100, $alt, "", "T", 0, "C", 1);
$pdf->cell(20, $alt, "Vlr Hist", "T", 0, "C", 1);
$pdf->cell(20, $alt, "Vlr Corr", "T", 0, "C", 1);
$pdf->cell(20, $alt, "Multa", "T", 0, "C", 1);
$pdf->cell(20, $alt, "Juros", "T", 0, "C", 1);
$pdf->cell(20, $alt, "Total", "T", 0, "C", 1);
$pdf->cell(0, $alt, "", "T", 1, "C", 1);
$pdf->ln(2);
$pdf->setx(70);
$pdf->cell(40, $alt, "TOTAL GERAL : ", 0, 0, "L", 0);
$pdf->cell(20, $alt, db_formatar($GeralVlrHist, "f"), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($GeralVlrCorr, "f"), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($GeralVlrMulta, "f"), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($GeralVlrJuros, "f"), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($GeralTotal, "f"), 0, 1, "R", 0);
$pdf->ln();

$pdf->AddPage("L");

$pdf->Ln(6);


foreach ($aAgrupaResumo as $sTipoAgrupa => $sCampo) {

    $nTotalHistResumo  = 0;
    $nTotalCorrResumo  = 0;
    $nTotalMultaResumo = 0;
    $nTotalJurosResumo = 0;
    $nTotalResumo      = 0;

    if ($sTipoAgrupa == "receita") {
        $sTituloAgrupa = "Receita";
    } else if ($sTipoAgrupa == "estrutural") {
        $sTituloAgrupa = "Estrutural";
    } else {
        $sTituloAgrupa = "Tipo de Débito";
    }

    $pdf->SetFont('Arial', 'B', $fonte);
    $pdf->Cell(215, $alt, "Resumo por {$sTituloAgrupa}", 1, 1, 'L', 1);
    $pdf->Cell(25, $alt, 'Código', 1, 0, 'C', 1);
    $pdf->Cell(90, $alt, 'Descrição', 1, 0, 'C', 1);
    $pdf->Cell(20, $alt, 'Vlr Histórico', 1, 0, 'C', 1);
    $pdf->Cell(20, $alt, 'Vlr Corrigido', 1, 0, 'C', 1);
    $pdf->Cell(20, $alt, 'Vlr Multa', 1, 0, 'C', 1);
    $pdf->Cell(20, $alt, 'Vlr Juros', 1, 0, 'C', 1);
    $pdf->Cell(20, $alt, 'Total', 1, 1, 'C', 1);

    foreach ($aResumos[$sTipoAgrupa] as $iCodResumo => $aValoresResumo) {

        $pdf->SetFont('Arial', '', $fonte);
        $pdf->Cell(25, $alt, $iCodResumo, 1, 0, 'C', 0);
        $pdf->Cell(90, $alt, $aValoresResumo['sDescricao'], 1, 0, 'L', 0);
        $pdf->Cell(20, $alt, db_formatar($aValoresResumo['nVlrHist'], 'f'), 1, 0, 'R', 0);
        $pdf->Cell(20, $alt, db_formatar($aValoresResumo['nVlrCorr'], 'f'), 1, 0, 'R', 0);
        $pdf->Cell(20, $alt, db_formatar($aValoresResumo['nMulta'], 'f'), 1, 0, 'R', 0);
        $pdf->Cell(20, $alt, db_formatar($aValoresResumo['nJuros'], 'f'), 1, 0, 'R', 0);
        $pdf->Cell(20, $alt, db_formatar($aValoresResumo['nTotal'], 'f'), 1, 1, 'R', 0);

        $nTotalHistResumo  += $aValoresResumo['nVlrHist'];
        $nTotalCorrResumo  += $aValoresResumo['nVlrCorr'];
        $nTotalMultaResumo += $aValoresResumo['nMulta'];
        $nTotalJurosResumo += $aValoresResumo['nJuros'];
        $nTotalResumo      += $aValoresResumo['nTotal'];
    }

    $pdf->SetFont('Arial', 'B', $fonte);
    $pdf->Cell(25, $alt, 'Total:', 1, 0, 'R', 0);
    $pdf->Cell(90, $alt, '', 1, 0, 'L', 0);
    $pdf->Cell(20, $alt, db_formatar($nTotalHistResumo, 'f'), 1, 0, 'R', 0);
    $pdf->Cell(20, $alt, db_formatar($nTotalCorrResumo, 'f'), 1, 0, 'R', 0);
    $pdf->Cell(20, $alt, db_formatar($nTotalMultaResumo, 'f'), 1, 0, 'R', 0);
    $pdf->Cell(20, $alt, db_formatar($nTotalJurosResumo, 'f'), 1, 0, 'R', 0);
    $pdf->Cell(20, $alt, db_formatar($nTotalResumo, 'f'), 1, 1, 'R', 0);

    $pdf->Ln(3);
}

$pdf->Output();