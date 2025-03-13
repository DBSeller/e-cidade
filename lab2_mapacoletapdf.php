<?php
include(modification("fpdf151/scpdf.php"));

/**
 * @param $pdf scpdf
 * @param $conteudo stdClass
 */
function defineCabecalho($pdf, $conteudo)
{
    $pdf->setXY(1, 1);
    $pdf->Image('imagens/files/'. $conteudo->pathImagem, 7, 3, 20);

    $tamanhoFonteNome = strlen($conteudo->laboratorio) > 42 ? 8 : 9;

    $pdf->SetFont('Arial', 'BI', $tamanhoFonteNome);
    $pdf->Text(33, 10, $conteudo->laboratorio);
    $pdf->SetFont('Arial', 'I', 8);

    $pdf->Text(33, 14, trim($conteudo->enderecoLaboratorio));
    $pdf->Text(33, 18, trim($conteudo->municipioDepartamento) . ' - ' . $conteudo->ufDepartamento);
    $pdf->Text(
        33,
        22,
        trim(
            db_formatar(
                $conteudo->telefoneLaboratorio,
                'telefone'
            )
        )
        . '   -    CNPJ : '
        . db_formatar(
            $conteudo->cnpjDepartamento,
            'cnpj'
        )
    );

    $pdf->Text(33, 26, trim($conteudo->emailDepartamento));
    $comprimento = ($pdf->w - $pdf->rMargin - $pdf->lMargin);

    $pdf->Text(33, 30, $conteudo->siteDepartamento);
    $espaco = $pdf->w - 80;
    $pdf->SetFont('Arial', '', 7);
    $margemEsquerda = $pdf->lMargin;
    $pdf->setleftmargin($espaco);
    $pdf->sety(6);
    $pdf->setfillcolor(235);
    $pdf->roundedrect($espaco - 3, 6, 75, 27, 2, 'DF', '123');
    $pdf->line(10, 33, $comprimento, 33);
    $pdf->setfillcolor(255);
    $pdf->multicell(0, 3, @$GLOBALS["head1"], 0, "J", 0);
    $pdf->multicell(0, 3, @$GLOBALS["head2"], 0, "J", 0);
    $pdf->multicell(0, 3, @$GLOBALS["head3"], 0, "J", 0);
    $pdf->multicell(0, 3, @$GLOBALS["head4"], 0, "J", 0);
    $pdf->multicell(0, 3, @$GLOBALS["head5"], 0, "J", 0);
    $pdf->setleftmargin($margemEsquerda);
    $pdf->SetY(35);
}

$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(220);
$pdf->SetFont('Arial', 'B', 9);

$periodo = $oGet->dataInicial . ' até ' . $oGet->dataFinal;

/*
 * Informações adicionais do cabeçalho
 * Obs: estas variáveis foram criadas
 * no arquivo lab2_mapacoletaconsulta.php
 */
$head1 = "Mapa de Coleta\n ";
$head2 = "Período Inicial: {$oGet->dataInicial}";
$head3 = "Período Final: {$oGet->dataFinal}";
$head4 = "Laboratório: " . trim($conteudo->cabecalho->laboratorio);
$head5 = $setor ? "Setor: {$setor}" : 'Setor: TODOS';

$pdf->AddPage();

/*
 * Variáveis padrões
 */
$alturaPadrao  = 5;
$larguraPadrao = 192;
$larguraRequisicao = 34;
$larguraDataRequisicao = 28;
$larguraNome = 80;
$larguraExames = 50;

$pdf->SetFont('Arial', '', 7);
$flagNovaPagina = true;
foreach ($conteudo->coletas as $coleta) {
    if ($flagNovaPagina) {
        defineCabecalho($pdf, $conteudo->cabecalho);
        /*
         * Headers das colunas
         */
        $pdf->Cell($larguraRequisicao, $alturaPadrao, "Requisição", 1, 0, "C", 1);
        $pdf->Cell($larguraNome, $alturaPadrao, "Nome", 1, 0, "C", 1);
        $pdf->Cell($larguraDataRequisicao, $alturaPadrao, "Data Requisição", 1, 0, "C", 1);
        $pdf->Cell($larguraExames, $alturaPadrao, "Exames", 1, 1, "C", 1);
        $flagNovaPagina = false;
    }

    /*
     * Cria string de Exames.
     */
    $exames = '';
    $countExames = 0;
    foreach ($coleta->exames as $exame) {
        $exames .= $exames ? ' - ' . $exame : $exame;
        ++$countExames;
    }

    $linhasCelulaExames = $pdf->NbLines($larguraExames, $exames);
    $alturaCelulaExames = $linhasCelulaExames * $alturaPadrao;

    $linhasCelulaNome = $pdf->NbLines($larguraNome, $coleta->nome);
    $alturaCelulaNome = $linhasCelulaNome * $alturaPadrao;

    /*
     * Solução para alinhar a altura das Multicells.
     * Preenche com linhas em branco a Multicell com menor número de linhas
     * Multicells: Nome e Exames
     */
    $strPreencheCelulaNome = '';
    $strPreencheCelulaExames = '';
    if ($linhasCelulaExames > $linhasCelulaNome) {
        $maxLinhasCelula = $linhasCelulaExames;
        for ($i = 0; $i < $maxLinhasCelula - $linhasCelulaNome; $i++) {
            $strPreencheCelulaNome .= "\n ";
        }
    } else {
        $maxLinhasCelula = $linhasCelulaNome;
        for ($i = 0; $i < $maxLinhasCelula - $linhasCelulaExames; $i++) {
            $strPreencheCelulaExames .= "\n ";
        }
    }

    /*
     * A altura da linha depende do número de Exames.
     */
    $alturaLinha = $alturaCelulaExames > $alturaCelulaNome ? $alturaCelulaExames : $alturaCelulaNome;

    // Imprime Requisição
    $pdf->Cell($larguraRequisicao, $alturaLinha, $coleta->requisicao, 1, 0, "C", 0);

    /*
     * Ajusta posição do cursor do PDF para evitar quebra de linha
     * na impressão do nome.
     */
    $currentY = $pdf->GetY();
    $currentX = $pdf->GetX();

    // Imprime nome paciente
    $pdf->MultiCell($larguraNome, $alturaPadrao, $coleta->nome . $strPreencheCelulaNome, 1, 'C');

    // Ajusta posição do cursor
    $pdf->SetXY($currentX + $larguraNome, $currentY);

    $pdf->Cell($larguraDataRequisicao, $alturaLinha, $coleta->dataRequisicao, 1, 0, "C", 0);

    // Imprime exames
    $pdf->MultiCell($larguraExames, $alturaPadrao, $exames . $strPreencheCelulaExames, 1);

    if ($pdf->getY() >= 260) {
        $pdf->AddPage();


        $flagNovaPagina = true;
    }
}

$pdf->Output();
