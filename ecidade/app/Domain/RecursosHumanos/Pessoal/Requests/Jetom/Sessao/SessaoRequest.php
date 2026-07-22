<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Sessao;

use App\Http\Requests\BaseFormRequest;

class SessaoRequest extends BaseFormRequest
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
            'rh247_sequencial' => 'nullable|integer',
            'rh247_comissao' => 'required|integer',
            'rh247_processada' => 'nullable|boolean',
            'rh247_tiposessao' => 'integer',
            'rh247_mes' => 'integer',
            'rh247_ano' => 'integer',
        ];
    }

    public function response(array $errors)
    {
        $mensagem = $errors[array_keys($errors)[0]][0];

        return response()->json([
            "message" => $mensagem,
            "errors" => $errors,
            "status" => 406
        ], 406);
    }

    public function messages()
    {
        return [
            'rh247_sequencial.integer' => utf8_encode('Código da sessão inválido.'),
            'rh247_comissao.required' => utf8_encode('Comissão não informada.'),
            'rh247_comissao.integer' => utf8_encode('Código da comissão inválido.'),
            'rh247_processada.boolean' => utf8_encode('Verificação de sessão processada está em formato inválido.'),
            'rh247_tiposessao.required' => utf8_encode('Tipo da sessão não informado.'),
            'rh247_tiposessao.integer' => utf8_encode('Tipo da sessão em formato inválido.'),
            'rh247_mes.integer' => utf8_encode('Mês da competência em formato inválido.'),
            'rh247_ano.integer' => utf8_encode('Ano da competência em formato inválido.')
        ];
    }
}
