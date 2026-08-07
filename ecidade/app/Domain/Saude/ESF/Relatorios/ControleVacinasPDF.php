<?php

namespace App\Domain\Saude\ESF\Relatorios;

use ECidade\Enum\Common\FaixaEtariaEnum;
use ECidade\Enum\Saude\ESF\SituacaoPacienteVacinacaoEnum;
use FpdfMultiCellBorder;

class ControleVacinasPDF extends FpdfMultiCellBorder
{
    /**
     * @var array
     */
    private $dados;

    /**
     * @var int
     */
    private $totalVacinas = 0;

    /**
     * @param array $dados
     */
    public function __construct(array $dados, \stdClass $request)
    {
        parent::__construct();
        $this->dados = $dados;

        global $head2;
        global $head4;
        global $head5;
        global $head6;

        $head2 = 'Relatório de Controle de Vacinas Aplicadas';

        $periodoInicial = db_formatar($request->periodoInicial, 'd');
        $periodoFinal = db_formatar($request->periodoFinal, 'd');

        $situacao = empty($request->situacao) ?
            'TODAS' :
            (new SituacaoPacienteVacinacaoEnum((int)$request->situacao))->name();

        $faixaEtaria = empty($request->faixaEtaria) ?
            'TODAS' :
            (new FaixaEtariaEnum((int)$request->faixaEtaria))->name();

        $head4 = "Período: {$periodoInicial} até {$periodoFinal}";
        $head5 = "Situação/Condição: {$situacao}";
        $head6 = "Faixa Etária: {$faixaEtaria}";
    }

    public function emitir()
    {
        $this->initPdf();
        $this->AddPage();

        foreach ($this->dados as $unidade) {
            if ($this->getAvailHeight() < 31) {
                $this->AddPage();
            }

            $this->SetFont('Arial', 'B', 8);
            $this->Cell(195, 5, $unidade->descricao, 0, 1);

            $this->montaCabecalho();
            $this->SetFont('Arial', '', 7);
            foreach ($unidade->vacinas as $vacina) {
                $this->montaLinha($vacina, $linha);
            }
    
            $this->Cell(1, 4, '', 0, 1);
        }

        if (count($this->dados) > 1) {
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(150, 4, 'TOTAL GERAL:', 0, 0, 'R');
            $this->SetFont('Arial', '', 7);
            $this->Cell(45, 4, "{$this->totalVacinas} vacinas aplicadas", 0, 1, 'L');
        }

        return $this->imprimir();
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
        $this->SetFont('Arial', 'B', 9);
        $this->exibeHeader(true);
    }

    private function montaCabecalho()
    {
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(80, 5, 'Imunobiológico', 1, 0, 'C', 1);
        $this->Cell(60, 5, 'Estratégia', 1, 0, 'C', 1);
        $this->Cell(34, 5, 'Dose', 1, 0, 'C', 1);
        $this->Cell(20, 5, 'Quantidade', 1, 1, 'C', 1);
    }

    private function montaLinha($vacina, &$linha)
    {
        $linha = 0;
        foreach ($vacina->estrategias as $estrategia) {
            foreach ($estrategia->doses as $dose) {
                if ($this->getAvailHeight() < 21) {
                    $this->AddPage();
                    $this->montaCabecalho();
                    $this->SetFont('Arial', '', 7);
                    $linha = 0;
                }
                
                $linha++;
                $color = !!($linha % 2);

                $this->Cell(80, 5, $vacina->descricao, 0, 0, 'C', $color);
                $this->Cell(60, 5, $estrategia->descricao, 0, 0, 'C', $color);
                $this->Cell(34, 5, $dose->descricao, 0, 0, 'C', $color);
                $this->Cell(20, 5, $dose->quantidade, 0, 1, 'C', $color);
            }
        }

        $this->Cell(1, 1, '', 0, 1);
        $this->Cell(150, 4, 'Total por Estratégia:', 0, 0, 'R');
        $x = $this->GetX();
        $total = 0;
        foreach ($vacina->estrategias as $estrategia) {
            $total += $estrategia->total;
            $this->SetX($x);
            $this->Cell(45, 4, "{$estrategia->descricao} - {$estrategia->total}", 0, 1, 'L');
        }

        $this->Cell(1, 1, '', 0, 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(150, 4, 'TOTAL:', 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->Cell(45, 4, "{$total} vacinas aplicadas", 0, 1, 'L');
        $this->Cell(1, 3, '', 0, 1);

        $this->totalVacinas += $total;
    }

    private function imprimir()
    {
        $fileName = 'tmp/controle_vacinas_aplicadas' . time() . '.pdf';
        $this->Output($fileName, false, true);

        return [
            "name" => "Relatório de Controle de Vacinas Aplicadas",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }
}
