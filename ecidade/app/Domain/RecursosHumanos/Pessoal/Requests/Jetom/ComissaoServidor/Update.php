<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoServidor;

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
            'id' => 'required|integer|exists:jetomcomissaoservidor,rh245_sequencial',
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
            'ativo' => 'required',
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
        $mensagem = $errors[array_keys($errors)[0]][0];
        return new DBJsonResponse([], $mensagem, 406);
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            "id.required" => "Código da função do servidor não informado.",
            "id.integer" => "Código inválido da função do servidor.",
            "id.exists" => "Código não encontrado da função do servidor.",
            "comissao.required" => "Código da comissão não informado.",
            "comissao.integer" => "Código inválido da comissão.",
            "comissao.exists" => "Comissão não encontrada.",
            "funcao.required" => "Função do servidor não informada.",
            "funcao.integer" => "Função inválida do servidor.",
            "funcao.exists" => "Função não encontrada.",
            "funcao.unique" => "Função já cadastrada para o servidor.",
            "matricula.required" => "Matricula não informada.",
            "matricula.integer" => "Matricula inválida.",
            "mesinicio.integer" => "Mês de inicio da função inválido.",
            "mesinicio.between" => "Mês de inicio da função deve ser entre 1 e 12.",
            "mesfim.integer" => "Mês de termino da função inválido.",
            "mesfim.between" => "Mês de termino da função deve ser entre 1 e 12.",
            "anoinicio.integer" => "Ano de inicio da função inválido.",
            "anoinicio.min" => "Ano inicial não pode ser inferior a " . date("Y") . ".",
            "anofim.integer" => "Ano de termino da função inválido.",
            "ativo.required" => "O status de ativo não informado para a função do servidor.",
        ];
    }
}
