<?php

namespace App\Domain\Saude\ESF\Requests;

use App\Http\Requests\DBFormRequest;
use ECidade\Enum\Common\FaixaEtariaEnum;
use ECidade\Enum\Saude\ESF\SituacaoCondicaoPacienteEnum;
use Illuminate\Validation\Rule;
use ReflectionException;

class RelatorioCondicoesSaudeRequest extends DBFormRequest
{
    /**
     * @return array
     * @throws ReflectionException
     */
    public function rules()
    {
        return [
            'data' => 'required|date',
            'DB_instit' => 'required|integer',
            'unidade' => ['integer', $this->validateUnidade()],
            'equipe' => ['integer', $this->validateEquipe()],
            'microarea' => ['integer', Rule::exists('microarea', 'sd34_i_codigo')],
            'condicao' => ['integer', Rule::in(SituacaoCondicaoPacienteEnum::toArray())],
            'sexo' => ['string', Rule::in(['M', 'F'])],
            'faixaEtaria' => ['integer', Rule::in(FaixaEtariaEnum::toArray())]
        ];
    }

    public function messages()
    {
        return [
            'DB_instit.*' => 'O campo DB_instit é obrigatório e deve ser do tipo inteiro.'
        ];
    }

    private function validateUnidade()
    {
        return Rule::exists('db_depart', 'coddepto')->where('instit', $this->get('DB_instit'));
    }

    private function validateEquipe()
    {
        return Rule::exists('.plugins.psf_equipe', 'psf_id')->where('psf_cod_estabelecimento', $this->get('unidade'));
    }
}
