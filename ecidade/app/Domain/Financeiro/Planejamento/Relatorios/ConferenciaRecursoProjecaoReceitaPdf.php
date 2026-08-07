<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios;

use stdClass;

class ConferenciaRecursoProjecaoReceitaPdf extends Pdf
{
    protected $wLinha = 279;
    /**
     * @var int
     */
    protected $wDadosReceita = 146;
    /**
     * @var float|int
     */
    protected $wValoresProjetados;

    protected $titulo = 'Conferência dos Recursos - Demonstrativo das Projeções da Receita';

    public function __construct($orientation = 'L', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
    }

    public function emitir()
    {
        $this->calculaColunas();
        $this->headers($this->titulo);

        $this->imprimeDados();

        $filename = sprintf('tmp/conferencia-projecao-receita-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    protected function calculaColunas()
    {
        $exercicios = $this->dados['planejamento']['exercicios'];
        $totalColunaValores = count($exercicios) + 1;

        $this->wValor = ($this->wLinha - $this->wDadosReceita) / $totalColunaValores;
        $this->wValoresProjetados = $this->wValor * count($exercicios);
    }

    protected function imprimeDados()
    {
        $this->cabecalhoReceita();
        foreach ($this->dados['dados'] as $receita) {
            if ($this->getAvailHeight() < 6) {
                $this->cabecalhoReceita();
            }
            $this->imprimeLinha($receita, $this->dados['planejamento']['exercicios']);
        }
        $this->imprimirTotalizador();
    }

    protected function imprimirTotalizador()
    {
        $w = $this->wLinha - $this->wValoresProjetados;

        if ($this->getAvailHeight() < 6) {
            $this->cabecalhoReceita();
        }
        $this->SetFont('Arial', 'B', 7);
        $this->Cell($w, 5, 'Total da projeção', 1, 0, 'R');
        foreach ($this->dados['totalizador'] as $valor) {
            $this->imprimeValor($valor);
        }
        $this->ln();
        $this->SetFont('Arial', '', 7);
    }

    protected function imprimeValor($valor, $h = 5)
    {
        $this->Cell($this->wValor, $h, formataValorMonetario($valor), 1, 0, 'R');
    }

    protected function cabecalhoReceita()
    {
        $exercicioProjecao = $this->dados['planejamento']['pl2_ano_inicial'] - 1;
        $exercicios = $this->dados['planejamento']['exercicios'];

        $totalColunaValores = count($exercicios) + 1;
        $this->wValor = ($this->wLinha - $this->wDadosReceita) / $totalColunaValores;

        $this->wValoresProjetados = $this->wValor * count($exercicios);

        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wDadosReceita, 5, 'Dados da Receita', 1, 0, 'C');
        $this->cellAdapt(8, $this->wValor, 5, "Previsão Atualizada", 'LRT', 0, 'C');
        $this->Cell($this->wValoresProjetados, 5, "Valores Projetados", 1, 1, 'C');

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, 'Estrutural', 1, 0, 'C');
        $this->Cell(76, 5, 'Descrição', 1, 0, 'C');
        $this->Cell(25, 5, 'Recurso Anterior', 1, 0, 'C');
        $this->Cell(25, 5, 'Recurso Exercício', 1, 0, 'C');

        $this->Cell($this->wValor, 5, $exercicioProjecao, 'RLB', 0, 'C');

        foreach ($exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0, 'C');
        }

        $this->Ln();
        $this->SetFont('Arial', '', 7);
    }

    /**
     * @param stdClass $receita
     * @param $exercicios
     * @return void
     */
    protected function imprimeLinha(stdClass $receita, $exercicios)
    {

        $this->SetFont('Arial', '', 6);
        if ($receita->sintetico) {
            $this->SetFont('Arial', 'B', 6);
        }
        $this->cellAdapt(6, 20, 5, $receita->fonte, 1, 0, 'C');
        $this->cellAdapt(6, 76, 5, $receita->descricao, 1, 0, 'L');
        $this->Cell(25, 5, "{$receita->recurso_original} - {$receita->complemento}", 1, 0, 'C');
        $this->Cell(25, 5, "{$receita->recurso} - {$receita->complemento}", 1, 0, 'C');


        $this->imprimeValor($receita->valor_base);
        foreach ($exercicios as $exercicio) {
            $this->imprimeValor($receita->{"valor_{$exercicio}"});
        }

        $this->ln();
    }
}
