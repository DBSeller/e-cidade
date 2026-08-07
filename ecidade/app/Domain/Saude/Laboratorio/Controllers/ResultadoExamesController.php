<?php

namespace App\Domain\Saude\Laboratorio\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Laboratorio\Services\ResultadoExameService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultadoExamesController extends Controller
{
    /**
     * @param integer $idRequisicaoExame
     * @param Request $request
     * @param ResultadoExameService $service
     * @return DBJsonResponse
     * @throws \Exception
     */
    public function get($idRequisicaoExame, Request $request, ResultadoExameService $service)
    {
        return new DBJsonResponse($service->getResultado($idRequisicaoExame, $request->header('cgs')));
    }
}
