<?php

namespace App\Domain\Patrimonial\Licitacoes\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Licitacoes\Services\IntegracaoComprasBrExportService;
use App\Domain\Patrimonial\Licitacoes\Services\IntegracaoComprasBrImportService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

/**
 * Controller Class
 */
class IntegracaoComprasBrController extends Controller
{
   /**
    * Exportar arquivo do compras BR
    *
    * @param Request $request
    * @return Illuminate\Http\JsonResponse
    */
    public function import(Request $request)
    {
        $this->validate($request, [
            'importFile' => 'required|file|mimes:txt,csv',
            'licitacao' => 'required|numeric'
        ]);

        $licitacao = $request->licitacao;
        $service = new IntegracaoComprasBrImportService;

        try {
            $file = $request->file('importFile')->getRealPath();
            $service->importFilePregao($file, $licitacao);
            return response()->json(true);
        } catch (Exception $e) {
            return new DBJsonResponse('', $e->getMessage(), 500);
        }
    }

    /**
     * Exportar arquivo para compras BR
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $this->validate($request, [
            'licitacao' => 'required|numeric'
        ]);

        $licitacao = $request->licitacao;
        $service   = new IntegracaoComprasBrExportService;

        try {
            $file = $service->exportFilePregao($licitacao);
            return response()->json($file);
        } catch (Exception $e) {
            return new DBJsonResponse('', $e->getMessage(), 500);
        }
    }
}
