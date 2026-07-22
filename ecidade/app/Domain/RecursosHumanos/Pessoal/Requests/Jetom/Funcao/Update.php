<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Funcao;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseFormRequest;

class Update extends BaseFormRequest
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
    public function rules(Request $request)
    {
        return $this->preValidacaoRule() ? $this->preValidacaoRule():[
            'descricao' => [
                'string',
                'required',
                Rule::unique('jetomfuncao', 'rh241_descricao')->where("rh241_instit", $request->instituicao),
            ],
            'instituicao' => 'required|integer'
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
            'instituicao.required' => utf8_encode('Instituição não informada.'),
            'instituicao.integer' => utf8_encode('Código da instituição inválido.'),
            'descricao.required' => utf8_encode('Descrição da função não informada.'),
            'descricao.string' => utf8_encode('Descrição inválida para a função.'),
            'descricao.unique' => utf8_encode('Descrição da função já cadastrada.')
        ];
    }
}
