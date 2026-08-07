<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConcessaoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rulesAssentConfig()
    {
        return [
            'rh500_assentamento' => ['integer', 'exists:tipoasse,h12_codigo'],
            'rh500_condede' => ['integer', 'exists:tipoasse,h12_codigo'],
            'rh500_datalimite' => ['date'],
            'rh500_naoconcede' => ['integer', 'exists:tipoasse,h12_codigo'],
            'rh500_selecao' => ['integer', 'exists:selecao,r44_selec'],
        ];
    }
    public function rulesAssentPerc()
    {
        return [
            'rh501_seqasentconf' => ['integer', 'exists:assentconf,rh500_sequencial'],
            'rh501_ordem' => ['required', 'integer'],
            'rh501_perc' => ['required'],
            'rh501_unidade' => ['required']
        ];
    }
    public function assentForm()
    {
        return [
            'rh502_seqassentconf' => ['integer', 'exists:assentconf,rh500_sequencial'],
            'rh502_codigo' => ['integer', 'exists:tipoasse,h12_codigo'],
            'rh502_condicao' => ['required'],
            'rh502_resultado' => ['required'],
            'rh502_operador' => ['required'],
            'rh502_multiplicador' => ['required']

        ];
    }

    public function assentConcedeConf()
    {
        return [
            'rh503_seqassentconf' => ['integer', 'exists:assentconf,rh500_sequencial'],
            'rh503_codigo' => ['integer', 'exists:tipoasse,h12_codigo'],
            'rh503_acao' => ['integer'],
            'rh503_tipo' => ['integer'],
            'rh503_condicao' => ['required'],
            'rh503_formula' => ['required']
        ];
    }
}
