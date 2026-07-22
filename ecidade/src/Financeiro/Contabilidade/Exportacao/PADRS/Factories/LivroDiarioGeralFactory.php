<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Factories;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies\LivroDiarioGeralService;

class LivroDiarioGeralFactory
{
    public static function getService($exercicio, $dataInicio, $dataFim, array $instituicoes)
    {
        return new LivroDiarioGeralService($exercicio, $dataInicio, $dataFim, $instituicoes);
    }
}
