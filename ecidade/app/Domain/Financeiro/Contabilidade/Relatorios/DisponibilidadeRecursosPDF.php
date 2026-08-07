<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use DBDate;
use Exception;

class DisponibilidadeRecursosPDF extends \FpdfMultiCellBorder
{
    /**
     * @var string
     */
    private $assinaturaContador;
    /**
     * @var string
     */
    private $texto;

    private $filtros ;

    public function __construct($filtros)
    {
        parent::__construct();

        $this->SetMargins(10, 8, 8);
        $this->Open();
        $this->SetAutoPageBreak(false, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->exibeHeader(true);

        $this->mostrarRodape(true);
        $this->mostrarEmissor(true);
        $this->mostrarTotalDePaginas(true);

        $this->filtros = $filtros;

        global $head1, $head3;

        $head1 = "Saldos das Contas de Disponibilidade de Recursos";
        $dtInicial = db_formatar($this->filtros->dataInicial, "d");
        $dtFinal = db_formatar($this->filtros->dataFinal, "d");
        $head3 = "Data: {$dtInicial} à {$dtFinal}";
    }

    /**
     * @param array $dados
     * @param DBDate $dataInicial
     * @param DBDate $dataFinal
     * @return string
     * @throws Exception
     */
    public function emitir(array $dados)
    {
        $this->AddPage("L");
        $this->SetFont('Arial', 'B', 10);
        $this->SetY($this->GetY() + 2);
        $this->cell(150, 4, "Descrição", "", 0, "C", 0);
        $this->cell(50, 4, "Saldo Anterior", "", 0, "C", 0);
        $this->cell(50, 4, "Saldo Atual", "", 1, "C", 0);

        $this->SetFont('Arial', '', 10);
        for ($i = 0; $i <=5; $i++) {
            $oDados = $dados[$i];
            $espaco = "";
            if ($i >= 1 && $i <= 4) {
                  $espaco = "  ";
            }

            $this->cell(150, 4, $espaco.$oDados->tipo, "", 0, "L", 0);
            $this->cell(50, 4, $oDados->saldo_anterior, "", 0, "R", 0);
            $this->cell(50, 4, $oDados->saldo_atual, "", 1, "R", 0);
        }
        $this->ln();

        $this->cell(150, 4, "  Total", "", 0, "L", 0);
        $this->cell(50, 4, $dados[6]->total_saldo_anterior, "", 0, "R", 0);
        $this->cell(50, 4, $dados[6]->total_saldo_atual, "", 1, "R", 0);

        $this->cell(150, 4, "  Diferença", "", 0, "L", 0);
        $this->cell(50, 4, $dados[6]->diferenca_saldo_anterior, "", 0, "R", 0);
        $this->cell(50, 4, $dados[6]->diferenca_saldo_atual, "", 1, "R", 0);

        return $this->imprimir();
    }

    protected function imprimir()
    {
        $fileName = 'tmp/saldo_contas_disponibilidade_recursos_' . time() . '.pdf';
        $this->Output($fileName, false, true);
        return  $fileName;
    }
}
