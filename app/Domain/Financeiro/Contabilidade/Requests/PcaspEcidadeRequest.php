<?php

namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class PcaspEcidadeRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'temVinculoTipoPlano' => ['nullable', Rule::in(['uniao', 'UF'])],
            'apenasAnaliticas' => 'nullable',
            'estrutural' => 'nullable',
            'exercicio' => 'required|integer',
        ];
    }
}
