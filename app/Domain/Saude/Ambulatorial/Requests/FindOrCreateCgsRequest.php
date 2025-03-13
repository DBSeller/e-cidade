<?php

namespace App\Domain\Saude\Ambulatorial\Requests;

use App\Http\Requests\DBFormRequest;
use ECidade\Enum\Saude\Ambulatorial\RacaCorEnum;
use Illuminate\Validation\Rule;

/**
 * @property integer $tipo
 * @property integer|null $cns
 * @property integer|null $cpf
 * @property string|null $nome
 * @property string|null $nome_social
 * @property string|null $nome_mae
 * @property string|null $nome_pai
 * @property string|null $data_nascimento
 * @property integer|null $raca
 * @property string|null $sexo
 * @property string $municipio
 * @property string|null $altura_relativa
 * @property string|null $idade_relativa
 * @property string|null $peso_relativo
 * @property string|null $observacoes
 */
class FindOrCreateCgsRequest extends DBFormRequest
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'tipo' => ['required', 'integer', Rule::in([1, 2])],
            'cns' => ['digits:15'],
            'cpf' => ['digits:11'],
            'nome' => ['required_if:tipo,1', 'string'],
            'nome_social' => ['string'],
            'nome_mae' => ['required_if:tipo,1', 'string'],
            'nome_pai' => ['string'],
            'data_nascimento' => ['required_if:tipo,1', 'date'],
            'raca' => ['required_if:tipo,2', 'integer', Rule::in(RacaCorEnum::toArray())],
            'sexo' => ['required_if:tipo,2', 'string', Rule::in(['M', 'F'])],
            'municipio' => ['required', 'digits:7'],
            'altura_relativa' => ['required_if:tipo,2', 'string'],
            'idade_relativa' => ['required_if:tipo,2', 'string'],
            'peso_relativo' => ['required_if:tipo,2', 'string'],
            'observacoes' => ['string']
        ];
    }
}
