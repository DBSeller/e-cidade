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
 * Class SalvarCronogramaDesembolsoDespesaRequest
 * @package App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Despesa
 */
class RecalcularGeralCronogramaDesembolsoDespesaRequest extends FormRequest
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
            'formula' => 'required|integer|filled',
            'mes' => 'required',
            'anos' => 'required',
            'detalhamentoiniciativas' => 'required',
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
            'formula.integer' => 'O campo "Fórmula" deve ser um inteiro.',
            'formula.filled' => 'O campo "Fórmula" deve ser preenchido.',
            'formula.required' => 'O campo "Fórmula" deve ser informado.',

            'exercicio.integer' => 'O campo "Exercício" deve ser um inteiro.',
            'exercicio.filled' => 'O campo "Exercício" deve ser preenchido.',
            'exercicio.required' => 'O campo "Exercício" deve ser informado.',

            'anos.required' => 'Deve ser informado uma coleção com os anos a serem recalculados.',
            'detalhamentoiniciativas.required' => 'Deve ser informado uma coleção com os ids das iniciativas.',
        ];
    }
}
