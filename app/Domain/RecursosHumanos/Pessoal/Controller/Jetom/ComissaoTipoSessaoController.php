<?php

namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoTipoSessao;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoTipoSessao\Update;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoTipoSessao\Sequencial;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoTipoSessao\Store;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class ComissaoTipoSessaoController extends Controller
{
    /**
     * @param Request $request
     * @return DBJsonResponse
     */
    public function index(Request $request)
    {
        if (!empty($request->comissao)) {
            return new DBJsonResponse(ComissaoTipoSessao::buscaTipoSessaoPorComissao($request->comissao));
        } else {
            return new DBJsonResponse(ComissaoTipoSessao::all());
        }
    }

    /**
     * @param Store $request
     * @return DBJsonResponse
     */
    public function store(Store $request)
    {
        try {
            $comissaoTipoSessao = new ComissaoTipoSessao();
            $comissaoTipoSessao->setComissao($request->comissao);
            $comissaoTipoSessao->setTipoSessao($request->tiposessao);
            $comissaoTipoSessao->setQuantidade($request->quantidade);

            if ($comissaoTipoSessao->callSave()) {
                return new DBJsonResponse(
                    ["id" => $comissaoTipoSessao->getSequencial()],
                    "Tipo de Sessão cadastrada com sucesso na comissão.",
                    200
                );
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao cadastrar o tipo de sessão na comissão.",
                400
            );
        }
    }

    /**
     * @param Sequencial $request
     * @param ComissaoTipoSessao $comissaoTipoSessao
     * @return DBJsonResponse
     */
    public function show(Sequencial $request, ComissaoTipoSessao $comissaoTipoSessao)
    {
        try {
            if ($comissaoTipoSessao->find($request->id)) {
                return new DBJsonResponse($comissaoTipoSessao->find($request->id), "Encontrado com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao buscar as informações do tipo de sessão da comissão.",
                400
            );
        }
    }

    /**
     * @param Update $request
     * @param ComissaoTipoSessao $comissaoTipoSessao
     * @return DBJsonResponse
     */
    public function update(Update $request, ComissaoTipoSessao $comissaoTipoSessao)
    {
        try {
            $comissaoTipoSessao->setComissao($request->comissao);
            $comissaoTipoSessao->setTipoSessao($request->tiposessao);
            $comissaoTipoSessao->setQuantidade($request->quantidade);

            if ($comissaoTipoSessao->callUpdate($request->id)) {
                return new DBJsonResponse([], "Tipo de sessão da comissão atualizado com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao atualizar o tipo de sessão da comissão.",
                400
            );
        }
    }

    /**
     * @param Sequencial $request
     * @param ComissaoTipoSessao $comissaoTipoSessao
     * @return DBJsonResponse
     */
    public function destroy(Sequencial $request, ComissaoTipoSessao $comissaoTipoSessao)
    {
        try {
            if ($comissaoTipoSessao->destroy($request->id)) {
                return new DBJsonResponse([], "Tipo de sessão da comissão excluido com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao excluir o tipo de sessão da comissão.",
                400
            );
        }
    }
}
