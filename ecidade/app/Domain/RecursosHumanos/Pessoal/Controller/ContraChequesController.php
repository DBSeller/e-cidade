<?php

namespace App\Domain\RecursosHumanos\Pessoal\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Services\ContraChequeService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class ContraChequesController extends Controller
{
    /**
     * @param Request $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function processarEmissao(Request $request)
    {
        validaRequest(
            $request->all(),
            [
                'DB_instit' => ['required', 'integer'],
                'ano' => ['required', 'integer'],
                'mes' => ['required', 'integer']
            ],
            [
                'DB_instit.required' => 'Instituição não informada'
            ]
        );

        $contraChequeService = new ContraChequeService();
        $contraChequeService->gerarContraChequePdf(
            $request->get('ano'),
            $request->get('mes'),
            db_getsession('DB_instit')
        );

        return new DBJsonResponse([], 'Emissão de contra cheques gerada com sucesso.');
    }
}
