<?php

namespace App\Domain\Educacao\CentralMatriculas\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class EscolasDisponiveisRequest extends FormRequest
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
            'etapa' => 'required|integer',
            'fase' => 'required|integer',
            'bairro' => 'integer|nullable',
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
            "integer" => (":attribute deve ser um inteiro."),
            "required" => ("Código :attribute deve ser informado."),
        ];
    }
}
