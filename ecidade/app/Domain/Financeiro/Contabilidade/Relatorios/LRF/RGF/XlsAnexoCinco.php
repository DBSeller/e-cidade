<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RGF;

class XlsAnexoCinco extends XlsRGF
{
    protected $nomeArquivo = "RGF - Anexo V";
    protected $saveAs = 'tmp/RGF_Anexo_V.xlsx';
}
