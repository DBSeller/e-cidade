<?php

namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Services\Relatorios\Conferencia\AtributosPlanoContasMscService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RelatorioConferenciaController extends Controller
{
    public function atributosPlanoContasMSC(Request $request)
    {
        $service = new AtributosPlanoContasMscService();
        $files = $service->setFiltros($request->all())
            ->emitir();

        return new DBJsonResponse($files, 'Emissão dos atributos Plano Contas MSC.');
    }
}
