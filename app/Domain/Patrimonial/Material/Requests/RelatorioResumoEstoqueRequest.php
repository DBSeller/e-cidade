<?php

namespace App\Domain\Patrimonial\Material\Requests;

use App\Http\Requests\DBFormRequest;

class RelatorioResumoEstoqueRequest extends DBFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'dataInicial' => 'date',
            'dataFinal' => 'date',
            'transferencias' => 'required',
            'depositos' => 'string',
            'ordem' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'dataInicial.date' => 'Data inicial informada é inválida.',
            'dataFinal.date' => 'Data final informada é inválida.',
        ];
    }
}
