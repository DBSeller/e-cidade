<?php


namespace App\Domain\Financeiro\Planejamento\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class SalvarLDORequest extends FormRequest
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
            'pl2_codigo' => 'integer|nullable',
            'pl2_tipo' => ['required', 'string', 'filled', Rule::in(['PPA', 'LDO', 'LOA'])],
            'pl2_codigo_pai' => 'required|integer|filled',
            'pl2_ano_inicial' => 'required|integer',
            'pl2_ano_final' => 'required|integer',
            'pl2_titulo' => 'required|string|filled',
            'pl2_base_calculo' => 'required|integer|between:1,2',
            'pl2_base_despesa' => 'integer|nullable',
            'pl2_composicao' => 'required|integer',
            'pl2_ementa' => 'string|nullable'
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
            'pl2_tipo.required' => 'Tipo do planejamento deve ser informado.',
            'pl2_tipo.string' => 'Tipo do planejamento deve ser uma string.',
            'pl2_tipo.filled' => 'Tipo do planejamento deve ser preenchido.',
            'pl2_tipo.in' => 'Tipos de planejamento aceitos: PPA, LDO e LOA.',
            'pl2_codigo_pai.required' => 'Código do plano vinculado, se informado, deve ser um inteiro.',
            'pl2_codigo_pai.integer' => 'Código do plano vinculado, se informado, deve ser um inteiro.',
            'pl2_codigo_pai.filled' => 'Código do plano vinculado deve ser informado.',
            'pl2_ano_inicial.required' => 'Campo "Ano Inicial" deve ser informado.',
            'pl2_ano_inicial.integer' => 'O campo "Ano Inicial" deve ser um inteiro.',
            'pl2_ano_final.required' => 'Campo "Ano Final" deve ser informado.',
            'pl2_ano_final.integer' => 'O campo "Ano Final" deve ser um inteiro.',
            'pl2_titulo.required' => 'Campo "Título" deve ser informado.',
            'pl2_titulo.string' => 'Campo "Título" deve ser uma string.',
            'pl2_titulo.filled' => 'Campo "Título" deve ser preenchido.',
            'pl2_base_calculo.required' => 'Campo "Base de Cálculo" deve ser informado.',
            'pl2_base_calculo.integer' => 'O campo "Base de Cálculo" deve ser um inteiro.',
            'pl2_base_calculo.between' => 'A base de cálculo só aceita os valores 1 e 2.',
            'pl2_base_despesa.integer' => 'O campo "Base da Despesa" deve ser um inteiro.',
            'pl2_composicao.integer' => 'Campo "Composiçao" deve ser um inteiro',
            'pl2_ementa.string' => 'Quando informado, "Emenda" deve ser um texto.',
        ];
    }
}
