<?php


namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Requests\Procedimento\AtualizacaoFonteRecursoRequest;
use App\Domain\Financeiro\Contabilidade\Requests\Procedimento\ManutencaoFonteRecursoDespesaRequest;
use App\Domain\Financeiro\Contabilidade\Requests\Procedimento\ManutencaoFonteRecursoReceitaRequest;
use App\Domain\Financeiro\Contabilidade\Services\ManutencaoFonteRecursoDespesaService;
use App\Domain\Financeiro\Contabilidade\Services\ManutencaoFonteRecursoReceitaService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

/**
 * Class ManutencaoFonteRecurso
 * @package App\Domain\Financeiro\Contabilidade\Controllers
 */
class ManutencaoFonteRecursoController extends Controller
{
    /**
     * @param ManutencaoFonteRecursoDespesaRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function lancamentosDespesa(ManutencaoFonteRecursoDespesaRequest $request)
    {
        $service = new ManutencaoFonteRecursoDespesaService();
        $service->setRequest($request);
        $empenhos = $service->buscarEmpenhos();

        $dados = [
            "recursos" => $service->buscarRecursos()->toArray(),
            "itens" => $empenhos
        ];

        return new DBJsonResponse($dados);
    }

    /**
     * @param ManutencaoFonteRecursoReceitaService $service
     * @return DBJsonResponse
     * @throws Exception,
     */
    public function lancamentosReceita(ManutencaoFonteRecursoReceitaRequest $request)
    {
        $service = new ManutencaoFonteRecursoReceitaService();
        $service->setRequest($request);
        $empenhos = $service->buscarLancamentos();

        $dados = [
            "recursos" => $service->buscarRecursos()->toArray(),
            "itens" => $empenhos
        ];

        return new DBJsonResponse($dados);
    }

    public function atualizarComplemento(AtualizacaoFonteRecursoRequest $request)
    {
        try {
            if (!$request->has('DB_anousu')) {
                throw new Exception("", 406);
            }

            if ($request->get('origem') === 'despesa') {
                $sevice = new ManutencaoFonteRecursoDespesaService();
            } else {
                $sevice = new ManutencaoFonteRecursoReceitaService();
            }
            $sevice->setRequest($request);
            $sevice->atualizaRecursos();
        } catch (Exception $e) {
            $codigo = $e->getCode() != 0 ? $e->getCode() : 406;
            throw new Exception($e->getMessage(), $codigo);
        }

        return new DBJsonResponse([], "Recursos atualizado com sucesso.");
    }
}
