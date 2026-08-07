<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoServidor;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;

class Sequencial extends BaseFormRequest
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
            'id' => 'required|filled|integer|exists:jetomcomissaoservidor,rh245_sequencial',
        ];
    }

    /**
     * @param array $errors
     * @return DBJsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $mensagem = $errors[array_keys($errors)[0]][0];
        return new DBJsonResponse($errors, $mensagem, 406);
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "id.required" => "Código do servidor na comissão não informado.",
            "id.filled" => "Código inválido do servidor na comissão.",
            "id.integer" => "Código inválido do servidor na comissão.",
            "id.exists" => "Código não encontrado do servidor na comissão.",
        ];
    }
}
