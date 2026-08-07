<?php


namespace App\Domain\Financeiro\Planejamento\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * Class SalvarComissao
 * @package App\Domain\Financeiro\Planejamento\Requests
 */
class SalvarComissaoRequest extends FormRequest
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
            'cgms' => 'required|array'
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
            'cgms.required' => 'Deve ser informado uma coleção de cgm.',
            'cgms.array' => 'A propriedade "cgms" deve ser um array.',
        ];
    }
}
