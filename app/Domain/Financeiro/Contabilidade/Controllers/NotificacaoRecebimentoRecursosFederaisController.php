<?php

namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Services\RelatorioNotificacaoRecebimentoRecursosService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ParameterException;

class NotificacaoRecebimentoRecursosFederaisController extends Controller
{
    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws ParameterException
     */
    public function processar(Request $request)
    {
        $service = new RelatorioNotificacaoRecebimentoRecursosService($request->all());
        $file = $service->emitirPdf();

        return new DBJsonResponse(['pdf' => $file]);
    }
}
