<?php

namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;

class ExcluirReduzidoRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'reduzido' => ['required', 'integer'],
            'exercicio' => ['required', 'integer'],
            'codcon' =>['required', 'integer'],
        ];
    }
}
