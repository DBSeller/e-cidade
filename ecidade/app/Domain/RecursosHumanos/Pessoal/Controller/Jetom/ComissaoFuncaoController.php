<?php

namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoFuncao;
use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoFuncao\Update;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoFuncao\Sequencial;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\ComissaoFuncao\Store;

class ComissaoFuncaoController extends Controller
{
    /**
     * @param  Request $request
     * @return ComissaoFuncao[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function index(Request $request)
    {
        if (!empty($request->comissao)) {
            $funcoes = ComissaoFuncao::select(
                [
                    'rh246_sequencial as codigo',
                    'rh246_comissao as comissao',
                    'rh241_descricao as descricao',
                    'rh246_funcao as funcao',
                    'rh246_quantidade as quantidade'
                ]
            )
                ->where('rh246_comissao', '=', $request->comissao)
                ->join("pessoal.jetomfuncao", "rh246_funcao", "=", "rh241_sequencial")
                ->orderBy('rh241_descricao')
                ->get();
            return new DBJsonResponse($funcoes);
        } else {
            return new DBJsonResponse(ComissaoFuncao::all());
        }
    }

    /**
     * @param  Store $request
     * @return DBJsonResponse
     */
    public function store(Store $request)
    {
        try {
            $comissaoFuncao = new ComissaoFuncao();
            $comissaoFuncao->setComissao($request->comissao);
            $comissaoFuncao->setFuncao($request->funcao);
            $comissaoFuncao->setQuantidade($request->quantidade);

            if ($comissaoFuncao->callSave()) {
                return new DBJsonResponse(
                    ["id" => $comissaoFuncao->getSequencial()],
                    "Função adicionada na comissão com sucesso.",
                    200
                );
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao adicionar a função na comissão.",
                400
            );
        }
    }

    /**
     * @param  Sequencial     $request
     * @param  ComissaoFuncao $comissaoFuncao
     * @return DBJsonResponse
     */
    public function show(Sequencial $request, ComissaoFuncao $comissaoFuncao)
    {
        try {
            if ($comissaoFuncao->find($request->id)) {
                return new DBJsonResponse($comissaoFuncao->find($request->id), "Encontrado com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao buscar a função da comissão.",
                400
            );
        }
    }

    /**
     * @param  Update         $request
     * @param  ComissaoFuncao $comissaoFuncao
     * @return DBJsonResponse
     */
    public function update(Update $request, ComissaoFuncao $comissaoFuncao)
    {
        try {
            $comissaoFuncao->setComissao($request->comissao);
            $comissaoFuncao->setFuncao($request->funcao);
            $comissaoFuncao->setQuantidade($request->quantidade);

            if ($this->passouLimiteQuantidadeCompetencia($comissaoFuncao)) {
                $mensagem = "Limite de Sessões inferior ao utilizado dentro da competência atual. "
                    . "Por favor revise a quantidade correta.";
                return new DBJsonResponse(
                    ["id" => $comissaoFuncao->getSequencial()],
                    $mensagem,
                    412
                );
            }
            if ($comissaoFuncao->callUpdate($request->id)) {
                return new DBJsonResponse([], "Função da comissão alterada com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao alterar a função da comissão.",
                400
            );
        }
    }

    /**
     * @param  Sequencial     $request
     * @param  ComissaoFuncao $comissaoFuncao
     * @return DBJsonResponse
     */
    public function destroy(Sequencial $request, ComissaoFuncao $comissaoFuncao)
    {
        /*
         * TODO implementar regra corretamente e validacao para exclusao
         */
        $funcao = ComissaoFuncao::find($request->id);
        $servidores = $funcao->getServidoresByFuncao();

        if (sizeof($servidores) > 0) {
            return new DBJsonResponse(
                [],
                "Existem servidores vinculados a função da comissão.",
                406
            );
        }

        try {
            if ($comissaoFuncao->destroy($request->id)) {
                return new DBJsonResponse([], "Função da comissão excluida com sucesso.");
            }
        } catch (\Exception $e) {
            return new DBJsonResponse(
                ["exception" => $e->getMessage()],
                "Ocorreu algum erro ao excluir a função da comissão.",
                400
            );
        }
    }

    /**
     * @param  \DBCompetencia $competencia
     * Funcao responsavel por validar se a quantidade de sessoes
     * que a funcao da comissao ultrapassou o limite dentro da competencia
     * @return boolean
     */
    public function passouLimiteQuantidadeCompetencia(ComissaoFuncao $comissaoFuncaoModel)
    {
        if ($comissaoFuncaoModel->getQuantidade() <= $this->getQuantidadeNaCompetencia($comissaoFuncaoModel)) {
            return true;
        }
        return false;
    }

    /**
     * Retorna a quantidade de sessoes em que a funcao apareceu dentro da competencia atual
     * @param ComissaoFuncao $comissaoFuncao
     * @return int
     */
    public function getQuantidadeNaCompetencia(ComissaoFuncao $comissaoFuncao)
    {
        $competencia = CompetenciaHelper::get();
        $dataInicial = "{$competencia->getAno()}-{$competencia->getMes()}-01";
        $dataFinal = "{$competencia->getAno()}-{$competencia->getMes()}-{$competencia->getUltimoDia()}";
        $quantidade = $comissaoFuncao->getFuncaoDaComissaoEmSessoesPorPeriodo($dataInicial, $dataFinal);

        return $quantidade->count();
    }
}
