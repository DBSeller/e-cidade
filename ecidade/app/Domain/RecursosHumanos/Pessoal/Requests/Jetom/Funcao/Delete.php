<?php

namespace App\Http\Requests\RecursosHumanos\Pessoal\Jetom\Funcao;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;

class Delete extends FormRequest
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
            'id' => 'required|integer|exists:jetomfuncao,rh241_sequencial',
        ];
    }

    /**
     * @param array $errors
     * @return DBJsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return new DBJsonResponse($errors, "Função não encontrada.", 406);
    }
}
