<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RGF;

class XlsAnexoUm extends XlsRGF
{
    protected $nomeArquivo = "Anexo I";
    protected $saveAs = 'tmp/RGF_Anexo_I.xlsx';
}
