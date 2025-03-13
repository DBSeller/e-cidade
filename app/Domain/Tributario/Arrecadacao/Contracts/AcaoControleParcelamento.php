<?php

namespace App\Domain\Tributario\Arrecadacao\Contracts;

use App\Domain\Tributario\Arrecadacao\Models\AgendamentoControleParcelamento;

interface AcaoControleParcelamento
{
    public function processar(AgendamentoControleParcelamento $agendamento);
}
