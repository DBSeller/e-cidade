<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Orcamentario\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;

class ReceitaRsMapper extends PlanoContas
{
    protected $linhaDados = 3;
    protected $colunasImportar = [10, 11, 12, 13];
    protected $colunaConta = 10;
    protected $colunasMapper = [
        10 => "conta",
        11 => "nome",
        12 => "funcao",
        13 => "sintetica"
    ];
}
