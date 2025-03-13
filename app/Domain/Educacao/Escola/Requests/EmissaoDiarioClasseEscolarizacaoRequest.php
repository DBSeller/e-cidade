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

namespace App\Domain\Educacao\Escola\Requests;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class EmissaoDiarioClasseRegularRequest
 * @package App\Domain\Educacao\Escola\Requests
 */
class EmissaoDiarioClasseEscolarizacaoRequest extends FormRequest
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
            'turma' => 'integer',
            'tipo_turma' => 'required|integer|filled',
//            'rechumano_escola' => 'required|integer|filled',
            'colunas' => 'required',
            'modelo' => 'required',

            'etapa' => 'required|array',
            'periodo' => 'required|integer',
            'disciplinas' => 'required|array',
//            'regente' => 'required|boolean',
            'registroManual' => 'required|boolean',
            'exibirPontos' => 'required|boolean',
            'exibirDiasLetivos' => 'required|boolean',
            'exibirSituacaoAlunoDiario' => 'required|boolean',
            'apenasAlunosAtivos' => 'required|boolean',
            'exibirTrocaTurma' => 'required|boolean',
            'exibirAvaliacoes' => 'required|boolean',
            'exibirDataPeriodo' => 'required|boolean',
            'exibirTotalFaltas' => 'required|boolean',
            'exibirSexo' => 'required|boolean',
            'exibirIdade' => 'required|boolean',
            'exibirFaltasAbonadas' => 'required|boolean',
            'exibirCodigo' => 'required|boolean',
            'exibirNascimento' => 'required|boolean',
            'exibirResultadoAnterior' => 'required|boolean',
            'exibirParecer' => 'required|boolean',
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
            "turma.integer" => "Código da turma não informado.",
            "turma.filled" => "O código da turma informado está vazio.",
            "turma.integer" => "Código da turma deve ser um inteiro.",
            "tipo_turma.required" => "Tipo da turma não informado.",
            "tipo_turma.filled" => "Tipo da turma informado está vazio.",
            "tipo_turma.integer" => "Tipo da turma deve ser um inteiro.",
//            "rechumano_escola.required" => "Código do profissional não informado.",
//            "rechumano_escola.filled" => "O código do profissional informado está vazio.",
//            "rechumano_escola.integer" => "Código do profissional deve ser um inteiro.",
            "colunas.required" => "Campo Colunas deve ser informado.",
            'etapa.required' => 'Etapa não foi informada.',
            'etapa.array' => 'Etapa deve ser um array.',
            'periodo.required' => 'Período não foi informado.',
            'periodo.integer' => 'Período deve ser um interiro.',
            'disciplinas.required' => 'Disciplinas não foi informado.',
            'disciplinas.array' => 'Disciplinas deve ser um array.',
        ];
    }
}
