<?php


namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Services\Relatorios\BalanceteReceitaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BalanceteReceitaController extends Controller
{
    public function emitirPorComplemento(Request $request)
    {
        $service = new BalanceteReceitaService();
        $service->setFiltrosRequest($request->all());
        $files = $service->emitir();
        return new DBJsonResponse($files, 'Emissão do balancete da receita.');
    }
}
