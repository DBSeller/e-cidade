<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\Pcasp;

class RjMapper extends Pcasp
{
    protected $linhaDados = 4;
    protected $colunaStatus = 12;
    protected $statusImportar = 'Ativa';
    protected $colunasImportar = [7, 9, 10, 11, 13, 14];
    protected $colunaConta = 7;
    protected $colunasMapper = [
        7 => "conta",
        9 => "nome",
        10 => "funcao",
        11 => "natureza",
        13 => "sintetica",
        14 => "indicador",
    ];
}
