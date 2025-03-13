<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Orcamentario\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;

class ReceitaRoMapper extends PlanoContas
{
    protected $linhaDados = 1;
    protected $colunasImportar = [7, 8, 9];
    protected $colunaConta = 7;
    protected $colunasMapper = [
        7 => "conta",
        8 => "nome",
        9 => "sintetica"
    ];
}
