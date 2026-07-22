<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoPermissao;

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
            'id' => 'required|filled|integer|exists:jetompermissao,rh251_sequencial',
        ];
    }

    /**
     * @param array $errors
     * @return DBJsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $mensagem = utf8_decode($errors[array_keys($errors)[0]][0]);
        return new DBJsonResponse($errors, $mensagem, 406);
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "id.required" => utf8_encode("Código da Permissão não informado."),
            "id.filled" => utf8_encode("O código da Permissão informado está vazio."),
            "id.integer" => utf8_encode("Código inválido da Permissão."),
            "id.exists" => utf8_encode("Permissão não encontrada."),
        ];
    }
}
