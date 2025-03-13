<?php

namespace App\Domain\Tributario\Arrecadacao\Reports;

use App\Domain\Tributario\Arrecadacao\Models\Operacoesrealizadastef;

abstract class RelatorioPendentesTef extends \GenericPdf
{
    /**
     * @var string
     */
    private $descricaoRelatorio = "OPERAÇÕES PENDENTES";

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
        $this->addpage("L");
        $this->setfillcolor(235);
        $this->setfont('arial', 'B', 9);

        $this->setfont('arial', 'B', 10);
        $this->setY(40);
        $this->setX(10);
        $this->Cell(279, 6, $this->descricaoRelatorio, 1, 0, "C", 1);
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
        $this->Cell(54, $height, "Bandeira", 1, 0, "C");

        $this->setY(50);
        $this->setX(139);
        $this->Cell(15, $height, "Parcela", 1, 0, "C");

        $this->setY(50);
        $this->setX(154);
        $this->Cell(45, $height, "Operação", 1, 0, "C");

        $this->setY(50);
        $this->setX(199);
        $this->Cell(25, $height, "Valor", 1, 0, "C");

        $this->setY(50);
        $this->setX(224);
        $this->Cell(25, $height, "AUT", 1, 0, "C");

        $this->setY(50);
        $this->setX(249);
        $this->Cell(40, $height, "Ação", 1, 0, "C");
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
            $this->Cell(54, $height, substr($oOperacao->getBandeira(), 0, 25), 1, 0, "C");

            $this->setY($altura);
            $this->setX(139);
            $this->Cell(15, $height, $oOperacao->getParcela(), 1, 0, "C");

            $this->setY($altura);
            $this->setX(154);
            $this->Cell(45, $height, substr($oOperacao->operacoesTef->getDescricao(), 0, 21), 1, 0, "C");

            $this->setY($altura);
            $this->setX(199);
            $this->Cell(25, $height, formataValorMonetario($oOperacao->getValor()), 1, 0, "C");

            $this->setY($altura);
            $this->setX(224);
            $this->Cell(25, $height, $oOperacao->getCodigoaprovacao(), 1, 0, "C");

            $sAcao = "DESFAZER";

            if ($oOperacao->getConcluidobaixabanco()) {
                $sAcao = "CONFIRMAR";
            }

            $this->setY($altura);
            $this->setX(249);
            $this->Cell(40, $height, $sAcao, 1, 0, "C");

            $altura += $height;
        }
    }
}
