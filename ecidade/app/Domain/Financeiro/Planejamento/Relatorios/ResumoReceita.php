<?php


namespace App\Domain\Financeiro\Planejamento\Relatorios;

/**
 * Class ResumoReceita
 * Resumo da projecao da receita.
 * @package App\Domain\Financeiro\Planejamento\Relatorios
 */
class ResumoReceita extends Pdf
{
    protected $wValor = 25;
    protected $titulo = 'Resumo da Projeção da Receita';

    protected $fonte = 7;

    protected $adicionaSeparadores = [
        '470000000000000',
        '480000000000000',
        '900000000000000',
    ];

    public function emitir()
    {
        $this->headers($this->titulo);
        $this->capa($this->titulo);
        $this->imprimeDados();

        $filename = sprintf('tmp/projecao-receita-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function imprimeDados()
    {
        $this->cabecalho();
        foreach ($this->dados['dados'] as $receita) {
            $wTitulo = $this->wTitulo;

            if (in_array($receita->fonte, $this->adicionaSeparadores)) {
                $this->ln();
                $this->Line(10, $this->GetY(), 203, $this->GetY());
            }
            if ($receita->nivel > 1) {
                $x = $receita->nivel * 2;
                $this->SetX(10+ $x);
                $wTitulo -= $x;
            }

            if ($receita->nivel < 3) {
                $this->SetFont('Arial', 'B', 7);
            }

            $this->cellAdapt($this->fonte, $wTitulo, $this->alturaLinha, $receita->descricao, 0);
            foreach ($this->exercicios as $exercicio) {
                $this->imprimeValor($receita->{"valor_{$exercicio}"});
            }

            $this->Ln();
            $this->Line(10, $this->GetY(), 203, $this->GetY());
            $this->SetFont('Arial', '', 7);
        }

        $this->SetFont('Arial', 'B', 7);
        $this->Cell($this->wTitulo, $this->alturaLinha, 'Total', 'BTR');
        foreach ($this->dados['totalizador'] as $valor) {
            $this->imprimeValor($valor);
        }
    }

    protected function imprimeValor($valor, $h = 4)
    {
        $this->Cell($this->wValor, $h, formataValorMonetario($valor), 'LBT', 0, 'R');
    }

    private function cabecalho()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $h = $this->alturaLinha * 2;

        if (count($this->exercicios) === 1) {
            $this->wValores = 50;
            $this->wValor = 50;
            $this->wTitulo = 143;
        }

        $this->Cell($this->wTitulo, $h, 'ORIGENS', 'BTR', 0, 'C');
        $this->Cell($this->wValores, $this->alturaLinha, 'PREVISÃO ATÉ O TÉRMINO DE', 'BTL', 1, 'C');
        $this->SetX($this->wTitulo +10);
        foreach ($this->exercicios as $exercicio) {
            $this->Cell($this->wValor, $this->alturaLinha, $exercicio, 'BTL', 0, 'C');
        }
        $this->ln();
        $this->SetFont('Arial', '', 7);
    }
}
