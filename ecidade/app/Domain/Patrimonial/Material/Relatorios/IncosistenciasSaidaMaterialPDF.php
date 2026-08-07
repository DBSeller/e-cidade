<?php

namespace App\Domain\Patrimonial\Material\Relatorios;

use App\Domain\Patrimonial\Material\Models\Material;
use ECidade\Patrimonial\Material\Models\Deposito;
use ECidade\Patrimonial\Material\Repositories\DepositoRepository;
use ECidade\Pdf\Pdf;
use Exception;
use materialEstoque;
use stdClass;

class IncosistenciasSaidaMaterialPDF extends Pdf
{
    /**
     * @var []
     */
    private $dados = [];
    private $depositos = [];

    public function __construct($dados)
    {
        parent::__construct('P');
        $this->dados = $dados;
        $this->addTitulo("");
        $this->addTitulo("INCOSISTÊNCIAS SAÍDA MATERIAL");
        $this->addTitulo("");
    }

    protected function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->SetMargins(10, 8, 8);
        $this->setFillColor(230);
        $this->SetAutoPageBreak(true, 15);
        $this->exibeHeader(true);
        $this->setExibeBrasao(true);
    }

    protected function imprimir()
    {
        $fileName = 'tmp/incosistencia_saida_' . time() . '.pdf';
        $this->Output($fileName, 'F');
        return [
            "name" => "Relatório Incosistência Saída Material PDF",
            "path" => $fileName,
            "file" => ECIDADE_REQUEST_PATH . $fileName
        ];
    }
    private function imprimirCabecalhoMateriais($depositoCodigo, $depositoDescricao)
    {
        $this->SetFont('arial', 'b', 7);
        $this->Cell(180, 5, "{$depositoCodigo} - {$depositoDescricao}", 0, 1, "L", 0);
        $this->Cell(30, 5, "Código Material", 0, 0, "L", 0);
        $this->Cell(70, 5, "Descrição", 0, 0, "L", 0);
        $this->Cell(30, 5, "Quantidade Saída", 0, 0, "L", 0);
        $this->Cell(20, 5, "Saldo Estoque", 0, 1, "L", 0);
    }

    /**
     * @throws Exception
     */
    public function emitirPdf()
    {
        $this->initPdf();
        $this->AddPage();
        $this->buscarDados();

        foreach ($this->depositos as $deposito) {
            $this->imprimirCabecalhoMateriais($deposito->codigo, $deposito->descricao);
            $this->SetFont('arial', '', 7);
            $this->setFillColor(255);
            $color = 255;
            $saldoItem = new materialEstoque();
            foreach ($deposito->materiais as $material) {
                if ($this->getAvailableHeight() < 5) {
                    $this->addPage();
                    $this->setFillColor(255);
                    $color = 255;
                    $this->imprimirCabecalhoMateriais();
                    $this->SetFont('arial', '', 7);
                }
                $this->SetFont('arial', '', 7);
                if ($color == 255) {
                    $color = 243;
                } else {
                    $color = 255;
                }
                $this->setFillColor($color);
                $this->Cell(30, 5, $material->codigo, 0, 0, "L", $color);
                $this->Cell(70, 5, $material->descricao, 0, 0, "L", $color);
                $this->Cell(30, 5, $material->quantidade, 0, 0, "L", $color);
                $this->Cell(
                    20,
                    5,
                    $saldoItem->getSaldoItem($material->codigo, $material->departamento)->consulta_material,
                    0,
                    1,
                    "L",
                    $color
                );
            }
        }

        return $this->imprimir();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function buscarDados()
    {
        foreach ($this->dados as $dado) {
            $depositoRepository = new DepositoRepository();
            $deposito = $depositoRepository->scopeDepartamento($dado->m70_coddepto)->first();

            $codigoDeposito = $deposito->getCodigo();
            if (!array_key_exists($codigoDeposito, $this->depositos)) {
                $this->depositos[$codigoDeposito] = (object)[
                    'codigo' => $codigoDeposito,
                    'descricao' => $deposito->getDepartamento()->getNomeDepartamento(),
                    'materiais' => []
                ];
            }
            $material = Material::find($dado->m70_codmatmater);
            $this->depositos[$codigoDeposito]->materiais[] = (object)[
                'codigo' => $material->m60_codmater,
                'departamento' => $deposito->getDepartamento()->getCodigo(),
                'descricao' => $material->m60_descr,
                'quantidade' => $dado->m70_quant
            ];
        }
    }
}
