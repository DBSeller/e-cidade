<?php

namespace App\Domain\Financeiro\Tesouraria\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Tesouraria\Services\RelatorioListaOperacoesService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RelatorioArquivoTefController extends Controller
{
    public function tef(Request $request)
    {
        $service = new RelatorioListaOperacoesService();

        $service->setPeriodo($request->get('dataInicial'), $request->get('dataFinal'));
        return new DBJsonResponse($service->emitir(), 'Relátório');
    }
}
