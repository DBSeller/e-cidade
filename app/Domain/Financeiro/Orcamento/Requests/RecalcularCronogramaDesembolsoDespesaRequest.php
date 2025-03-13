<?php

namespace App\Domain\Financeiro\Orcamento\Requests;

use App\Http\Requests\DBFormRequest;

class RecalcularCronogramaDesembolsoDespesaRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'base_calculo' => 'required|integer|filled',
            'formula' => 'required|integer|filled',
            'mes' => 'required',
            'cronogramas' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'base_calculo.required' => 'O campo "Base de cálculo" deve ser informado.',

            'formula.integer' => 'O campo "Fórmula" deve ser um inteiro.',
            'formula.filled' => 'O campo "Fórmula" deve ser preenchido.',
            'formula.required' => 'O campo "Fórmula" deve ser informado.',

            'exercicio.integer' => 'O campo "Exercício" deve ser um inteiro.',
            'exercicio.filled' => 'O campo "Exercício" deve ser preenchido.',
            'exercicio.required' => 'O campo "Exercício" deve ser informado.',

            'mes.required' => 'O campo "mês" deve ser informado.',
            'anos.required' => 'Deve ser informado uma coleção com os anos a serem recalculados.',

            'cronogramas.array' => 'Deve ser informado o(s) cronograma(s).'
        ];
    }
}
