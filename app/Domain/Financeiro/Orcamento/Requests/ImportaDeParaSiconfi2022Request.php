<?php

namespace App\Domain\Financeiro\Orcamento\Requests;

use App\Http\Requests\DBFormRequest;

class ImportaDeParaSiconfi2022Request extends DBFormRequest
{
    public function rules()
    {
        return [
            'file' => 'required',
            'atualizaNome' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo deve ser informado.',
        ];
    }
}
