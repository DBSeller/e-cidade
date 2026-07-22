<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoTipoSessao;

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
            'id' => 'required|filled|integer|exists:jetomcomissaotiposessao,rh249_sequencial',
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
            "id.required" => utf8_encode("Código do Tipo de Sessão da comissão não informado."),
            "id.filled" => utf8_encode("Código inválido do tipo de sessão da comissão."),
            "id.integer" => utf8_encode("Código inválido do tipo de sessão da comissão."),
            "id.exists" => utf8_encode("Código não encontrado do tipo de sessão da comissão."),
        ];
    }
}
