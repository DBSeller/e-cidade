<?php

namespace App\Domain\Tributario\ISSQN\Controller\Veiculos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\ISSQN\Model\Veiculos\CondutorAuxiliar;
use App\Domain\Tributario\ISSQN\Services\Veiculos\CondutorAuxiliarService;
use App\Domain\Tributario\ISSQN\Repository\Veiculos\CondutorAuxiliarRepository;
use App\Domain\Tributario\ISSQN\Requests\Veiculos\CondutorAuxiliar\Store as StoreRequest;
use App\Domain\Tributario\ISSQN\Requests\Veiculos\CondutorAuxiliar\Update as UpdateRequest;

class CondutorAuxiliarController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(
        CondutorAuxiliarRepository $condutorAuxiliarRepository,
        CondutorAuxiliarService $condutorAuxiliarService
    ) {
        $this->repository = $condutorAuxiliarRepository;
        $this->service = $condutorAuxiliarService;
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
        $model = $this->service->salvarCondutorAuxiliar($ordemServico);

        return new DBJsonResponse($model, 'Condutor auxiliar salvo com sucesso.', 200);
    }

    /**
     * Busca um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'q173_sequencial' => 'required|filled|integer'
        ]);

        return new DBJsonResponse($this->repository->find($request->q173_sequencial));
    }

    /**
     * Salva um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $ordemServico = (object)$request->all();
        $model = $this->service->salvarCondutorAuxiliar($ordemServico);

        return new DBJsonResponse($model, 'Condutor auxiliar salvo com sucesso.', 200);
    }

    /**
     * Deleta um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'q173_sequencial' => 'required|filled|integer|exists:issveiculocondutorauxiliar'
        ]);
        $this->repository->delete($request->q173_sequencial);

        return new DBJsonResponse([], 'Condutor auxiliar excluido com sucesso.', 200);
    }
}
