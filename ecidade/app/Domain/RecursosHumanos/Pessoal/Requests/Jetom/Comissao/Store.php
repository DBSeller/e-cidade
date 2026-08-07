<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Comissao;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class Store extends BaseFormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array|bool
     */
    public function rules()
    {
        return $this->preValidacaoRule() ? $this->preValidacaoRule() : [
            'instituicao' => 'required|filled|integer|max:50',
            'descricao' => [
                'required',
                'filled',
                'string',
                'max:50',
                Rule::unique(
                    'jetomcomissao',
                    'rh242_descricao'
                )->where(
                    "rh242_instit",
                    $this->request->all()['instituicao']
                ),
            ],

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
            "descricao.required" => utf8_encode("É necessário informar a descrição da comissão."),
            "descricao.filled" => utf8_encode("Descrição não pode estar vazia."),
            "descricao.string" => utf8_encode("Descrição inválida."),
            "descricao.unique" => utf8_encode("Esta descrição já cadastrada na instituição."),
            "descricao.max" => utf8_encode("Excedido o limite máximo de 50 caracteres."),
            "instituicao.required" => utf8_encode("Instituição não informada para o cadastro da comissão."),
            "instituicao.filled" => utf8_encode("O código da instituição esta vazio."),
            "instituicao.integer" => utf8_encode("Código inválido da Instituição."),
        ];
    }
}
