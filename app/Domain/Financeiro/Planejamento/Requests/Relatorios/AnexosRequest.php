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

namespace App\Domain\Financeiro\Planejamento\Requests\Relatorios;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class AnexosRequest
 * @package App\Domain\Financeiro\Planejamento\Requests\Relatorios
 */
class AnexosRequest extends FormRequest
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
            'planejamento_id' => 'required|integer|filled|exists:planejamento,pl2_codigo',
            'codigo_relatorio' => 'required|integer|filled|exists:orcparamrel,o42_codparrel',
            'periodo' => 'required|integer|filled',
            'instituicoes' => 'required',
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
            'planejamento_id.integer' => 'Código se informado deve ser um inteiro.',
            'planejamento_id.required' => 'O campo "Código" deve ser informado.',
            'planejamento_id.filled' => 'O campo "Código" deve ser preenchido.',
            'planejamento_id.exists' => 'Planejamento não encontrado no banco de dados.',

            'codigo_relatorio.integer' => 'Relatório se informado deve ser um inteiro.',
            'codigo_relatorio.required' => 'O Código do Relatório deve ser informado.',
            'codigo_relatorio.filled' => 'O "Código do Relatório" deve ser preenchido.',
            'codigo_relatorio.exists' => 'Relatório não encontrado no banco de dados.',


            'periodo.integer' => 'Período se informado deve ser um inteiro.',
            'periodo.required' => 'O Código do Período deve ser informado.',
            'periodo.filled' => 'O "Código do Período" deve ser preenchido.',

            'instituicoes.required' => 'Deve ser informado a(s) Instituições.',
        ];
    }
}
