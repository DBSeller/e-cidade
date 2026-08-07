<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoServidor;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoServidor;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoFuncao;
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
            'matricula' => 'required|integer',
            'mesinicio' => 'integer|between:1,12',
            'mesfim' => 'integer|between:1,12',
            'anoinicio' => 'integer|min:' . date("Y"),
            'anofim' => 'integer',
            'funcao' => [
                'required',
                'integer',
                'exists:jetomfuncao,rh241_sequencial',
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
            "comissao.exists" => utf8_encode("Comissão não encontrada."),
            "funcao.required" => utf8_encode("Função do servidor não informada."),
            "funcao.integer" => utf8_encode("Função inválida do servidor."),
            "funcao.exists" => utf8_encode("Função não encontrada."),
            "funcao.unique" => utf8_encode("Função já cadastrada para o servidor."),
            "matricula.required" => utf8_encode("Matricula não informada."),
            "matricula.integer" => utf8_encode("Matricula inválida."),
            "mesinicio.integer" => utf8_encode("Mês de inicio da função inválido."),
            "mesinicio.between" => utf8_encode("Mês de inicio da função deve ser entre 1 e 12."),
            "mesfim.integer" => utf8_encode("Mês de termino da função inválido."),
            "mesfim.between" => utf8_encode("Mês de termino da função deve ser entre 1 e 12."),
            "anoinicio.integer" => utf8_encode("Ano de inicio da função inválido."),
            "anoinicio.min" => utf8_encode("Ano inicial não pode ser inferior a " . date("Y") . "."),
            "anofim.integer" => utf8_encode("Ano de termino da função inválido."),
        ];
    }
}
