<?php

namespace App\Domain\Financeiro\Orcamento\Requests\Relatorios;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class AcompanhamentoCronogramaRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'DB_anousu' => 'required|integer',
            'periodicidade' => ['required', 'string', Rule::in(['mensal', 'bimestral'])],
            'instituicoes' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'DB_anousu.required' => 'O campo "Ano da Sessão" deve ser informado.',
            'periodicidade.required' => 'O campo "Periodicidade" deve ser informado.',
            'instituicoes.required' => 'O campo "Instituições" deve ser informado.',
        ];
    }
}
