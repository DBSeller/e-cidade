<?php


namespace App\Http\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

abstract class DBFormRequest extends FormRequest
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
    abstract public function rules();
}
