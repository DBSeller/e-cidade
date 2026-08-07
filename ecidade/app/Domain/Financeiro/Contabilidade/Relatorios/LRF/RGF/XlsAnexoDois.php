<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RGF;

class XlsAnexoDois extends XlsRGF
{
    protected $nomeArquivo = "RGF - Anexo II";
    protected $saveAs = 'tmp/RGF_Anexo_II.xlsx';

    public function setAnoReferencia($ano)
    {
        $this->setVariavel('ano_referencia', 'Ano de referência: ' . $ano);
        $this->setVariavel('exercicio', $ano);
    }
}
