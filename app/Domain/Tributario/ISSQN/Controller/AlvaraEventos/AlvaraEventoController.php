<?php

namespace App\Domain\Tributario\ISSQN\Controller\AlvaraEventos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\AlvaraEvento;
use App\Domain\Tributario\ISSQN\Services\AlvaraEventos\AlvaraEventoService;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\AlvaraEventoRepository;
use App\Domain\Tributario\ISSQN\Requests\AlvaraEventos\AlvaraEvento\Store as StoreRequest;
use App\Domain\Tributario\ISSQN\Requests\AlvaraEventos\AlvaraEvento\Update as UpdateRequest;

class AlvaraEventoController extends Controller
{
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(
        AlvaraEventoRepository $alvaraEventoRepository,
        AlvaraEventoService $alvaraEventoService
    ) {
        $this->repository = $alvaraEventoRepository;
        $this->service = $alvaraEventoService;
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
        $alvaraEvento = (object)$request->all();
        $model = $this->service->salvarAlvaraEvento($alvaraEvento);

        return new DBJsonResponse($model, 'Alvara Liberado com sucesso.', 200);
    }

    /**
     * Salva um novo recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate($request, [
            'q170_codigo' => 'required|filled|integer'
        ]);

        return new DBJsonResponse($this->repository->find($request->q170_codigo));
    }

    /**
     * Salva um novo recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $alvaraEvento = (object)$request->all();
        $model = $this->service->salvarAlvaraEvento($alvaraEvento);

        return new DBJsonResponse($model, 'Alvara Liberado com sucesso.', 200);
    }

    /**
     * Salva um novo recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'q170_codigo' => 'required|filled|integer|exists:alvaraevento'
        ]);
        $this->repository->delete($request->q170_codigo);

        return new DBJsonResponse([], 'Alvará excluido com sucesso.', 200);
    }

    /**
     * Busca um recurso
     *
     * @return \Illuminate\Http\Response
     */
    public function getAlvaraEvento(Request $request)
    {
        $this->validate($request, [
            'q170_codigo' => 'required|filled|integer'
        ]);

        return new DBJsonResponse($this->service->getAlvaraEvento($request->q170_codigo));
    }
}
