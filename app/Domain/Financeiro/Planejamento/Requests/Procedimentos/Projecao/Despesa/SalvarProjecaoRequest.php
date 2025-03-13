<?php


namespace App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Projecao\Despesa;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class SalvarProjecaoRequest extends FormRequest
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
            'DB_id_usuario' => 'required|integer|filled',
            'DB_instit' => 'required|integer|filled',
            'DB_anousu' => 'required|integer|filled',
            'projecao' => 'required|string|filled',
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
            'DB_id_usuario.required' => 'Código do usuário deve ser informado.',
            'DB_id_usuario.integer' => 'Código do usuário se informado deve ser um inteiro.',
            'DB_id_usuario.filled' => 'Código do usuário se informado deve ser preenchido.',
            'DB_instit.required' => 'Código da instituição deve ser informado.',
            'DB_instit.integer' => 'Código da instituição se informado deve ser um inteiro.',
            'DB_instit.filled' => 'Código da instituição se informado deve ser preenchido.',
            'DB_anousu.required' => 'Ano da sessão deve ser informado.',
            'DB_anousu.integer' => 'Ano da sessão se informado deve ser um inteiro.',
            'DB_anousu.filled' => 'Ano da sessão se informado deve ser preenchido.',
            'projecao.required' => 'Projeção deve ser informada.',
            'projecao.string' => 'Projeção se informada deve ser uma string.',
            'projecao.filled' => 'Projeção deve ser preenchida.',
        ];
    }
}
