<?php


namespace App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Projecao;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class PersisteFatorCorrecaoRequest extends FormRequest
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
            'planejamento' => 'required|integer|filled',
            'natureza' => ['required', 'integer'],
            'deflator' => ['required', 'boolean'],
            'valores' => 'required',
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
            'planejamento.required' => 'Planejamento deve ser informado.',
            'planejamento.integer' => 'Planejamento se informado deve ser um inteiro.',
            'planejamento.filled' => 'Planejamento se informado deve ser preenchido.',
            'natureza.required' => 'O campo "Natureza" deve ser informado.',
            'natureza.integer' => 'O campo "Natureza" deve  ser um inteiro.',
            'valores.filled' => 'Os campos "Valores" deveram ser informados.',
        ];
    }
}
