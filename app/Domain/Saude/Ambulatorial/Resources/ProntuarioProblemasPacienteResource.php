<?php

namespace App\Domain\Saude\Ambulatorial\Resources;

use App\Domain\Saude\Ambulatorial\Models\ProntuarioProblemaPaciente;
use Illuminate\Database\Eloquent\Collection;

/**
 * @package App\Domain\Saude\Ambulatorial\Resources
 */
class ProntuarioProblemasPacienteResource
{
    public static function toObject(ProntuarioProblemaPaciente $model)
    {
        return (object)[
            'prontuario' => $model->s171_prontuario,
            'data' => $model->prontuario->sd24_d_cadastro->format('d/m/Y')
        ];
    }

    public static function toArray(Collection $collection)
    {
        $dados = [];
        
        foreach ($collection as $model) {
            $dados[] = static::toObject($model->pivot);
        }

        return $dados;
    }
}
