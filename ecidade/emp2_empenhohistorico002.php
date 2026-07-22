<?php
/**
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
use \ECidade\Pdf\Pdf;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("std/DBDate.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$oDataInicial = new DBDate($data_inicial);
$oDataFinal = new DBDate($data_final);

$datainicial = $oDataInicial->getDate(DBDate::DATA_PTBR);
$datafinal = $oDataFinal->getDate(DBDate::DATA_PTBR);

$pdf = new Pdf('L');

$pdf->exibeHeader(true);
$pdf->addTitulo('Empenhos por historico');
$pdf->addTitulo("Período de: {$datainicial} até: {$datafinal}");
$pdf->addTitulo("Palavra-chave: ".$palavra_chave);

$pdf->init();
$pdf->SetFont('Arial', '', 8);

$iAltura = '4';

$oDaoEmpenho = new cl_empempenho;
$camposItensEmpenho = "distinct riseqitem     as item_empenho";
$camposItensEmpenho .= "         ,ricodmater   as codigo_material";
$camposItensEmpenho .= "         ,rsdescr      as descricao_material";
$camposItensEmpenho .= "         ,rnquantini   as quantidade";
$camposItensEmpenho .= "         ,rnvalorini   as valor_total";
$camposItensEmpenho .= "         ,rnvaloruni   as valor_unitario";
$camposItensEmpenho .= "         ,round(rnsaldovalor,2) as saldo_valor";

$sqlBuscaHistoricoEmpenho = "  
      select (e60_codemp||'/'||e60_anousu) as codemp,
             e60_numemp as numemp,
             e60_emiss as data_emissao,(e60_numcgm||'-'||z01_nome) as credor,
             e60_vlremp,
             e60_vlranu,
             e60_vlrliq,
             e60_vlrpag,
             e60_resumo as resumo 
        from empempenho
  inner join cgm on e60_numcgm = z01_numcgm 
       where e60_emiss between '$datainicial' and '$datafinal'
         and e60_resumo ilike '%$palavra_chave%'
    order by credor,codemp";

$rsBuscaHistoricoEmpenho = db_query($sqlBuscaHistoricoEmpenho);
$totalHistoricos = pg_num_rows($rsBuscaHistoricoEmpenho);

if($totalHistoricos == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$credor = db_utils::fieldsMemory($rsBuscaHistoricoEmpenho, 0)->credor;
imprimirInfoCredor($pdf, $iAltura,$credor);

for ($i = 0; $i < $totalHistoricos; $i++) {

  $oHistoricoEmpenho = db_utils::fieldsMemory($rsBuscaHistoricoEmpenho, $i);

  if ($pdf->gety() > $pdf->geth() - 60 ){
    $pdf->addpage();
  }

  if ($credor != $oHistoricoEmpenho->credor) {

    $credor = $oHistoricoEmpenho->credor;
    imprimirInfoCredor($pdf, $iAltura,$credor);
  }

  imprimirCabecalhoHistoricoEmpenho($pdf, $iAltura); 
  ImprimirDadosHistoricoEmpenho($pdf, $iAltura,$oHistoricoEmpenho);
  imprimirInfoItensEmpenho($pdf, $iAltura,$oHistoricoEmpenho->codemp);
  imprimirCabecalhoItensEmpenho($pdf, $iAltura);

  $sqlItensEmpenho = $oDaoEmpenho->sql_query_itens_consulta_empenho(
    $oHistoricoEmpenho->numemp, 
    $camposItensEmpenho, 
    'riseqitem');

  $rsBuscaItensEmpenho = db_query($sqlItensEmpenho);
  $totalItensEmpenho = pg_numrows($rsBuscaItensEmpenho);
  $alternador_cinza = true;

  if ($totalItensEmpenho > 0) {

    for ($j = 0; $j<$totalItensEmpenho; $j++) {

      $alternador_cinza = !$alternador_cinza;
      $oItemEmpenho = db_utils::fieldsMemory($rsBuscaItensEmpenho,$j);
      imprimirDadosItemEmpenho($pdf, $iAltura,$oItemEmpenho,$alternador_cinza);
    }
  } else {
    
    $oItemEmpenho = new stdClass();
    $oItemEmpenho->codigo_material = "0";
    imprimirDadosItemEmpenho($pdf, $iAltura,$oItemEmpenho,false);
  }

  imprimirDelimitador($pdf, $iAltura);

}
$pdf->Output();

/**
 * Função que imprime o cabeçalho
 * do historico do empenho
 */
function imprimirCabecalhoHistoricoEmpenho($pdf, $iAltura) {
  
  $pdf->SetFillColor(235);
  $pdf->ln(3);
  $pdf->SetFont('', 'b', 8);
  $pdf->cell(30, $iAltura, "N.Empenho:"   , 0, 0, "L", 1);
  $pdf->cell(30, $iAltura, "Seq.Empenho"  , 0, 0, "L", 1);
  $pdf->cell(30, $iAltura, "Data Emissão"   , 0, 0, "L", 1);
  $pdf->cell(30, $iAltura, "Empenhado"  , 0, 0, "L", 1);
  $pdf->cell(30, $iAltura, "Anulado"    , 0, 0, "L", 1);
  $pdf->cell(30, $iAltura, "Liquidado"  , 0, 0, "L", 1);
  $pdf->cell(30, $iAltura, "Pago" , 0, 0, "L", 1);
  $pdf->cell(60, $iAltura, "Histórico" , 0, 1, "C", 1);
}

/**
 * Função que imprime o cabeçalho 
 * dos itens do empenho
 */
function imprimirCabecalhoItensEmpenho($pdf, $iAltura) {
  
  if ($pdf->gety() > $pdf->geth() - 45 ){
    $pdf->addpage();
  }

  $pdf->SetFillColor(235);
  $pdf->ln(3);
  $pdf->SetFont('', 'b', 8);
  $pdf->setX($pdf->getX()+30);

  $pdf->cell(20, $iAltura, "Item:"   , 0, 0, "C", 1);
  $pdf->cell(25, $iAltura, "Codigo Material:"  , 0, 0, "C", 1);
  $pdf->cell(70, $iAltura, "Descrição Material: " , 0, 0, "C", 1);
  $pdf->cell(20, $iAltura, "Quantidade:"  , 0, 0, "C", 1);
  $pdf->cell(20, $iAltura, "Valor Total:"    , 0, 0, "C", 1);
  $pdf->cell(25, $iAltura, "Valor Unitário:"  , 0, 0, "C", 1);
  $pdf->cell(20, $iAltura, "Saldo:" , 0, 1, "C", 1);
}

/**
 * Função que imprime os dados do item do empenho
 */
function imprimirDadosItemEmpenho($pdf, $iAltura,$oItemEmpenho,$alternador_cinza) {

  if ($pdf->gety() > $pdf->geth() - 30 ){
    $pdf->addpage();
  }

  $pdf->SetFont('Arial', '', 8);
  $pdf->setX($pdf->getX()+30);
  
  if ($oItemEmpenho->codigo_material != "0") {

    $current_x = $pdf->getX();
    $current_y = $pdf->getY();
    
    $pdf->setX($current_x +45);
    $pdf->multicell(70, $iAltura, $oItemEmpenho->descricao_material , "TB", "C",$alternador_cinza);

    $multicell_y = $pdf->getY();

    /**
     * O multicell quando expande pode avançar para outra pagina. A condição abaixo verifica quando
     * ocorre esse comportamento. E então , a posição y é reinicializada para a linha do cabeçalho 
     * do relatorio
     */
    if($multicell_y-$current_y<0){
      $current_y = 33;
    }

    $pdf->setXY($current_x,$current_y);
    $pdf->cell(20, $multicell_y-$current_y, $oItemEmpenho->item_empenho , "TB", 0, "C", $alternador_cinza);
    $pdf->cell(25, $multicell_y-$current_y, $oItemEmpenho->codigo_material , "TB", 0, "C", $alternador_cinza);

    $pdf->setX($pdf->getX()+70);
    $pdf->cell(20, $multicell_y-$current_y, $oItemEmpenho->quantidade , "TB",0, "C", $alternador_cinza);
    $pdf->cell(20, $multicell_y-$current_y, db_formatar($oItemEmpenho->valor_total,"f") , "TB", 0, "C", $alternador_cinza);
    $pdf->cell(25, $multicell_y-$current_y, db_formatar($oItemEmpenho->valor_unitario,"f") , "TB", 0, "C", $alternador_cinza);
    $pdf->cell(20, $multicell_y-$current_y, db_formatar($oItemEmpenho->saldo_valor,"f") , "TB", 1, "C", $alternador_cinza);
  } else {
    $pdf->cell(200, $iAltura, "Nenhum registro encontrado" , "TB", 1, "C", $alternador_cinza);
  }
}

/**
 * Função que imprime a informação delimitadora dos
 * itens do empenho
 */
function imprimirInfoItensEmpenho($pdf, $iAltura,$numemp) {

  $pdf->SetFillColor(180);
  $pdf->ln(3);
  $pdf->SetFont('', 'b', 8);

  $pdf->cell(270, $iAltura, "ITENS DO EMPENHO: ".$numemp , 0, 1, "C", 1); 
}

/**
 * Função que imprime a informação do credor
 */
function imprimirInfoCredor($pdf, $iAltura,$credor) {

  $pdf->ln(10);
  $pdf->SetFont('', 'b', 10);

  $pdf->cell(270, $iAltura, $credor , 0, 1, "C", 0); 
}

/**
 * Função para delimitar espaço
 */
function imprimirDelimitador($pdf, $iAltura) {
  
  $pdf->ln(3);
  $pdf->SetFillColor(180);
  $pdf->SetFont('', 'b', 8);
  $pdf->cell(270, $iAltura, "", 0, 1, "C", 1); 
}

/**
 * Função que imprime os dados do Historico do empenho
 */
function ImprimirDadosHistoricoEmpenho($pdf, $iAltura,$oHistoricoEmpenho) {

  $pdf->SetFont('Arial', '', 8);
  $pdf->cell(30, $iAltura, $oHistoricoEmpenho->codemp  , 0, 0, "L");
  $pdf->cell(30, $iAltura, $oHistoricoEmpenho->numemp  , 0, 0, "L");
  $pdf->cell(30, $iAltura, db_formatar($oHistoricoEmpenho->data_emissao,"d")  , 0, 0, "L");
  $pdf->cell(30, $iAltura, db_formatar($oHistoricoEmpenho->e60_vlremp,"f")  , 0, 0, "L");
  $pdf->cell(30, $iAltura, db_formatar($oHistoricoEmpenho->e60_vlranu,"f")  , 0, 0, "L");
  $pdf->cell(30, $iAltura, db_formatar($oHistoricoEmpenho->e60_vlrliq,"f"), 0, 0, "L");
  $pdf->cell(30, $iAltura, db_formatar($oHistoricoEmpenho->e60_vlrpag,"f") , 0, 0, "L");
  $pdf->multicell(60, 4, $oHistoricoEmpenho->resumo , 0, 'J');
}

