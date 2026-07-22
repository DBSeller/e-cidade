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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("fpdf151/PDFDocument.php"));

$oGet = db_utils::postMemory($_GET);
$aInconsistencias = (array) DBString::utf8_decode_all(json_decode(file_get_contents('tmp/servidores_inconsistencia_assentamento.json')));

$filtros = new stdClass();
$filtros->colunaMatricula = 20;
$filtros->colunaNome = 70;
$filtros->colunaInconsistencia = 100;
$filtros->limiteAlturaPagina = 275;
$filtros->comprimentoLinha = $filtros->colunaMatricula + $filtros->colunaNome + $filtros->colunaInconsistencia = 100;

$pdf = new PDFDocument();
$pdf->addHeaderDescription("RELATÓRIO DE MATRÍCULAS COM INCONSISTÊNCIA NA INCLUSÃO DE ASSENTAMENTOS EM LOTE.");
$pdf->addHeaderDescription("");

$pdf->Open();
$pdf->SetFillColor(225);
$pdf->SetAutoPageBreak(false);

$pdf->AddPage();
$pdf->setFontFamily('arial');
$pdf->SetFontSize(8);

imprimeCabecalho($pdf, $filtros);

foreach ($aInconsistencias as $inconsistencia) {
    $altura = 4;
    if ($pdf->GetY() > $filtros->limiteAlturaPagina) {
        $pdf->AddPage();
        imprimeCabecalho($pdf, $filtros);
    }

    $linhas = 4; //altura da linha
    $linhasMensagem = $pdf->getMultiCellHeight($filtros->colunaInconsistencia, $altura, $inconsistencia->mensagem);
    $linhasNome = $pdf->getMultiCellHeight($filtros->colunaNome, $altura, $inconsistencia->nome);

    if ($linhasMensagem > $linhas) {
        $linhas = $linhasMensagem;
    }
    if ($linhasNome > $linhas) {
        $linhas = $linhasMensagem;
    }

    $linhas = $linhas / $altura;
    $x = $pdf->getX();
    $y = $pdf->getY();
    $pdf->MultiCell($filtros->colunaMatricula, $altura*$linhas, $inconsistencia->matricula, 1, 0, 'C');
    $pdf->setY($y);
    $pdf->setX($x+$filtros->colunaMatricula);
    $x = $pdf->getX();
    $y = $pdf->getY();
    $pdf->MultiCell($filtros->colunaNome, $altura, $inconsistencia->nome, "RLT", 'L');
    $pdf->setY($y);
    $pdf->setX($x+$filtros->colunaNome);
    $pdf->MultiCell($filtros->colunaInconsistencia, $altura, $inconsistencia->mensagem, "RLT", 1, 'L');
    $pdf->line(20,$pdf->getY(), $filtros->comprimentoLinha, $pdf->getY());
}
$pdf->showPDF();

/**
 * @param PDFDocument $pdf
 * @param $filtros
 */
function imprimeCabecalho(PDFDocument $pdf, $filtros)
{
    $pdf->setBold(true);
    $pdf->Cell($filtros->colunaMatricula, 4, "Matrícula", 1, 0, 'C', 1);
    $pdf->Cell($filtros->colunaNome, 4, 'Nome', 1, 0, 'C', 1);
    $pdf->Cell($filtros->colunaInconsistencia,      4, 'Inconsistência',      1, 1, 'C', 1);

    $pdf->setBold(false);
}
