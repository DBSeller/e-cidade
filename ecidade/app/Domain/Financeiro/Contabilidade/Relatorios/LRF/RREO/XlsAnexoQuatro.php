<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Xls;
use ECidade\Library\SpreadSheet\Template\Parser;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class XlsAnexoQuatro extends XlsRREO
{
    protected $nomeArquivo = "anexo IV";
    protected $saveAs = 'tmp/Anexo_IV.xlsx';
}
