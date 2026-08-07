<?php


namespace App\Domain\Financeiro\Orcamento\Requests\Cadastro;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class RecursoSalvarAntes2022Request extends FormRequest
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
            'codigo' => 'integer|nullable',
            'descricao' => 'required|string|filled',
            'codigoTribunal' => 'required|string|filled',
            'finalidade' => 'required|string|filled',
            'dataLimite' => 'date|nullable',
            'codigoSiconf' => 'string|nullable',
            'loaIdentificacao' => 'integer|nullable',
            'loaTipo' => 'integer|nullable',
            'loaGrupo' => 'integer|nullable',
            'loaEspecificacao' => 'filled',
            'complemento' => 'string|filled',
            'tipoRecurso' => 'required|integer',
            'codigoRecurso' => 'nullable',
            'codigoEstrutural' =>'required|integer',
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
            'descricao.required' => 'Informe a descrição do recurso.',
            'descricao.filled' => 'A descrição deve ser preenchida.',
            'codigoTribunal.required' => 'O código do Tribunal deve ser informado.',
            'codigoTribunal.filled' => 'O código do Tribunal deve ser preenchido.',
            'finalidade.required' => 'A Finalidade do recurso deve ser informada.',
            'finalidade.filled' => 'A Finalidade do recurso deve ser informada.',
            'dataLimite.date' => 'Se a data limite for informada, deve ser uma data válida.',
            'codigoSiconf.string' => 'Código do Siconf se informado deve ser preenchido.',
            'loaIdentificacao.integer' => 'Código da Identificação de recurso deve ser um inteiro.',
            'loaTipo.integer' => 'Código do Tipo de recurso deve ser um inteiro.',
            'loaGrupo.integer' => 'Código do Grupo de recurso deve ser um inteiro.',
            'loaEspecificacao.filled' => 'Especificação de recurso deve ser preenchido.',
            'complemento.filled' => 'Complemento de recurso deve ser preenchido.',
            "colunas.required" => "Campo Colunas deve ser informado.",
            "tipoRecurso.required" => "Tipo do Recurso não foi informado.",
            "tipoRecurso.integer" => "Tipo do Recurso deve ser um valor interiro.",
            "codigoEstrutural.required" => "Código do estrutural não foi informado.",
            "codigoEstrutural.integer" => "Código do estrutural deve ser um valor interiro.",
        ];
    }
}
