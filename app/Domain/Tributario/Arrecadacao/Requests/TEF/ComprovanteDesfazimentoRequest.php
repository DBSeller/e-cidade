<?php

namespace App\Domain\Tributario\Arrecadacao\Requests\TEF;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class ComprovanteDesfazimentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "numnov" => ["required", "integer"],
            "grupo" => ["required", "integer"],
            "DB_modulo" => ["required", "integer"],
            "DB_itemmenu_acessado" => ["required", "integer"]
        ];
    }

    public function response(array $errors)
    {
        $mensagem = utf8_decode($errors[array_keys($errors)[0]][0]);
        return new DBJsonResponse($errors, $mensagem, 406);
    }

    public function messages()
    {
        return [
            "numnov.required"        => utf8_encode("Numnov não informado."),
            "numnov.integer"         => utf8_encode("Numnov inválido."),

            "grupo.required"        => utf8_encode("Grupo não informado."),
            "grupo.integer"         => utf8_encode("Grupo inválido."),

            "DB_modulo.required"        => utf8_encode("Módulo não informado."),
            "DB_modulo.integer"         => utf8_encode("Módulo inválido."),

            "DB_itemmenu_acessado.required"        => utf8_encode("Menu não informado."),
            "DB_itemmenu_acessado.integer"         => utf8_encode("Menu inválido.")
        ];
    }
}
