<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoFuncao;

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
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:jetomcomissaofuncao,rh246_sequencial',
            'comissao' => [
                'required',
                'integer',
                'exists:jetomcomissao,rh242_sequencial',
            ],
            'funcao' => [
                'required',
                'integer',
                'exists:jetomfuncao,rh241_sequencial',
                Rule::unique("jetomcomissaofuncao", "rh246_funcao")
                    ->where("rh246_comissao", $this->request->get('comissao'))
                    ->whereNot("rh246_sequencial", $this->request->get('id'))
            ],
            'quantidade' => [
                'integer',
                'required'
            ],
        ];
    }

    /**
     * @param  array $errors
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
            "id.required" => utf8_encode("Código da configuração da função da comissão não informado."),
            "id.integer" => utf8_encode("Código inválido da configuração da função da comissão."),
            "id.exists" => utf8_encode("Configuração da função da comissão não encontrada."),
            "comissao.required" => utf8_encode("Código da comissão não informado."),
            "comissao.integer" => utf8_encode("Código inválido da comissão."),
            "comissao.exists" => utf8_encode("Comissão não encontrada"),
            "funcao.required" => utf8_encode("Código da função não informado."),
            "funcao.integer" => utf8_encode("Código inválido da função."),
            "funcao.exists" => utf8_encode("Função não encontrada."),
            "funcao.unique" => utf8_encode("Função já cadastrada para a comissão."),
            "quantidade.required" => utf8_encode("Quantidade não informada."),
            "quantidade.integer" => utf8_encode("Quantidade inválida"),
        ];
    }
}
