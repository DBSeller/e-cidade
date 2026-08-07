<?php

namespace App\Domain\Patrimonial\Material\Relatorios;

use ECidade\Pdf\Pdf;

abstract class RelatorioRastreabilidade extends Pdf
{
    /**
     * @var array
     */
    protected $dados;

    final public function emitir()
    {
        $this->initPdf();
        $this->addPage();
        $this->escreverArquivo($this->dados);

        return $this->imprimir();
    }

    /**
     * @return array
     */
    abstract protected function imprimir();

    /**
     * @param array $dados
     */
    abstract protected function escreverArquivo(array $dados);

    protected function initPdf()
    {
        $this->mostrarRodape();
        $this->mostrarTotalDePaginas();
        $this->setMargins(8, 8, 8);
        $this->setAutoPageBreak(false, 10);
        $this->aliasNbPages();
        $this->setFillColor(235);
        $this->setFont('Arial', 'B', 9);
        $this->exibeHeader();
    }
}
