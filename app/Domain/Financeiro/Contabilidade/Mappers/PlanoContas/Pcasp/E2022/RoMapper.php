<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\Pcasp;

class RoMapper extends Pcasp
{
    protected $linhaDados = 1;
    protected $colunaStatus = 12;
    protected $statusImportar = 'ATIVA';
    protected $colunasImportar = [7, 8, 9, 10, 13, 14];
    protected $colunaConta = 7;
    protected $colunasMapper = [
        7 => "conta",
        8 => "nome",
        9 => "funcao",
        10 => "natureza",
        13 => "sintetica",
        14 => "indicador",
    ];
}
