<?php


namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;

class EmissaoBalancetesRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'modelo' => ['required', 'string', 'filled'],
            'nivel' => ['required_if:modelo,==,sintetico', 'array'],
            'instituicoes' => ['required', 'string', 'filled'],
            'dataInicio' => ['required','date_format:d/m/Y'],
            'dataFinal' => ['required', 'date_format:d/m/Y', 'after_or_equal:dataInicio'],
            'filtros' => ['required'],
        ];
    }
}
