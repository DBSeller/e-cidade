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
use App\Domain\Financeiro\Planejamento\Services\RelatorioProjecaoDespesaAgrupadaService;
use App\Domain\Financeiro\Planejamento\Services\RelatorioProjecaoDespesaAgrupadaSinteticoService;
use App\Domain\Financeiro\Planejamento\Services\Relatorios\RelatorioProjecaoDespesaConferenciaRecrusoService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class RelatorioProjecaoDespesaAgrupadaController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class RelatorioProjecaoDespesaController extends Controller
{
    public function agrupadoPor(Request $request)
    {
        $service = new RelatorioProjecaoDespesaAgrupadaService($request->all());
        $files = $service->emitirPdf();

        return new DBJsonResponse($files, 'Projeção da despesa.');
    }

    public function agrupadoSintetico(Request $request)
    {
        $service = new RelatorioProjecaoDespesaAgrupadaSinteticoService($request->all());
        $files = $service->emitirPdf();

        return new DBJsonResponse($files, 'Projeção da despesa.');
    }

    public function conferenciaRecurso(Request $request)
    {
        $service = new RelatorioProjecaoDespesaConferenciaRecrusoService($request->all());
        $files = $service->emitirPdf();

        return new DBJsonResponse($files, 'Projeção da despesa.');
    }
}
