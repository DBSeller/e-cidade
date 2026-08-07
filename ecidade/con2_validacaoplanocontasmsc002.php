<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/oucvsgit
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

require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification('fpdf151/pdf.php'));
require_once(modification("libs/db_stdlib.php"));

const FONT = 'Arial';
const HEIGHT = 4;

$relatorio = new stdClass;
$relatorio->contas = array();
$relatorio->ano = $_GET['ano'];

try {
    $queryContasInconsistentes  = " SELECT codigo_conta, estrutural, descricao FROM ( ";
    $queryContasInconsistentes .= "   SELECT c61_codcon AS codigo_conta, c60_estrut AS estrutural, c60_descr AS descricao ";
    $queryContasInconsistentes .= "   FROM conplanoreduz ";
    $queryContasInconsistentes .= "   INNER JOIN conplano ON c60_codcon = c61_codcon AND c60_anousu = c61_anousu ";
    $queryContasInconsistentes .= "   WHERE c61_anousu = {$relatorio->ano} ";
    $queryContasInconsistentes .= "   AND c61_reduz IN ( ";
    $queryContasInconsistentes .= "     SELECT DISTINCT c69_credito ";
    $queryContasInconsistentes .= "     FROM ";
    $queryContasInconsistentes .= "     ( SELECT c69_credito FROM conlancamval WHERE c69_anousu = {$relatorio->ano} ";
    $queryContasInconsistentes .= "      UNION ALL ";
    $queryContasInconsistentes .= "      SELECT c69_debito FROM conlancamval WHERE c69_anousu = {$relatorio->ano} ";
    $queryContasInconsistentes .= "     ) AS x ";
    $queryContasInconsistentes .= "   ) ";
    $queryContasInconsistentes .= " ) AS y ";
    $queryContasInconsistentes .= " WHERE codigo_conta NOT IN ( SELECT c120_conplano FROM conplanoatributos WHERE c120_anousu = {$relatorio->ano} )";
    $queryContasInconsistentes .= " ORDER BY 2 ";

    db_inicio_transacao();
    $resultadoContasInconsistentes = db_query($queryContasInconsistentes);

    if (!$resultadoContasInconsistentes) {
        throw new Exception('Erro ao buscar dados da junta comercial.');
    }

    if (pg_num_rows($resultadoContasInconsistentes) == 0) {
        throw new Exception('Nenhum registro encontrado para os filtros informados.');
    }

     $relatorio->contas = db_utils::getCollectionByRecord($resultadoContasInconsistentes);

    if (empty($relatorio->contas)) {
        throw new Exception("Nenhum registro encontrado para os filtros informados.");
    }

    db_fim_transacao();

    $head1 = 'RELATÓRIO DE VALIDAÇÃO DO PLANO DE CONTAS DA MATRIZ DE SALDO CONTÀBIL';
    $head2 = '';
    $head3 = 'Ano: ' . ($relatorio->ano);

    $pdf = new PDF;
    $pdf->Open();
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->SetFillColor(220);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont(FONT, 'b', 8);
    $pdf->Cell(40, HEIGHT, 'Estrutural', 1, 0, 'C', 1);
    $pdf->Cell(25, HEIGHT, 'Conta', 1, 0, 'C', 1);
    $pdf->Cell(127, HEIGHT, 'Descrição', 1, 1, 'C', 1);

    foreach ($relatorio->contas as $conta) {
        $pdf->SetFont(FONT, '', 8);
        $pdf->Cell(40, HEIGHT, $conta->estrutural, 1, 0, 'C');
        $pdf->Cell(25, HEIGHT, $conta->codigo_conta, 1, 0, 'C');
        $pdf->Cell(127, HEIGHT, $conta->descricao, 1, 1, 'C');
    }

    if (($pdf->getY() + (HEIGHT * 2)) > ($pdf->h - 10)) {
        $pdf->AddPage();
    }

    $pdf->Output();
} catch (\Exception $e) {
    db_fim_transacao(true);
    $sMsg = urlencode($e->getMessage());
    db_redireciona('db_erros.php?fechar=true&db_erro=' . $sMsg);
}
