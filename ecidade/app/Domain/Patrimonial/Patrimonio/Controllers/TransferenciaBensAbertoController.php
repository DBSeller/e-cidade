<?php

namespace App\Domain\Patrimonial\Patrimonio\Controllers;

use Exception;
use App\Domain\Patrimonial\Patrimonio\Models\BensTransf;
use App\Domain\Patrimonial\Patrimonio\Services\BensTransferenciaAbertoService;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @package App\Domain\Patrimonial\Patrimonio\Controllers
 */
class TransferenciaBensAbertoController extends Controller
{
    public function relatorioTransferenciaBensAberto(Request $request)
    {
        $service = new BensTransferenciaAbertoService();
        $relatorio = $service->gerarRelatorioBensTrasferenciaAbertos((object)$request->all());
        return new DBJsonResponse($relatorio->emitir(), 'Emitindo relatório');
    }
}
