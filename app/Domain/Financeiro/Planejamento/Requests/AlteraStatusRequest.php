<?php


namespace App\Domain\Financeiro\Planejamento\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * Class AlteraSituacaoRequest
 * @package App\Domain\Financeiro\Planejamento\Requests
 */
class AlteraStatusRequest extends FormRequest
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
            'pl2_codigo' => 'required|integer|filled',
            'pl2_tipo' => ['required', 'string', 'filled', Rule::in(['PPA', 'LDO', 'LOA'])],
            'pl1_codigo' => 'required|string|exists:status',
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
            'pl2_codigo.integer' => 'Código se informado deve ser um inteiro.',
            'pl2_codigo.required' => 'O campo "Código" deve ser informado.',
            'pl2_codigo.filled' => 'O campo "Código" deve ser preenchido.',
            'pl2_tipo.required' => 'Tipo do planejamento deve ser informado.',
            'pl2_tipo.string' => 'Tipo do planejamento deve ser uma string',
            'pl2_tipo.filled' => 'Tipo do planejamento deve ser preenchido',
            'pl1_codigo.required' => 'Situação deve ser informada.',
            'pl1_codigo.integer' => 'Situação deve ser um inteiro.',
        ];
    }
}
