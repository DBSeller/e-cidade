<?php

namespace App\Domain\Financeiro\Orcamento\Requests\Cadastro;

use App\Http\Requests\DBFormRequest;

class RecursosExcluirRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            "codigos" => "required|array"
        ];
    }
}
