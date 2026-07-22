<?php

namespace App\Domain\Saude\Ambulatorial\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\Ambulatorial\Requests\SalvarProblemasPacienteRequest;
use App\Http\Controllers\Controller;
use App\Domain\Saude\Ambulatorial\Services\ProblemasPacienteService;
use Illuminate\Http\Request;

class ProblemasPacienteController extends Controller
{
    /**
     * @var ProblemasPacienteService
     */
    private $service;

    public function __construct(ProblemasPacienteService $service)
    {
        $this->service = $service;
    }
    
    public function salvar(SalvarProblemasPacienteRequest $request)
    {
        $this->service->salvarFromRequest($request);

        return new DBJsonResponse([], 'Problema/Condição salvo com sucesso!');
    }

    public function apagar(Request $request)
    {
        $regra = [
            'id' => 'required|integer'
        ];
        validaRequest($request->all(), $regra);

        $this->service->apagar($request->get('id'));

        return new DBJsonResponse([], 'Problema/Condição apagado com sucesso!');
    }

    public function getByPaciente($id)
    {
        $regra = [
            'id' => 'required|integer'
        ];
        validaRequest(['id' => $id], $regra);

        return new DBJsonResponse($this->service->getProblemasPaciente($id));
    }
}
