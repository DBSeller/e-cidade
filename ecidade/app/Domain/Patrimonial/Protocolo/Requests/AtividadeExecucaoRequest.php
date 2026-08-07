<?php

namespace App\Domain\Patrimonial\Protocolo\Requests;

use App\Http\Requests\DBFormRequest;

class AtividadeExecucaoRequest extends DBFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'codigoTipoProcesso' => 'required|integer',
            'codigoAtividade' => 'required|integer',
            'ordem' => 'integer',
        ];
    }
}
