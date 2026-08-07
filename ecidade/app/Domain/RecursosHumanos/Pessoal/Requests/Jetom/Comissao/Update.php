<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Comissao;

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

        // dd($this->request->all());
        return $this->preValidacaoRule() ? $this->preValidacaoRule() : [
            'id' => 'required|integer|exists:jetomcomissao,rh242_sequencial',
            'instituicao' => [
                'required',
                'integer'
            ],
            'descricao' => [
                'string',
                'required',
                'max:50',
                Rule::unique(
                    'jetomcomissao',
                    'rh242_descricao'
                )
                ->where(
                    "rh242_instit",
                    $this->request->get('instituicao')
                )
                ->whereNot('rh242_sequencial', $this->request->get('id')),
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
            "id.required" => utf8_encode("Código da comissão não informado."),
            "id.integer" => utf8_encode("Código inválido da comissão."),
            "id.exists" => utf8_encode("Comissão não encontrada."),
            "descricao.required" => utf8_encode("Descrição da comissão não informada."),
            "descricao.string" => utf8_encode("Descrição inválida da comissão."),
            "descricao.unique" => utf8_encode("Encontrada outra comissão com o mesmo nome na instituição."),
            "descricao.max" => utf8_encode("Excedido o limite máximo de 50 caracteres para a descrição da comissão."),
            "instituicao.required" => utf8_encode("Instituição da comissão não informada."),
            "instituicao.integer" => utf8_encode("Instituição inválida da comissão."),
        ];
    }
}
