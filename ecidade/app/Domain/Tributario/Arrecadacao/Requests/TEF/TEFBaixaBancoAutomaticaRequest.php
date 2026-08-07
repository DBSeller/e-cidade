<?php

namespace App\Domain\Tributario\Arrecadacao\Requests\TEF;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class TEFBaixaBancoAutomaticaRequest extends FormRequest
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
            "numpre" => ["required", "integer"],
            "valor" => ["required", "numeric"],
            "conta" => ["required", "integer"],
            "DB_instit" => ["required", "integer"],
            "DB_coddepto" => ["required", "integer"],
            "DB_id_usuario" => ["required", "integer"],
            "DB_datausu" => ["required", "integer"]
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
            "numpre.required"        => utf8_encode("Numpre do recibo não informado."),
            "numpre.integer"         => utf8_encode("Numpre do recibo inválido."),

            "valor.required"         => utf8_encode("Valor do recibo não informado."),
            "valor.numeric"           => utf8_encode("Valor do recibo inválido."),

            "conta.required"         => utf8_encode("Conta do caixa não informado."),
            "conta.integer"          => utf8_encode("Conta do caixa inválido."),

            "DB_instit.required"     => utf8_encode("Código da instituição não informado."),
            "DB_instit.integer"      => utf8_encode("Código da instituição inválido."),

            "DB_coddepto.required"   => utf8_encode("Código do departamentro não informado."),
            "DB_coddepto.integer"    => utf8_encode("Código do departamentro inválido."),

            "DB_id_usuario.required" => utf8_encode("Código do usuário não informado."),
            "DB_id_usuario.integer"  => utf8_encode("Código do usuário inválido."),

            "DB_datausu.required"    => utf8_encode("Data do sistema não informada não informado."),
            "DB_datausu.integer"     => utf8_encode("Data do sistema não informada inválido.")
        ];
    }
}
