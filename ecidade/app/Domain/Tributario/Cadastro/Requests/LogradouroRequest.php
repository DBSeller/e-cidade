<?php

namespace App\Domain\Tributario\Cadastro\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class LogradouroRequest extends FormRequest
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
            "cep" => ["integer"],
            "bairro" => ["integer"]
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
            "cep.integer"        => utf8_encode("CEP inválido."),
            "bairro.integer"         => utf8_encode("Bairro inválido")
        ];
    }
}
