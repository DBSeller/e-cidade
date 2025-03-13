<?php
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\Layout;

class AnexoVI extends \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoVI
{
    protected function imprimirAjusteMetodologico()
    {

        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 6, "AJUSTE METODOLÓGICO", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $label = "Até o {$this->anexo->getPeriodo()->getSigla()} / " . $this->anexo->getAno();
        $this->pdf->Cell($this->pdf->getAvailWidth(), 6, $label, 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for ($linha = 70; $linha <= 77; $linha++) {
            $dadosLinha = $this->linhas[$linha];
            $borda = $linha === 77 ? '1' : 'LR';
            $preenche = $linha === 77 ? '1' : '0';
            $bold = $linha === 77 ? true : false;
            $this->pdf->setBold($bold);
            $this->pdf->Cell(145, 4, $dadosLinha->descricao, $borda, 0, \PDFDocument::ALIGN_LEFT, $preenche);
            $valor = trim(db_formatar($dadosLinha->saldo, 'f'));
            $this->pdf->Cell($this->pdf->getAvailWidth(), 4, $valor, $borda, 1, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->setBold(false);
        }

        $this->pdf->ln(4);
        $dadosLinha = $this->linhas[78];
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 4, $dadosLinha->descricao, '1', 0, \PDFDocument::ALIGN_LEFT, 1);
        $valor = trim(db_formatar($dadosLinha->saldo, 'f'));
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, $valor, '1', 1, \PDFDocument::ALIGN_RIGHT, 1);
        $this->pdf->setBold(false);

        $label = "Continua " . $this->pdf->getCurrentPage() . "/{nb}";
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, $label, 0, 1, \PDFDocument::ALIGN_RIGHT);

        $this->pdf->ln(4);
    }

    protected function imprimirInformacoesAdicionais()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 6, "INFORMAÇÕES ADICIONAIS", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 6, "PREVISÃO ORÇAMENTÁRIA", 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for ($linha = 79; $linha <= 82; $linha++) {
            $dadosLinha = $this->linhas[$linha];
            $identacao = \relatorioContabil::getIdentacao($dadosLinha->nivel);
            $this->pdf->Cell(145, 4, $identacao . $dadosLinha->descricao, 1, 0, \PDFDocument::ALIGN_LEFT, 0);
            $valor = trim(db_formatar($dadosLinha->previsao, 'f'));
            $this->pdf->Cell($this->pdf->getAvailWidth(), 4, $valor, 1, 1, \PDFDocument::ALIGN_RIGHT, 0);
        }
    }
}
