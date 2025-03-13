<?php

namespace App\Domain\Financeiro\Planejamento\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class SalvarAreaResultadoRequest
 * @package App\Domain\Financeiro\Planejamento\Requests
 */
class SalvarAreaResultadoRequest extends FormRequest
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
            'pl4_codigo' => 'integer|nullable',
            'pl4_titulo' => 'required|string|filled',
            'pl4_contextualizacao' => 'string|nullable'
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
            'pl4_titulo.required' => 'Deve ser informado um título para a área.'
        ];
    }
}
