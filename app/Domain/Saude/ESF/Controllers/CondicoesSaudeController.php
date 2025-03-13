<?php

namespace App\Domain\Saude\ESF\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\ESF\Requests\RelatorioCondicoesSaudeRequest;
use App\Domain\Saude\ESF\Services\CondicoesSaudeService;
use App\Http\Controllers\Controller;
use Exception;

class CondicoesSaudeController extends Controller
{
    /**
     * @param RelatorioCondicoesSaudeRequest $request
     * @param CondicoesSaudeService $service
     * @return DBJsonResponse
     * @throws Exception
     */
    public function relatorio(RelatorioCondicoesSaudeRequest $request, CondicoesSaudeService $service)
    {
        $pdf = $service->gerarRelatorio((object)$request->all());

        return new DBJsonResponse($pdf->emitir(), 'Emitindo PDF.');
    }
}
