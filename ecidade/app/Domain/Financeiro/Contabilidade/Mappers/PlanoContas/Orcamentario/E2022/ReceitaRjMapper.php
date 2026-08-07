<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Orcamentario\E2022;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;

class ReceitaRjMapper extends PlanoContas
{
    protected $linhaDados = 1;
    protected $colunasImportar = [0, 1];
    protected $colunaConta = 0;
    protected $colunasMapper = [
        0 => "conta",
        1 => "nome"
    ];
}
