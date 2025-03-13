<?php


namespace App\Domain\Financeiro\Planejamento\Requests\Orcamento;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class GerarRequest extends FormRequest
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
            'planejamento_id' => 'required|integer|filled|exists:planejamento,pl2_codigo',
        ];
    }

    /**
     * @param array $errors
     * @return DBJsonResponse|JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $mensagem = $errors[array_keys($errors)[0]][0];
        return new DBJsonResponse($errors, $mensagem, 406, false);
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'planejamento_id.integer' => 'Código se informado deve ser um inteiro.',
            'planejamento_id.required' => 'O campo "Código" deve ser informado.',
            'planejamento_id.filled' => 'O campo "Código" deve ser preenchido.',
            'planejamento_id.exists' => 'Planejamento não encontrado no banco de dados.',
        ];
    }
}
