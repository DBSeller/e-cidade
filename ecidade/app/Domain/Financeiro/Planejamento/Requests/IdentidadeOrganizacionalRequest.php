<?php


namespace App\Domain\Financeiro\Planejamento\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * Class IdentidadeOrganizacionalRequest
 * @package App\Domain\Financeiro\Planejamento\Requests
 */
class IdentidadeOrganizacionalRequest extends FormRequest
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
            'pl2_missao' => 'string|nullable',
            'pl2_visao' => 'string|nullable',
            'pl2_valores' => 'string|nullable',
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
            'pl2_missao.string' => 'Quando informado, "Missao" deve ser um texto.',
            'pl2_visao.string' => 'Quando informado, "Visão" deve ser um texto.',
            'pl2_valores.string' => 'Quando informado, "Valores" deve ser um texto.',
        ];
    }
}
