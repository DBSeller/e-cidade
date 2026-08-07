<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios;

class ConferenciaRecursoProjecaoDespesaPdf extends Pdf
{

    protected $titulo = 'Conferência dos Reursos - Projeção da Despesa';
    protected $wLinha = 279;

    protected $fonte = 7;
    protected $wValoresProjetados = 20;

    public function __construct($orientation = 'L', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
    }

    public function emitir()
    {
        $this->calculaColunas();
        $this->headers($this->titulo);

        $this->imprimeDados();

        $filename = sprintf('tmp/projecao-despesa-por-agrupador-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function imprimeDados()
    {
        $this->cabecalho();
        foreach ($this->dados['dados'] as $dado) {
            $descricaoOriginal = sprintf('%s - %s', $dado->codigo_original, $dado->descricao_original);
            $descricaoExercicio = sprintf('%s - %s', $dado->codigo_exercicio, $dado->descricao_exercicio);
            $this->cellAdapt($this->fonte, $this->wSubTitulo, 4, $descricaoOriginal, 1, 0, 'L');
            $this->cellAdapt($this->fonte, $this->wSubTitulo, 4, $descricaoExercicio, 1, 0, 'L');

            $this->cellAdapt($this->fonte, $this->wValor, 4, formataValorMonetario($dado->valorBase), 1, 0, 'R');

            foreach ($this->exercicios as $exercicio) {
                $valor = formataValorMonetario($dado->exerciciosPlanejamento[$exercicio]);
                $this->cellAdapt($this->fonte, $this->wValor, 4, $valor, 1, 0, 'R');
            }
            $this->Ln();
        }

        $totalizador = $this->dados['totalizador'];
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wTituloTotalizador, 4, 'Total', 1, 0, 'R');
        $this->cellAdapt($this->fonte, $this->wValor, 4, formataValorMonetario($totalizador->valorBase), 1, 0, 'R');
        foreach ($this->exercicios as $exercicio) {
            $valor = formataValorMonetario($totalizador->exercicios[$exercicio]);
            $this->cellAdapt($this->fonte, $this->wValor, 4, $valor, 1, 0, 'R');
        }
        $this->Ln();
    }

    private function cabecalho()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wTitulo, 5, 'Dados da Despesa', 1, 0, 'C');
        $this->Cell($this->wValor, 5, 'Valor', 'LRT', 0, 'C');
        $this->cellAdapt($this->fonte, $this->wValoresProjetados, 5, 'Valores Projetados', 1, 1, 'C');

        $this->Cell($this->wSubTitulo, 5, 'Recurso Anterior', 1, 0, 'C');
        $this->Cell($this->wSubTitulo, 5, 'Recurso do Exercício', 1, 0, 'C');
        $this->Cell($this->wValor, 5, 'Base', 'LRB', 0, 'C');

        foreach ($this->exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0, 'C');
        }
        $this->Ln();
        $this->SetFont('Arial', '', 7);
    }

    private function calculaColunas()
    {

        $totalExerciciosImprimir = count($this->exercicios);
        $wExercicios = ($totalExerciciosImprimir * $this->wValor) + $this->wValor;

        $this->wTitulo = $this->wLinha - $wExercicios;
        $this->wSubTitulo = $this->wTitulo / 2;
        $this->wTituloTotalizador = $this->wTitulo;
        $this->wValoresProjetados = $this->wValor * $totalExerciciosImprimir;
    }
}
