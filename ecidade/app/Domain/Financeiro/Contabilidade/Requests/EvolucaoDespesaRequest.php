<?php


namespace App\Domain\Financeiro\Contabilidade\Requests;

use App\Http\Requests\DBFormRequest;

class EvolucaoDespesaRequest extends DBFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'tipo_pagamento' => ['required', 'string', 'filled'],
            'mes' => ['required', 'string', 'filled'],
            'instituicoes' =>['required', 'array', 'filled'],
            'exercicio' => ['required', 'string', 'filled'],
            'filtros' => ['required'],
        ];
    }

    protected function convertInstituicoesJsonToArray()
    {
        if ($this->request->has('instituicoes')) {
            $val = str_replace('\"', '"', $this->request->get('instituicoes'));
            $this->request->set('instituicoes', \JSON::create()->parse($val));
        }
    }

    public function getValidatorInstance()
    {
        $this->convertInstituicoesJsonToArray();
        return parent::getValidatorInstance();
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'tipo_pagamento.required' => 'O filtro Valores Exibidos deve ser selecionado.',
            'mes.required' => 'O filtro Mês deve ser selecionado.',
            'instituicoes.required' => 'Deve ser selecionada pelo menos uma Instituição.',
            'filtros.required' => 'Os filtros devem ser selecionados.',
        ];
    }
}
