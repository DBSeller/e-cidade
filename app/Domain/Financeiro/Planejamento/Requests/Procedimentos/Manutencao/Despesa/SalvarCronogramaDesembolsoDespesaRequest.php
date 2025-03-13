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
class SalvarCronogramaDesembolsoDespesaRequest extends FormRequest
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
            'detalhamentoiniciativa_id' => 'required|integer|filled',
            'exercicio' => 'required|integer|filled',
            'janeiro' => 'required|numeric|filled',
            'fevereiro' => 'required|numeric|filled',
            'marco' => 'required|numeric|filled',
            'abril' => 'required|numeric|filled',
            'maio' => 'required|numeric|filled',
            'junho' => 'required|numeric|filled',
            'julho' => 'required|numeric|filled',
            'agosto' => 'required|numeric|filled',
            'setembro' => 'required|numeric|filled',
            'outubro' => 'required|numeric|filled',
            'novembro' => 'required|numeric|filled',
            'dezembro' => 'required|numeric|filled',

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

            'detalhamentoiniciativa_id.integer' => 'O campo "Detalhamento" deve ser um inteiro.',
            'detalhamentoiniciativa_id.filled' => 'O campo "Detalhamento" deve ser preenchido.',
            'detalhamentoiniciativa_id.required' => 'O campo "Detalhamento" deve ser informado.',

            'exercicio.integer' => 'O campo "Exercício" deve ser um inteiro.',
            'exercicio.filled' => 'O campo "Exercício" deve ser preenchido.',
            'exercicio.required' => 'O campo "Exercício" deve ser informado.',

            'janeiro.filled' => 'O valor de "Janeiro" deve ser preenchido.',
            'janeiro.required' => 'O valor de "Janeiro" deve ser informado.',
            'janeiro.numeric' => 'O valor de "Janeiro" se informado deve ser um numéric.',

            'fevereiro.filled' => 'O valor de "Fevereiro" deve ser preenchido.',
            'fevereiro.required' => 'O valor de "Fevereiro" deve ser informado.',
            'fevereiro.numeric' => 'O valor de "Fevereiro" se informado deve ser um numéric.',

            'marco.filled' => 'O valor de "Março" deve ser preenchido.',
            'marco.required' => 'O valor de "Março" deve ser informado.',
            'marco.numeric' => 'O valor de "Março" se informado deve ser um numéric.',

            'abril.filled' => 'O valor de "Abril" deve ser preenchido.',
            'abril.required' => 'O valor de "Abril" deve ser informado.',
            'abril.numeric' => 'O valor de "Abril" se informado deve ser um numéric.',

            'maio.filled' => 'O valor de "Maio" deve ser preenchido.',
            'maio.required' => 'O valor de "Maio" deve ser informado.',
            'maio.numeric' => 'O valor de "Maio" se informado deve ser um numéric.',

            'junho.filled' => 'O valor de "Junho" deve ser preenchido.',
            'junho.required' => 'O valor de "Junho" deve ser informado.',
            'junho.numeric' => 'O valor de "Junho" se informado deve ser um numéric.',

            'julho.filled' => 'O valor de "Julho" deve ser preenchido.',
            'julho.required' => 'O valor de "Julho" deve ser informado.',
            'julho.numeric' => 'O valor de "Julho" se informado deve ser um numéric.',

            'agosto.filled' => 'O valor de "Agosto" deve ser preenchido.',
            'agosto.required' => 'O valor de "Agosto" deve ser informado.',
            'agosto.numeric' => 'O valor de "Agosto" se informado deve ser um numéric.',

            'setembro.filled' => 'O valor de "Setembro" deve ser preenchido.',
            'setembro.required' => 'O valor de "Setembro" deve ser informado.',
            'setembro.numeric' => 'O valor de "Setembro" se informado deve ser um numéric.',

            'outubro.filled' => 'O valor de "Outubro" deve ser preenchido.',
            'outubro.required' => 'O valor de "Outubro" deve ser informado.',
            'outubro.numeric' => 'O valor de "Outubro" se informado deve ser um numéric.',

            'novembro.filled' => 'O valor de "Novembro" deve ser preenchido.',
            'novembro.required' => 'O valor de "Novembro" deve ser informado.',
            'novembro.numeric' => 'O valor de "Novembro" se informado deve ser um numéric.',

            'dezembro.filled' => 'O valor de "Dezembro" deve ser preenchido.',
            'dezembro.required' => 'O valor de "Dezembro" deve ser informado.',
            'dezembro.numeric' => 'O valor de "Dezembro" se informado deve ser um numéric.',
        ];
    }
}
