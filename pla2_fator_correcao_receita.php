<?php
require_once(modification("fpdf151/fpdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));


$sql = "
    select distinct o57_codfon, o57_fonte, o57_descr, nomeinst, exercicio, percentual, deflator
      from planejamento.fatorcorrecaoreceita
      join orcamento.orcfontes on (o57_codfon, o57_anousu) = (orcfontes_id, anoorcamento)
      join contabilidade.conplanoorcamentoanalitica on (c61_codcon, c61_anousu) = (o57_codfon, o57_anousu)
      join configuracoes.db_config on db_config.codigo = conplanoorcamentoanalitica.c61_instit
     where planejamento_id = {$_GET['planejamento']};
";

$rs = db_query($sql);
$dados = [];
db_utils::makeCollectionFromRecord($rs, function ($dado) use (&$dados) {
    if (!array_key_exists($dado->o57_fonte, $dados)) {
        $dados[$dado->o57_fonte] = (object)[
            'natureza' => $dado->o57_fonte,
            'descricao' => $dado->o57_descr,
            'instituicao' => $dado->nomeinst,
            'deflator' => $dado->deflator == 't',
            'valores' => []
        ];
    }

    $dados[$dado->o57_fonte]->valores[$dado->exercicio] = $dado->percentual;
});

ksort($dados);

$head1 = "Fatores de Correção aplicados as naturezas de receita.";
$pdf = new FpdfMultiCellBorder();
$pdf->exibeHeader(true);
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFillColor(235);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

foreach ($dados as $natureza) {
    $pdf->SetFont('Arial', 'B', 8);
    $estrutural = new \ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita($natureza->natureza);
    $pdf->MultiCell(
        190,
        4,
        "{$estrutural->getEstruturalComMascara()} - {$natureza->descricao} - {$natureza->instituicao}",
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


