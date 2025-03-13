<?php

namespace App\Domain\Patrimonial\Protocolo\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Protocolo\Requests\RuaCepRequest;
use App\Domain\Patrimonial\Protocolo\Services\ProtocoloService;
use App\Http\Controllers\Controller;

class ProtocoloController extends Controller
{
    private $protocoloService;

    public function __construct(ProtocoloService $protocoloService)
    {
        $this->protocoloService = $protocoloService;
    }

    public function getRuaByCep(RuaCepRequest $request)
    {
        try {
            $aCgm = $this->protocoloService->getRuaByCep($request->cep);

            return new DBJsonResponse($aCgm);
        } catch (\Exception $exception) {
            return new DBJsonResponse(
                null,
                $exception->getMessage(),
                ($exception->getCode() ? $exception->getCode() : 400)
            );
        }
    }

    public function getRuaByCepMunicipio(RuaCepRequest $request)
    {
        try {
            $aCgm = $this->protocoloService->getRuaByCepMunicipio($request->cep);

            return new DBJsonResponse($aCgm);
        } catch (\Exception $exception) {
            return new DBJsonResponse(
                null,
                $exception->getMessage(),
                ($exception->getCode() ? $exception->getCode() : 400)
            );
        }
    }
}
