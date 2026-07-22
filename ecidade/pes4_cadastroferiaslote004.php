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

/**
 * Inclusão das bibliotecas necessárias.
 */
include(modification("fpdf151/pdf.php"));

/**
 * Testamos se a collection de erros está armazenada na sessão.
 * Em caso negativo emitimos um erro.
 */
if (!isset($_SESSION['inconsistencias_cadastroferiaslote'])) {
  
  $sMessageError  = "Não existem erros registrados no último processamento de ";
  $sMessageError .= "cadastro de férias em lote sem confirmação.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMessageError}");
}

/**
 * Setamos as informações genéricas do relatório.
 * Como: altura das linhas, contador de registros e título do relatório.
 */
$iAlturaLinha       = 4;
$iContadorRegistros = 0;
$head3              = 'Inconsistencias - Cadastro de Férias em Lote';
$head4              = 'Tipo de Processamento: Sem Confirmação';

/**
 * Iniciamos o relatório.
 */
$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetFont('arial', 'b', 8);
$oPdf->AddPage();

/**
 * Criamos o cabeçalho da tabela do relatório.
 */
$oPdf->Cell(20,  $iAlturaLinha, 'Matrícula',      1, 0, 'C', 1);
$oPdf->Cell(70,  $iAlturaLinha, 'Nome',           1, 0, 'L', 1);
$oPdf->Cell(100, $iAlturaLinha, 'Inconsistência', 1, 1, 'C', 1);

$oPdf->SetFont('arial', '', 7);

/**
 * Percorremos as inconsistencias para exibi-las na tabela do relatório.
 * A cada loop incrementamos 1 no contador de registros.
 */
foreach ($_SESSION['inconsistencias_cadastroferiaslote'] as $oInconsistencia) {
  
  $oPdf->Cell(20,  $iAlturaLinha, $oInconsistencia->regist, 1, 0, 'C', 0);
  $oPdf->Cell(70,  $iAlturaLinha, $oInconsistencia->nome,   1, 0, 'L', 0);
  $oPdf->Cell(100, $iAlturaLinha, $oInconsistencia->erro,   1, 1, 'L', 0);
  $iContadorRegistros++;
}

/**
 * Exibimos a quantidade total de inconsistencias.
 */
$oPdf->SetFont('arial', 'b', 8);
$oPdf->Cell(190,  $iAlturaLinha, "Total de registros: {$iContadorRegistros}", 1, 0, 'R', 0);

/**
 * Terminamos o relatório.
 */
$oPdf->Output();