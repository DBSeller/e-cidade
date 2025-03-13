<?php

namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Services\ConsultaLancamentoPcaspService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class ConsultaLancamentoPcaspController extends Controller
{
    /**
     * @param Request
     * @return DBJsonResponse
     * @throws Excepetion
     */
    public function getValoresPorDocumento(Request $request)
    {
        $service = new ConsultaLancamentoPcaspService();
        $service->setPeriodo($request->get('dataInicial'), $request->get('dataFinal'))
            ->setContas($request->get('contas'))->setFiltroDocumentos($request->get('filtroDocumentos'));
        $documentosEncontrados = $service->getValoresPorDocumento();
        if (empty($documentosEncontrados)) {
            throw new Exception('Não foram encontrados registros dentro do período informado!');
        }
        return new DBJsonResponse($documentosEncontrados);
    }

    /**
     * @param Request
     * @return DBJsonResponse
     */
    public function getValoresPorRecurso(Request $request)
    {
        $service = new ConsultaLancamentoPcaspService();
        $service->setPeriodo($request->get('dataInicial'), $request->get('dataFinal'))
            ->setContas($request->get('contas'))->setFiltroDocumentos($request->get('filtroDocumentos'));
        $recursosEncontrados = $service->getValoresPorRecurso($request->get('documentos'));
        return new DBJsonResponse($recursosEncontrados);
    }

    /**
     * @param Request
     * @return DBJsonResponse
     */
    public function getInfoLancamentos(Request $request)
    {
        $service = new ConsultaLancamentoPcaspService();
        $service->setPeriodo($request->get('dataInicial'), $request->get('dataFinal'))
            ->setFiltroDocumentos($request->get('filtroDocumentos'));
        $lancamentosEncontrados = $service->getInfoLancamentos($request->get('conta'), $request->get('documento'));
        return new DBJsonResponse(($lancamentosEncontrados));
    }
}
