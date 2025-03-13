<?php

namespace App\Domain\Saude\Ambulatorial\Requests;

use App\Http\Requests\DBFormRequest;

class SaveAcompanhamentoAcsRequest extends DBFormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'integer',
            'unidade' => 'required|integer',
            'profissional' => 'required|integer',
            'paciente' => 'required|integer',
            'data_hora' => 'required|date',
            'evolucao' => 'required|string|max:1600'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'id.integer' => 'O campo id deve ser um inteiro',
            'unidade.*' => 'O campo unidade é obrigatório e deve ser um inteiro',
            'profissional.*' => 'O campo profissional é obrigatório e deve ser um inteiro',
            'paciente.*' => 'O campo paciente é obrigatório e deve ser um inteiro',
            'data_hora.*' => 'O campo data é obrigatório e deve ser do tipo data!',
            'evolucao.*' => 'O campo evolução é obrigatório e deve ser uma string com no máximo 1600 caracteres!'
        ];
    }
}
