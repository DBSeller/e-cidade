<?php
namespace App\Domain\Patrimonial\Protocolo\Requests\Processo\ProcessoDocumento;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Requests\BaseFormRequest;

class DocumentosPorProcesso extends BaseFormRequest
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
            'codigoProcesso' => 'required|filled|integer',
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
            "codigoProcesso.required" => utf8_encode("Código do processo não informado."),
            "codigoProcesso.filled" => utf8_encode("O código do processo informado está vazio."),
            "codigoProcesso.integer" => utf8_encode("Código inválido do processo."),
        ];
    }
}
