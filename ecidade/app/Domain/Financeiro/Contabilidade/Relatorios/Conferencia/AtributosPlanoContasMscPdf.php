<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\Conferencia;

use ECidade\Pdf\Pdf;

class AtributosPlanoContasMscPdf extends Pdf
{
    protected $wLine = 193;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->init(false);
    }

    public function headers($exercicio, array $nomeInstituicoes)
    {
        $this->addTitulo("Exercício: $exercicio");
        if (!empty($nomeInstituicoes)) {
            $this->addTitulo("Instituições: " . implode(', ', $nomeInstituicoes));
        }
    }

    public function contasSemAtributos(array $contasSemAtributos)
    {
        if (empty($contasSemAtributos)) {
            return true;
        }
        $this->addPage();
        $this->setFont("Arial", 'B', 8);
        $this->cell($this->wLine, 5, "Estruturais sem Configuração de Atributos", 'TB', 1, 'C', 1);
        $this->setFont("Arial", '', 8);
        foreach ($contasSemAtributos as $conta) {
            $this->cell($this->wLine, 5, $conta->estrutural, 'TB', 1);
        }
    }

    public function contasComAtributos(array $contasComAtributos)
    {
        if (empty($contasComAtributos)) {
            return true;
        }

        $wContas = $this->wLine - 30;

        $this->addPage();
        $this->setFont("Arial", 'B', 8);
        $this->cell(30, 5, "Estruturais", 'TBR', 0, 'C', 1);
        $this->cell($wContas, 5, "Configuração de Atributos", 'TBL', 1, 'C', 1);
        $this->setFont("Arial", '', 8);
        foreach ($contasComAtributos as $conta) {
            $this->cell(30, 5, $conta->estrutural, 'TBR', 0);
            $this->cell($wContas, 5, $conta->informacoes_complementares, 'TBL', 1);
        }
    }

    public function imprimir()
    {
        $filename = sprintf('tmp/atributos-contas-msc-%s.pdf', time());
        $this->Output($filename, 'F');
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }
}
