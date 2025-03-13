<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO\XlsRREO;

class EvolucaoDespesaXls extends XlsRREO
{
    protected $nomeArquivo = "Evolucao Despesa Mensal";
    protected $saveAs = 'tmp/evolucao_despesa_mensal.xlsx';
}
