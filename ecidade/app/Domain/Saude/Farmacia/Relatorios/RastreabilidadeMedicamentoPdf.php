<?php

namespace App\Domain\Saude\Farmacia\Relatorios;

use App\Domain\Patrimonial\Material\Relatorios\RelatorioRastreabilidade;

class RastreabilidadeMedicamentoPdf extends RelatorioRastreabilidade
{
    const QUEBRA_MEDICAMENTO = 1;
    const QUEBRA_DEPOSITO = 2;

    private $quebra;

    public function __construct(array $dados, $quebra)
    {
        $this->dados = $dados;
        $this->quebra = $quebra;
        parent::__construct('L');
        $this->addTitulo('Relatório Rastreabilidade Medicamento');
    }

    protected function imprimir()
    {
        $nomeArquivo = 'tmp/rastreabilidade-medicamento'. time() . '.pdf';
        $this->output('F', $nomeArquivo);

        return [
            "name" => "Relatório Rastreabilidade Medicamento",
            "path" => $nomeArquivo,
            'pathExterno' => ECIDADE_REQUEST_PATH . $nomeArquivo
        ];
    }

    protected function escreverArquivo(array $dados)
    {
        $quebras = [self::QUEBRA_MEDICAMENTO, self::QUEBRA_DEPOSITO];
        if (!in_array($this->quebra, $quebras)) {
            $this->imprimirCabecalho();
        }
        
        $linhaImpressa = 0;
        foreach ($dados as $dado) {
            if ($this->getAvailableHeight() < 4 && !in_array($this->quebra, $quebras)) {
                $this->addPage();
                $this->imprimirCabecalho();
                $linhaImpressa = 0;
            }
            $linhaImpressa++;
            $cor = !($linhaImpressa % 2);
            $this->imprimirLinha($dado, $cor);
        }
    }

    private function imprimirCabecalho()
    {
        $this->setFont('Arial', 'B', 8);
        $this->cell(75, 5, 'MEDICAMENTO', 1, 0, 'C', true);
        $this->cell(25, 5, 'UNIDADE', 1, 0, 'C', true);
        $this->cell(75, 5, 'DEPÓSITO', 1, 0, 'C', true);
        $this->cell(25, 5, 'LOCALIZAÇÃO', 1, 0, 'C', true);
        $this->cell(20, 5, 'LOTE', 1, 0, 'C', true);
        $this->cell(20, 5, 'VALIDADE', 1, 0, 'C', true);
        $this->cell(21, 5, 'QUANTIDADE', 1, 0, 'C', true);
        $this->cell(20, 5, 'VALOR', 1, 1, 'C', true);
    }

    private function imprimirLinha($estoque, $cor)
    {
        if (property_exists($estoque, 'estoques') && is_array($estoque->estoques)) {
            return $this->imprimirLinhaComQuebra($estoque);
        }

        $this->setFont('Arial', '', 7);
        $this->cellAdapt(7, 75, 4, "{$estoque->idMedicamento} - {$estoque->medicamento}", 0, 0, 'L', $cor);
        $this->cellAdapt(7, 25, 4, $estoque->unidadeMedida, 0, 0, 'C', $cor);
        $this->cellAdapt(7, 75, 4, "{$estoque->idDepartamento} - {$estoque->departamento}", 0, 0, 'L', $cor);
        $this->cellAdapt(7, 25, 4, $estoque->localizacao, 0, 0, 'L', $cor);
        $this->cellAdapt(7, 20, 4, $estoque->lote, 0, 0, 'C', $cor);
        $this->cell(20, 4, $estoque->validade, 0, 0, 'C', $cor);
        $this->cell(21, 4, $estoque->quantidade, 0, 0, 'R', $cor);
        $this->cell(20, 4, $estoque->valor, 0, 1, 'R', $cor);
    }

    private function imprimirCabecalhoQuebra($dados = null)
    {
        $this->setFont('Arial', 'B', 8);

        if ($dados != null) {
            $this->cell(281, 5, "{$dados->id} - {$dados->descricao}", 0, 1);
        }
        
        if ($this->quebra == self::QUEBRA_MEDICAMENTO) {
            $this->cell(101, 5, 'DEPÓSITO', 1, 0, 'C', true);
            $this->cell(30, 5, 'UNIDADE', 1, 0, 'C', true);
        } else {
            $this->cell(101, 5, 'MEDICAMENTO', 1, 0, 'C', true);
            $this->cell(30, 5, 'UNIDADE', 1, 0, 'C', true);
        }
        $this->cell(35, 5, 'LOCALIZAÇÃO', 1, 0, 'C', true);
        $this->cell(30, 5, 'LOTE', 1, 0, 'C', true);
        $this->cell(30, 5, 'VALIDADE', 1, 0, 'C', true);
        $this->cell(25, 5, 'QUANTIDADE', 1, 0, 'C', true);
        $this->cell(30, 5, 'VALOR', 1, 1, 'C', true);
    }

    private function imprimirLinhaComQuebra($dados)
    {
        if ($this->getAvailableHeight() < 14) {
            $this->addPage();
        }

        $this->imprimirCabecalhoQuebra($dados);
        $linhaImpressa = 0;
        foreach ($dados->estoques as $estoque) {
            if ($this->getAvailableHeight() < 4) {
                $this->addPage();
                $this->imprimirCabecalhoQuebra();
                $linhaImpressa = 0;
            }

            $linhaImpressa++;
            $cor = !($linhaImpressa % 2);

            $this->setFont('Arial', '', 7);
            if ($this->quebra == self::QUEBRA_MEDICAMENTO) {
                $this->cellAdapt(7, 101, 4, "{$estoque->idDepartamento} - {$estoque->departamento}", 0, 0, 'L', $cor);
                $this->cellAdapt(7, 30, 4, $estoque->unidadeMedida, 0, 0, 'C', $cor);
            } else {
                $this->cellAdapt(7, 101, 4, "{$estoque->idMedicamento} - {$estoque->medicamento}", 0, 0, 'L', $cor);
                $this->cellAdapt(7, 30, 4, $estoque->unidadeMedida, 0, 0, 'C', $cor);
            }
            $this->cellAdapt(7, 35, 4, $estoque->localizacao, 0, 0, 'L', $cor);
            $this->cellAdapt(7, 30, 4, $estoque->lote, 0, 0, 'C', $cor);
            $this->cell(30, 4, $estoque->validade, 0, 0, 'C', $cor);
            $this->cell(25, 4, $estoque->quantidade, 0, 0, 'R', $cor);
            $this->cell(30, 4, $estoque->valor, 0, 1, 'R', $cor);
        }

        $this->cell(4, 3, '', 0, 1);
    }
}
