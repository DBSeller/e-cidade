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

class SalvarDetalhamentoDespesaRequest extends FormRequest
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
            'pl20_codigo' => 'integer',
            'pl20_instituicao' => 'required|integer|filled',
            'pl20_orcorgao' => 'required|integer|filled',
            'pl20_orcunidade' => 'required|integer|filled',
            'pl20_orcfuncao' => 'required|integer|filled',
            'pl20_orcsubfuncao' => 'required|integer|filled',
            'pl20_orcelemento' => 'required|integer|filled',
            'pl20_recurso' => 'required|integer|filled',
            'pl20_subtitulo' => 'required|integer|filled',
            'pl20_concarpeculiar' => 'required|string|filled',
            'pl20_esferaorcamentaria' => 'required|integer|filled',
            'pl20_iniciativaprojativ' => 'required|integer|filled',
            'pl20_anoorcamento' => 'required|integer|filled',
            'pl20_valorbase' => 'numeric|nullable',
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
            'pl20_codigo.integer' => 'Código se informado deve ser um inteiro.',

            'pl20_instituicao.required' => 'O campo "Código da Instituição" deve ser informado.',
            'pl20_instituicao.integer' => 'Código da Instituição se informado deve ser um inteiro.',
            'pl20_instituicao.filled' => 'O campo "Código da Instituição" deve ser preenchido.',

            'pl20_orcorgao.required' => 'O campo "Código do Órgão" deve ser informado.',
            'pl20_orcorgao.integer' => 'Código do Órgão se informado deve ser um inteiro.',
            'pl20_orcorgao.filled' => 'O campo "Código do Órgão" deve ser preenchido.',

            'pl20_orcunidade.required' => 'O campo "Código da unidade" deve ser informado.',
            'pl20_orcunidade.integer' => 'Código da unidade se informado deve ser um inteiro.',
            'pl20_orcunidade.filled' => 'O campo "Código da unidade" deve ser preenchido.',

            'pl20_orcfuncao.required' => 'O campo "Código da Função" deve ser informado.',
            'pl20_orcfuncao.integer' => 'Código da Função se informado deve ser um inteiro.',
            'pl20_orcfuncao.filled' => 'O campo "Código da Função" deve ser preenchido.',

            'pl20_orcsubfuncao.required' => 'O campo "Código da Subfunção" deve ser informado.',
            'pl20_orcsubfuncao.integer' => 'Código da Subfunção se informado deve ser um inteiro.',
            'pl20_orcsubfuncao.filled' => 'O campo "Código da Subfunção" deve ser preenchido.',

            'pl20_orcelemento.required' => 'O campo "Código da Natureza de Despesa" deve ser informado.',
            'pl20_orcelemento.integer' => 'Código da Natureza de Despesa se informado deve ser um inteiro.',
            'pl20_orcelemento.filled' => 'O campo "Código da Natureza de Despesa" deve ser preenchido.',

            'pl20_recurso.required' => 'O campo "Código do Recurso" deve ser informado.',
            'pl20_recurso.integer' => 'Código do Recurso se informado deve ser um inteiro.',
            'pl20_recurso.filled' => 'O campo "Código do Recurso" deve ser preenchido.',

            'pl20_subtitulo.required' => 'O campo "Código do Subtítulo" deve ser informado.',
            'pl20_subtitulo.integer' => 'Código do Subtítulo se informado deve ser um inteiro.',
            'pl20_subtitulo.filled' => 'O campo "Código do Subtítulo" deve ser preenchido.',

            'pl20_concarpeculiar.required' => 'O campo "Caracteristica Peculiar" deve ser informado.',
            'pl20_concarpeculiar.string' => 'Caracteristica Peculiar se informado deve ser um inteiro.',
            'pl20_concarpeculiar.filled' => 'O campo "Caracteristica Peculiar" deve ser preenchido.',

            'pl20_esferaorcamentaria.required' => 'O campo "Esfera Orçamentária" deve ser informado.',
            'pl20_esferaorcamentaria.integer' => 'Esfera Orçamentária se informado deve ser um inteiro.',
            'pl20_esferaorcamentaria.filled' => 'O campo "Esfera Orçamentária" deve ser preenchido.',

            'pl20_iniciativaprojativ.required' => 'O campo "Código da Iniciativa" deve ser informado.',
            'pl20_iniciativaprojativ.integer' => 'Código da Iniciativa se informado deve ser um inteiro.',
            'pl20_iniciativaprojativ.filled' => 'O campo "Código da Iniciativa" deve ser preenchido.',

            'pl20_anoorcamento.required' => 'O campo "Exercício do Orçamento" deve ser informado.',
            'pl20_anoorcamento.integer' => 'Exercício do Orçamento se informado deve ser um inteiro.',
            'pl20_anoorcamento.filled' => 'O campo "Exercício do Orçamento" deve ser preenchido.',

            'valores.required' => 'Deve ser informado uma coleção de valores.',

            'pl20_valorbase.numeric' => 'O campo "Valor Base" se informado deve ser um valor.',
        ];
    }
}
