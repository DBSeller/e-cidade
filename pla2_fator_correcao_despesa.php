<?php
require_once(modification("fpdf151/fpdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));


$sql = "
    select distinct o56_codele, o56_elemento, o56_descr, pl7_exercicio as exercicio, pl7_percentual as percentual, deflator
      from planejamento.fatorcorrecaodespesa
      join orcamento.orcelemento on (o56_codele, o56_anousu) = (pl7_orcelemento, pl7_anoorcamento)
     where pl7_planejamento = {$_GET['planejamento']};
";

$rs = db_query($sql);
$dados = [];
db_utils::makeCollectionFromRecord($rs, function ($dado) use (&$dados) {
    if (!array_key_exists($dado->o56_elemento, $dados)) {
        $dados[$dado->o56_elemento] = (object)[
            'natureza' => $dado->o56_elemento,
            'o56_codele' => $dado->o56_codele,
            'descricao' => $dado->o56_descr,
            'deflator' => $dado->deflator == 't',
            'valores' => []
        ];
    }

    $dados[$dado->o56_elemento]->valores[$dado->exercicio] = $dado->percentual;
});

 ksort($dados);

$head1 = "Fatores de Correção aplicados as naturezas da despesa.";
$pdf = new FpdfMultiCellBorder();
$pdf->exibeHeader(true);
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFillColor(235);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

foreach ($dados as $natureza) {
    $pdf->SetFont('Arial', 'B', 8);
    $estrutural = new \ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural($natureza->natureza);
    $pdf->MultiCell(
        190,
        4,
        "{$estrutural->getEstruturalComMascara()} - {$natureza->descricao}",
        0,
        'L',
        1
    );
    $pdf->SetFont('Arial', '', 7);
    ksort($natureza->valores);
    foreach ($natureza->valores as $exercicio => $valor) {
        if ($natureza->deflator) {
            $valor *= -1;
        }
        $pdf->cell(20, 4, "$exercicio: $valor%", 0, 0);
    }

    $pdf->ln();
}

$pdf->Output();


