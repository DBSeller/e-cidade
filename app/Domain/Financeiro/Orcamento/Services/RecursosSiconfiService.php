<?php

namespace App\Domain\Financeiro\Orcamento\Services;

use App\Domain\Financeiro\Orcamento\Models\FonteRecurso;

class RecursosSiconfiService
{
    public function getRecursosInativar($exercicio)
    {
        $data = new \DateTime("$exercicio-01-01");
        return $this->getRecursosExercicio($exercicio)
            ->filter(function (FonteRecurso $fonteRecurso) use ($data) {
                $recurso = $fonteRecurso->recurso;
                if (empty($recurso->o15_datalimite) or
                    !empty($recurso->o15_datalimite) && $recurso->o15_datalimite > $data) {
                    return true;
                }
            })->each(function (FonteRecurso $fonteRecurso) {
                $fonteRecurso->data_limite = null;
                $complemento = $fonteRecurso->recurso->complemento;
                $fonteRecurso->complemento = "{$complemento->o200_sequencial} - {$complemento->o200_descricao}";
                if (!empty($fonteRecurso->recurso->o15_datalimite)) {
                    $fonteRecurso->data_limite = $fonteRecurso->recurso->o15_datalimite->format('Y-m-d');
                }
            });
    }

    private function getRecursosExercicio($exercicio)
    {
        return FonteRecurso::query()
            ->with('recurso')
            ->where('exercicio', $exercicio)
            ->orderBy('gestao')
            ->get();
    }

    public function getRecursos($exercicio)
    {
        return $this->getRecursosExercicio($exercicio)
            ->each(function (FonteRecurso $fonteRecurso) {
                $fonteRecurso->data_limite = null;
                $complemento = $fonteRecurso->recurso->complemento;
                $fonteRecurso->complemento = "{$complemento->o200_sequencial} - {$complemento->o200_descricao}";
                if (!empty($fonteRecurso->recurso->o15_datalimite)) {
                    $fonteRecurso->data_limite = $fonteRecurso->recurso->o15_datalimite->format('Y-m-d');
                }
            });
    }
}
