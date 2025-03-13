<?php

namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class PcaspPadraoRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'conta' => 'nullable',
            'exercicio' => 'required|integer',
            'tipoPlano' => ['nullable', Rule::in(['uniao', 'UF'])],
            'apenasAnaliticas' => 'nullable',
            'existeVinculo' => 'nullable',
        ];
    }
}
