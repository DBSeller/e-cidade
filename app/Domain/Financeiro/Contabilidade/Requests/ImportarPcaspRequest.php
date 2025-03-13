<?php

namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class ImportarPcaspRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'plano' => ['required', Rule::in(['uniao', 'UF'])],
            'file' => 'required',
            'exercicio' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo deve ser informado.',
            'plano.in' => 'Apenas os planos da Uni.',
        ];
    }
}
