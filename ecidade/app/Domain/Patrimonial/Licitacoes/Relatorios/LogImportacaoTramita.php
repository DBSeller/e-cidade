<?php

namespace App\Domain\Patrimonial\Licitacoes\Relatorios;

use ECidade\Pdf\Pdf;

class LogImportacaoTramita
{
    /**
     * @var Pdf
     */
    private $pdf;
    /**
     * @var array
     */
    private $licitacoesEncontradas;
    /**
     * @var array
     */
    private $licitacoesNaoEncontradas;

    public function __construct()
    {
        $this->pdf = new Pdf();
        $this->pdf->init();
    }

    public function setLicitacoesEncontradas(array $licitacoesEncontradas)
    {
        $this->licitacoesEncontradas = $licitacoesEncontradas;
    }

    public function setLicitacoesNaoEncontradas(array $licitacoesNaoEncontradas)
    {
        $this->licitacoesNaoEncontradas = $licitacoesNaoEncontradas;
    }

    public function emitir()
    {
        $this->processar();

        $file = 'tmp/log_importacao_tramita.pdf';
        $this->pdf->output($file, 'F');
        return [
            'pdf' => $file,
            'linkExterno' => ECIDADE_REQUEST_PATH . $file
        ];
    }

    private function processar()
    {
        $this->imprimirEncontradas();
        $this->imprimirNaoEncontradas();
    }

    private function imprimirEncontradas()
    {
        $this->pdf->setFont('Arial', 'B', 8);
        if (empty($this->licitacoesEncontradas)) {
            $this->pdf->cell(192, 5, 'Nenhuma licitação presente no arquivo foi encontrada no e-cidade.');
            return;
        }

        $this->pdf->cell(192, 5, 'LICITAÇÕES MAPEADAS', '', 1, 'C');

        $this->imprimeTabela($this->licitacoesEncontradas);
        $this->pdf->ln();
    }

    private function imprimirNaoEncontradas()
    {
        $this->pdf->setFont('Arial', 'B', 8);
        if (empty($this->licitacoesNaoEncontradas)) {
            $this->pdf->cell(192, 5, 'Todas licitações presente no arquivo foram mapeadas no e-cidade.');
            return;
        }

        $this->pdf->cell(192, 5, 'LICITAÇÕES NÃO MAPEADAS', '', 1, 'C');
        $this->imprimeTabela($this->licitacoesNaoEncontradas);
    }

    private function imprimeTabela(array $licitacoes)
    {
        $this->pdf->cell(20, 5, 'Sagres', 'TBR', 0, 'C');
        $this->pdf->cell(132, 5, 'Instituição', 'TBR', 0, 'C');
        $this->pdf->cell(20, 5, 'Licitação', 1, 0, 'C');
        $this->pdf->cell(20, 5, 'Modalidade', 'TBL', 1, 'C');

        $this->pdf->setFont('Arial', '', 8);
        foreach ($licitacoes as $licitacao) {
            $this->pdf->cell(20, 5, $licitacao->sagres, 'TBR', 0, 'C');
            $this->pdf->cell(132, 5, $licitacao->instituicao, 1, 0, 'L');
            $this->pdf->cell(20, 5, $licitacao->licitacao, 1, 0, 'C');
            $this->pdf->cell(20, 5, $licitacao->modalidade, 'TBL', 1, 'C');
        }
    }
}
