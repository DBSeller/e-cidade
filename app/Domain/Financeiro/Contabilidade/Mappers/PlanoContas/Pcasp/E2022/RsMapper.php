<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\Pcasp;

class RsMapper extends Pcasp
{
    protected $linhaDados = 4;
    protected $colunaStatus = 14;
    protected $statusImportar = 'ATIVA';
    protected $colunasImportar = [10, 11, 12, 13, 15, 17];
    protected $colunaConta = 10;
    protected $colunasMapper = [
        10 => "conta",
        11 => "nome",
        12 => "funcao",
        13 => "natureza",
        17 => "sintetica",
        15 => "indicador",
    ];
}
