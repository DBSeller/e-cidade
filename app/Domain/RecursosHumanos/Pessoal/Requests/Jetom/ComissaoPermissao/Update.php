<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoPermissao;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class Update extends BaseFormRequest
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
        return [
            'id' => 'required|integer|exists:jetompermissao,rh251_sequencial',
            'comissao' => 'required|filled|integer',
            'matricula' => [
                'required',
                'filled',
                'integer',
                Rule::unique(
                    'jetompermissao',
                    'rh251_matricula'
                )
                ->where(
                    "rh251_comissao",
                    $this->request->get('comissao')
                ),
            ],
        ];
    }

    /**
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
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
            "id.integer" => utf8_encode("Código inválido da Permissão."),
            "id.exists" => utf8_encode("Permissão não encontrada."),

            "comissao.required" => utf8_encode("Comissão não informada para o cadastro da comissão."),
            "comissao.filled" => utf8_encode("O código da Comissão esta vazio."),
            "comissao.integer" => utf8_encode("Código inválido da Comissão."),

            "matricula.required" => utf8_encode("É necessário informar a Matricula da comissão."),
            "matricula.filled" => utf8_encode("Matricula não pode estar vazia."),
            "matricula.integer" => utf8_encode("Matricula inválida."),
            "matricula.unique" => utf8_encode("Esta matricula já tem cadastro na permissão."),
        ];
    }
}
