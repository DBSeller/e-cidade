<?php

namespace App\Domain\Tributario\ISSQN\Controller\AlvaraEventos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\OrdemServico;
use App\Domain\Tributario\ISSQN\Services\AlvaraEventos\OrdemServicoService;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\OrdemServicoRepository;
use App\Domain\Tributario\ISSQN\Requests\AlvaraEventos\OrdemServico\Store as StoreRequest;
use App\Domain\Tributario\ISSQN\Requests\AlvaraEventos\OrdemServico\Update as UpdateRequest;

class OrdemServicoController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(
        OrdemServicoRepository $ordemServicoRepository,
        ordemServicoService $ordemServicoService
    ) {
        $this->repository = $ordemServicoRepository;
        $this->service = $ordemServicoService;
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
        $model = $this->service->salvarOrdemServico($ordemServico);

        return new DBJsonResponse($model, 'Ordem de serviço salva com sucesso.', 200);
    }

    /**
     * Busca um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'q168_codigo' => 'required|filled|integer'
        ]);

        return new DBJsonResponse($this->repository->find($request->q168_codigo));
    }

    /**
     * Busca um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrdemServico(Request $request)
    {
        $this->validate($request, [
            'q168_codigo' => 'required|filled|integer'
        ]);

        return new DBJsonResponse($this->service->getOrdemServico($request->q168_codigo));
    }

    /**
     * Salva um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $ordemServico = (object)$request->all();
        $model = $this->service->salvarOrdemServico($ordemServico);

        return new DBJsonResponse($model, 'Ordem de serviço salva com sucesso.', 200);
    }

    /**
     * Deleta um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'q168_codigo' => 'required|filled|integer|exists:ordemservico'
        ]);
        $this->repository->delete($request->q168_codigo);

        return new DBJsonResponse([], 'Ordem de serviço excluida com sucesso.', 200);
    }

    /**
     * Processa uma ordem de servico
     *
     * @return \Illuminate\Http\Response
     */
    public function processar(StoreRequest $request)
    {
        $dadosOrdemServico = (object)$request->all();
        $model = $this->service->processarOrdemServico($dadosOrdemServico);

        return new DBJsonResponse($model, 'Ordem de serviço salva com sucesso.', 200);
    }

    /**
     * Remove uma ordem de servico e seus fiscais
     *
     * @return \Illuminate\Http\Response
     */
    public function desprocessar(Request $request)
    {
        $this->validate($request, [
            'q168_codigo' => 'required|filled|integer|exists:ordemservico'
        ]);

        $this->service->desprocessarOrdemServico($request->q168_codigo);

        return new DBJsonResponse([], 'Ordem de serviço excluida com sucesso.', 200);
    }
}
