<?php


namespace App\Domain\Financeiro\Orcamento\Requests\Cadastro;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class ComplementoRequest
 * @package App\Domain\Financeiro\Orcamento\Requests\Cadastro
 */
class ComplementoSalvarRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
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
    public function rules()
    {
        return [
            'codigo' => 'integer|nullable',
            'descricao' => 'required|string|filled',
            'msc' => 'boolean',
            'tribunal' => 'boolean',
        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'codigo.integer' => 'Quando informado o código deve ser um inteiro.',
            'descricao.required' => 'A descrição deve ser informada.',
            'descricao.filled' => 'A descrição deve ser preenchida.',
            'msc.boolean' => 'O campo "MSC" deve ser um booleano.',
            'tribunal.boolean' => 'O campo "Tribunal" deve ser um booleano.',
        ];
    }
}
