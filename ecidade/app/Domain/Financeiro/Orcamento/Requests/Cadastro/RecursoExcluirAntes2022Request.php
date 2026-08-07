<?php


namespace App\Domain\Financeiro\Orcamento\Requests\Cadastro;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class RecursoExcluirRequest
 * @package App\Domain\Financeiro\Orcamento\Requests\Cadastro
 */
class RecursoExcluirAntes2022Request extends FormRequest
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
            'codigo' => 'required|integer',
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
            'codigo.required' => 'Informe o código do recurso.',
            'codigo.integer' => 'O código deve ser um intero.',
        ];
    }
}
