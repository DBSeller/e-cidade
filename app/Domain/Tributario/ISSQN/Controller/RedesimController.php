<?php

namespace App\Domain\Tributario\ISSQN\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Ouvidoria\Services\AtendimentoProcessoService;
use App\Domain\Tributario\Arrecadacao\Services\ComprovanteDesfazimentoTefService;
use App\Domain\Tributario\ISSQN\Services\Redesim\RelatorioInscricoesService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Tributario\ISSQN\Services\Redesim\RedesimService;
use Illuminate\Support\Facades\Log;

class RedesimController extends Controller
{
    private $redesimService;

    public function __construct(RedesimService $redesimService)
    {
        $this->redesimService = $redesimService;
    }

    public function incluirInscricao(Request $request, AtendimentoProcessoService $atendimentoProcessoService)
    {
        try {
            $codigoInscricao = $this->redesimService->incluirInscricaoBalcaoUnico(
                $atendimentoProcessoService,
                (object) $request->all()
            );

            return response()->json(["status" => "OK", "numeroInscricao" => $codigoInscricao, "arquivoPDF" => ""]);
        } catch (\Exception $exception) {
            Log::useDailyFiles(storage_path().'/logs/redesim/inclusaoInscricao/log.log');
            Log::error($exception);

            $aErrorMessage = [$exception->getMessage(), $exception->getFile(), $exception->getLine()];
            return response()->json(
                ["status" => "NOK", "descricao" => utf8_encode(implode(" - ", $aErrorMessage))],
                500
            );
        }
    }

    public function relatorioInscricoes(Request $request)
    {
        $service = new RelatorioInscricoesService();
        $service->setDataInicio($request->dataInicio);
        $service->setDataFim($request->dataFim);
        $service->setMostraArquivo(false);
        $service->setRetornaBase64(false);
        $service->gerar();

        return new DBJsonResponse([
            "arquivo" => $service->getArquivo()
        ]);
    }
}
