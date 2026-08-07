<?php

namespace App\Domain\Educacao\Escola\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TrocaAlunosTurmaRequest
 * @package App\Domain\Educacao\Escola\Requests
 */
class TrocaAlunosTurmaRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'turmaOrigem' => 'integer|required',
            'turmaDestino' => 'integer|required',
            'etapaDestino' => 'integer|required',
            'importarAvaliacoes' => 'required'
        ];
    }

    /**
     * @param array $errors
     * @return DBJsonResponse|JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $mensagem = $errors[array_keys($errors)[0]][0];
        return new DBJsonResponse($errors, $mensagem, 406, false);
    }


    /**
     * @return array
     */
    public function messages()
    {
        return [
            "turmaOrigem.integer" => "Código da Turma de Origem deve ser um inteiro.",
            "turmaDestino.integer" => "Código da Turma de Destino deve ser um inteiro.",
            "turmaOrigem.required" => "Código da Turma de Origem deve ser informado.",
            "turmaDestino.required" => "Código da Turma de Destino deve ser informado.",
            "etapaDestino.integer" => "Código da Etapa de Destino deve ser um inteiro.",
            "etapaDestino.required" => "Código da Etapa de Destino deve ser informado.",
            "importarAvaliacoes.required" => "Importar Avaliacoes deve ser informado.",
        ];
    }
}
