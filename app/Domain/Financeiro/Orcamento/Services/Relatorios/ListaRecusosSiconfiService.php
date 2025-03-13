<?php

namespace App\Domain\Financeiro\Orcamento\Services\Relatorios;

use App\Domain\Financeiro\Contabilidade\Relatorios\Pdf;
use App\Domain\Financeiro\Orcamento\Services\ClassificacaoFonteRecursoService;

class ListaRecusosSiconfiService extends Pdf
{
    public function emitir()
    {
        $this->wLinhaDescricao = $this->wLinhaP - 20;
        $this->SetAutoPageBreak(true, 10);
        $classificacoes = $this->processar();
        $this->AddPage();

        $this->codificacaoNaoPadronizada();

        foreach ($classificacoes as $classificacao) {
            $this->imprimeClassificacao($classificacao);
            foreach ($classificacao['fontes_siconfi'] as $siconfis) {
                $descricao = $siconfis['descricao'];
                $linhas = $this->NbLines($this->wLinhaDescricao, $descricao);
                $h = $linhas * 5;

                $this->Cell(20, $h, $siconfis['codigo_siconfi'], 1, 0, 'C');
                $this->MultiCell($this->wLinhaDescricao, 5, $descricao, 1);
            }

            $this->ln();
        }

        $filename = sprintf('tmp/siconfi_2022-%s.pdf', time());
        $this->Output('F', $filename);

        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    /**
     * @return void
     */
    protected function imprimeCabecalho()
    {
        $this->Cell(20, 5, 'Código', 1, 0, 'C', 1);
        $this->Cell($this->wLinhaDescricao, 5, 'Descrição', 1, 1, 'L', 1);
        $this->SetFont('Arial', '', 8);
    }

    private function processar()
    {
        $service = new ClassificacaoFonteRecursoService();
        return $service->getComRecursosSiconfi()->toArray();
    }

    private function imprimeClassificacao($classificacao)
    {
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wLinhaP, 5, $classificacao['descricao'], 1, 1, 'L', 1);
        $this->imprimeCabecalho();
    }

    private function codificacaoNaoPadronizada()
    {
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wLinhaP, 5, 'Codificação não padronizada', 1, 1, 'L', 1);
        $this->imprimeCabecalho();
        $this->SetFont('Arial', '', 8);
        $this->Cell(20, 5, '1', 1, 0, 'C');
        $this->Cell($this->wLinhaDescricao, 5, 'Recursos do Exercício Corrente', 1, 1, 'L');
        $this->Cell(20, 5, '2', 1, 0, 'C');
        $this->Cell($this->wLinhaDescricao, 5, 'Recursos de Exercícios Anteriores', 1, 1, 'L');
        $this->Cell(20, 5, '9', 1, 0, 'C');
        $this->Cell($this->wLinhaDescricao, 5, 'Recursos Condicionados', 1, 1, 'L');
        $this->ln();
    }
}
