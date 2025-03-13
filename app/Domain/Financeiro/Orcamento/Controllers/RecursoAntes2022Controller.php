<?php


namespace App\Domain\Financeiro\Orcamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Orcamento\Models\Recurso;
use App\Domain\Financeiro\Orcamento\Requests\Cadastro\RecursoExcluirAntes2022Request;
use App\Domain\Financeiro\Orcamento\Requests\Cadastro\RecursoSalvarAntes2022Request;
use App\Domain\Financeiro\Orcamento\Services\RecursoAntes2022Service;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class RecursoController
 * @package App\Domain\Financeiro\Orcamento\Controllers
 */
class RecursoAntes2022Controller extends Controller
{
    protected $service;

    /**
     * RecursoController constructor.
     * @param RecursoAntes2022Service $service
     */
    public function __construct(RecursoAntes2022Service $service)
    {
        $this->service = $service;
    }

    public function get($data = null)
    {
        $recursos = Recurso::query()->when(!empty($data), function ($query) use ($data) {
            $query->whereRaw("(o15_datalimite is null or o15_datalimite >= ?)", [$data]);
        })->orderBy('o15_recurso')
            ->orderBy('o15_complemento')
            ->get()
            ->toArray();

        $recursos = array_values($recursos);
        return new DBJsonResponse($recursos, 'Lista de Recursos');
    }

    /**
     * @param RecursoSalvarAntes2022Request $recursoRequest
     * @return JsonResponse
     * @throws Exception
     */
    public function salvar(RecursoSalvarAntes2022Request $recursoRequest)
    {
        $this->service->salvarAntes2022($recursoRequest);
        return response()->json(
            [
                'error' => false,
                "message" => utf8_encode("Recurso salvo com sucesso.")
            ]
        );
    }

    /**
     * @param RecursoExcluirAntes2022Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function excluir(RecursoExcluirAntes2022Request $request)
    {
        $this->service->excluirAntes2022($request);
        return response()->json(
            [
                'error' => false,
                "message" => utf8_encode("Recurso excluído com sucesso.")
            ]
        );
    }
}
