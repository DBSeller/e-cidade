<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO\XlsRREO;

class EvolucaoReceitaXls extends XlsRREO
{
    protected $nomeArquivo = "Evolucao Receita Mensal";
    protected $saveAs = 'tmp/evolucao_receita_mensal.xlsx';
}
