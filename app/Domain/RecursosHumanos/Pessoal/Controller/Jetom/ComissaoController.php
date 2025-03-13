<?php
namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Helper\DateHelper;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Comissao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoFuncao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Funcao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Sessao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\TipoSessao;
use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Comissao\Sequencial;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Comissao\Store;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Comissao\Update;
use App\Domain\RecursosHumanos\Pessoal\Services\Jetom\ComissaoService;
use App\Http\Controllers\Controller;
use ECidade\Lib\Session\DefaultSession;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

class ComissaoController extends Controller
{

    /**
     * @param  ServerRequestInterface|null $request
     * @return DBJsonResponse
     */
    public function index(ServerRequestInterface $request = null)
    {
        $instituicao = ($request->getQueryParams()['instituicao']);
        if (!empty($request)) {
            if (isset($request->getQueryParams()['instituicao']) && !empty($request->getQueryParams()['instituicao'])) {
                DefaultSession::getInstance()->set('DB_instit', $request->getQueryParams()['instituicao']);
            }
        }

        if ($request->getParsedBody()['buscaTodasComissoes'] === "true"
            || $request->getParsedBody()['buscaTodasComissoes'] === true) {
            return new DBJsonResponse(Comissao::all());
        } else {
            return new DBJsonResponse(Comissao::getComissoesVigentes($instituicao));
        }


        return new DBJsonResponse(Comissao::getComissoesVigentes());
    }

    /**
     * @param  Sequencial $request
     * @return DBJsonResponse
     */
    public function show(Sequencial $request)
    {
        $retorno = ComissaoService::lancamentosServidorByComissao($request);
        return new DBJsonResponse($retorno);
    }

    /**
     *
     * Retorna multiplas informações de uma comissão para consumir na grid dinamica
     *
     * @todo Precisa retornar:
     * @return Comissao com todas suas relações
     *
     */
    public function getComissao(Sequencial $request)
    {
        $configuracoes = [];
        $lancamentosServidor = ComissaoService::lancamentosServidorByComissao($request);

        $configuracoes[TipoSessao::getDescricaoByTipo(TipoSessao::NORMAL)] = ["maximo" => 0, "uso" => 0];
        $configuracoes[TipoSessao::getDescricaoByTipo(TipoSessao::EXTRAORDINARIA)] = ["maximo" => 0, "uso" => 0];
        $configuracoes[TipoSessao::getDescricaoByTipo(TipoSessao::URGENTE)] = ["maximo" => 0, "uso" => 0];
        foreach ($lancamentosServidor->sessao as $sessao) {
            $sessao->matriculas = array_pluck(
                $sessao->servidores->toArray(),
                'dados_servidor.rh245_matricula'
            );
            unset($sessao->servidores);
            $codigoTipo = $sessao->rh247_tiposessao;
            unset($sessao->tipo);
            $sessao->tipo = TipoSessao::getDescricaoByTipo($codigoTipo);
            $configuracoes[TipoSessao::getDescricaoByTipo($codigoTipo)]["uso"] += 1;
        }

        $configuracaoComissao = $lancamentosServidor->tipoSessao->map(function ($sessao) {
            $elemento = [];
            $elemento[TipoSessao::getDescricaoByTipo($sessao->rh249_tiposessao)] = $sessao->rh249_quantidade;
            return $elemento;
        });

        foreach ($configuracaoComissao as $configuracao) {
            foreach ($configuracao as $key => $value) {
                $configuracoes[$key]["maximo"] = $value;
            }
        }
        unset($lancamentosServidor->tipoSessao);
        $lancamentosServidor->configuracao = $configuracoes;
        return new DBJsonResponse($lancamentosServidor);
    }

    /**
     * @param  Sequencial $request
     * @return DBJsonResponse
     */
    public function delete(Sequencial $request)
    {
        $comissao = new Comissao();
        try {
            if ($comissao->destroy($request->id)) {
                return new DBJsonResponse([], "Comissão excluída com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->errorInfo],
                "Não foi possivel excluir a comissão.",
                400
            );
        }
    }

    /**
     * @param  Store $request
     * @return DBJsonResponse
     */
    public function store(Store $request)
    {
        try {
            $comissao = new Comissao();

            $comissao->setDescricao($request->descricao);
            $comissao->setInstituicao($request->instituicao);
            $comissao->setDataInicio($request->datainicio);
            $comissao->setDataFim($request->datafim);

            if ($comissao->callSave()) {
                return new DBJsonResponse(
                    ["id" => $comissao->getSequencial()],
                    "Comissão cadastrada com sucesso."
                );
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->errorInfo],
                "Ocorreu algum erro ao cadastrar a comissão.",
                400
            );
        }
    }

    /**
     * @param  Update $request
     * @return DBJsonResponse
     */
    public function update(Update $request)
    {
        try {
            $comissao = new Comissao();
            $comissao->setInstituicao($request->instituicao);
            $comissao->setDescricao($request->descricao);
            $comissao->setDataInicio($request->datainicio);
            $comissao->setDataFim($request->datafim);

            if ($comissao->callUpdate($request->id)) {
                return new DBJsonResponse([], "Comissão alterada com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->errorInfo],
                "Ocorreu algum erro ao alterar a comissão."
            );
        }
    }
}
