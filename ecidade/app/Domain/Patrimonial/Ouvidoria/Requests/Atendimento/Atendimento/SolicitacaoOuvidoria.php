<?php
namespace App\Domain\Patrimonial\Ouvidoria\Requests\Atendimento\Atendimento;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;

class SolicitacaoOuvidoria extends BaseFormRequest
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
            'numeroProcesso' => 'required|filled|integer',
            'anoProcesso' => 'required|filled|integer',
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
            "numeroProcesso.required" => utf8_encode("Código da Numero de Processo não informado."),
            "numeroProcesso.filled" => utf8_encode("O código da Numero de Processo informado está vazio."),
            "numeroProcesso.integer" => utf8_encode("Código inválido da Numero de Processo."),
            "anoProcesso.required" => utf8_encode("Código do anoProcesso não informado."),
            "anoProcesso.filled" => utf8_encode("O código do anoProcesso informado está vazio."),
            "anoProcesso.integer" => utf8_encode("Código inválido do anoProcesso."),
        ];
    }
}
