<?php

namespace App\Domain\Saude\Farmacia\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Saude\Farmacia\Services\MedicamentoService;

class MedicamentoController extends Controller
{
    /**
     * @var MedicamentoService
     */
    private $service;

    public function __construct(MedicamentoService $service)
    {
        $this->service = $service;
    }

    public function getEstoque(Request $request)
    {
        $rules = [
            'idMedicamento' => 'required|integer',
            'DB_coddepto' => 'required|integer'
        ];

        validaRequest($request->all(), $rules);

        return new DBJsonResponse($this->service->getEstoque($request->idMedicamento, $request->DB_coddepto));
    }
}
