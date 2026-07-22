<?php


namespace App\Domain\Financeiro\Tesouraria\Relatorios\Tef;

use FpdfMultiCellBorder;

class ListaMovimentosOperacoesPDF extends FpdfMultiCellBorder
{
    /**
     * @var array
     */
    private $data = [];

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
        $this->exibeHeader(true);
        $this->mostrarRodape(true);
        $this->mostrarEmissor(true);
        $this->mostrarTotalDePaginas(true);
        $this->Open();
        $this->SetAutoPageBreak(true, 15);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', '', 8);
    }

    /**
     * @param string $periodo
     */
    public function headers($periodo)
    {
        global $head1;
        $head1 = 'Lista de Movimentos em Operações';

        global $head2;
        $head2 = $periodo;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function emitir()
    {
        $this->cabecalho();

        $totalValorBruto = 0;
        $totalValorDescontos = 0;
        $totalValorLiquido = 0;
        foreach ($this->data as $data) {
            $this->Cell(21, 4, $data->numero_autorizacao, 1, 0, 'C');
            $this->Cell(21, 4, $data->numero_cv, 1, 0, 'C');
            $this->Cell(21, 4, db_formatar($data->data_venda, 'd'), 1, 0, 'C');
            $this->Cell(21, 4, db_formatar($data->data_vencimento, 'd'), 1, 0, 'C');
            $this->Cell(21, 4, $data->parcela, 1, 0, 'C');
            $this->Cell(21, 4, $data->total_parcelas, 1, 0, 'C');
            $this->Cell(22, 4, db_formatar($data->valor_bruto, 'f'), 1, 0, 'R');
            $this->Cell(22, 4, db_formatar($data->valor_descontos, 'f'), 1, 0, 'R');
            $this->Cell(22, 4, db_formatar($data->valor_liquido, 'f'), 1, 1, 'R');
            $totalValorBruto += $data->valor_bruto;
            $totalValorDescontos += $data->valor_descontos;
            $totalValorLiquido += $data->valor_liquido;
        }

        $this->SetFont('Arial', 'B', 8);

        $this->Cell(126, 4, 'Totais', 1, 0, 'R');
        $this->Cell(22, 4, db_formatar($totalValorBruto, 'f'), 1, 0, 'R');
        $this->Cell(22, 4, db_formatar($totalValorDescontos, 'f'), 1, 0, 'R');
        $this->Cell(22, 4, db_formatar($totalValorLiquido, 'f'), 1, 1, 'R');

        $filename = sprintf('tmp/lista-movimentos-%s.pdf', time());
        $this->Output($filename, false, true);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function cabecalho()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(21, 4, 'Autorização', 1, 0, 'C', 1);
        $this->Cell(21, 4, 'NSU', 1, 0, 'C', 1);
        $this->Cell(21, 4, 'Dt Venda', 1, 0, 'C', 1);
        $this->Cell(21, 4, 'Dt Venc.', 1, 0, 'C', 1);
        $this->Cell(21, 4, 'Parcela', 1, 0, 'C', 1);
        $this->Cell(21, 4, 'T. Parcelas', 1, 0, 'C', 1);
        $this->Cell(22, 4, 'Vlr. Bruto', 1, 0, 'C', 1);
        $this->Cell(22, 4, 'Vlr. Descontos', 1, 0, 'C', 1);
        $this->Cell(22, 4, 'Vlr. Líquido', 1, 1, 'C', 1);
        $this->SetFont('Arial', '', 8);
    }
}
