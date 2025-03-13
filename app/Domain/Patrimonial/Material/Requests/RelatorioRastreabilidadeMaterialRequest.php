<?php

namespace App\Domain\Patrimonial\Material\Requests;

use App\Http\Requests\DBFormRequest;

class RelatorioRastreabilidadeMaterialRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'depositos' => 'array',
            'depositos.*' => 'integer',
            'materiais' => 'array',
            'materiais.*' => 'integer',
            'estoqueZerado' => 'required|boolean',
            'quebra' => 'integer',
            'ordem' => 'integer',
            'tipo' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'depositos.*.integer' => 'Os itens do campo depositos devem ser do tipo inteiro.',
            'materiais.*.integer' => 'Os itens do campo materiais devem ser do tipo inteiro.'
        ];
    }
}
