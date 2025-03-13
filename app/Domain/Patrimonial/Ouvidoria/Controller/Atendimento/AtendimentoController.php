<?php

namespace App\Domain\Patrimonial\Ouvidoria\Controller\Atendimento;

use App\Domain\Patrimonial\Ouvidoria\Services\AcaoProcessoService;
use DBDate;
use ECidade\Lib\Session\DatabaseSession;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Helper\ProcessoEletronicoHelper;
use ECidade\V3\Extension\Registry;
use App\Http\Controllers\Controller;
use ECidade\Lib\Request\EAuth\EAuth;

use ECidade\Lib\Session\DefaultSession;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Ouvidoria\Model\Atendimento\Atendimento;
use App\Domain\Patrimonial\Ouvidoria\Services\CidadaoCgmLegacyService;
use App\Domain\Patrimonial\Ouvidoria\Services\AtendimentoProcessoService;
use App\Domain\Patrimonial\Ouvidoria\Repository\Atendimento\AtendimentoRepository;
use App\Domain\Patrimonial\Ouvidoria\Requests\Atendimento\Atendimento\ProcessoOuvidoria;
use App\Domain\Patrimonial\Ouvidoria\Requests\Atendimento\Atendimento\AprovarAtendimento;
use App\Domain\Patrimonial\Ouvidoria\Requests\Atendimento\Atendimento\RejeitarAtendimento;
use App\Domain\Patrimonial\Ouvidoria\Requests\Atendimento\Atendimento\SolicitacaoOuvidoria;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Filter\ListagemAtividades as FiltroListagemAtividades;
use InstituicaoRepository;

class AtendimentoController extends Controller
{

    private $repository;
    private $atendimentoProcessoService;
    private $cidadaoCgmLegacyService;

    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct(
        AtendimentoRepository      $atendimentoRepository,
        AtendimentoProcessoService $atendimentoProcessoService,
        CidadaoCgmLegacyService    $cidadaoCgmLegacyService
    ) {
        $this->repository = $atendimentoRepository;
        $this->atendimentoProcessoService = $atendimentoProcessoService;
        $this->cidadaoCgmLegacyService = $cidadaoCgmLegacyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return DBJsonResponse
     */
    public function index()
    {
        return new DBJsonResponse($this->repository->findAll());
    }

    /**
     * Busca os atendimentos/processos da ouvidoria de acordo com os parametros passados.
     *
     * @param \App\Domain\Patrimonial\Ouvidoria\Requests\Atendimento\Atendimento\ProcessoOuvidoria $atendimento
     * @param FiltroListagemProcessos $filtroProcesso
     * @return DBJsonResponse
     */
    public function buscarProcessosOuvidoria(ProcessoOuvidoria $request, FiltroListagemProcessos $filtroProcesso)
    {
        $defaultSession = DefaultSession::getInstance();

        $filtroProcesso->setCodigoInstituicao($defaultSession->get(DefaultSession::DB_INSTIT));
        $filtroProcesso->setCodigoDepartamento($defaultSession->get(DefaultSession::DB_CODDEPTO));

        $filtroProcesso->setSituacaoOuvidoriaAtendimento(Atendimento::ATIVO);

        if (!empty($request->dataInicio) && !empty($request->dataFim)) {
            $dataInicio = new DBDate($request->dataInicio);
            $dataFim = new DBDate($request->dataFim);

            $filtroProcesso->setDataInicio($dataInicio);
            $filtroProcesso->setDataFim($dataFim);
        }

        if (!empty($request->ultimoSequencial)) {
            $filtroProcesso->setUltimoSequencial($request->ultimoSequencial);
        }

        $atendimentos = $this->repository->buscarProcessosOuvidoria($filtroProcesso);

        return new DBJsonResponse($atendimentos);
    }

    /**
     * Busca os dados de uma solicitação
     *
     * @param SolicitacaoOuvidoria $request
     * @param FiltroListagemProcessos $filtroProcesso
     * @return DBJsonResponse
     */
    public function buscarSolicitacaoOuvidoria(SolicitacaoOuvidoria $request, FiltroListagemProcessos $filtroProcesso)
    {
        $defaultSession = DefaultSession::getInstance();
        $filtroProcesso->setCodigoInstituicao($defaultSession->get(DefaultSession::DB_INSTIT));
        $filtroProcesso->setCodigoDepartamento($defaultSession->get(DefaultSession::DB_CODDEPTO));
        $filtroProcesso->setNumeroProcesso($request->numeroProcesso);
        $filtroProcesso->setAnoProcesso($request->anoProcesso);
        $solicitacao = $this->atendimentoProcessoService->buscarSolicitacaoOuvidoria($filtroProcesso);

        return new DBJsonResponse($solicitacao);
    }

    /**
     * Busca os dados de uma solicitação
     *
     * @param SolicitacaoOuvidoria $request
     * @param FiltroListagemProcessos $filtroProcesso
     * @return DBJsonResponse
     */
    public function buscarSolicitacaoOuvidoriaPorProcesso(
        SolicitacaoOuvidoria $request,
        FiltroListagemProcessos $filtroProcesso
    ) {

        $defaultSession = DefaultSession::getInstance();
        $filtroProcesso->setNumeroProcesso($request->numeroProcesso);
        $filtroProcesso->setAnoProcesso($request->anoProcesso);
        $filtroProcesso->setCodigoProcessoProtocolo($request->processoProtocolo);

        $solicitacao = $this->atendimentoProcessoService->buscarSolicitacaoOuvidoria($filtroProcesso);

        return new DBJsonResponse($solicitacao);
    }

    /**
     * Ação para aprovar um processo de atendimento/ouvidoria
     *
     * @param SolicitacaoOuvidoria $atendimento
     * @param FiltroListagemProcessos $filtroProcesso
     * @return DBJsonResponse
     * @throws \Exception
     */
    public function aprovarProcessoOuvidoria(AprovarAtendimento $request, FiltroListagemProcessos $filtroProcesso)
    {
        $defaultSession = DefaultSession::getInstance();
        DefaultSession::getInstance()->set(DefaultSession::DB_CODDEPTO, db_getsession('DB_coddepto'));
        DatabaseSession::getInstance()->addSessionToDatabase();
        $filtroProcesso->setCodigoInstituicao($defaultSession->get(DefaultSession::DB_INSTIT));
        $filtroProcesso->setCodigoDepartamento($defaultSession->get(DefaultSession::DB_CODDEPTO));
        $filtroProcesso->setNumeroProcesso($request->numeroProcesso);
        $filtroProcesso->setAnoProcesso($request->anoProcesso);

        $solicitacao = $this->atendimentoProcessoService->buscarSolicitacaoOuvidoria($filtroProcesso);
        $solicitacao->metadados = \JSON::create()->parse($solicitacao->metadados);
        if (!empty($request->inscricao)) {
            $solicitacao->inscricao = $request->inscricao;
        }
        $dadosEauth = $this->atendimentoProcessoService->extrairInformacoesMetadadosEauth($solicitacao->metadados);
        $oCgmResponsavel = $this->atendimentoProcessoService->getCgmResponsavelByMetadados($solicitacao->metadados);

        $emailSecundario = null;
        if (trim($solicitacao->metadados->acao) === "atualizacao_cadastral") {
            $cgm = $this->cidadaoCgmLegacyService->getCgmBySolicitacao($solicitacao);

            if (!$oCgmResponsavel) {
                $oCgmResponsavel = $cgm;
            }
            $secaoContato = array_filter($solicitacao->metadados->secoes, function ($secao) {
                return $secao->nome === "contato";
            });

            if (!empty($secaoContato)) {
                $secaoContato =  reset($secaoContato);
                $campoEmail = array_filter($secaoContato->campos, function ($campo) {
                    return trim($campo->nome) == "email";
                });

                if (!empty($campoEmail)) {
                    $campoEmail = reset($campoEmail);
                    $emailSecundario = $campoEmail->resposta;
                }
            }

            /**
             * ARQUIVA PROCESSO
             */
            $this->atendimentoProcessoService->rejeitarProcesso(
                $cgm,
                $solicitacao,
                "Recadastramento acesso arquivado pelo sistema",
                $oCgmResponsavel
            );

            $processo = $this->atendimentoProcessoService->getProcesso();

            if ($cgm->getCodigo() != InstituicaoRepository::getInstituicaoPrefeitura()->getCgm()->getCodigo()) {
                $requerente = \CgmFactory::getInstanceByCgm($cgm->getCodigo());
                $cpfCnpj = null;
                if ($requerente instanceof \CgmJuridico) {
                    $cpfCnpj = $requerente->getCnpj();
                }

                if ($requerente instanceof \CgmFisico) {
                    $cpfCnpj = $requerente->getCpf();
                }

                $eauth = new EAuth();

                $mensagemNotificacao = "O atendimento {$request->numeroProcesso} ";
                $mensagemNotificacao .= "do titular {$oCgmResponsavel->getNome()}, foi aprovado. ";

                /***
                 * SALVA USUARIO NA APLICAÇÃO DO EAUTH
                 */
                $result = $eauth->salvarUsuarioEauth(
                    $requerente->getNome(),
                    $emailSecundario,
                    $cpfCnpj,
                    $solicitacao->ov33_client_atendimento_id
                );

                if (!$result->success) {
                    throw new \Exception($result->message);
                }

                $eauth->sendMessage(
                    $cpfCnpj,
                    $solicitacao->ov33_client_atendimento_id,
                    $mensagemNotificacao
                );
            }

            $mensagem = "Atendimento aprovado com sucesso.";
            $mensagem .= "\nCriado processo {$processo->getNumero()}/{$processo->getAno()}";
        } elseif ($solicitacao->formareclamacao == 9) {
            if (empty($dadosEauth)) {
                throw new \Exception("Erro ao extrair informações para envio ao eauth");
            }
            $cgm = $this->cidadaoCgmLegacyService->getCgmBySolicitacaoPrimeiroACesso($solicitacao, $dadosEauth);
            if (!$oCgmResponsavel) {
                $oCgmResponsavel = $cgm;
            }
            /**
             * ARQUIVA PROCESSO
             */
            $this->atendimentoProcessoService->rejeitarProcesso(
                $cgm,
                $solicitacao,
                "Primeiro acesso arquivado pelo sistema",
                $oCgmResponsavel
            );
            $processo = $this->atendimentoProcessoService->getProcesso();

            /***
             * SALVA USUARIO NA APLICAÇÃO DO EAUTH
             */
            $eauth = new EAuth();
            $result = $eauth->salvarUsuarioEauth(
                $dadosEauth->nome,
                $dadosEauth->email,
                $dadosEauth->cgccpf,
                $solicitacao->ov33_client_atendimento_id
            );

            if (!$result->success) {
                throw new \Exception($result->message);
            }

            $mensagem = "Atendimento aprovado com sucesso.";
            $mensagem .= "\nCriado processo {$processo->getNumero()}/{$processo->getAno()}";
        } else {
            $cgm = $this->cidadaoCgmLegacyService->getCgmBySolicitacao($solicitacao);

            if (!$oCgmResponsavel) {
                $oCgmResponsavel = $cgm;
            }
            $this->atendimentoProcessoService->aprovarProcesso(
                $cgm,
                $solicitacao,
                $oCgmResponsavel,
                $request->observacao
            );
            $processo = $this->atendimentoProcessoService->getProcesso();

            $acaoProcessoService = new AcaoProcessoService();
            $acaoProcessoService->setSolicitacao($solicitacao)
                ->setCamposAdicionais($request->camposAdicionais)
                ->setProcesso($processo)
                ->executa();

            if ($cgm->getCodigo() != InstituicaoRepository::getInstituicaoPrefeitura()->getCgm()->getCodigo()) {
                $requerente = \CgmFactory::getInstanceByCgm($cgm->getCodigo());
                $cpfCnpj = null;
                if ($requerente instanceof \CgmJuridico) {
                    $cpfCnpj = $requerente->getCnpj();
                }

                if ($requerente instanceof \CgmFisico) {
                    $cpfCnpj = $requerente->getCpf();
                }

                $eauth = new EAuth();

                $mensagemNotificacao = "O atendimento {$request->numeroProcesso} ";
                $mensagemNotificacao .= "do titular {$oCgmResponsavel->getNome()}, foi aprovado. ";
                $mensagemNotificacao .= "Processo {$processo->getNumero()}/{$processo->getAno()}";

                $eauth->sendMessage(
                    $cpfCnpj,
                    $solicitacao->ov33_client_atendimento_id,
                    $mensagemNotificacao
                );
            }


            $mensagem = "Atendimento aprovado com sucesso.";
            $mensagem .= "\nCriado processo {$processo->getNumero()}/{$processo->getAno()}";
            $mensagem .= "\n{$acaoProcessoService->getMensagem()}";
        }

        return new DBJsonResponse(null, $mensagem);
    }

    /**
     * Ação para aprovar um processo de atendimento/ouvidoria
     *
     * @param SolicitacaoOuvidoria $atendimento
     * @param FiltroListagemProcessos $filtroProcesso
     * @return DBJsonResponse
     */
    public function rejeitarProcessoOuvidoria(RejeitarAtendimento $request, FiltroListagemProcessos $filtroProcesso)
    {
        $defaultSession = DefaultSession::getInstance();
        DefaultSession::getInstance()->set(DefaultSession::DB_CODDEPTO, db_getsession('DB_coddepto'));
        DatabaseSession::getInstance()->addSessionToDatabase();
        $filtroProcesso->setCodigoInstituicao($defaultSession->get(DefaultSession::DB_INSTIT));
        $filtroProcesso->setCodigoDepartamento($defaultSession->get(DefaultSession::DB_CODDEPTO));
        $filtroProcesso->setNumeroProcesso($request->numeroProcesso);
        $filtroProcesso->setAnoProcesso($request->anoProcesso);

        $solicitacao = $this->atendimentoProcessoService->buscarSolicitacaoOuvidoria($filtroProcesso);
        $solicitacao->metadados = \JSON::create()->parse($solicitacao->metadados);

        $cgm = $this->cidadaoCgmLegacyService->getCgmBySolicitacao($solicitacao);

        $oCgmResponsavel = $this->atendimentoProcessoService->getCgmResponsavelByMetadados($solicitacao->metadados);

        if (!$oCgmResponsavel) {
            $oCgmResponsavel = $cgm;
        }

        $this->atendimentoProcessoService->rejeitarProcesso($cgm, $solicitacao, $request->motivo, $oCgmResponsavel);
        $processo = $this->atendimentoProcessoService->getProcesso();

        $eauth = new EAuth();

        if ($cgm->getCodigo() != InstituicaoRepository::getInstituicaoPrefeitura()->getCgm()->getCodigo()) {
            $requerente = \CgmFactory::getInstanceByCgm($cgm->getCodigo());
            if ($requerente instanceof \CgmJuridico) {
                $cpfCnpj = $requerente->getCnpj();
            }

            if ($requerente instanceof \CgmFisico) {
                $cpfCnpj = $requerente->getCpf();
            }

            $mensagemNotificacao = "O atendimento {$request->numeroProcesso} ";
            $mensagemNotificacao .= "do titular {$oCgmResponsavel->getNome()},";
            $mensagemNotificacao .= " foi rejeitado. Motivo: {$request->motivo}";

            $emailSecundario = null;
            if (trim($solicitacao->metadados->acao) === "atualizacao_cadastral") {
                $secaoContato = array_filter($solicitacao->metadados->secoes, function ($secao) {
                    return $secao->nome === "contato";
                });

                if (!empty($secaoContato)) {
                    $secaoContato =  reset($secaoContato);
                    $campoEmail = array_filter($secaoContato->campos, function ($campo) {
                        return trim($campo->nome) == "email";
                    });

                    if (!empty($campoEmail)) {
                        $campoEmail = reset($campoEmail);
                        $emailSecundario = $campoEmail->resposta;
                    }
                }
            }

            $eauth->sendMessage(
                $cpfCnpj,
                $solicitacao->ov33_client_atendimento_id,
                $mensagemNotificacao,
                trim($emailSecundario),
                false
            );
        }

        if ($solicitacao->formareclamacao == 9) {
            $dadosEauth = $this->atendimentoProcessoService->extrairInformacoesMetadadosEauth($solicitacao->metadados);
            $mensagemNotificacao = "O atendimento {$request->numeroProcesso} foi rejeitado. {$request->motivo}";

            $eauth->sendMessage(
                $dadosEauth->cgccpf,
                $solicitacao->ov33_client_atendimento_id,
                $mensagemNotificacao,
                trim($dadosEauth->email),
                true
            );
        }

        $mensagem = "Atendimento rejeitado com sucesso.";
        $mensagem .= "\nCriado processo {$processo->getNumero()}/{$processo->getAno()}";

        return new DBJsonResponse(null, $mensagem);
    }


    public function existeInscricaoAlvara(
        AprovarAtendimento       $request,
        FiltroListagemProcessos  $filtroProcesso,
        FiltroListagemAtividades $filtroAtividades
    ) {
        $responseData = ['success' => false];
        $container = Registry::get('app.container')->get('tributario.container');
        $parameterBag = $container->get('ProcessoEletronicoParameterBag');

        $containerPatrimonial = Registry::get('app.container')->get('patrimonial.container');
        $inclusaoCgmService = $containerPatrimonial->get('Servicos\InclusaoCgmLegacy');

        $defaultSession = DefaultSession::getInstance();
        $filtroProcesso->setCodigoInstituicao($defaultSession->get(DefaultSession::DB_INSTIT));
        $filtroProcesso->setCodigoDepartamento($defaultSession->get(DefaultSession::DB_CODDEPTO));
        $filtroProcesso->setNumeroProcesso($request->get('numeroProcesso'));
        $filtroProcesso->setAnoProcesso($request->get('anoProcesso'));

        $solicitacao = $this->atendimentoProcessoService->buscarSolicitacaoOuvidoria($filtroProcesso);
        $filtroProcesso->setCodigoTipoProcesso($solicitacao->tipo_processo);

        $solicitacao->metadados = \JSON::create()->parse($solicitacao->metadados);
        $serviceProcessosAlvaraOnline = $container->get('Inscricao\Service\AlvaraOnline');
        $acao = $parameterBag->getAcaoByTipoProcesso($solicitacao->tipo_processo);
        $dados = json_decode($serviceProcessosAlvaraOnline->retornarProcessoAlvara($filtroProcesso, $filtroAtividades));
        $cgms = ProcessoEletronicoHelper::consultarCgmsByDados(
            $inclusaoCgmService,
            $dados,
            $acao
        );

        if ($cgms['cgmEmpresa'] != true) {
            return new DBJsonResponse($responseData, "");
        }

        $issbaseRepository = new \cl_issbase();
        $sql = $issbaseRepository->sql_query_file(null, "*", null, "q02_numcgm={$cgms['cgmEmpresa']->getCodigo()}");
        $rs = pg_fetch_all(db_query($sql));

        if (!empty($rs) and count($rs) > 0) {
            $responseData["success"] = true;
            $responseData["inscricoes"] = array();
            foreach ($rs as $inscricao) {
                $responseData["inscricoes"][] = $inscricao['q02_inscr'];
            }
            return new DBJsonResponse($responseData, "");
        }
        return new DBJsonResponse($responseData, "");
    }
}
