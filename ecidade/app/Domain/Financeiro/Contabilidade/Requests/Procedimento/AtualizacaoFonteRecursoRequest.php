<?php


namespace App\Domain\Financeiro\Contabilidade\Requests\Procedimento;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class ManutencaoFonteRecursoDespesaRequest
 * @package App\Domain\Financeiro\Contabilidade\Requests\Procedimento
 */
class AtualizacaoFonteRecursoRequest extends FormRequest
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
            'origem' => 'required|string',
            'itens' => 'required|array',
            'DB_anousu' => 'required',
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
            'origem.required' => 'Deve ser informado a origem dos dados. Despesa ou Receita.',
            'origem.string' => 'Origem deve ser uma string.',
            'itens.required' => 'Deve ser informado os empenhos/lançamentos em um array de itens.',
            'itens.array' => 'Deve ser informado os empenhos/lançamentos em um array de itens.',
            'DB_anousu.required' => 'Deve ser informado o ano que esta trabalhando.',
        ];
    }
}
