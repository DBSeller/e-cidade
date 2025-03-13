<?php

namespace App\Domain\Saude\Laboratorio\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Laboratorio\Services\RequisicaoExameService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequisicaoExamesController extends Controller
{
    /**
     * @param integer $idRequisicao
     * @param Request $request
     * @param RequisicaoExameService $service
     * @return DBJsonResponse
     * @throws \Exception
     */
    public function get($idRequisicao, Request $request, RequisicaoExameService $service)
    {
        return new DBJsonResponse($service->getInfo($idRequisicao, $request->header('cgs')));
    }
}
