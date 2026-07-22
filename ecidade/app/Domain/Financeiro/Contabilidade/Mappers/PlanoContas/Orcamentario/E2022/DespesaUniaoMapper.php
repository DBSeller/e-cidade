<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Orcamentario\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;

class DespesaUniaoMapper extends PlanoContas
{
    protected $linhaDados = 1;
    protected $colunasImportar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
    protected $colunaConta = 8;

    protected $colunasMapper = [
        0 => 'classe',
        1 => 'grupo',
        2 => 'modalidade',
        3 => 'elemento',
        4 => 'subelemento',
        5 => 'desdobramento1',
        6 => 'desdobramento2',
        7 => 'desdobramento3',
        8 => 'conta',
        9 => 'nome',
        10 => 'funcao',
        11 => 'sintetica',
    ];
}
