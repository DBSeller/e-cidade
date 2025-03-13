<?php

namespace App\Domain\Saude\Farmacia\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Farmacia\Services\DemandaReprimidaService;
use App\Domain\Saude\Farmacia\Requests\SalvarDemandaReprimidaRequest;
use App\Domain\Saude\Farmacia\Requests\RelatorioDemandaReprimidaRequest;
use DateTime;

class DemandaReprimidaController extends Controller
{
    /**
     * @var DemandaReprimidaService
     */
    private $service;

    public function __construct(DemandaReprimidaService $service)
    {
        $this->service = $service;
    }

    public function salvar(SalvarDemandaReprimidaRequest $request)
    {
        $this->service->salvar($request);

        return new DBJsonResponse([], 'Demanda salva com sucesso!');
    }

    public function apagar(Request $request)
    {
        $rules = [
            'id' => 'required|integer'
        ];
        validaRequest($request->all(), $rules);

        $this->service->getRepository()->delete($request->id);

        return new DBJsonResponse([], 'Demanda apagada com sucesso!');
    }

    public function getByPaciente($cgs)
    {
        return new DBJsonResponse($this->service->getByPaciente($cgs));
    }

    public function relatorio(RelatorioDemandaReprimidaRequest $request)
    {
        $dados = $this->service->buscar((object)$request->all());
        if ($dados->isEmpty()) {
            throw new \Exception('Não foram encontrados registros com os filtros informados.');
        }

        $pdf = $this->service->gerarRelatorio($dados)
            ->setPeriodo(new DateTime($request->periodoInicial), new DateTime($request->periodoFinal))
            ->setDepartamentos(array_map('utf8_decode', $request->get('txtDepartamentos', ['TODOS'])));

        return new DBJsonResponse(
            $pdf->emitir($request->ordem, $request->somenteTotal, $request->exibeObservacao),
            'Emitindo relatório!'
        );
    }
}
