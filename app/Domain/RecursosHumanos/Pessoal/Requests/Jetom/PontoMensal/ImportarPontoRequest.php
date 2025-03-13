<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\PontoMensal;

use App\Http\Requests\DBFormRequest;

/**
 * @property string $file
 * @property string $mes
 * @property string $separador
 * @property string $acao
 * @property string $ponto
 * @property integer $exercicio
 * @property integer $DB_instit
 */
class ImportarPontoRequest extends DBFormRequest
{
    public function rules()
    {
        return [
            'file' => 'required',
            'exercicio' => 'required|integer',
            'mes' => 'required',
            'separador' => 'required|string',
            'acao' => 'required|string',
            'ponto' => 'required|string',
            'DB_instit' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo deve ser informado.',
            'exercicio.required' => 'O ano de competência deve ser informado.',
            'mes.required' => 'O mês de competência deve ser informado.',
            'separador.required' => 'O separador do CSV deve ser informado.',
            'acao.required' => 'Ação em caso de duplicidade deve ser informada.',
            'ponto.required' => 'A tabela para importar deve ser informada.',
            'DB_instit.required' => 'A instituição deve ser informada.'
        ];
    }
}
