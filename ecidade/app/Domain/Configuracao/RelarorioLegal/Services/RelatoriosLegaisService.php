<?php

namespace App\Domain\Configuracao\RelarorioLegal\Services;

use App\Domain\Configuracao\RelarorioLegal\Model\Relatorio;
use Illuminate\Database\Eloquent\Collection;

class RelatoriosLegaisService
{
    /**
     * @return Collection
     */
    public function relatoriosLRF()
    {
        return Relatorio::query()->relatoriosLrf()
            ->orderBy('o42_codparrel', 'desc')
            ->get();
    }
}
