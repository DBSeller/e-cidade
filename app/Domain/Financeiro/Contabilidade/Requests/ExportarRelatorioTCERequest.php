<?php

namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;

/**
 * @property integer $mes
 * @property integer $anousu
 */
class ExportarRelatorioTCERequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'mes' => ['required', 'integer'],
            'anousu' => ['required','integer']
        ];
    }

    public function messages()
    {
        return [
            'mes.required' => 'O mes deve ser informado.',
            'anousu.required' => 'O ano deve ser informada.'
        ];
    }
}
