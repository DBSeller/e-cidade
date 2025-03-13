<?php

namespace App\Domain\Patrimonial\Licitacoes\Requests;

use App\Http\Requests\DBFormRequest;

class ImportarTramitaRequest extends DBFormRequest
{

    public function rules()
    {
        return [
            'file' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo deve ser informado.'
        ];
    }
}
