<?php


namespace App\Domain\Financeiro\Contabilidade\Requests\LRF\RREO;

use App\Http\Requests\DBFormRequest;

class AnexosRREORequest extends DBFormRequest
{

    public function rules()
    {
        return [
            'codigo_relatorio' => 'required',
            'periodo' => 'required',
            'DB_anousu' => 'required',
        ];
    }
}
