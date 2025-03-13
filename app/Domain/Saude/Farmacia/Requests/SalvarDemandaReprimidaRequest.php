<?php

namespace App\Domain\Saude\Farmacia\Requests;

use App\Http\Requests\DBFormRequest;

class SalvarDemandaReprimidaRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'id' => 'integer',
            'paciente' => 'required|integer',
            'medicamento' => 'required|integer',
            'quantidade' => 'required|integer',
            'observacoes' => 'string',
            'DB_id_usuario' => 'required|integer',
            'DB_coddepto' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'DB_id_usuario.required' => 'Obrigatório informar o id do usuário(DB_id_usuario).',
            'DB_coddepto.required' => 'Obrigatório informar o id do departamento(DB_coddepto).'
        ];
    }
}
