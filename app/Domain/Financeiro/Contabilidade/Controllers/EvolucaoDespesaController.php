<?php


namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Requests\EvolucaoDespesaRequest;
use App\Domain\Financeiro\Contabilidade\Services\Relatorios\EvolucaoDespesaService;

class EvolucaoDespesaController extends Controller
{
    public function demonstrativoEvolucaoDespesa(EvolucaoDespesaRequest $request)
    {
        $service = new EvolucaoDespesaService();
        $service->setFiltrosRequest($request->all());
        $files = $service->emitir();
        return new DBJsonResponse($files, 'Demonstrativo da Evolução da Despesa.');
    }
}
