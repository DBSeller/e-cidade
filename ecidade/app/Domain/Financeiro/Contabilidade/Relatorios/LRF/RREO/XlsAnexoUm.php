<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO;

class XlsAnexoUm extends XlsRREO
{
    protected $nomeArquivo = "anexo_I";
    protected $saveAs = 'tmp/Anexo_I.xlsx';
}
