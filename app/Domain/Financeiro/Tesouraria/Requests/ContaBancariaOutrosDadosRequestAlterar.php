<?php


namespace App\Domain\Financeiro\Tesouraria\Requests;

use App\Http\Requests\DBFormRequest;

class ContaBancariaOutrosDadosRequestAlterar extends DBFormRequest
{
    public function rules()
    {
        return [
            'conta' => ['required', 'string'],
            'changed' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'conta.required' => 'A conta deve ser informado.',
            'changed.required' => 'A mudança deve ser informado.',
        ];
    }
}
