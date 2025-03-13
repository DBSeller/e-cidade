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
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_libtxt.php"));
require_once(modification("dbforms/db_funcoes.php"));

if (!isset($_SESSION["aConflitoLancamento"]) || count($_SESSION["aConflitoLancamento"]) == 0) {
	
	db_redireciona("db_erros.php?fechar=true&db_erro=Array armazenado na sessão não localizado. Contate o suporte!");
	exit;
}

$aRegrasEmConflito = $_SESSION["aConflitoLancamento"];

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','',8);
$oPdf->SetAutoPageBreak(false);
$iAltura = 4;
$head2    = "Relatório de Regras Contábeis em Conflito";
$head3    = "Data: ".date("d/m/Y", db_getsession('DB_datausu'));
$head4    = "Total de Conflitos: ".count($aRegrasEmConflito);


$lEscreveHeader = true;
foreach ($aRegrasEmConflito as $iIndice => $oConflito) {

	if ($oPdf->GetY() > $oPdf->h - 30 || $lEscreveHeader) {

		montarCabecalho($oPdf, $iAltura);
		$lEscreveHeader = false;
	}
	$oPdf->cell(10,  $iAltura, $oConflito->c45_coddoc     , 0, 0, "C", 0);
	$oPdf->cell(20,  $iAltura, $oConflito->c47_seqtranslr , 0, 0, "C", 0);
	$oPdf->cell(100, $iAltura, $oConflito->c46_descricao  , 0, 0, "L", 0);
	$oPdf->cell(10,  $iAltura, $oConflito->c46_ordem      , 0, 0, "C", 0);
	$oPdf->cell(20,  $iAltura, $oConflito->c47_debito     , 0, 0, "C", 0);
	$oPdf->cell(20,  $iAltura, $oConflito->c47_credito    , 0, 0, "C", 0);
	$oPdf->cell(100, $iAltura, $oConflito->sDescricao     , 0, 1, "L", 0);
}

/**
 * Função que monta o cabeçalho quando necessário
 * @param FPDF $oPdf
 * @param integer $iAltura
 */
function montarCabecalho($oPdf, $iAltura) {

	$oPdf->AddPage();
	$oPdf->setfont('arial', 'b', 8);
	$oPdf->cell(10,  $iAltura, "Doc.",        1, 0, "C", 1);
	$oPdf->cell(20,  $iAltura, "Regra",       1, 0, "C", 1);
	$oPdf->cell(100, $iAltura, "Lançamento",  1, 0, "C", 1);
	$oPdf->cell(10,  $iAltura, "Ordem",       1, 0, "C", 1);
	$oPdf->cell(20,  $iAltura, "C. Débito",   1, 0, "C", 1);
	$oPdf->cell(20,  $iAltura, "C. Crédito",  1, 0, "C", 1);
	$oPdf->cell(100, $iAltura, "Diagnóstico", 1, 1, "C", 1);
	$oPdf->setfont('arial', '', 6);
}
$oPdf->Output();
?>