<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoConfiguracao;

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
     * @return array
     */
    public function rules()
    {
        return [
            'comissao' => [
                'required',
                'integer',
                'exists:jetomcomissao,rh242_sequencial',
                Rule::unique("jetomcomissaoconfiguracao", "rh243_comissao")
                ->where("rh243_funcao", $this->request->get('funcao'))
                ->where("rh243_tiposessao", $this->request->get('tiposessao'))
            ],
            'funcao' => [
                'required',
                'integer',
                'exists:jetomfuncao,rh241_sequencial'
            ],
            'tiposessao' => [
                'required',
                'integer',
                'exists:jetomtiposessao,rh240_sequencial'
            ],
            'rubrica' => [
                'string',
                'required',
                'max:4'
            ],
            'valor' => [
                'numeric',
                'required',
                'regex:/^\d+(\.\d{1,2})?$/'
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
            "comissao.required" => utf8_encode("Código da comissão não informado."),
            "comissao.integer" => utf8_encode("Código inválido da comissão."),
            "comissao.exists" => utf8_encode("Não foi encontrada a comissão com o código informado."),
            "comissao.unique" => utf8_encode("Configuração já cadastrada."),
            "funcao.required" => utf8_encode("Código da função não informado."),
            "funcao.integer" => utf8_encode("Código inválido da função."),
            "funcao.exists" => utf8_encode("Função não encontrada."),
            "tiposessao.required" => utf8_encode("Tipo de sessão não informado."),
            "tiposessao.integer" => utf8_encode("Código inválido para o tipo de sessão informado."),
            "tiposessao.exists" => utf8_encode("Tipo de sessão não encontrado."),
            "valor.required" => utf8_encode("Valor não informado."),
            "valor.numeric" => utf8_encode("Valor inválido."),
            "valor.regex" => utf8_encode("O formato do valor esta inválido."),
        ];
    }
}
