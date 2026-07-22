<?php

namespace App\Domain\Financeiro\Orcamento\Services;

use App\Domain\Financeiro\Orcamento\Models\ClassificacaoFonteRecurso;

class ClassificacaoFonteRecursoService
{
    /**
     * Retorna as classificações de recursos com os recursos do siconfi
     * @return ClassificacaoFonteRecurso[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getComRecursosSiconfi()
    {
        return ClassificacaoFonteRecurso::all()->map(function (ClassificacaoFonteRecurso $classificacao) {
            if (!$classificacao->fontesSiconfi->isEmpty()) {
                return $classificacao->toArray();
            }
        })->filter();
    }
}
