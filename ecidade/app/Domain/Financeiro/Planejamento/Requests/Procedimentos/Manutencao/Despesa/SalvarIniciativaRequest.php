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

namespace App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class SalvarIniciativaRequest
 * @package App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa
 */
class SalvarIniciativaRequest extends FormRequest
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
            'pl12_codigo' => 'integer',
            'pl12_objetivo' => 'nullable|integer',
            'pl12_orcprojativ' => 'required|integer|filled',
            'pl12_anoorcamento' => 'required|integer|filled',
            'pl12_programaestrategico' => 'required|integer|filled',
            'pl12_origeminiciativa' => 'integer|nullable',
            'pl12_periodoacao' => 'integer|nullable',
            'pl12_valorbase' => 'numeric|nullable',
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
            'pl12_codigo.integer' => 'Código se informado deve ser um inteiro.',
            'pl12_objetivo.integer' => 'Objetivo se informado deve ser um inteiro.',

            'pl12_orcprojativ.integer' => 'O campo "Ação" deve ser um inteiro.',
            'pl12_orcprojativ.filled' => 'O campo "Ação" deve ser preenchido.',
            'pl12_orcprojativ.required' => 'O campo "Ação" deve ser informado.',

            'pl12_anoorcamento.integer' => 'O campo "Ano do Orçamento" deve ser um inteiro.',
            'pl12_anoorcamento.filled' => 'O campo "Ano do Orçamento" deve ser preenchido.',
            'pl12_anoorcamento.required' => 'O campo "Ano do Orçamento" deve ser informado.',

            'pl12_programaestrategico.integer' => 'O campo "Programa Estratégico" deve ser um inteiro.',
            'pl12_programaestrategico.filled' => 'O campo "Programa Estratégico" deve ser preenchido.',
            'pl12_programaestrategico.required' => 'O campo "Programa Estratégico" deve ser informado.',

            'pl12_origeminiciativa.integer' => 'O campo "Origem" se informado deve ser um inteiro.',
            'pl12_periodoacao.integer' => 'O campo "Período" se informado deve ser um inteiro.',

            'pl12_valorbase.numeric' => 'O campo "Valor Base" se informado deve ser um numéric.',
        ];
    }
}
