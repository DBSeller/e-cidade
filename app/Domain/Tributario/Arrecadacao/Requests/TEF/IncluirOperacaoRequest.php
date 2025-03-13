<?php

namespace App\Domain\Tributario\Arrecadacao\Requests\TEF;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class IncluirOperacaoRequest extends FormRequest
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
            "valor" => ["required", "numeric"],
            "nsu" => ["integer", "nullable"],
            "operacaotef" => ["required", "integer"],
            "bandeira" => ["string", "nullable"],
            "parcela" => ["nullable", "integer"],
            "confirmado" => ["boolean", "nullable"],
            "mensagemretorno" => ["string", "nullable"],
            "desfeito" => ["boolean", "nullable"],
            "codigoaprovacao" => ["string", "nullable"],
            "nsuautorizadora" => ["integer", "nullable"],
            "cartao" => ["string", "nullable"],
            "retorno" => ["string", "nullable"],
            "grupo" => ["required", "integer"],
            "confirmadoautorizadora" => ["boolean", "nullable"],
            "terminal" => ["required", "integer"],
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
            "numnov.required"         => utf8_encode("Número do recibo não informado."),
            "numnov.integer"          => utf8_encode("Número do recibo inválido."),

            "valor.required"          => utf8_encode("Valor do recibo não informado."),
            "valor.numeric"           => utf8_encode("Valor do recibo inválido."),

            "nsu.integer"             => utf8_encode("NSU do CTF inválido."),

            "operacaotef.required"    => utf8_encode("Operação não informada."),
            "operacaotef.integer"     => utf8_encode("Operação inválida."),

            "bandeira.string"         => utf8_encode("Bandeira inválido."),

            "parcela.integer"          => utf8_encode("Parcela inválido."),

            "confirmado.string"       => utf8_encode("Confirmado inválido."),

            "mensagemretorno.string"  => utf8_encode("Mensagem de Retorno inválida."),

            "desfeito.string"         => utf8_encode("Desfeito inválido."),

            "codigoaprovacao.string"  => utf8_encode("Código de Aprovação inválido."),

            "nsuautorizadora.integer" => utf8_encode("NSU da Autorizadora inválido."),

            "cartao.string"           => utf8_encode("Formato cartão inválido."),

            "retorno.string"          => utf8_encode("Retorno do CTFClient inválido."),

            "grupo.required"          => utf8_encode("Grupo não informado."),
            "grupo.string"            => utf8_encode("Grupo inválido."),

            "terminal.required"       => utf8_encode("Terminal não informado."),
            "terminal.string"         => utf8_encode("Terminal inválido."),

            "confirmadoautorizadora.boolean" => utf8_encode("Confirmação da Autorizadora inválida."),

            "DB_instit.required"      => utf8_encode("Código da instituição não informado."),
            "DB_instit.integer"       => utf8_encode("Código da instituição inválido."),

            "DB_coddepto.required"    => utf8_encode("Código do departamentro não informado."),
            "DB_coddepto.integer"     => utf8_encode("Código do departamentro inválido."),

            "DB_id_usuario.required"  => utf8_encode("Código do usuário não informado."),
            "DB_id_usuario.integer"   => utf8_encode("Código do usuário inválido."),

            "DB_datausu.required"     => utf8_encode("Data do sistema não informada não informado."),
            "DB_datausu.integer"      => utf8_encode("Data do sistema não informada inválido.")
        ];
    }
}
