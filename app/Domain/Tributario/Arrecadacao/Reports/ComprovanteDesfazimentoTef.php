<?php

namespace App\Domain\Tributario\Arrecadacao\Reports;

use App\Domain\Tributario\Arrecadacao\Models\Operacoesrealizadastef;

abstract class ComprovanteDesfazimentoTef extends \GenericPdf
{
    /**
     * @var string
     */
    private $descricaoRelatorio = "COMPROVANTE DE DESFAZIMENTO DE OPERAÇÕES TEF";

    /**
     * @var Operacoesrealizadastef
     */
    private $operacoes;

    /**
     * @param Operacoesrealizadastef[] $operacoes
     * @return ComprovanteDesfazimentoTef
     */
    protected function setOperacoes($operacoes)
    {
        $this->operacoes = $operacoes;
        return $this;
    }

    protected function gerar()
    {
        $this->globalVariables();
        $this->headerFile();
        $this->bodyFile();

        $this->generate();
    }

    private function globalVariables()
    {
        global $head2;
        $head2 = $this->descricaoRelatorio;
    }

    private function headerFile()
    {
        $this->Open();
        $this->AliasNbPages();
        $this->addpage("P");
        $this->setfillcolor(235);
        $this->setfont('arial', 'B', 9);

        $this->setfont('arial', 'B', 10);
        $this->setY(40);
        $this->setX(10);
        $this->Cell(192, 6, $this->descricaoRelatorio, 1, 0, "C", 1);
    }

    private function bodyFile()
    {
        $this->headerTable(4);
        $this->bodyTable(5);
    }

    private function headerTable($height)
    {
        $this->setfont('arial', 'B', 9);

        $this->setY(50);
        $this->setX(10);
        $this->Cell(20, $height, "NSU", 1, 0, "C");

        $this->setY(50);
        $this->setX(30);
        $this->Cell(20, $height, "Recibo", 1, 0, "C");

        $this->setY(50);
        $this->setX(50);
        $this->Cell(35, $height, "Cartão", 1, 0, "C");

        $this->setY(50);
        $this->setX(85);
        $this->Cell(40, $height, "Bandeira", 1, 0, "C");

        $this->setY(50);
        $this->setX(125);
        $this->Cell(15, $height, "Parcela", 1, 0, "C");

        $this->setY(50);
        $this->setX(140);
        $this->Cell(40, $height, "Operação", 1, 0, "C");

        $this->setY(50);
        $this->setX(180);
        $this->Cell(22, $height, "Valor", 1, 0, "C");
    }

    private function bodyTable($height)
    {
        $this->setfont('arial', '', 9);

        $altura = 54;

        foreach ($this->operacoes as $oOperacao) {
            $this->setY($altura);
            $this->setX(10);
            $this->Cell(20, $height, $oOperacao->getNsu(), 1, 0, "C");

            $this->setY($altura);
            $this->setX(30);
            $this->Cell(20, $height, $oOperacao->getNumnov(), 1, 0, "C");

            $this->setY($altura);
            $this->setX(50);
            $this->Cell(35, $height, $oOperacao->getCartao(), 1, 0, "C");

            $this->setY($altura);
            $this->setX(85);
            $this->Cell(40, $height, substr($oOperacao->getBandeira(), 0, 17), 1, 0, "C");

            $this->setY($altura);
            $this->setX(125);
            $this->Cell(15, $height, $oOperacao->getParcela(), 1, 0, "C");

            $this->setY($altura);
            $this->setX(140);
            $this->Cell(40, $height, substr($oOperacao->operacoesTef->getDescricao(), 0, 19), 1, 0, "C");

            $this->setY($altura);
            $this->setX(180);
            $this->Cell(22, $height, formataValorMonetario($oOperacao->getValor()), 1, 0, "C");

            $altura += $height;
        }
    }
}
