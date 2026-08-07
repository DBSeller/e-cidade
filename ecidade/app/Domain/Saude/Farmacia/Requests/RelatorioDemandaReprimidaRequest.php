<?php

namespace App\Domain\Saude\Farmacia\Requests;

use App\Http\Requests\DBFormRequest;

class RelatorioDemandaReprimidaRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'pacientes' => 'array',
            'pacientes.*' => 'integer',
            'medicamentos' => 'array',
            'medicamentos.*' => 'integer',
            'departamentos' => 'array',
            'departamentos.*' => 'integer',
            'txtDepartamentos' => 'array',
            'txtDepartamento.*' => 'string',
            'periodoInicial' => ['required', 'date'],
            'periodoFinal' => ['required', 'date'],
            'somenteTotal' => ['required', 'boolean'],
            'exibeObservacao' => ['required', 'boolean'],
            'ordem' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'pacientes.*.integer' => 'Os valores do campo pacientes devem ser do tipo inteiro.',
            'medicamentos.*.integer' => 'Os valores do campo medicamentos devem ser do tipo inteiro.',
            'departamentos.*.integer' => 'Os valores do campo departamentos devem ser do tipo inteiro.',
            'txtDepartamentos.*.string' => 'Os valores do campo txtDepartamentos devem ser do tipo string.'
        ];
    }
}
