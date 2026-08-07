<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios;

use stdClass;

class ConferenciaRecursoProjecaoReceitaPorRecursoPdf extends ConferenciaRecursoProjecaoReceitaPdf
{
    protected $titulo = 'Conferência dos Recursos - Demonstrativo das Projeções da Receita - por Recurso';

    protected $wDadosReceita = 192;

    protected function cabecalhoReceita()
    {
        $exercicioProjecao = $this->dados['planejamento']['pl2_ano_inicial'] - 1;
        $exercicios = $this->dados['planejamento']['exercicios'];

        $totalColunaValores = count($exercicios) + 1;

        $this->wValor = ($this->wLinha - $this->wDadosReceita) / $totalColunaValores;
        $this->wValoresProjetados = $this->wValor * count($exercicios);

        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wDadosReceita, 5, 'Recursos', 1, 0, 'C');
        $this->cellAdapt(8, $this->wValor, 5, "Previsão Atualizada", 'LRT', 0, 'C');
        $this->Cell($this->wValoresProjetados, 5, "Valores Projetados", 1, 1, 'C');

        $this->SetFont('Arial', 'B', 6);
        $this->Cell(20, 5, 'Recurso Anterior', 1, 0, 'C');
        $this->Cell(20, 5, 'Recurso Exercício', 1, 0, 'C');
        $this->Cell(76, 5, 'Descrição Anterior', 1, 0, 'C');
        $this->Cell(76, 5, 'Descrição Exercício', 1, 0, 'C');

        $this->Cell($this->wValor, 5, $exercicioProjecao, 'RLB', 0, 'C');

        foreach ($exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0, 'C');
        }

        $this->Ln();
        $this->SetFont('Arial', '', 7);
    }

    /**
     * @param $recurso
     * @param $exerciciosAnteriores
     * @param $exercicios
     */
    protected function imprimeLinha(stdClass $recurso, $exercicios)
    {
        $this->SetFont('Arial', '', 6);
        $this->cellAdapt(6, 20, 5, "$recurso->recurso_original - $recurso->complemento", 1, 0, 'C');
        $this->cellAdapt(6, 20, 5, "$recurso->recurso - $recurso->complemento", 1, 0, 'C');
        $this->cellAdapt(6, 76, 5, $recurso->descricao, 1, 0, 'L');
        $this->cellAdapt(6, 76, 5, $recurso->descricao_recurso_original, 1, 0, 'L');
        $this->imprimeValor($recurso->valor_base);
        foreach ($exercicios as $exercicio) {
            $this->imprimeValor($recurso->{"valor_{$exercicio}"});
        }

        $this->ln();
    }
}
