<?php

namespace App\Domain\Tributario\ITBI\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\ITBI\Services\EmissaoGuiaService;
use App\Http\Controllers\Controller;
use App\Domain\Tributario\ITBI\Requests\EmissaoGuiaRequest;

class EmissaoGuiaController extends Controller
{
    public function emitir(EmissaoGuiaRequest $request)
    {
        $emissaoGuiaService = new EmissaoGuiaService();
        $emissaoGuiaService->setNumeroGuia($request->numeroGuia);
        $emissaoGuiaService->verificaGuiaPaga();
        $emissaoGuiaService->setMostraArquivo(false);
        $emissaoGuiaService->emitir();

        return new DBJsonResponse([
            "arquivo" => $emissaoGuiaService->getArquivo()
        ]);
    }
}
