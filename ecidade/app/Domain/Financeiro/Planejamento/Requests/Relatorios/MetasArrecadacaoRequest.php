<?php

namespace App\Domain\Financeiro\Planejamento\Requests\Relatorios;

use App\Http\Requests\DBFormRequest;
use Illuminate\Validation\Rule;

class MetasArrecadacaoRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'planejamento_id' => 'required|integer|filled|exists:planejamento,pl2_codigo',
            'exercicio' => 'required|integer',
            'DB_anousu' => 'required|integer',
            'agruparPor' => ['required', 'string', Rule::in(['receita', 'recurso', 'fonte_recurso'])],
            'periodicidade' => ['required', 'string', Rule::in(['mensal', 'bimestral'])],
            'instituicoes' => 'required|array',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'planejamento_id.integer' => 'Código se informado deve ser um inteiro.',
            'planejamento_id.required' => 'O campo "Código" deve ser informado.',
            'planejamento_id.filled' => 'O campo "Código" deve ser preenchido.',
            'planejamento_id.exists' => 'Planejamento não encontrado no banco de dados.',
            'exercicio.required' => 'O campo "Exercício" deve ser informado.',
            'DB_anousu.required' => 'O campo "Ano da Sessão" deve ser informado.',
            'agruparPor.required' => 'O campo "Agrupar por" deve ser informado.',
            'periodicidade.required' => 'O campo "Periodicidade" deve ser informado.',
            'instituicoes.required' => 'O campo "Instituições" deve ser informado.',
        ];
    }
}
