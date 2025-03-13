<?php

namespace ECidade\Financeiro\Tesouraria\Relatorios\Bordero;

use DBDate;
use ECidade\Financeiro\Tesouraria\Relatorios\Bordero\Services\BorderoService;
use Exception;
use FpdfMultiCellBorder;

/**
 * Class RelatorioBordero
 */
class RelatorioBordero extends FpdfMultiCellBorder
{
    /**
     * @var string
     */
    private $modeloRelatorio;
    /**
     * @var BorderoService
     */
    private $service;

    /**
     * RelatorioBordero constructor.
     * @param BorderoService $borderoService
     */
    public function __construct(BorderoService $borderoService)
    {
        /* Inicia as configurações do PDF */
        parent::__construct('P');

        $this->service = $borderoService;
        $tipo = $this->service->getTipoRelatorio();
        $this->modeloRelatorio = $tipo == BorderoService::SINTETICO ? "SINTÉTICO" : "ANALÍTICO";

        $this->buildHeaders();

        $this->Open();
        $this->AliasNbPages();
        $this->setExibeBrasao(true);
        $this->exibeHeader(true);
        $this->SetAutoPageBreak(false, 10);
        $this->SetFillColor(225);
        $this->SetMargins(8, 10);
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);

        $this->AddPage();
    }

    /**
     * @throws Exception
     */
    public function emitir()
    {
        $contas = $this->service->getDados();

        if (empty($contas)) {
            $this->setY(50);
            $this->setX(10);
            $this->SetFont('Arial', 'B', 7);
            $this->Cell(192, 4, "Não há movimentações no período selecionado", 0, 1, "C");
        }

        switch ($this->service->getTipoRelatorio()) {
            case BorderoService::ANALITICO:
                $this->imprimeAnalitico($contas);
                break;
            case BorderoService::SINTETICO:
                $this->imprimeSintetico($contas);
                break;
            default:
                throw new Exception("Modelo de relatório inválido.");
        }

        $this->output();
    }

    private function buildHeaders()
    {
        $GLOBALS["head2"] = "RELATÓRIO DE BORDERÔ {$this->modeloRelatorio}";
        $GLOBALS["head4"] = "DATA INICIAL DE EMISSÃO: {$this->service->getDataInicial()}";
        $GLOBALS["head5"] = "DATA FIM DE EMISSÃO: {$this->service->getDataFinal()}";
    }

    private function imprimeAnalitico($contas)
    {
        foreach ($contas as $datas) {
            $this->imprimeSubCabecalho($datas, $this->service->getTipoRelatorio());
            $this->imprimeCredores($datas);
        }
    }

    private function imprimeCredores($datas)
    {
        foreach ($datas as $data => $dias) {
            $this->setX(10);
            $this->SetFont('Arial', '', 6);
            $this->Cell(20, 4, db_formatar($data, 'd'), 0, 0);

            foreach ($dias->credores as $key => $credor) {
                if ($this->h-20 <= $this->GetY()) {
                    $this->AddPage();
                    $this->imprimeSubCabecalho($datas, $this->service->getTipoRelatorio());
                    $this->setX(10);
                    $this->SetFont('Arial', '', 6);
                    $this->Cell(20, 4, db_formatar($data, 'd'), 0, 0);
                }
                $this->setX(30);
                $this->cell(80, 4, $credor->credor, 0, 0);
                $empenho = $credor->empenho;
                $slip = $credor->slip;
                if (!empty($empenho)) {
                    $this->cell(22, 4, "Emp: ".$empenho, 0, 0);
                } elseif (!empty($slip)) {
                    $this->cell(22, 4, "Slip: ".$slip, 0, 0);
                } else {
                    $this->cell(22, 4, "", 0, 0);
                }
                $this->cell(20, 4, str_pad($credor->arquivo, 7, "0", STR_PAD_LEFT), 0, 0, 'R');
                $this->cell(25, 4, db_formatar($credor->valor, 'f'), 0, 0, 'R');
                $this->cell(25, 4, $credor->data_pagamento->getDate(DBDate::DATA_PTBR), 0, 1, 'R');
            }
            $this->escreveLinhaTotalDia($dias->total_dia);
        }
    }

    private function imprimeSubCabecalho($datas, $tipoRelatorio)
    {
        $dados = array_shift($datas);

        $this->setX(10);
        $this->SetFont('Arial', 'B', 7);
        $this->cell(192, 4, "", 0, 1);
        $this->setX(10);
        $this->cell(102, 4, $dados->descricao, "T", 0, "L", 1);
        $this->cell(90, 4, $dados->conta_pagadora, "T", 1, "R", 1);

        if ($tipoRelatorio == BorderoService::ANALITICO) {
            $this->setX(10);
            $this->SetFont('Arial', 'B', 7);
            $this->cell(20, 4, "Data", 'TB', 0);
            $this->cell(80, 4, "Credor", 'TB', 0);
            $this->cell(22, 4, "Empenho/Slip", 'TB', 0, 'L');
            $this->cell(20, 4, "OBN", 'TB', 0, 'R');
            $this->cell(25, 4, "Valor", 'TB', 0, 'R');
            $this->cell(25, 4, "Data Pagto", 'TB', 1, 'R');
            $this->SetFont('Arial', '', 6);
        }
        if ($tipoRelatorio == BorderoService::SINTETICO) {
            $this->setX(10);
            $this->SetFont('Arial', 'B', 7);
            $this->cell(20, 4, "Data", 'TB', 0);
            $this->cell(90, 4, "Credor", 'TB', 0);
            $this->cell(25, 4, "OBN", 'TB', 0, 'R');
            $this->cell(30, 4, "Valor", 'TB', 0, 'R');
            $this->cell(27, 4, "Data Pagto", 'TB', 1, 'R');
            $this->SetFont('Arial', '', 6);
        }
        $this->SetFont('Arial', '', 6);
    }

    private function imprimeSintetico(array $contas)
    {
        foreach ($contas as $contaPagadora => $datas) {
            $this->imprimeSubCabecalho($datas, $this->service->getTipoRelatorio());
            $this->imprimeCredoresSintetico($datas, $contaPagadora);
        }
    }

    private function imprimeCredoresSintetico($datas)
    {
        foreach ($datas as $data => $dias) {
            $this->setX(10);
            $this->SetFont('Arial', '', 6);
            $this->Cell(20, 4, db_formatar($data, 'd'), 0, 0);
            $this->setX(30);
            $this->cell(90, 4, "Diversos credores", 0, 0);

            foreach ($dias->credores as $key => $credor) {
                foreach ($credor as $diversos) {
                    if ($this->h-20 <= $this->GetY()) {
                        $this->AddPage();
                        $this->imprimeSubCabecalho($datas, $this->service->getTipoRelatorio());
                        $this->setX(10);
                        $this->SetFont('Arial', '', 6);
                        $this->Cell(20, 4, db_formatar($data, 'd'), 0, 0);
                        $this->setX(30);
                        $this->cell(90, 4, "Diversos credores", 0, 0);
                    }
                    $this->setX(120);
                    $this->cell(25, 4, str_pad($key, 7, "0", STR_PAD_LEFT), 0, 0, 'R');
                    $this->cell(30, 4, db_formatar($diversos->valor, 'f'), 0, 0, 'R');
                    $this->cell(27, 4, $diversos->data_pagamento->getDate(DBDate::DATA_PTBR), 0, 1, 'R');
                }
            }
            $this->escreveLinhaTotalDia($dias->total_dia);
        }
    }

    private function escreveLinhaTotalDia($total_dia)
    {
        $this->setX(10);
        $this->SetFont('Arial', 'B', 7);
        $this->cell(170, 4, 'Total do Dia: ', 'TB', 0, 'R');
        $this->cell(22, 4, db_formatar($total_dia, 'f'), 'TB', 1, 'R');
    }
}
