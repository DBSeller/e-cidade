<?php

namespace App\Domain\Tributario\Arrecadacao\Requests\TEF;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class SalvarAgendamentoControleParcelamentoRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'regraParcelamento' => Rule::unique('controleparc_agendamento', 'ar49_regra_parcelamento')
                ->where('ar49_agendamento_ativo', true)
        ];
    }

    public function messages()
    {
        return [
            'regraParcelamento.unique' => 'Regra de parcelamento já cadastrada.'
        ];
    }
}
