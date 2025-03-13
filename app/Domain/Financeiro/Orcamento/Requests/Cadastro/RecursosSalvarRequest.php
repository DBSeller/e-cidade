<?php

namespace App\Domain\Financeiro\Orcamento\Requests\Cadastro;

use App\Http\Requests\DBFormRequest;

class RecursosSalvarRequest extends DBFormRequest
{

    public function rules()
    {
        return [
            "codificacao" => "required|integer",
            "classificacao" => "required|integer",
            "recursoSiconfi" => "required|integer",
            "descricao" => "required|string",
            "complemento" => "required|integer",
            "finalidade" => "required|string",
            "codigo" => "nullable|integer",
        ];
    }
}
