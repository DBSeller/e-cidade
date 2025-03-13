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
 * Class SalvarMetaObjetivoRequest
 * @package App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa
 */
class SalvarIndicadorProgramaEstrategicoRequest extends FormRequest
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
            'pl22_codigo' => 'integer',
            'pl22_indice' => 'required|numeric|filled',
            'pl22_ano' => 'required|integer|filled',
            'pl22_orcindica' => 'required|integer|filled',
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

            'pl22_codigo.integer' => 'O campo "Código do Indicador" deve ser informado.',

            'pl22_indice.required' => 'O campo "Índice" deve ser informado.',
            'pl22_indice.filled' => 'O campo "Índice" deve ser preenchido.',
            'pl22_indice.numeric' => 'O campo "Índice" deve ser um decimal.',
            
            'pl22_ano.required' => 'O campo "Ano" deve ser informado.',
            'pl22_ano.filled' => 'O campo "Ano" deve ser preenchido.',
            'pl22_ano.integer' => 'O campo "Ano" deve ser um inteiro.',
            
            'pl22_orcindica.required' => 'Código do indicador deve ser informado.',
            'pl22_orcindica.filled' => 'O campo "Código do indicador" deve ser preenchido.',
            'pl22_orcindica.integer' => 'O campo "Código do indicador" deve ser um inteiro.',
        ];
    }
}
