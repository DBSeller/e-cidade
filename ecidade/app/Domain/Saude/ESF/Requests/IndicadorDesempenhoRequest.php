<?php

namespace App\Domain\Saude\ESF\Requests;

use App\Http\Requests\DBFormRequest;

/**
 * @package App\Domain\Saude\ESF\Requests
 */
class IndicadorDesempenhoRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'periodoInicio' => ['required', 'date'],
            'periodoFim' => ['required', 'date'],
            'indicador' => ['required', 'integer'],
            'unidades' => 'array',
            'unidades.*' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'unidades.*.integer' => 'Os valores do campo unidades devem ser do tipo inteiro.'
        ];
    }
}
