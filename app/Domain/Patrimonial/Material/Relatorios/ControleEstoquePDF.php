<?php

namespace App\Domain\Patrimonial\Material\Relatorios;

use DBDate;
use ECidade\Patrimonial\Material\Helpers\Material as MaterialHelper;
use FpdfMultiCellBorder;

class ControleEstoquePDF extends FpdfMultiCellBorder
{
    /**
     * @var array
     */
    private $depositos;

    public function __construct(array $depositos, $dadosCabecalho)
    {
        parent::__construct('L');
        global $head1;
        global $head3;
        global $head4;
        $head1 = "RELATÓRIO CONTROLE DE ESTOQUE";
        $material = $dadosCabecalho->material;
        $head3 = sprintf("Material: %s - %s", $material->m60_codmater, $material->m60_descr);
        if (!empty($dadosCabecalho->dataInicial) && !empty($dadosCabecalho->dataFinal)) {
            $head4 = sprintf(
                "De %s Até %s",
                db_formatar($dadosCabecalho->dataInicial, 'd'),
                db_formatar($dadosCabecalho->dataFinal, 'd')
            );
        } elseif (!empty($dadosCabecalho->dataInicial)) {
            $head4 = sprintf("Apartir de %s", db_formatar($dadosCabecalho->dataInicial, 'd'));
        } elseif (!empty($dadosCabecalho->dataFinal)) {
            $head4 = sprintf("Até %s", db_formatar($dadosCabecalho->dataFinal, 'd'));
        }

        $this->depositos = $depositos;
    }

    protected function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->SetMargins(8, 8, 8);
        $this->Open();
        $this->SetAutoPageBreak(true, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', 'B', 9);
        $this->exibeHeader(true);
        $this->setExibeBrasao(true);
    }

    protected function imprimir()
    {
        $fileName = 'tmp/relatorio_controle_estoque_' . time() . '.pdf';
        $this->Output($fileName, false, true);
        return [
            "name" => "Relatório Controle de Estoque PDF",
            "path" => $fileName,
            "file" => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    public function emitirPdf()
    {
        $this->initPdf();
        $this->AddPage();

        $totalGeral = (object)[
            "quantidade_entrada" => 0,
            "valor_entrada" => 0,
            "quantidade_saida" => 0,
            "valor_saida" => 0,
            "quantidade_total" => 0,
            "valor_total" => 0,
            "quantidade_anterior" => 0,
            "valor_anterior" => 0
        ];

        foreach ($this->depositos as $deposito) {
            $totalGeral->quantidade_anterior += $deposito->quantidade_anterior;
            $totalGeral->valor_anterior += round($deposito->saldo_anterior, 2);

            if ($this->getAvailHeight() < 25) {
                $this->AddPage('L');
            }
            $this->SetFillColor(225);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(180, 5, '', 0, 1, "L");
            $this->Cell(180, 5, $deposito->codigo . " - " . $deposito->descricao, 0, 0, "L");
            $quantidadeAnterior = MaterialHelper::arredondarQuantidade($deposito->quantidade_anterior);
            $this->Cell(50, 5, 'Quantidade anterior: ' . $quantidadeAnterior, 0, 0);
            $saldoAnterior = $this->formatarValor($deposito->saldo_anterior);
            $this->Cell(50, 5, 'Saldo anterior: ' . $saldoAnterior, 0, 1);

            $this->headerMovimentos();

            $cor = true;
            foreach ($deposito->lancamentos as $movimento) {
                if ($this->getAvailHeight() < 6) {
                    $cor = true;
                    $this->AddPage('L');
                    $this->headerMovimentos();
                }
                $cor = !$cor;
                $data = DBDate::create($movimento->data);
                $this->Cell(15, 4, $data->getDate(DBDate::DATA_PTBR), 0, 0, "L", $cor);
                $this->Cell(10, 4, $movimento->matestoqueini, 0, 0, "L", $cor);
                $this->Cell(80, 4, $movimento->descricao_movimentacao, 0, 0, "L", $cor);
                $this->Cell(25, 4, $movimento->login, 0, 0, "L", $cor);
                $quantidadeEntrada = MaterialHelper::arredondarQuantidade($movimento->quantidade_entrada);
                $this->Cell(15, 4, (string)$quantidadeEntrada, 0, 0, "C", $cor);
                $this->Cell(15, 4, $this->formatarValor($movimento->valor_unitario_entrada, 4), 0, 0, "L", $cor);
                $this->Cell(20, 4, $this->formatarValor($movimento->total_entrada), 0, 0, "L", $cor);
                $quantidadeSaida = MaterialHelper::arredondarQuantidade($movimento->quantidade_saida);
                $this->Cell(15, 4, (string)$quantidadeSaida, 0, 0, "C", $cor);
                $this->Cell(15, 4, $this->formatarValor($movimento->valor_unitario_saida, 4), 0, 0, "L", $cor);
                $this->Cell(20, 4, $this->formatarValor($movimento->total_saida), 0, 0, "L", $cor);
                $quantidadeSaldo = MaterialHelper::arredondarQuantidade($movimento->saldo_quantidade);
                $this->Cell(15, 4, (string)$quantidadeSaldo, 0, 0, "C", $cor);
                $this->Cell(15, 4, $this->formatarValor($movimento->saldo_valor_unitario, 4), 0, 0, "L", $cor);
                $this->Cell(20, 4, $this->formatarValor($movimento->saldo_subtotal), 0, 1, "L", $cor);
            }
            $this->SetFillColor(225);
            $this->SetFont('Arial', 'B', 7);

            $initial = (object)[
                "quantidade_entrada" => 0,
                "total_entrada" => 0,
                "quantidade_saida" => 0,
                "total_saida" => 0
            ];
            $totais = array_reduce($deposito->lancamentos, function ($carry, $item) {
                $carry->quantidade_entrada += $item->quantidade_entrada;
                $carry->total_entrada += $item->total_entrada;
                $carry->quantidade_saida += $item->quantidade_saida;
                $carry->total_saida += $item->total_saida;
                return $carry;
            }, $initial);

            $ultimaMovimentacao = array_pop($deposito->lancamentos);
            $this->Cell(130, 4, "Totais: ", "TB", 0, "R", 1);
            $quantidadeTotalEntrada = (string)MaterialHelper::arredondarQuantidade($totais->quantidade_entrada);
            $this->Cell(15, 4, $quantidadeTotalEntrada, "TB", 0, "C", 1);
            $this->Cell(15, 4, "", "TB", 0, "C", 1);
            $this->Cell(20, 4, $this->formatarValor($totais->total_entrada), "TB", 0, "L", 1);
            $quantidadeTotalSaida = (string)MaterialHelper::arredondarQuantidade($totais->quantidade_saida);
            $this->Cell(15, 4, $quantidadeTotalSaida, "TB", 0, "C", 1);
            $this->Cell(15, 4, "", "TB", 0, "C", 1);
            $this->Cell(20, 4, $this->formatarValor($totais->total_saida), "TB", 0, "L", 1);
            $quantidadeFinal = (string)MaterialHelper::arredondarQuantidade($ultimaMovimentacao->saldo_quantidade);
            $this->Cell(15, 4, $quantidadeFinal, "TB", 0, "C", 1);
            $this->Cell(15, 4, $this->formatarValor($ultimaMovimentacao->saldo_valor_unitario, 4), "TB", 0, "L", 1);
            $this->Cell(20, 4, $this->formatarValor($ultimaMovimentacao->saldo_subtotal), "TB", 1, "L", 1);

            $totalGeral->quantidade_entrada += $totais->quantidade_entrada;
            $totalGeral->valor_entrada += $totais->total_entrada;
            $totalGeral->quantidade_saida += $totais->quantidade_saida;
            $totalGeral->valor_saida += $totais->total_saida;
            $totalGeral->quantidade_total += $ultimaMovimentacao->saldo_quantidade;
            $totalGeral->valor_total += $ultimaMovimentacao->saldo_subtotal;

            if ($deposito->tem_ajustes) {
                $observacao = sprintf(
                    '%s - %s %s',
                    '* AJUSTE ESTOQUE',
                    'Ajuste correspondente a correção de distorções meramente contábeis,',
                    'causadas pela variação de preços.'
                );
                $this->SetFont('Arial', '', 6);
                $this->Cell(190, 4, $observacao, 0, 1);
            }
        }

        $this->Cell(180, 5, '', 0, 1);
        $this->headerTotalGeral();
        $this->imprimirTotalGeral($totalGeral);

        return $this->imprimir();
    }

    private function formatarValor($valor, $decimais = 2)
    {
        $valorFormatado = number_format($valor, $decimais, ',', '.');
        if ($valorFormatado == '-0,00') {
            $valorFormatado = '0,00';
        }
        return "R$ " . $valorFormatado;
    }

    private function headerMovimentos()
    {
        $this->SetFillColor(225);
        $this->SetFont('Arial', 'B', 7);

        $this->Cell(15, 8, "Data", "TRB", 0, "C", 1);
        $this->Cell(10, 8, "Lanc.", 1, 0, "C", 1);
        $this->Cell(80, 8, "Descr.", 1, 0, "C", 1);
        $this->Cell(25, 8, "Login", 1, 0, "C", 1);
        $this->Cell(50, 4, "Entradas", 1, 0, "C", 1);
        $this->Cell(50, 4, "Saídas", 1, 0, "C", 1);
        $this->Cell(50, 4, "Saldo", "LTB", 1, "C", 1);
        $this->SetX(138);
        $this->Cell(15, 4, "Quant.", 1, 0, "C", 1);
        $this->Cell(15, 4, "Vlr. Unit.", 1, 0, "C", 1);
        $this->Cell(20, 4, "Total", 1, 0, "C", 1);
        $this->Cell(15, 4, "Quant.", 1, 0, "C", 1);
        $this->Cell(15, 4, "Vlr. Unit.", 1, 0, "C", 1);
        $this->Cell(20, 4, "Total", 1, 0, "C", 1);
        $this->Cell(15, 4, "Quant.", 1, 0, "C", 1);
        $this->Cell(15, 4, "Vlr. Unit.", 1, 0, "C", 1);
        $this->Cell(20, 4, "Total", "LTB", 1, "C", 1);

        $this->SetFont('Arial', '', 7);
    }

    private function headerTotalGeral()
    {
        $this->SetFillColor(225);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(130, 8, "Consolidado ", "TRB", 0, "R", 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(50, 4, "Entradas", 1, 0, "C", 1);
        $this->Cell(50, 4, "Saídas", 1, 0, "C", 1);
        $this->Cell(50, 4, "Saldo", "LTB", 1, "C", 1);
        $this->SetX(138);
        $this->Cell(23, 4, "Quant.", 1, 0, "C", 1);
        $this->Cell(27, 4, "Total", 1, 0, "C", 1);
        $this->Cell(23, 4, "Quant.", 1, 0, "C", 1);
        $this->Cell(27, 4, "Total", 1, 0, "C", 1);
        $this->Cell(23, 4, "Quant.", 1, 0, "C", 1);
        $this->Cell(27, 4, "Total", "LTB", 1, "C", 1);
    }

    private function imprimirTotalGeral($totalGeral)
    {
        $quantidadeAnterior = MaterialHelper::arredondarQuantidade($totalGeral->quantidade_anterior);
        $this->Cell(50, 4, 'Quantidade anterior: ' . $quantidadeAnterior, "TB", 0, 'L', 1);
        $valorAnterior = $this->formatarValor($totalGeral->valor_anterior);
        $this->Cell(50, 4, 'Saldo anterior: ' . $valorAnterior, "TB", 0, 'L', 1);

        $this->Cell(30, 4, 'Total geral: ', "TB", 0, "R", 1);
        $totalGeralQuantidadeEntrada = (string)MaterialHelper::arredondarQuantidade($totalGeral->quantidade_entrada);
        $this->Cell(23, 4, $totalGeralQuantidadeEntrada, "TB", 0, "C", 1);
        $this->Cell(27, 4, $this->formatarValor($totalGeral->valor_entrada), "TB", 0, "C", 1);
        $totalGeralQuantidadeSaida = (string)MaterialHelper::arredondarQuantidade($totalGeral->quantidade_saida);
        $this->Cell(23, 4, $totalGeralQuantidadeSaida, "TB", 0, "C", 1);
        $this->Cell(27, 4, $this->formatarValor($totalGeral->valor_saida), "TB", 0, "C", 1);
        $totalGeralQuantidadeEstoque = (string)MaterialHelper::arredondarQuantidade($totalGeral->quantidade_total);
        $this->Cell(23, 4, $totalGeralQuantidadeEstoque, "TB", 0, "C", 1);
        $this->Cell(27, 4, $this->formatarValor($totalGeral->valor_total), "TB", 1, "C", 1);
    }
}
