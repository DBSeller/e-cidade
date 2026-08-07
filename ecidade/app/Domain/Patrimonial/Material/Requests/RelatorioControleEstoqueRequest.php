<?php

namespace App\Domain\Patrimonial\Material\Requests;

use App\Http\Requests\DBFormRequest;

class RelatorioControleEstoqueRequest extends DBFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'materialCodigo' => 'required|integer',
            'depositoCodigo' => 'integer',
            'dataInicial' => 'date',
            'dataFinal' => 'date'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'materialCodigo.required' => 'Código do Material deve ser preenchido.',
            'materialCodigo.integer' => 'Código do Material deve ser numero inteiro.',
            'dataInicial.date' => 'Data inicial informada é inválida.',
            'dataFinal.date' => 'Data final informada é inválida.'
        ];
    }
}
