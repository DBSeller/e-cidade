<?php

namespace App\Domain\Saude\TFD\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use App\Domain\Saude\TFD\Models\AgendaSaida;
use App\Domain\Saude\TFD\Services\AgendaSaidaService;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\TFD\Repositories\AgendaSaidaRepository;
use App\Domain\Saude\TFD\Requests\RelatorioViagensPorMotoristaRequest;

/**
 * @package App\Domain\Saude\TFD\Controllers
 */
class AgendaSaidaController extends Controller
{
    public function relatorioViagensPorMotorista(RelatorioViagensPorMotoristaRequest $request)
    {
        $repository = new AgendaSaidaRepository(AgendaSaida::class);
        $viagens = $repository->getViagens(function ($query) use ($request) {
            $query->whereBetween('tf17_d_datasaida', [$request->periodoInicial, $request->periodoFinal]);
            if (count($request->motoristas) > 0) {
                $query->whereIn('tf18_i_motorista', $request->motoristas);
            }
            if ($request->has('destino')) {
                $query->where('tf18_i_destino', '=', $request->destino);
            }
        }, $request->ordem);

        if ($viagens->isEmpty()) {
            throw new Exception('Nenhum registro encontrado!');
        }

        $service = new AgendaSaidaService();
        $relatorio = $service->gerarRelatorioViagensPorMotorista($viagens, $request->tipo);

        return new DBJsonResponse($relatorio->emitir($request->ordem), 'Emitindo relatório');
    }
}
