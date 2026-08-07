<?php

namespace App\Domain\Financeiro\Orcamento\Requests\Relatorios;

use Illuminate\Validation\Rule;

class AcompanhamentoReceita extends AcompanhamentoCronogramaRequest
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            ['agruparPor' => ['required', 'string', Rule::in(['receita', 'recurso', 'fonte_recurso'])]]
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
