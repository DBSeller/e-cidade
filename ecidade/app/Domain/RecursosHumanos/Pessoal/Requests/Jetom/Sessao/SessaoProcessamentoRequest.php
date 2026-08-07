<?php

namespace App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Sessao;

use App\Http\Requests\BaseFormRequest;

class SessaoProcessamentoRequest extends BaseFormRequest
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
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ];
    }

    protected function getValidatorInstance()
    {
        if ($this->request->get('ids')) {
            $this->merge(['ids' => json_decode($this->get('ids'))]);
        }

        return parent::getValidatorInstance();
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
            'ids.required' => utf8_encode('É necessário informar as Sessões a serem processadas.'),
            'ids.array' => utf8_encode('É necessário informar as Sessões a serem processadas.'),
            'ids.*.integer' => utf8_encode('Código da sessão informada é inválido.'),
        ];
    }
}
