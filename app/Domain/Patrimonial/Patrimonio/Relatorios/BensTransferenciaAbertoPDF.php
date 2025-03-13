<?php

namespace App\Domain\Patrimonial\Patrimonio\Relatorios;

use FpdfMultiCellBorder;

/**
 * Classe responsável por montar um relatório em PDF
 * @package App\Domain\Patrimonial\Patrimonio\Relatorios
 */
class BensTransferenciaAbertoPDF extends FpdfMultiCellBorder
{
    /**
     * @var array $dados
     */
    private $dados;

    private $totalDestino;

    public function __construct(array $dados)
    {
        parent::__construct();
        $this->dados = $dados;
        $this->mostrarEmissor(true);

        global $head2;

        $head2 = 'Relátorio de Transferências de Bens em Aberto';
    }

    public function emitir()
    {
        $this->initPdf();
        $this->AddPage('L');

        foreach ($this->dados as $departamento) {
            $linhaImpressa = 0;

            if ($this->getAvailHeight() < 1) {
                $this->AddPage('L');
            }

            $this->montaCabecalhoTransf(
                $departamento->id,
                $departamento->descricao
            );
            $this->montaCabecalho();
            foreach ($departamento->transferencias as $transferencia) {
                $linhaImpressa++;
                $color = !($linhaImpressa % 2);

                if ($this->getAvailHeight() < 5) {
                    $this->AddPage('L');
                    $this->montaCabecalho();
                    $this->SetFont('Arial', '', 8);
                }
                $this->montaLinha($transferencia, $color);
            }
            $this->Cell(1, 4, '', 0, 1);
        }
        return $this->imprimir();
    }

    private function montaLinha($transfe, $cor)
    {
        $this->SetFont('Arial', '', 7);
        $this->Cell(20, 5, $transfe->codigo_transferencia, 0, 0, 'C', $cor);
        $this->Cell(42, 5, $transfe->data_transferencia, 0, 0, 'C', $cor);
        $this->Cell(110, 5, $transfe->nome_usuario, 0, 0, 'C', $cor);
        $this->Cell(110, 5, $transfe->id_departamento_destino. ' - ' .
            $transfe->departamento_destino, 0, 1, 'C', $cor);
    }

    private function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->SetMargins(8, 8, 8);
        $this->Open();
        $this->SetAutoPageBreak(false, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', '', 9);
        $this->exibeHeader(true);
    }

    private function montaCabecalhoTransf($dpOrigemCodigo, $dpOrigemNome)
    {
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(282, 5, 'Departamento Origem: ' . $dpOrigemCodigo . ' - ' . $dpOrigemNome, 1, 1, 'L', 1);
    }

    private function montaCabecalho()
    {
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 5, 'Código', 1, 0, 'C', 1);
        $this->Cell(42, 5, 'Data', 1, 0, 'C', 1);
        $this->Cell(110, 5, 'Usuário', 1, 0, 'C', 1);
        $this->Cell(110, 5, 'Departamento Destino', 1, 1, 'C', 1);
    }

    private function imprimir()
    {
        $fileName = 'tmp/bens_transferencia_aberto' . time() . '.pdf';
        $this->Output($fileName, false, true);

        return [
            "name" => "Relatório de Bens de Transferência em Aberto",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }
}
