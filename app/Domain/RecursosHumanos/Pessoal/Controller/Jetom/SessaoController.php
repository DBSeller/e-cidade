<?php

namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Sessao;
use App\Domain\RecursosHumanos\Pessoal\Model\Jetom\PermissaoComissao;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Sessao\SessaoProcessamentoRequest;
use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\Sessao\SessaoRequest;
use App\Domain\RecursosHumanos\Pessoal\Services\Jetom\SessaoService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ECidade\Lib\Session\DefaultSession;
use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;

class SessaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request, Sessao $sessao)
    {
        try {
            $codigo_instituicao = $request->get('instituicao');

            if (isset($codigo_instituicao) && !empty($codigo_instituicao)) {
                DefaultSession::getInstance()->set('DB_instit', $codigo_instituicao);
            }

            DefaultSession::getInstance()->atualizaDadosUsuario($request->usuario);

            $db_session = [
                'id_usuario' => (int)DefaultSession::getInstance()->get('DB_id_usuario'),
                'instit' => (int)DefaultSession::getInstance()->get('DB_instit')
            ];

            $competencia = CompetenciaHelper::get();
            $ano_competencia = $competencia->getAno();
            $mes_competencia = $competencia->getMes();

            if ($request->ano) {
                $ano_competencia = $request->ano;
            }

            if ($request->mes) {
                $mes_competencia = $request->mes;
            }

            /* Retorno da rotina Sessões */
            if (!$request->get('rh247_processada')) {
                $em_processo = [];

                return new DBJsonResponse(
                    Sessao::with('servidores')->where($em_processo)->orderBy('rh247_data', 'desc')
                    ->when($mes_competencia, function ($query) use ($mes_competencia) {
                        return $query->where('rh247_mes', $mes_competencia);
                    })
                    ->when($ano_competencia, function ($query) use ($ano_competencia) {
                        return $query->where('rh247_ano', $ano_competencia);
                    })->get()
                );
            }

            /**
             * Verificamos se o usuario logado e o dbseller
             */
            if (DefaultSession::getInstance()->get('DB_id_usuario') != 1) {
                $dataMatriculas = $sessao::usuarioLoginsAtivos($db_session);

                /* Busca dados da permissão para o servidor */
                $permissaoData = PermissaoComissao::whereIn(
                    'rh251_matricula',
                    $dataMatriculas
                )->get();

                /* Verifica se o servidor tem permissão */
                if ($permissaoData->isEmpty()) {
                    throw new Exception("Servidor sem permissão.");
                }

                // dados de comissoes
                $dataComissoes = $permissaoData->map(
                    function ($model) {
                        return $model->rh251_comissao;
                    }
                )->toArray();
            }
            /**
             * se estiver na pagina de processamento, seta coluna processada
             */
            $em_processo = $request->get('rh247_processada') ?
            ['rh247_processada' => $request->get('rh247_processada')]
            : [] ;

            /**
             * Verificamos se o usuario logado e o dbseller
             */
            $sessaoData = [];
            if (DefaultSession::getInstance()->get('DB_id_usuario') != 1) {
                $sessaoData = Sessao::with('servidores')
                ->where($em_processo)->orderBy('rh247_data', 'desc')
                ->when($mes_competencia, function ($query) use ($mes_competencia) {
                    return $query->where('rh247_mes', $mes_competencia);
                })->when($ano_competencia, function ($query) use ($ano_competencia) {
                    return $query->where('rh247_ano', $ano_competencia);
                })
                ->join('jetomcomissao', 'rh242_sequencial', 'rh247_comissao')
                ->where('rh242_instit', DefaultSession::getInstance()->get('DB_instit')) // busca por instituicao
                ->whereIn('rh242_sequencial', $dataComissoes) // busca por comissões
                ->get();
            } else {
                $sessaoData = Sessao::with('servidores')
                    ->where($em_processo)->orderBy('rh247_data', 'desc')
                    ->when($mes_competencia, function ($query) use ($mes_competencia) {
                        return $query->where('rh247_mes', $mes_competencia);
                    })->when($ano_competencia, function ($query) use ($ano_competencia) {
                        return $query->where('rh247_ano', $ano_competencia);
                    })
                    ->join('jetomcomissao', 'rh242_sequencial', 'rh247_comissao')
                    ->where('rh242_instit', DefaultSession::getInstance()->get('DB_instit')) // busca por instituica
                    ->get();
            }

            /* Retorno da rotina Processamento */
            return new DBJsonResponse(
                $sessaoData
            );
        } catch (Exception $exception) {
            return new DBJsonResponse(
                ['exception' => $exception->getMessage()],
                'Não foi possível buscar as informações da Sessão.',
                400
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SessaoRequest $request
     * @return JsonResponse
     */
    public function store(SessaoRequest $request)
    {
        try {
            $erros = SessaoService::lancamentoLote($request);
            return new DBJsonResponse($erros, "Sessões lançadas com sucesso.");
        } catch (Exception $e) {
            return new DBJsonResponse(
                [],
                $e->getMessage(),
                400
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $sessao = Sessao::with(['servidores', 'comissao.servidores'])->find($id);

            if (empty($sessao)) {
                return new DBJsonResponse([], 'Nenhuma Sessão foi encontrada com o código informado.', 410);
            }

            return new DBJsonResponse($sessao);
        } catch (Exception $exception) {
            return new DBJsonResponse(
                null,
                'Não foi possível buscar as informação da Sessão.',
                400
            );
        }
    }

    /**
     * @param int $id
     * @return DBJsonResponse
     */
    public function destroy($id)
    {
        try {
            $sessao = Sessao::find($id);

            if (empty($sessao)) {
                throw new Exception('Nenhuma Sessão foi encontrada com o código informado.');
            }

            if ($sessao->isProcessada()) {
                throw new Exception('Não é possível excluir uma Sessão já processada.');
            }

            $sessao->servidores()->delete();
            $sessao->delete();
        } catch (Exception $exception) {
            return new DBJsonResponse(null, $exception->getMessage(), 400);
        }

        return new DBJsonResponse($sessao, 'Sessão excluída com sucesso.');
    }

    /**
     * @param SessaoProcessamentoRequest $request
     * @return DBJsonResponse
     */
    public function processar(SessaoProcessamentoRequest $request)
    {
        try {
            $sessoesProcessadas = [];
            foreach ($request->get('ids') as $id) {
                $sessao = Sessao::with('servidores')->find($id);
                if (empty($sessao)) {
                    throw new Exception('Nenhuma Sessão foi encontrada com o código informado.');
                }

                if ($sessao->isProcessada()) {
                    throw new Exception('Não é possível reprocessar uma Sessão.');
                }

                $sessao->processar();

                $sessoesProcessadas[] = $sessao;
            }

            $descricao = "Sessão processada";
            if (count($request->get('ids')) > 1) {
                $descricao = "Sessões processadas";
            }

            return new DBJsonResponse($sessoesProcessadas, "{$descricao} com sucesso.");
        } catch (Exception $exception) {
            return new DBJsonResponse(null, $exception->getMessage(), 400);
        }
    }
}
