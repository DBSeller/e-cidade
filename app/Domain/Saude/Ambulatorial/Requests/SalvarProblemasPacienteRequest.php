<?php

namespace App\Domain\Saude\Ambulatorial\Requests;

use App\Http\Requests\DBFormRequest;

class SalvarProblemasPacienteRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'id' => ['integer'],
            'prontuario' => ['required_if:problema,12', 'integer'],
            'problema' => ['required', 'integer'],
            'paciente' => ['required', 'integer'],
            'DB_id_usuario' => ['required', 'integer'],
            'dataInicio' => ['required_if:problema,12', 'date'],
            'dataFim' => ['date'],
            'ativo' => ['boolean']
        ];
    }

    public function messages()
    {
        return [
            'dataInicio.required_if' => 'O campo data de inicio é obrigatório quando o problema for pré-natal(12).',
            'prontuario.required_if' => 'O campo prontuario é obrigatório quando o problema for for pré-natal(12).'
        ];
    }
}
