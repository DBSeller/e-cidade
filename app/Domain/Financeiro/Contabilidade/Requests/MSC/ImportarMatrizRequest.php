<?php

namespace App\Domain\Financeiro\Contabilidade\Requests\MSC;

use App\Http\Requests\DBFormRequest;

class ImportarMatrizRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'file' => 'required',
            'exercicio' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo deve ser informado.',
        ];
    }
}
