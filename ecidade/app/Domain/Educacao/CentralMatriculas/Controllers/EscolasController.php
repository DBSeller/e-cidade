<?php

namespace App\Domain\Educacao\CentralMatriculas\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Educacao\CentralMatriculas\Requests\EscolasDisponiveisRequest;
use App\Domain\Educacao\CentralMatriculas\Services\EscolasService;
use App\Http\Controllers\Controller;
use Exception;

class EscolasController extends Controller
{
    /**
     * @param EscolasDisponiveisRequest $escolasDisponiveisRequest
     * @return DBJsonResponse
     * @throws Exception
     */
    public function disponiveisPreMatricula(EscolasDisponiveisRequest $escolasDisponiveisRequest)
    {
        $etapa = $escolasDisponiveisRequest->get("etapa");
        $fase = $escolasDisponiveisRequest->get("fase");

        $escolasService = new EscolasService();
        $escolas = $escolasService->buscarEscolasDisponiveis($etapa, $fase);

        return new DBJsonResponse($escolas->toArray());
    }
}
