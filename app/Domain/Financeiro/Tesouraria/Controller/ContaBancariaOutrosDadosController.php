<?php

namespace App\Domain\Financeiro\Tesouraria\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Tesouraria\Requests\ContaBancariaOutrosDadosRequestAlterar;
use App\Domain\Financeiro\Tesouraria\Requests\ContaBancariaOutrosDadosRequestBuscar;
use App\Domain\Financeiro\Tesouraria\Services\ContaBancariaOutrosDadosService;
use App\Http\Controllers\Controller;

class ContaBancariaOutrosDadosController extends Controller
{
    public function buscar(ContaBancariaOutrosDadosRequestBuscar $request)
    {
        $service = new ContaBancariaOutrosDadosService();
        return new DBJsonResponse($service->buscar($request->all()), 'Busca feita com sucesso.');
    }
    public function alterar(ContaBancariaOutrosDadosRequestAlterar $request)
    {
        $service = new ContaBancariaOutrosDadosService();
        return new DBJsonResponse($service->alterar($request->all()), 'Alteração feita com sucesso.');
    }
}
