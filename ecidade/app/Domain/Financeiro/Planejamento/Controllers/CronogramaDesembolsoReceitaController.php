<?php

namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Manutencao\Cronograma\CronogramaRequest;
use App\Domain\Financeiro\Planejamento\Services\CronogramaDesembolsoReceitaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CronogramaDesembolsoReceitaController extends Controller
{
    /**
     * @var CronogramaDesembolsoReceitaService
     */
    private $service;

    public function __construct(CronogramaDesembolsoReceitaService $service)
    {
        $this->service = $service;
    }

    public function buscar(CronogramaRequest $request)
    {
        $receitas = array_values($this->service->getPorRequest($request));

        return new DBJsonResponse($receitas, 'Estimativa do cronograma.');
    }

    public function salvarMetas(Request $request)
    {
        $metasArrecadacao = str_replace('\"', '"', $request->get('metasArrecacadacao'));
        $metasArrecadacao = \JSON::create()->parse($metasArrecadacao);

        if (!is_array($metasArrecadacao) || empty($metasArrecadacao)) {
            throw new \Exception('Revise os dados enviados.', 403);
        }
        $this->service->salvarMetasArrecadacao($metasArrecadacao);
        return new DBJsonResponse([], 'Metas de arrecadação salva.');
    }

    public function recalcular(Request $request)
    {
        $this->service->recalcular($request->all());
        return new DBJsonResponse([], 'Metas de arrecadação recalculada.');
    }
}
