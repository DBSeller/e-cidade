<?php

namespace App\Domain\Tributario\ISSQN\Controller\Veiculos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\ISSQN\Model\Veiculos\Veiculo;
use App\Domain\Tributario\ISSQN\Services\Veiculos\VeiculoService;
use App\Domain\Tributario\ISSQN\Repository\Veiculos\VeiculoRepository;
use App\Domain\Tributario\ISSQN\Requests\Veiculos\Veiculo\Store as StoreRequest;
use App\Domain\Tributario\ISSQN\Requests\Veiculos\Veiculo\Update as UpdateRequest;

class VeiculoController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(
        VeiculoRepository $veiculoRepository,
        VeiculoService $veiculoService
    ) {
        $this->repository = $veiculoRepository;
        $this->service = $veiculoService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new DBJsonResponse($this->repository->findAll());
    }

    /**
     * Salva um novo recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $ordemServico = (object)$request->all();
        $model = $this->service->salvarVeiculo($ordemServico);

        return new DBJsonResponse($model, 'Veículo salvo com sucesso.', 200);
    }

    /**
     * Busca um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'q172_sequencial' => 'required|filled|integer'
        ]);

        return new DBJsonResponse($this->repository->find($request->q172_sequencial));
    }


    /**
     * Salva um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $ordemServico = (object)$request->all();
        $model = $this->service->salvarVeiculo($ordemServico);

        return new DBJsonResponse($model, 'Veículo salvo com sucesso.', 200);
    }

    /**
     * Deleta um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'q172_sequencial' => 'required|filled|integer|exists:issveiculo'
        ]);
        $this->repository->delete($request->q172_sequencial);

        return new DBJsonResponse([], 'Veículo excluido com sucesso.', 200);
    }

    public function getVeiculo(Request $request)
    {
        $this->validate($request, [
            'q172_sequencial' => 'required|filled|integer'
        ]);
        
        return new DBJsonResponse($this->service->getVeiculo($request->q172_sequencial));
    }

    /**
     * Função que remove uma inscrição de veículo e seus condutores auxiliares
     *
     * @return \Illuminate\Http\Response
     */
    public function desprocessar(Request $request)
    {
        $this->validate($request, [
            'q172_sequencial' => 'required|filled|integer|exists:issveiculo'
        ]);

        $this->service->desprocessarInscricaoVeiculo($request->q172_sequencial);
        return new DBJsonResponse([], 'Inscrição de veículo excluída com sucesso.', 200);
    }
}
