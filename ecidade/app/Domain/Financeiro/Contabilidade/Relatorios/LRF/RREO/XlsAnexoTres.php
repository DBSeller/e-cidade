<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO;

class XlsAnexoTres extends XlsRREO
{
    protected $nomeArquivo = "Anexo III";
    protected $saveAs = 'tmp/Anexo_III.xlsx';
}
