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

namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\Financeiro\Planejamento\Services\Relatorios\RelatorioPlanejamentoRclService;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Requests\Relatorios\AnexosRequest;

class RelatoriosPlanejamentoRclController extends Controller
{
    /**
     * @throws \Exception
     */
    public function previsaoRclOutrosAnexos(AnexosRequest $request)
    {
        $planejamento = (new Planejamento())
            ->where('pl2_codigo', $request->get('planejamento_id'))->first();
        $request->merge(['tipo_planejamento' => $planejamento->pl2_tipo ? $planejamento->pl2_tipo : '']);
        $service = new RelatorioPlanejamentoRclService($request->all());
        $files = $service->emitir();
        return new DBJsonResponse($files, 'PREVISÃO DA RECEITA CORRENTE LÍQUIDA - '.$planejamento->pl2_titulo);
    }
}
