<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Orcamentario\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;

class ReceitaUniaoMapper extends PlanoContas
{
    protected $linhaDados = 1;
    protected $colunasImportar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
    protected $colunaConta = 10;

    protected $colunasMapper = [
        0 => 'categoria',
        1 => 'origem',
        2 => 'especie',
        3 => 'desdobramento1',
        4 => 'desdobramento2',
        5 => 'desdobramento3',
        6 => 'tipo',
        7 => 'desdobramento4',
        8 => 'desdobramento5',
        9 => 'desdobramento6',
        10 => 'conta',
        11 => 'nome',
        12 => 'funcao',
        13 => 'sintetica',
    ];
}
