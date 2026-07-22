<?php


namespace App\Domain\Financeiro\Tesouraria\Requests;

use App\Http\Requests\DBFormRequest;

class ContaBancariaOutrosDadosRequestBuscar extends DBFormRequest
{
    public function rules()
    {
        return [
            'DB_instit' => ['required'],
            'DB_anousu' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'DB_instit.required' => 'A conta deve ser informado.',
            'DB_anousu.required' => 'A mudança deve ser informado.',
        ];
    }
}
