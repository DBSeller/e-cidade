<?php


namespace App\Domain\Financeiro\Planejamento\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class SalvarObjetivoRequest
 * @package App\Domain\Financeiro\Planejamento\Requests
 */
class SalvarObjetivoRequest extends FormRequest
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
            'pl5_codigo' => 'integer|nullable',
            'pl5_arearesultado' => 'required|integer|filled',
            'pl5_titulo' => 'required|string|filled',
            'pl5_contextualizacao' => 'string|nullable',
            'pl5_fonte' => 'string|nullable'
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
            'pl5_arearesultado.integer' => 'Código se informado deve ser um inteiro.',
            'pl5_arearesultado.required' => 'O campo "Código" deve ser informado.',
            'pl5_arearesultado.filled' => 'O campo "Código" deve ser preenchido.',
            'pl5_titulo.required' => 'Deve ser informado um título para a área.'
        ];
    }
}
