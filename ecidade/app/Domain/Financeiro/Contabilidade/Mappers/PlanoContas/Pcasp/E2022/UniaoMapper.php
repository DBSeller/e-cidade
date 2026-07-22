<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp\Pcasp;

class UniaoMapper extends Pcasp
{
    protected $linhaDados = 1;
    protected $colunaStatus = 17;
    protected $statusImportar = 'Ativa';
    protected $colunasImportar = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];

    protected $colunaConta = 10;
    protected $colunasMapper = [
        0 => "classe",
        1 => "grupo",
        2 => "subgrupo",
        3 => "titulo",
        4 => "subtitulo",
        5 => "item",
        6 => "subitem",
        7 => "desdobramento1",
        8 => "desdobramento2",
        9 => "desdobramento3",
        10 => "conta",
        11 => "nome",
        12 => "funcao",
        13 => "natureza",
        14 => "sintetica",
        15 => "indicador",
        16 => "informacoescomplementares"
    ];
}
