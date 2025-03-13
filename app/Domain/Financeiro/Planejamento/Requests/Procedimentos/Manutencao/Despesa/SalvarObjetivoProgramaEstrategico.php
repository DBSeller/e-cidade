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

class SalvarObjetivoProgramaEstrategico extends FormRequest
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
            'pl9_codigo' => 'required|integer|filled',
            'pl11_codigo' => 'integer',
            'pl11_numero' => 'required|filled',
            'pl11_ods' => 'integer',
            'pl11_descricao' => 'required|string|filled',
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
            'pl9_codigo.integer' => 'Código do programa estratégico se informado deve ser um inteiro.',
            'pl9_codigo.required' => 'O campo "Código do programa estratégico" deve ser informado.',
            'pl9_codigo.filled' => 'O campo "Código do programa estratégico" deve ser preenchido.',
            'pl11_codigo.integer' => 'O campo "Código do Objetivo" deve ser informado.',

            'pl11_numero.filled' => 'O campo "Número do Objetivo" deve ser preenchido.',
            'pl11_numero.required' => 'O campo "Número do Objetivo" deve ser informado.',

            'pl11_ods.integer' => 'O campo "ODS" deve ser um inteiro.',

            'pl11_descricao.required' => 'O campo "Descrição" deve ser informado.',
            'pl11_descricao.string' => 'O campo "Descrição" deve ser uma string.',
            'pl11_descricao.filled' => 'O campo "Descrição" deve ser preenchido.',

            'valores.required' => 'Deve ser informado uma coleção de valores.'
        ];
    }
}
