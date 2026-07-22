<?php

namespace App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Cronograma;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class CronogramaRequest extends DBFormRequest
{

    public function rules()
    {
        return [
            'planejamento_id' => 'required|integer',
            'exercicio' => 'required|integer',
            'DB_anousu' => 'required|integer',
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'planejamento_id.required' => 'O campo "Planejamento" deve ser informado.',
            'planejamento_id.integer' => 'O código do Planejamento se informado deve ser um inteiro.',
            'exercicio.required' => 'Exercício do planejamento deve ser informado.',
            'exercicio.integer' => 'Exercício do planejamento deve ser um inteiro.',
            'DB_anousu.required' => 'Ano da sessão deve ser informado.',
            'DB_anousu.integer' => 'Ano da sessão deve ser um inteiro.',
        ];
    }
}
