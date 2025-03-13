<?php

namespace App\Domain\Financeiro\Orcamento\Requests\Cadastro;

use App\Http\Requests\DBFormRequest;

class RecursosInativarRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            "dataLimite" => "nullable|date",
            "codigos" => "required|array"
        ];
    }
}
