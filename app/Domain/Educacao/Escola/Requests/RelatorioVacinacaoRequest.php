<?php

namespace App\Domain\Educacao\Escola\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class RelatorioVacinacaoRequest extends FormRequest
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
            'escola' => 'integer',
            'tipo' => 'required',
            'vacinas' => 'array',
        ];
    }

    /**
     * @param array $errors
     * @return DBJsonResponse|JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $mensagem = $errors[array_keys($errors)[0]][0];
        return new DBJsonResponse($errors, $mensagem, 406, true);
    }


    /**
     * @return array
     */
    public function messages()
    {
        return [
            "escola.integer" => "Código da escola não informado.",
            'vacinas.required' => 'Vacinas não foi informado.',
            'vacinas.array' => 'Vacinas deve ser um array.',
            'tipo.required' => 'Tipo de relatório não informado.',
        ];
    }
}
