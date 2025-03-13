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

require_once modification("fpdf151/pdf.php");
require_once modification("libs/db_sql.php");
require_once modification("libs/db_utils.php");

$clpcorcam = new cl_pcorcam();
$clpcorcamforne = new cl_pcorcamforne();
$clpcorcamitem = new cl_pcorcamitem();
$clpcorcamval = new cl_pcorcamval();
$clliclicita = new cl_liclicita();
$clpcorcamdescla = new cl_pcorcamdescla();
$clpcorcamtroca = new cl_pcorcamtroca();
$clliclicitemanu = new cl_liclicitemanu();
$clempparametro = new cl_empparametro();

$clrotulo = new rotulocampo();
$clrotulo->label('');

parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
//echo $HTTP_SERVER_VARS['QUERY_STRING'];

/**
 * Printa os valores de um material solicitado
 *
 * @param mixed $result_valor O resultado de uma db_query procurando os valores
 * @param string $campoValor Qual campo deverá ser usado na hora de mostrar o valor
 * @param int $alt A altura de cada célula
 * @param bool $fundoEscuro Se a célula deve ter um fundo escuro
 * @param PDF $pdf O pdf onde serão escritos os valores
 *
 * @return int A soma total dos valores das propostas que tiverem $pc24_pontuacao == 1
 */
function printValoresItem($result_valor, $campoValor, $altura, $fundoEscuro, PDF $pdf)
{
    $valorTotal = 0;

    $xOriginnal = $pdf->GetRightMargin();

    foreach(pg_fetch_all($result_valor) as $resultadoValor) {
        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pontuacao = $resultadoValor['pc24_pontuacao'];
        $pc23_valor = $resultadoValor['pc23_valor'];
        $valor = $resultadoValor[$campoValor];

        if ($pontuacao == 1) {
            $valorprint = getValorPrint($valor);
            $pdf->cell(21, $altura,$valorprint , 0, 1, 'R', $fundoEscuro);
            $valorTotal += $valor;

            $pdf->SetXY($x, $y + $altura);
        } else {
            $valorprint = getValorPrint($pc23_valor);
            $pdf->cell(21, $altura, $valorprint, 0, 1, 'R', $fundoEscuro);
        }

        if (empty($xOriginnal)) {
            $xOriginnal = $pdf->GetX();
        }
    }

    $pdf->SetX($xOriginnal);

    return $valorTotal;
}

function getValorPrint($valor)
{
    $valorprint = db_formatar($valor, 'f');
    if (isParaiba()) {
        $valorprint = db_formatar($valor, 'f', " ", 0, "e", 4);
    }
    return $valorprint;
}

$iAnoUsu = db_getsession('DB_anousu');
$sSqlEmpParam = $clempparametro->sql_query($iAnoUsu, "e30_numdec");
$rsSqlEmpParam = $clempparametro->sql_record($sSqlEmpParam);
if ($clempparametro->numrows > 0) {

  $oEmpParam = db_utils::fieldsMemory($rsSqlEmpParam, 0);
  $iCasasDecimais = $oEmpParam->e30_numdec;
} else {
  $iCasasDecimais = 2;
}
if(isParaiba()){
    $iCasasDecimais = 4;
}

$result_info = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null, "distinct l21_codliclicita,pc22_codorc,l20_tipojulg", null, "l21_codliclicita=$l20_codigo and l20_instit = " . db_getsession("DB_instit")));
if ($clpcorcamitem->numrows > 0) {
  db_fieldsmemory($result_info, 0);
}
$result = $clliclicita->sql_record($clliclicita->sql_query($l20_codigo));
if ($clliclicita->numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
  exit();
}

if (isset($imp_descla) && $imp_descla == "S") {
  if ($l20_tipojulg == 1) {
    $campos = "l04_descricao,l21_ordem,z01_nome,pc32_motivo,pc01_codmater,pc01_descrmater";
    $agrupar = "group by l04_descricao,l21_ordem,z01_nome,pc32_motivo,pc01_codmater,pc01_descrmater";
  } else {
    $campos = "l04_descricao,l21_ordem,z01_nome,pc32_motivo";
    $agrupar = "group by l04_descricao,l21_ordem,z01_nome,pc32_motivo";
  }

  $res_descla = $clpcorcamdescla->sql_record($clpcorcamdescla->sql_query_descla_lote(null, null, $campos, "l21_ordem,l04_descricao,z01_nome", "l21_codliclicita = $l20_codigo $agrupar"));
}

if (isset($imp_troca) && $imp_troca == "S") {
  $sql_troca = "select pc01_codmater, pc01_descrmater, pc25_motivo, l04_descricao, nome_julgado, nome_trocado, l21_ordem
                   from (select distinct on(pc25_orcamitem) pc25_orcamitem, pc25_codtroca, pc01_codmater, pc01_descrmater,
                                                            pc25_motivo, l04_descricao, cgm.z01_nome as nome_julgado,
                                                            cgm_ant.z01_nome as nome_trocado, l21_ordem
                         from pcorcamtroca
                              inner join pcorcamjulg              on pcorcamjulg.pc24_orcamitem      = pcorcamtroca.pc25_orcamitem
                              inner join pcorcamforne             on pcorcamforne.pc21_orcamforne    = pcorcamjulg.pc24_orcamforne
                              inner join cgm                      on cgm.z01_numcgm                  = pcorcamforne.pc21_numcgm
                              inner join pcorcamitemlic           on pcorcamitemlic.pc26_orcamitem   = pcorcamtroca.pc25_orcamitem
                              inner join liclicitem               on liclicitem.l21_codigo           = pcorcamitemlic.pc26_liclicitem
                              inner join liclicitemlote           on liclicitemlote.l04_liclicitem   = liclicitem.l21_codigo
                              inner join pcprocitem               on pcprocitem.pc81_codprocitem     = liclicitem.l21_codpcprocitem
                              inner join solicitem                on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem
                              inner join solicitempcmater         on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                              inner join pcmater                  on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater
                              left  join pcorcamforne as forneant on forneant.pc21_orcamforne        = pcorcamtroca.pc25_forneant
                              left  join cgm as cgm_ant           on cgm_ant.z01_numcgm              = forneant.pc21_numcgm
                              left  join pcorcamval               on pcorcamval.pc23_orcamitem       = pcorcamjulg.pc24_orcamitem
                              left  join pcorcamdescla            on pcorcamdescla.pc32_orcamitem    = pcorcamval.pc23_orcamitem and
                                                                     pcorcamdescla.pc32_orcamforne   = pcorcamval.pc23_orcamforne
                         where l21_situacao     = 0           and
                               l21_codliclicita = $l20_codigo and
                               pc24_pontuacao   = 1           and
                               pc32_orcamitem is null         and
                               pc32_orcamforne is null
                         order by pc25_orcamitem desc, pc25_codtroca desc) as x
                   order by l21_ordem,pc01_descrmater";

  //   echo $sql_troca; exit;
  $res_troca = $clpcorcamtroca->sql_record($sql_troca);
}

if (isset($imp_lote) && $imp_lote == "S") {
  $res_lote = $clliclicitemanu->sql_record($clliclicitemanu->sql_query_anu(null, "l21_ordem,pc01_codmater,pc01_descrmater,l04_descricao,l07_motivo", "l21_ordem,pc01_descrmater", "l20_codigo = $l20_codigo"));
}

db_fieldsmemory($result, 0);
$head3 = "Orçamento: " . @$pc22_codorc;
$head5 = "Licitacao: $l20_numero/" . substr($l20_datacria, 0, 4);
$orcamento = @$pc22_codorc;
//$modelo=2;
if ($modelo == 1) {

  //-----------------------------  MODELO 1  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 1  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 1  -----------------------------------------------------------------------------------------------------------------//
  $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null, "*", null, "pc21_codorc=$orcamento"));
  $numrows_forne = $clpcorcamforne->numrows;
  if ($numrows_forne == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Fornecedores cadastrados.');
  }
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 8);
  $troca = 1;
  $alt = 6;
  $total = 0;
  $p = 0;
  $max_itens = 0;
  $max = false;
  $quant_imp = 0;
  for($x = 0; $x < $numrows_forne; $x ++) {
    db_fieldsmemory($result_forne, $x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {

      if ($pdf->gety() > $pdf->h - 30 || $max == false) {
        $pdf->addpage('L');
        if ($max) {

          $max_itens -= 11;
        }
      }

      $p = 0;
      $pdf->setfont('arial', 'b', 9);
      $pdf->cell(60, $alt, "Fornecedor", 1, 0, "C", 1);
      $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null, "distinct l21_ordem,
	                                          pc22_orcamitem,
	                                          pc01_descrmater,
                                            pc54_formacontrole", "l21_ordem", "pc22_codorc=$orcamento"));
      $numrows_itens = $clpcorcamitem->numrows;
      if ($numrows_itens > $max_itens + 11) {

        $max_itens = $max_itens + 11;
        $max = true;

      } else {

        $max = false;
        $max_itens = $numrows_itens;

      }

      $t = 0;
      for($w = $quant_imp; $w < $max_itens; $w ++) {

        db_fieldsmemory($result_itens, $w);
        if ($w == ($max_itens - 1)) {
          $t = 1;
        }
        $pdf->cell(20, $alt, $l21_ordem, 1, $t, "C", 1);
      }
      $troca = 0;
    }
    $alt = 4;
    $pdf->setfont('arial', '', 7);
    $pdf->cell(60, $alt, substr($z01_nome, 0, 40), 0, 0, "L", $p);
    $t = 0;
    $cont_quant = 0;
    for($w = $quant_imp; $w < $max_itens; $w ++) {
      db_fieldsmemory($result_itens, $w);

      $lControlaRegistroPrecoValor = ($pc54_formacontrole == aberturaRegistroPreco::CONTROLA_VALOR);

      $pdf->setfont('arial', '', 7);
      if ($w == ($max_itens - 1)) {
        $t = 1;
      }
      $result_valor = $clpcorcamval->sql_record($clpcorcamval->sql_query_julg(null, null, "pc23_valor,pc24_pontuacao, pc23_percentualdesconto", null, "pc23_orcamforne=$pc21_orcamforne and pc23_orcamitem=$pc22_orcamitem"));
      if ($clpcorcamval->numrows > 0) {
        db_fieldsmemory($result_valor, 0);
        if ($pc24_pontuacao == 1) {
          $pdf->setfont('arial', 'b', 8);
        }
        $valorcell = getValorPrint(($lControlaRegistroPrecoValor ? @$pc23_percentualdesconto : @$pc23_valor));
        $pdf->cell(20, $alt, $valorcell, 0, $t, "R", $p);
      } else {
        $pdf->cell(20, $alt, "0,00", 0, $t, "R", $p);
      }
      $cont_quant ++;
    }
    if ($x == $numrows_forne - 1 && $max == true) {
      $quant_imp = $cont_quant + $quant_imp;
    }
    if ($x == $numrows_forne - 1 && $max == true) {

      $x = - 1;
      $troca = 1;
      $total = 0;
      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(280, $alt, '	', "T", 1, "L", 0);
    }

    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }
    $total ++;
  }
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(60 + 20 * $cont_quant, $alt, 'TOTAL DE FORNECEDORES  :  ' . $numrows_forne, "T", 1, "L", 0);
  $pdf->ln();
  $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null, "distinct l21_ordem,pc22_orcamitem,pc01_descrmater,pc11_resum, pc11_numero", "l21_ordem", "pc22_codorc=$orcamento"));
  $numrows_itens = $clpcorcamitem->numrows;
  $p = 0;
  $troca = 1;
  $alt = 4;
  $valor_tot = 0;

  for($x = 0; $x < $numrows_itens; $x ++) {

    db_fieldsmemory($result_itens, $x);

    $lControlaRegistroPrecoValor = ($pc54_formacontrole == aberturaRegistroPreco::CONTROLA_VALOR);

    if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {

      if ($pdf->gety() > $pdf->h - 30) {

        $pdf->addpage('L');

      }

      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(20, $alt, "ITEM", 1, 0, "C", 1);
      $pdf->cell(100, $alt, "MATERIAL", 1, 0, "C", 1);
      $pdf->cell(20, $alt, "SOLICITAÇÃO", 1, 0, "C", 1);
      $pdf->cell(21, $alt, ($lControlaRegistroPrecoValor ? "DESCONTO %" : "VALOR"), 1, 1, "C", 1);

      $p = 0;
      $troca = 0;

    }

    $pdf->setfont('arial', '', 7);
    $pdf->cell(20, $alt, $l21_ordem, 0, 0, "C", $p);
    $pdf->cell(100, $alt, substr($pc01_descrmater . " - " . $pc11_resum, 0, 65), 0, 0, "L", $p);
    $result_valor = $clpcorcamval->sql_record($clpcorcamval->sql_query_julg(null, null, "pc23_valor,pc24_pontuacao, pc23_percentualdesconto", null, "pc23_orcamitem=$pc22_orcamitem
	                                                                          and pc24_pontuacao=1"));

    $pdf->cell(20, $alt, $pc11_numero, 0, 0, "C", $p);

    $sCampoValor = ($lControlaRegistroPrecoValor ? "pc23_percentualdesconto" : "pc23_valor");

    if ($clpcorcamval->numrows > 0) {
        $valor_tot += printValoresItem($result_valor, $sCampoValor, $alt, $p, $pdf);
    } else {
        $pdf->cell(21, $alt, db_formatar('0', 'f'), 0, 1, 'R', $p);
    }

    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }
  }

  if (!$lControlaRegistroPrecoValor) {
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(140, $alt, 'TOTAL :', "T", 0, "R", 0);
    $valorTotalPrint = getValorPrint(@$valor_tot);
    $pdf->cell(20, $alt, $valorTotalPrint, "T", 1, "R", 0);
  }

} else if ($modelo == 2) {

  //-----------------------------  MODELO 2  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 2  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 2  -----------------------------------------------------------------------------------------------------------------//
  $result_itens = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterlic(null, "distinct pc22_orcamitem,pc01_descrmater,pc11_resum,pc11_quant,m61_descr,l21_ordem, pc11_numero", "l21_ordem", "pc22_codorc=$orcamento"));
  $numrows_itens = $clpcorcamitem->numrows;
  if ($numrows_itens == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem itens cadastrados.');
  }
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setdrawcolor(0);
  $pdf->setfont('arial', 'b', 9);
  $troca = 1;
  $alt = 6;
  $total = 0;
  $p = 0;
  $max_forne = 0;
  $max = false;
  $quant_imp = 0;
  $valor_total = 0;

  $arr_subtotganhoun = array ();
  $arr_subtotcotadoun = array ();

  $arr_subtotganhovlr = array ();
  $arr_subtotcotadovlr = array ();

  $arr_totalganho = array ();
  $arr_totalcotado = array ();

  $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null, "*", null, "pc21_codorc=$orcamento"));
  $numrows_forne = $clpcorcamforne->numrows;

  for($i = 0; $i < $numrows_forne; $i ++) {
    db_fieldsmemory($result_forne, $i);

    $arr_subtotganhoun [$i] = 0;
    $arr_subtotcotadoun [$i] = 0;

    $arr_subtotganhovlr [$i] = 0;
    $arr_subtotcotadovlr [$i] = 0;

    $arr_totalganho [$pc21_orcamforne] = 0;
    $arr_totalcotado [$pc21_orcamforne] = 0;
  }

  $total_quant = 0;

  for($i = 0; $i < $numrows_itens; $i ++) {
    db_fieldsmemory($result_itens, $i);
    $total_quant += $pc11_quant;
  }

  for($x = 0; $x < $numrows_itens; $x ++) {
    db_fieldsmemory($result_itens, $x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {
      if ($pdf->gety() > $pdf->h - 30 || $max == false) {
        $pdf->addpage('L');
      }

      $p = 0;

      /*
       * monta cabeçalho
      */

      $alturaCelulaCabecalho = 6;
      $larguraHeaderItem = 10;
      $larguraHeaderSolicitacao = 20;
      $larguraHeaderDescrProduto = 54;
      $larguraHeaderUnidade = 20;
      $larguraHeaderQuantidade = 15;
      $larguraHeaderValorUnitario = $imp_vlrun == "S" ? 20 : 0;
      $larguraHeaderFornecedor = 15;
      $larguraHeaderObservacao = 45;
      $posicaoXSegundoFornecedor = 90;

      if ($imp_vlrun == "N") {
          $posicaoXSegundoFornecedor = $posicaoXSegundoFornecedor - 20;
      }

      $pdf->setfont('arial', 'b', 9);
      $pdf->cell($larguraHeaderItem, $alturaCelulaCabecalho, "Item", 1, 0, "C", 1);
      $pdf->cell($larguraHeaderSolicitacao, $alturaCelulaCabecalho, "Solicitação", 1, 0, "C", 1);
      $pdf->cell($larguraHeaderDescrProduto, $alturaCelulaCabecalho, "Descr. Produto", 1, 0, "C", 1);
      $pdf->cell($larguraHeaderUnidade, $alturaCelulaCabecalho, "Unidade", 1, 0, "C", 1);
      $pdf->cell($larguraHeaderQuantidade, $alturaCelulaCabecalho, "Quant.", 1, 0, "C", 1);

      if ($troca != 0) {

        if ($numrows_forne > $max_forne + 2) {
          $max_forne = $max_forne + 2;
          $max = true;
        } else {
          $max = false;
          $max_forne = $numrows_forne;
        }
      }

      $t = 0;
      $condicaoPosicao = 0;
      for($w = $quant_imp; $w < $max_forne; $w ++) {

        db_fieldsmemory($result_forne, $w);

        if ($pdf->gety() > $pdf->h - 30) {

          $t = 1;

        }

        if ($w == ($max_forne -1)) {

          $t = 1;
        }

        if ($imp_vlrun == "S") {
            $pdf->cell($larguraHeaderValorUnitario, $alturaCelulaCabecalho, "Vlr. Un.", 1, 0, "C", 1);
        }

        $pdf->cell($larguraHeaderFornecedor, $alturaCelulaCabecalho, "(" . ($w + 1) . ")", 1, 0, "C", 1);
        $pdf->cell($larguraHeaderObservacao, $alturaCelulaCabecalho, "Observação", 1, $t, "C", 1);

        $condicaoPosicao++;
      }
      $troca = 0;

    }

    $t = 0;
    $cont_quant = 0;
    $alt = 4;
    $larguraObservacao = 45;
    $alturaMaiorCelula = 4;

    $dadosFornecedoresItem = new stdClass();
    $dadosFornecedoresItem->fornecedores = array();

    $campos = "pc23_orcamforne, pc23_valor, pc23_vlrun, pc24_pontuacao, pc23_obs";
    $sql = $clpcorcamval->sql_query_julg(null, null, $campos, null, "pc23_orcamitem=$pc22_orcamitem");
    $rs = db_query($sql);

    $totalLinhas = pg_num_rows($rs);

    for ($contador = 0; $contador < $totalLinhas; $contador++) {
        $fornecedor = db_utils::fieldsMemory($rs, $contador);

        $pdf->setfont('arial', '', 7);

        if ($fornecedor->pc24_pontuacao == 1) {
            $pdf->setfont('arial', 'b', 8);
        }

        $linhasCelulaObservacao = $pdf->NbLines($larguraObservacao, $fornecedor->pc23_obs);
        $alturaCelulaObservacao = $linhasCelulaObservacao * $alt;
        $fornecedor->alturaCelulaObservacao = $alturaCelulaObservacao;

        if ($alturaCelulaObservacao > $alturaMaiorCelula) {
            $alturaMaiorCelula = $alturaCelulaObservacao;
        }

        $dadosFornecedoresItem->fornecedores[$fornecedor->pc23_orcamforne] = $fornecedor;
    }

    for($w = $quant_imp; $w < $max_forne; $w ++) {
        $posicaoYCabecalho = $pdf->getY();
        $posicaoXCabecalho = $pdf->getX();
        $posicaoX = $pdf->getX();
        $posicaoY = $pdf->getY();

        db_fieldsmemory($result_forne, $w);

        $pdf->setfont('arial', '', 7);

        if ($w == ($max_forne - 1)) {
            $t = 1;
        }

        $fornecedorLancouProposta = false;
        $alturaAtualObservacao = 4;

        if (array_key_exists($pc21_orcamforne, $dadosFornecedoresItem->fornecedores)) {
          $dados = $dadosFornecedoresItem->fornecedores[$pc21_orcamforne];
          $fornecedorLancouProposta = true;
          $alturaAtualObservacao = $dados->alturaCelulaObservacao;

          if ($dados->pc24_pontuacao == 1) {
            $pdf->setfont('arial', 'b', 8);
            $fundo = 1;
            $arr_subtotganhoun [$w] += $dados->pc23_vlrun;
            $arr_subtotganhovlr [$w] += $dados->pc23_valor;
            $arr_totalganho [$pc21_orcamforne] += $dados->pc23_valor;
          } else {
            $fundo = 0;
          }

          $arr_subtotcotadoun [$w] += $dados->pc23_vlrun;
          $arr_subtotcotadovlr [$w] += $dados->pc23_valor;
          $arr_totalcotado [$pc21_orcamforne] += $dados->pc23_valor;

          if ($imp_vlrun == "S") {
              $pdf->setX($posicaoX + $larguraHeaderItem + $larguraHeaderSolicitacao + $larguraHeaderDescrProduto + $larguraHeaderUnidade + $larguraHeaderQuantidade);
              $pdf->cell($larguraHeaderValorUnitario, $alturaMaiorCelula, db_formatar($dados->pc23_vlrun, 'f', ' ', 0, 'd', $iCasasDecimais), 1, 0, "R", $fundo);
          }

          $pdf->setX($posicaoX + $larguraHeaderItem + $larguraHeaderSolicitacao + $larguraHeaderDescrProduto + $larguraHeaderUnidade + $larguraHeaderQuantidade + $larguraHeaderValorUnitario);
          $pdf->cell($larguraHeaderFornecedor, $alturaMaiorCelula, $dados->pc23_valor, 1, 0, "R", $fundo);

          if ($imp_vlrtotal == "S") {
              if (isset($arr_valor [$w]) && trim(@$arr_valor [$w]) != "") {
                  $arr_valor [$w] += @$dados->pc23_valor;
              }
              $valor_total += $dados->pc23_valor;
          }
        } else {
          if ($imp_vlrun == "S") {
            $pdf->setX($posicaoX + $larguraHeaderItem + $larguraHeaderSolicitacao + $larguraHeaderDescrProduto + $larguraHeaderUnidade + $larguraHeaderQuantidade);
            $pdf->cell($larguraHeaderValorUnitario, $alturaMaiorCelula, "0,00", 1, 0, "R", 0);
          }
          $pdf->setX($posicaoX + $larguraHeaderItem + $larguraHeaderSolicitacao + $larguraHeaderDescrProduto + $larguraHeaderUnidade + $larguraHeaderQuantidade + $larguraHeaderValorUnitario);
          $pdf->cell($larguraHeaderFornecedor, $alturaMaiorCelula, "0,00", 1, 0, "R", 0);
        }

        $pdf->setX($posicaoX + $larguraHeaderItem + $larguraHeaderSolicitacao + $larguraHeaderDescrProduto + $larguraHeaderUnidade + $larguraHeaderQuantidade + $larguraHeaderValorUnitario + $larguraHeaderFornecedor);

        if ($alturaAtualObservacao === $alturaMaiorCelula) {
            $pdf->multiCell($larguraObservacao, $alt, $fornecedorLancouProposta ? $dados->pc23_obs : '', 1, 'C', 0);
        } else {
            $pdf->cell($larguraHeaderObservacao, $alturaMaiorCelula, $fornecedorLancouProposta ? $dados->pc23_obs : '', 1, 0, "C");
        }

        $cont_quant ++;

        if ($max_forne % 2 === 0) {
          $pdf->SetY($posicaoY);
          $pdf->SetX($posicaoXSegundoFornecedor);
        }
    }

    $pdf->setY($posicaoY);

    $pdf->setfont('arial', '', 7);
    $pdf->cell(10, $alturaMaiorCelula, $l21_ordem, 1, 0, "C", 0);
    $pdf->cell(20, $alturaMaiorCelula, $pc11_numero, 1, 0, "C", 0);
    $pdf->cell(54, $alturaMaiorCelula, substr($pc01_descrmater, 0, 35), 1, 0, "L", 0);
    $pdf->cell(20, $alturaMaiorCelula, $m61_descr, 1, 0, "C", 0);
    $pdf->cell(15, $alturaMaiorCelula, $pc11_quant, 1, 0, "C", 0);

    $pdf->setY($posicaoY + $alturaMaiorCelula);

    $alt = 4;

    if ($x == $numrows_itens - 1 && $max == true) {
      $quant_imp = $cont_quant + $quant_imp;
      $x = - 1;
      $troca = 1;
      $total = 0;

      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(139, $alt, db_formatar($total_quant, "f"), 1, 0, "R", 0);
      $pdf->cell(140, $alt, "", 1, 1, "R", 0);

      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // Impressao de 2 em 2 fornecedores, separados por subtotal ganho e cotado, incluindo valores unitarios e total
      if ($w == 2) { // posicao 0 e 1 dos arrays de subtotais
        $ind = 0;
      } else {
        $ind = $w - 2; // posicoes 2 em diante dos arrays de subtotais, sempre de 2 em 2
      }

      for($xx = $ind; $xx < $w; $xx ++) {
        if (($xx % 2) == 0) {
          $tam = 139;
          $msg = "SUBTOTAL GANHO ";
        } else {
          $tam = 20;
          $msg = "";
        }

        if (($xx + 1) >= $w) {
          $br = 1;
        } else {
          $br = 0;
        }
        $totalGanhoUn = getValorPrint($arr_subtotganhoun [$xx]);
        $totalGanhoVlr = getValorPrint($arr_subtotganhovlr [$xx]);
        $pdf->cell($tam, $alt, $msg . $totalGanhoUn, 1, 0, "R", 0);
        $pdf->cell(60, $alt, $totalGanhoVlr, 1, $br, "R", 0);
      }

      if ($w == 2) {
        $ind = 0;
      } else {
        $ind = $w - 2;
      }

      for($xx = $ind; $xx < $w; $xx ++) {
        if (($xx % 2) == 0) {
          $tam = 139;
          $msg = "SUBTOTAL COTADO ";
        } else {
          $tam = 20;
          $msg = "";
        }

        if (($xx + 1) >= $w) {
          $br = 1;
        } else {
          $br = 0;
        }
        $subTotCotadoUn = getValorPrint($arr_subtotcotadoun [$xx]);
        $subTotCotadoVlr = getValorPrint($arr_subtotcotadovlr [$xx]);
        $pdf->cell($tam, $alt, $msg . $subTotCotadoUn, 1, 0, "R", 0);
        $pdf->cell(60, $alt, $subTotCotadoVlr, 1, $br, "R", 0);
      }
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $pdf->ln();
    }

    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }
    $total ++;

  }

  $pdf->Ln(4);
  $pdf->setfont('arial', 'b', 8);

  // Ficou pendente valores a serem impressos
  if ($quant_imp < $max_forne) {
    $pdf->cell(139, $alt, "QUANT. TOTAL " . db_formatar($total_quant, "f"), 1, 0, "R", 0);
    $pdf->cell(60, $alt, "", 1, 1, "R", 0);

    $subTotGanhoUn = getValorPrint($arr_subtotganhoun [$quant_imp]);
    $subTotGanhoVlr = getValorPrint($arr_subtotganhovlr [$quant_imp]);
    $pdf->cell(139, $alt, "SUBTOTAL GANHO " . $subTotGanhoUn, 1, 0, "R", 0);
    $pdf->cell(60, $alt, $subTotGanhoVlr, 1, 1, "R", 0);
    $subTotCotadoUn = getValorPrint($arr_subtotcotadoun [$quant_imp]);
    $subTotCotadoVlr = getValorPrint($arr_subtotcotadovlr [$quant_imp]);
    $pdf->cell(139, $alt, "SUBTOTAL COTADO " . $subTotCotadoUn, 1, 0, "R", 0);
    $pdf->cell(60, $alt, $subTotCotadoVlr, 1, 1, "R", 0);

    $pdf->ln();
  }

  if ($pdf->gety() > $pdf->h - 30) {
    $pdf->addpage("L");
  }

  $pdf->cell(65, $alt, "FORNECEDOR(ES)", 1, 0, "L", 1);
  $pdf->cell(30, $alt, "VALOR GANHO", 1, 0, "R", 1);
  $pdf->cell(30, $alt, "VALOR COTADO", 1, 1, "R", 1);

  $total_ganho = 0;
  $total_cotado = 0;

  $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null, "*", null, "pc21_codorc=$orcamento"));
  $numrows_forne = $clpcorcamforne->numrows;
  for($i = 0; $i < $numrows_forne; $i ++) {
    db_fieldsmemory($result_forne, $i);
    $cont = $i;
    $cont ++;

    $pdf->cell(65, $alt, substr($z01_nome, 0, 25) . " (" . $cont . ")", 0, 0, "L", $p);
    $valorTotalGanho = getValorPrint($arr_totalganho [$pc21_orcamforne]);
    $valorTotalCotado = getValorPrint($arr_totalcotado [$pc21_orcamforne]);
    $pdf->cell(30, $alt, $valorTotalGanho, 0, 0, "R", $p);
    $pdf->cell(30, $alt, $valorTotalCotado, 0, 1, "R", $p);
    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }

    $total_ganho += $arr_totalganho [$pc21_orcamforne];
    $total_cotado += $arr_totalcotado [$pc21_orcamforne];
  }
  if ($numrows_forne > 0) {
    $pdf->cell(125, 1, "", "T", 1, "R", 0);
    $totalGanho = getValorPrint($total_ganho);
    $totalCotado = getValorPrint($total_cotado);
    $pdf->cell(95, $alt, "TOTAIS " . $totalGanho, 0, 0, "R", 0);
    $pdf->cell(30, $alt, $totalCotado, 0, 1, "R", 0);
  }

  $pdf->ln();

  if ($imp_vlrtotal == "S") {
    if ($pdf->gety() + 17 > $pdf->h - 30) {
      $pdf->AddPage("L");
      $pdf->cell(20, $alt * 2, "", 0, 1, "L", 0);
    }
    $valorTotal = getValorPrint($valor_total);
    $pdf->cell(60, $alt, "TOTAL GERAL " . $valorTotal, 0, 1, "R", 0);
  }
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo não foi selecionado.');
}

$pdf->setfont('arial', '', 8);
if (isset($imp_descla) && $imp_descla == "S") {
  if ($clpcorcamdescla->numrows > 0) {
    $pdf->ln();
    $tam = 250;
    $tam_justifica = 90;

    if ($l20_tipojulg != 1) {
      $tam += 40;
      $tam_justifica += 80;
    }

    $pdf->cell($tam, $alt, "DESCLASSIFICAÇÃO", 1, 1, "C", 1);
    $pdf->cell(70, $alt, "Fornecedor", 1, 0, "C", 1);

    if ($l20_tipojulg != 1) {
      $pdf->cell(40, $alt, "Descrição lote", 1, 0, "C", 1);
    } else {
      $pdf->cell(10, $alt, "Item", 1, 0, "C", 1);

      $pdf->cell(80, $alt, "Descrição material", 1, 0, "C", 1);
    }

    $pdf->cell($tam_justifica, $alt, "Justificativa", 1, 1, "C", 1);

    $p = 0;
    for($i = 0; $i < $clpcorcamdescla->numrows; $i ++) {
      db_fieldsmemory($res_descla, $i);
      if ($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage("L");
      }

      $pdf->cell(70, $alt, substr($z01_nome, 0, 40), 0, 0, "L", $p);

      if ($l20_tipojulg != 1) {
        $pdf->cell(40, $alt, $l04_descricao, 0, 0, "L", $p);
      } else {
        $pdf->cell(10, $alt, $l21_ordem, 0, 0, "C", $p);
        $pdf->cell(80, $alt, $pc01_codmater . " - " . $pc01_descrmater, 0, 0, "L", $p);
      }

      $pdf->multicell($tam_justifica, $alt, $pc32_motivo, 0, "J", $p);

      if ($p == 0) {
        $p = 1;
      } else {
        $p = 0;
      }
    }
  }
}

if (isset($imp_troca) && $imp_troca == "S") {
  if ($clpcorcamtroca->numrows > 0) {
    $pdf->ln();
    $tam = 280;
    if ($l20_tipojulg != 1) {
      $tam = 270;
    }

    if ($pdf->gety() > $pdf->h - 50) {
      $pdf->addpage("L");
    }

    $pdf->cell($tam, $alt, "TROCA FORNECEDOR", 1, 1, "C", 1);

    if ($l20_tipojulg == 1) {
      $pdf->cell(10, $alt, "Item", 1, 0, "C", 1);
      $pdf->cell(40, $alt, "Descrição material", 1, 0, "C", 1);
    }

    if ($l20_tipojulg != 1) {
      $pdf->cell(40, $alt, "Descrição lote", 1, 0, "C", 1);
    }

    $pdf->cell(60, $alt, "Fornecedor substituto", 1, 0, "C", 1);
    $pdf->cell(60, $alt, "Fornecedor substituido", 1, 0, "C", 1);
    $pdf->cell(110, $alt, "Justificativa", 1, 1, "C", 1);

    $p = 0;
    $lote = "";
    for($i = 0; $i < $clpcorcamtroca->numrows; $i ++) {
      db_fieldsmemory($res_troca, $i);
      if ($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage("L");
      }

      if ($lote != "") {
        if ($lote == $l04_descricao) {
          continue;
        }
      }

      if ($l20_tipojulg == 1) {
        $pdf->cell(10, $alt, $l21_ordem, 0, 0, "C", $p);
        $pdf->cell(40, $alt, substr($pc01_codmater . " - " . $pc01_descrmater, 0, 23), 0, 0, "L", $p);
      }

      if ($l20_tipojulg != 1) {
        $pdf->cell(40, $alt, $l04_descricao, 0, 0, "L", $p);
      }

      $pdf->cell(60, $alt, substr(@$nome_julgado, 0, 30), 0, 0, "L", $p);
      $pdf->cell(60, $alt, substr($nome_trocado, 0, 30), 0, 0, "L", $p);
      $pdf->multicell(110, $alt, $pc25_motivo, 0, "J", $p);

      if ($lote != $l04_descricao) {
        $lote = $l04_descricao;
      }

      if ($p == 0) {
        $p = 1;
      } else {
        $p = 0;
      }
    }
  }
}

if (isset($imp_lote) && $imp_lote == "S") {
  if ($clliclicitemanu->numrows > 0) {
    $pdf->ln();
    $tam = 240;

    if ($l20_tipojulg != 1) {
      $tam += 40;
    }

    $pdf->cell($tam, $alt, "ANULAÇÃO DE LOTES/ITENS", 1, 1, "C", 1);
    if ($l20_tipojulg == 1) {
      $pdf->cell(10, $alt, "Item", 1, 0, "C", 1);
    }

    $pdf->cell(40, $alt, "Descrição material", 1, 0, "C", 1);

    if ($l20_tipojulg != 1) {
      $pdf->cell(40, $alt, "Descrição lote", 1, 0, "C", 1);
    }

    $pdf->cell(190, $alt, "Justificativa", 1, 1, "C", 1);

    $p = 0;
    $descrmater = "";
    for($i = 0; $i < $clliclicitemanu->numrows; $i ++) {
      db_fieldsmemory($res_lote, $i);
      if ($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage("L");
      }

      if ($descrmater != "") {
        if ($descrmater == $pc01_descrmater) {
          continue;
        }
      }

      if ($l20_tipojulg == 1) {
        $pdf->cell(10, $alt, $l21_ordem, 0, 0, "C", $p);
      }

      $pdf->cell(40, $alt, $pc01_codmater . " - " . $pc01_descrmater, 0, 0, "L", $p);

      if ($l20_tipojulg != 1) {
        if (isset($l04_descricao) && trim($l04_descricao) == "") {
          $l04_descricao = "SEM LOTE";
        }
        $pdf->cell(40, $alt, $l04_descricao, 0, 0, "L", $p);
      }

      $pdf->multicell(190, $alt, $l07_motivo, 0, "J", $p);

      if ($descrmater != $pc01_descrmater) {
        $descrmater = $pc01_descrmater;
      }

      if ($p == 0) {
        $p = 1;
      } else {
        $p = 0;
      }
    }
  }
}

$pdf->Output();
?>
