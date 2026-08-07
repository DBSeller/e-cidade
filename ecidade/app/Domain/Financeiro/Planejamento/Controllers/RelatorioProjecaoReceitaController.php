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
use App\Domain\Financeiro\Planejamento\Services\RelatorioProjecaoReceitaService;
use App\Domain\Financeiro\Planejamento\Services\Relatorios\ConferenciaRecursoProjecaoReceitaService;
use App\Domain\Financeiro\Planejamento\Services\Relatorios\ResumoProjecaoReceitaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class RelatorioProjecaoReceitaController
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class RelatorioProjecaoReceitaController extends Controller
{
    public function emitir(Request $request)
    {
        $service = new RelatorioProjecaoReceitaService($request->all());
        $files = $service->emitir();

        return new DBJsonResponse($files, 'Demonstrativo das Projeções da Receita');
    }

    public function emitirResumo(Request $request)
    {
        $service = new ResumoProjecaoReceitaService($request->all());
        $files = $service->emitirPdf();

        return new DBJsonResponse($files, 'Resumo da Projeção da Receita');
    }

    public function emitirConferenciaRecurso(Request $request)
    {
        $service = new ConferenciaRecursoProjecaoReceitaService($request->all());
        $files = $service->emitir();

        return new DBJsonResponse($files, 'Demonstrativo das Projeções da Receita');
    }
}
