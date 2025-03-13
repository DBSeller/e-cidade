<?php

namespace ECidade\Educacao\Escola\Censo\Log;

use FpdfMultiCellBorder;

/**
 * Class LogImportarCodigoInep
 * @package ECidade\Educacao\Escola\Censo\Log
 */
class LogImportarCodigoInep extends Log
{
    const ERRO = 0;
    const IMPORTADO = 1;

    /**
     * @param $type
     * @param $log
     */
    public function add($type, $log)
    {
        $this->logs[$type][] = $log;
    }

    /**
     * @return string
     */
    public function write()
    {
        $pdf = new FpdfMultiCellBorder();
        $pdf->exibeHeader(true);
        $pdf->Open();
        $pdf->AliasNbPages();
        $pdf->SetFillColor(235);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetFont("arial", "b", 7);

        foreach ($this->logs as $index => $informacoes) {
            $titulo = $index === self::ERRO ? 'Inconsistências' : "Importados";
            $pdf->SetFont("arial", "B", 8);
            $pdf->Cell(183, 4, $titulo, "B", 1);
            $pdf->SetFont("arial", "", 7);
            $fill = true;

            foreach ($informacoes as $informacao) {
                $pdf->MultiCell(193, 4, $informacao, 1, '', $fill);
                $fill = !$fill;
            }

            $pdf->ln();
        }

        $filePath = "tmp/log_importacao_inep" . time() . ".pdf";
        $pdf->Output($filePath, false, true);

        return $filePath;
    }
}
