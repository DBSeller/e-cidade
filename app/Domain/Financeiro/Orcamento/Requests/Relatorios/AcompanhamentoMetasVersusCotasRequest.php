<?php

namespace App\Domain\Financeiro\Orcamento\Requests\Relatorios;

use Illuminate\Validation\Rule;

class AcompanhamentoMetasVersusCotasRequest extends AcompanhamentoReceita
{
    public function rules()
    {
        return array_merge(
            parent::rules(),
            ['agruparPor' => ['required', 'string', Rule::in(['geral', 'recurso', 'fonte_recurso'])]]
        );
    }
}
