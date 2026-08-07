<?php

namespace App\Domain\Financeiro\Orcamento\Requests\Relatorios;

use Illuminate\Validation\Rule;

class AcompanhamentoDespesaRequest extends AcompanhamentoCronogramaRequest
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            ['agruparPor' => [
                'required',
                'string',
                Rule::in([
                    'orgao',
                    'unidade',
                    'funcao',
                    'subfuncao',
                    'programa',
                    'iniciativa',
                    'elemento',
                    'recurso',
                ])],
            ]
        );
    }

    public function messages()
    {
        return array_merge(
            parent::messages(),
            ['agruparPor.required' => 'O campo "Agrupar por" deve ser informado.']
        );
    }
}
