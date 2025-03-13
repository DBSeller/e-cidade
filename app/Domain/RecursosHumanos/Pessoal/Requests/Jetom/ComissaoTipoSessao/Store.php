<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoTipoSessao;

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
            ],
            'tiposessao' => [
                'required',
                'integer',
                'exists:jetomtiposessao,rh240_sequencial',
                Rule::unique("jetomcomissaotiposessao", "rh249_tiposessao")
                ->where("rh249_comissao", $this->request->get('comissao'))

            ],
            'quantidade' => [
                'integer',
                'required'
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
            "comissao.required" => utf8_encode("Código do tipo de sessão da comissão não informado."),
            "comissao.integer" => utf8_encode("Código inválido do tipo de sessão da comissão."),
            "comissao.exists" => utf8_encode("Código não encontrado do tipo de sessão da comissão."),
            "tiposessao.required" => utf8_encode("Código do tipo de sessão não informado."),
            "tiposessao.integer" => utf8_encode("Código inválido do tipo de sessão."),
            "tiposessao.exists" => utf8_encode("Código não encontrado do tipo de sessão."),
            "tiposessao.unique" => utf8_encode("Tipo de sessão já cadastrado para a comissão."),
            "quantidade.required" => utf8_encode("Quantidade não informada."),
            "quantidade.integer" => utf8_encode("Quantidade inválida."),
        ];
    }
}
