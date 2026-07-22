<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Receita;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class SalvarEstimativaReceitaRequest
 * @package App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Receita
 */
class SalvarEstimativaReceitaRequest extends FormRequest
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
            'id' => 'integer',
            'instituicao_id' => 'required|integer|filled',
            'orcorgao_id' => 'required|integer|filled',
            'orcunidade_id' => 'required|integer|filled',
            'orcfontes_id' => 'required|integer|filled',
            'natureza' => 'required|string|filled',
            'recurso_id' => 'required|integer|filled',
            'concarpeculiar_id' => 'required|string|filled',
            'esferaorcamentaria' => 'required|integer|filled',
            'planejamento_id' => 'required|integer|filled',
            'anoorcamento' => 'required|integer|filled',
            'valorbase' => 'numeric|nullable',
            'valores' => 'required'
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
            'id.integer' => 'Código se informado deve ser um inteiro.',

            'instituicao_id.required' => 'O campo "Código da Instituição" deve ser informado.',
            'instituicao_id.integer' => 'Código da Instituição se informado deve ser um inteiro.',
            'instituicao_id.filled' => 'O campo "Código da Instituição" deve ser preenchido.',

            'orcorgao_id.required' => 'O campo "Código do Órgão" deve ser informado.',
            'orcorgao_id.integer' => 'Código do Órgão se informado deve ser um inteiro.',
            'orcorgao_id.filled' => 'O campo "Código do Órgão" deve ser preenchido.',

            'orcunidade_id.required' => 'O campo "Código da unidade" deve ser informado.',
            'orcunidade_id.integer' => 'Código da unidade se informado deve ser um inteiro.',
            'orcunidade_id.filled' => 'O campo "Código da unidade" deve ser preenchido.',

            'orcfontes_id.required' => 'O campo "Código da Natureza da Receita" deve ser informado.',
            'orcfontes_id.integer' => 'Código da Natureza da Receita se informado deve ser um inteiro.',
            'orcfontes_id.filled' => 'O campo "Código da Natureza da Receita" deve ser preenchido.',

            'recurso_id.required' => 'O campo "Código do Recurso" deve ser informado.',
            'recurso_id.integer' => 'Código do Recurso se informado deve ser um inteiro.',
            'recurso_id.filled' => 'O campo "Código do Recurso" deve ser preenchido.',

            'concarpeculiar_id.required' => 'O campo "Caracteristica Peculiar" deve ser informado.',
            'concarpeculiar_id.string' => 'Caracteristica Peculiar se informado deve ser um inteiro.',
            'concarpeculiar_id.filled' => 'O campo "Caracteristica Peculiar" deve ser preenchido.',

            'natureza.required' => 'O campo "Natureza" deve ser informado.',
            'natureza.string' => 'Natureza se informado deve ser um inteiro.',
            'natureza.filled' => 'O campo "Natureza" deve ser preenchido.',

            'esferaorcamentaria.required' => 'O campo "Esfera Orçamentária" deve ser informado.',
            'esferaorcamentaria.integer' => 'Esfera Orçamentária se informado deve ser um inteiro.',
            'esferaorcamentaria.filled' => 'O campo "Esfera Orçamentária" deve ser preenchido.',

            'planejamento_id.required' => 'O campo "Código do Planejamento" deve ser informado.',
            'planejamento_id.integer' => 'Código do Planejamento se informado deve ser um inteiro.',
            'planejamento_id.filled' => 'O campo "Código do Planejamento" deve ser preenchido.',

            'anoorcamento.required' => 'O campo "Exercício do Orçamento" deve ser informado.',
            'anoorcamento.integer' => 'Exercício do Orçamento se informado deve ser um inteiro.',
            'anoorcamento.filled' => 'O campo "Exercício do Orçamento" deve ser preenchido.',

            'valores.required' => 'Deve ser informado uma coleção de valores.',
        ];
    }
}
