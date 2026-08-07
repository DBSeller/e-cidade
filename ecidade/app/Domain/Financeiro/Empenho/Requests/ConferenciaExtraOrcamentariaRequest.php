<?php


namespace App\Domain\Financeiro\Empenho\Requests;

use App\Http\Requests\DBFormRequest;

class ConferenciaExtraOrcamentariaRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'data1' => ['required', 'string'],
            'data2' => ['required', 'string'],
            'tipo' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'conta.required' => 'A data 1 deve ser informado.',
            'changed.required' => 'A data 2 deve ser informado.',
            'tipo' => 'O tipo da conferencia deve ser informada.',
        ];
    }
}
