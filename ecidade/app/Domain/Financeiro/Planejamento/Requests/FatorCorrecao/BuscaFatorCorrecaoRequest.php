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

namespace App\Domain\Financeiro\Planejamento\Requests\FatorCorrecao;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class BuscaFatorCorrecaoRequest extends FormRequest
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
            'planejamento_id' => 'required|integer|filled',
            'natureza_id' => 'required|integer|filled',
            'tipo' => ['required', 'string', Rule::in(['despesa', 'receita'])],
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
            'planejamento_id.integer' => 'Planejamento se informado deve ser um inteiro.',
            'planejamento_id.required' => 'O campo "Planejamento" deve ser informado.',
            'planejamento_id.filled' => 'O campo "Planejamento" deve ser preenchido.',
            'natureza_id.integer' => 'Natureza se informado deve ser um inteiro.',
            'natureza_id.required' => 'O campo "Natureza" deve ser informado.',
            'natureza_id.filled' => 'O campo "Natureza" deve ser preenchido.',
            'tipo.required' => 'Deve ser informado o tipo da natureza que deve ser buscado.',
            'tipo.string' => 'O "Tipo" deve ser uma string.',
            'tipo.in' => 'O "Tipo" deve ser preenchido com o valor: despesa ou receita.',
        ];
    }
}
