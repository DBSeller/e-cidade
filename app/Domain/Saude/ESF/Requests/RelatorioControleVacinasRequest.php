<?php

namespace App\Domain\Saude\ESF\Requests;

use App\Domain\Saude\ESF\Models\Imunobiologico;
use App\Http\Requests\DBFormRequest;
use ECidade\Enum\Common\FaixaEtariaEnum;
use ECidade\Enum\Saude\ESF\DoseEnum;
use ECidade\Enum\Saude\ESF\EstrategiaVacinacaoEnum;
use ECidade\Enum\Saude\ESF\SituacaoPacienteVacinacaoEnum;
use Illuminate\Validation\Rule;

/**
 * [Description RelatorioControleVacinasRequests]
 */
class RelatorioControleVacinasRequest extends DBFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'unidade' => 'integer',
            'imunobiologico' => ['integer', Rule::exists('.plugins.imunobiologico', 'psf22_id_esus')],
            'estrategia' => ['integer', Rule::in(EstrategiaVacinacaoEnum::toArray())],
            'dose' => ['integer', Rule::in(DoseEnum::toArray())],
            'situacao' => ['integer', Rule::in(SituacaoPacienteVacinacaoEnum::toArray())],
            'faixaEtaria' => ['integer', Rule::in(FaixaEtariaEnum::toArray())],
            'periodoInicial' => 'required|date',
            'periodoFinal' => 'required|date'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'imunobiologico.exists' => 'Imunobiológico não cadastrado no sistema.',
            'estrategia.in' => 'A estratégia informada é inválida.',
            'dose.in' => 'A dose informada é inválida.',
            'situacao.in' => 'A situação/condição informada é inválida.',
            'faixaEtaria.in' => 'A faixa Etária informada é inválida.',
        ];
    }
}
