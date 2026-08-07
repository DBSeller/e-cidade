<?php

namespace App\Domain\Configuracao\Menu\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Configuracao\Menu\Requests\DuplicaPermissoesSaudeRequest;
use App\Domain\Configuracao\Menu\Services\DuplicaPermissoesSaudeService;
use App\Http\Controllers\Controller;

class PermissoesController extends Controller
{
    /**
     * @param DuplicaPermissoesSaudeRequest $request
     * @param DuplicaPermissoesSaudeService $service
     * @return DBJsonResponse
     * @throws \Exception
     */
    public function duplicarSaude(DuplicaPermissoesSaudeRequest $request, DuplicaPermissoesSaudeService $service)
    {
        $service->execute($request->anoOrigem, $request->anoDestino);

        return new DBJsonResponse([], 'Permissões duplicadas com sucesso.');
    }
}
