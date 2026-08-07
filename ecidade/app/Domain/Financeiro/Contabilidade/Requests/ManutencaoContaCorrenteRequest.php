<?php

namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;

class ManutencaoContaCorrenteRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'exercicio' => ['required', 'integer'],
            'codcon' => ['required', 'integer'],
            'contaCorrente' => ['required', 'integer'],
        ];
    }
}
