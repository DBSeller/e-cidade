<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

use App\Domain\Financeiro\Planejamento\Models\Valor;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\ProcessoJudicial;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\ProcessoJudicialService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\ServidorService as ServidorServiceProcesso;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Servidor as ServidorProcesso;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository as ServidorRepositoryProcesso;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\ContratoService;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Contrato;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\AbonoRepository;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\AbonoService;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Abono;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\MudancaRepository;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\MudancaService;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Mudanca;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Unicidade;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\UnicidadeService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\UnicidadeRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Periodo;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\PeriodoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\PeriodoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoBase;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\TributoBaseService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoBaseRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoContribuicao;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\TributoContribuicaoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoContribuicaoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoIRRF;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\TributoIRRFService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoIRRFRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Vinculo;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\VinculoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\VinculoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Duracao;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\DuracaoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DuracaoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Desligamento;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\DesligamentoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DesligamentoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Advogado;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\AdvogadoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\AdvogadoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Dependente;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\DependenteService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DependenteRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Pensao;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\PensaoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\PensaoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Retencao;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\RetencaoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\RetencaoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\ValorRetencao;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\ValorRetencaoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ValorRetencaoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\DeducaoSuspensa;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\DeducaoSuspensaService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DeducaoSuspensaRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\SuspensaPensao;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\SuspensaPensaoService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\SuspensaPensaoRepository;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoIRRFComplementar;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\TributoIRRFComplementarService;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoIRRFComplementarRepository;

use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\V3\Extension\Registry;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Exclusao;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\ExclusaoService;
use ECidade\RecursosHumanos\RH\PontoEletronico\Contrato\Model\ContratoJornada;
use ECidade\RecursosHumanos\Pessoal\Model\ContratoEmergencial;
use ECidade\RecursosHumanos\ESocial\Repository\PagamentosRendimentosTrabalho;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\SuspensaoPensaoRepository;
use ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial\SuspensaoPensaoService;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');
require_once (modification("libs/JSON.php"));

$parametros = JSON::requestParameters();

$retorno = new stdClass();
$retorno->status = 1;
$retorno->erro = false;
$retorno->mensagem = '';
try {
    
    db_inicio_transacao();

    $servidorProcessosJudicialEsocialService = new ProcessoJudicialService();

    switch ($parametros->acao) {
        case 'inicializar':
            $instituicaoSessao = InstituicaoRepository::getInstituicaoSessao();
        
            $where = [
                "r70_ativo IS TRUE",
                "r70_instit = {$instituicaoSessao->getCodigo()}"    
            ];
        
            $campos = [
                "z01_numcgm AS cgm",
                "z01_cgccpf AS cnpj",
                "z01_nome AS nome"
            ];
        
            $dao = new cl_rhlota();
            $sql = $dao->sql_query_lota_cgm(
                null,
                'DISTINCT ' . implode(', ', $campos),
                'z01_numcgm',
                implode(' AND ', $where)
            );
        
            $rs = db_query($sql);
        
            $retorno->empregadores = pg_fetch_all($rs);    
            break;
        case 'salvarProcessoJudicial':
            $processo = $servidorProcessosJudicialEsocialService->salvar($parametros);
            $sequencialProcesso = $processo->getSequencial();
            $retorno->mensagem = "Processo judicial salvo com sucesso.";
            break;
        case 'autocompletaNumeroProcesso':
            $oJson = new services_json();
            $processoRepository = new ProcessoJudicialRepository;
            $processoRepository->resetScopes();
            $processos = $processoRepository
                ->scopeNumerosProcessos($autocompleta->valorDigitado)
                ->get();
            $numeroProcesso = [];
            foreach ($processos as $processo) {
                $dados = new stdClass();
                $dados->numeroProcesso = $processo->getNumeroProcesso();
                $numeroProcesso[] = $dados;
            }

            $retorno->dados = $oJson->encode($numeroProcesso);;
            break;
        case 'listaProcessos':
            $listaProcesso = $servidorProcessosJudicialEsocialService->listaProcesso();
            $dados = [];
            foreach ($listaProcesso as $processo) {
                $dadosProcesso[] = JSON::create()->parse($processo->serialize());
            }
            $retorno->mensagem = "Clique no bot?o <strong>'Novo'</strong> para incluir um processo.";
            $retorno->dados = $dadosProcesso;
            break;
        case 'editarProcesso':
            $processo = $servidorProcessosJudicialEsocialService->retornaProcesso((int) $parametros->sequencial);
            $dadosProcesso = [];
            if (!empty($processo)) {
                $dadosProcesso[] = JSON::create()->parse($processo->serialize());
            }
            $retorno->dados = $dadosProcesso;
            break;
        case 'excluirProcesso':
            $sequencialProcesso = $parametros->sequencial;
            $processoJudicialRepository =  new ProcessoJudicialRepository();
            $processoModel = $processoJudicialRepository
                ->scopeSequencial($sequencialProcesso)
                ->get();

            if (empty($processoModel)) {
                $retorno->dados = [];
                break;
            };
            $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
            $servidoresProcessos = $servidorRepositoryProcesso 
                ->scopeSequencialProcesso((int) $parametros->sequencial)
                ->get();

            $servidoresModel = [];
            foreach ($servidoresProcessos as $servidorProcesso) {
                $servidoresModel[] = $servidorProcesso;
            }
            $contratosModel = [];
            $vinculosModel = [];
            $tributosModel = [];
            $IRRFsModel = [];
            foreach ($servidoresModel as $servidorUnicoProcesso) {
                $contratoRepository = new ContratoRepository;
                $contratos = $contratoRepository
                    ->scopeSequencialServidor((int) $servidorUnicoProcesso->getSequencial())
                    ->get();

                foreach ($contratos as $contrato) {
                    $contratosModel[] = $contrato;
                }
                $vinculoRepository = new VinculoRepository();
                $vinculos = $vinculoRepository
                    ->scopeSequencialServidor((int) $servidorUnicoProcesso->getSequencial())
                    ->get();

                foreach ($vinculos as $vinculo) {
                    $vinculosModel[] = $vinculo;
                }
                $tributoBaseRepository = new TributoBaseRepository;
                $tributosBase = $tributoBaseRepository
                    ->scopeSequencialServidor((int) $servidorUnicoProcesso->getSequencial())
                    ->get();
                foreach ($tributosBase as $tributoBase) {
                    $tributosModel[] = $tributoBase;
                }
                $tributoIRRFRepository = new TributoIRRFRepository;
                $tributosIRRF = $tributoIRRFRepository
                    ->scopeSequencialServidor((int) $servidorUnicoProcesso->getSequencial())
                    ->get();
                foreach ($tributosIRRF as $tributoIRRF) {
                    $IRRFsModel[] = $tributoIRRF;
                }
            }

            $anoAbonoModel = [];
            $mudancasModel = [];
            $unicidadesModel = [];
            $periodosModel = [];

            foreach ($contratosModel as $contrato) {
                $anoAbonoRepository = new AbonoRepository();
                $anoAbonos = $anoAbonoRepository
                    ->scopeSequencialContrato((int) $contrato->getSequencial())
                    ->get();
                foreach ($anoAbonos as $anoAbono) {
                   $anoAbonoModel[] = $anoAbono;
                }

                $mudancaRepository = new MudancaRepository();
                $mudancas = $mudancaRepository
                    ->scopeSequencialContrato((int) $contrato->getSequencial())
                    ->get();
                foreach ($mudancas as $mudanca) {
                    $mudancasModel[] = $mudanca;
                }

                $unicidadeRepository = new UnicidadeRepository();
                $unicidades = $unicidadeRepository
                    ->scopeSequencialContrato((int) $contrato->getSequencial())
                    ->get();
                foreach ($unicidades as $unicidade) {
                    $unicidadesModel[] = $unicidade;
                }

                $periodoRepository = new PeriodoRepository();
                $periodos = $periodoRepository
                    ->scopeSequencialContrato((int) $contrato->getSequencial())
                    ->get();

                foreach ($periodos as $periodo) {
                    $periodosModel[] = $periodo;
                }
            }

            foreach ($periodosModel as $periodo) {
                $periodoRepository->resetScopes();
                $periodoRepository->delete($periodo);
            }
            foreach ($unicidadesModel as $unicidade) {
                $unicidadeRepository->resetScopes();
                $unicidadeRepository->delete($unicidade);
            }
            foreach ($mudancasModel as $mudanca) {
                $mudancaRepository->resetScopes();
                $mudancaRepository->delete($mudanca);
            }
            foreach ($IRRFsModel as $IRRF) {
                $tributoIRRFRepository->resetScopes();
                $tributoIRRFRepository->delete($IRRF);
            }
            foreach ($tributosModel as $tributos) {
                $tributoBaseRepository->resetScopes();
                $tributoBaseRepository->delete($tributos);
            }
            foreach ($vinculosModel as $vinculo) {
                $vinculoRepository->resetScopes();
                $vinculoRepository->delete($vinculo);
            }

            foreach ($anoAbonoModel as $anoAbono) {
                $anoAbonoRepository->resetScopes();
                $anoAbonoRepository->delete($anoAbono);
            }

            foreach ($contratosModel as $contrato) {
                $contratoRepository->resetScopes();
                $contratoRepository->delete($contrato);
            }

            foreach ($servidoresModel as $servidor) {
                $servidorRepositoryProcesso->resetScopes();
                $servidorRepositoryProcesso->delete($servidor);
            }
            
            $processoJudicialRepository->resetScopes();
            $processo = $processoJudicialRepository->delete($processoModel[0]);

            $retorno->dados = $processoModel[0]->getNumeroProcesso();
            break;
        case 'buscarSelecao':
            $servidores = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), $parametros->selecao);
            foreach ($servidores as $servidor) {
                $parametros->matriculas[] = $servidor->getMatricula();
            }
            break;
        case 'vinculaServidor':
            $registrosExcluidos = [];
            $matriculas = json_decode($parametros->json, true);

            if (!empty((int) $parametros->codigoSelecao )) {
                $servidores = ServidorRepository::getServidoresBySelecao(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), $parametros->codigoSelecao);
                $listaMatriculas = [];
                foreach ($servidores as $servidor) {
                    $listaMatriculas['matriculas'][] = $servidor->getMatricula();
                }
                $matriculas = $listaMatriculas;
            }

            $retorno->erro = true;
            foreach ($matriculas['matriculas'] as $matricula) {
                $retorno->erro = false;
                $servidor = \ServidorRepository::getInstanciaByCodigo($matricula);
                $instituicao = (int) db_getsession('DB_instit');
                $codigoCategoria = (int) $servidor->getVinculo()->getCodigoCategoria();
                $servidorProcesso = new ServidorProcesso();
                $servidorProcesso->setCodigoCategoria($codigoCategoria);
                $servidorProcesso->setCodigoInstituicao($instituicao);
                $servidorProcesso->setSequencialProcesso((int) $parametros->sequencialProcesso);
                $servidorProcesso->setMatricula($matricula);

                $servidorProcessosService = new ServidorServiceProcesso($servidorProcesso->getSequencialProcesso());
                $servidorProcesso = $servidorProcessosService->salvar($servidorProcesso);

                //Informações Adicionais ref. ao Contrato de trabalho
                $contratoRepository = new ContratoRepository();

                $contratoExiste = $contratoRepository
                    ->scopeSequencialServidor($servidorProcesso->getSequencial())
                    ->get();

                $contrato = new Contrato();
                if(!empty($contratoExiste)) {
                    $contrato->setSequencial($contratoExiste[0]->getSequencial());
                }
                $contrato->setTipoContrato($parametros->tipoContrato);
                $contrato->setIndicativoContrato($parametros->indicativoContrato);
                $contrato->setDataAdmissaoOrigem($servidor->getDataAdmissao()->getDate());
                $contrato->setIndicativoReintegracao($parametros->indicativoReintegracao);
                $contrato->setIndicativoCategoria($parametros->indicativoNovaCategoria);
                $contrato->setIndicativoNaturezaAtividade($parametros->novaAtividade); 
                $contrato->setIndicativoMotivoDesligamento($parametros->motivoDesligamento);
                $contrato->setNomeServidor($servidor->getCgm()->getNome());
                $contrato->setCodigoCBO($servidor->getDadosCargo()->rh37_cbo);
                if (isset($parametros->naturezaAtividadeMudanca)) {
                    $contrato->setNaturezaAtividade($parametros->naturezaAtividadeMudanca);
                }

                $contrato->setCodigoCategoria($servidor->getVinculo()->getCodigoCategoria());
                $processo = ProcessoJudicialRepository::find((int) $parametros->sequencialProcesso);
                $contrato->setDataSentenca($processo->getDataSentenca());
                $contrato->setDataAcordo($processo->getDataCelebracaoAcordo());

                //Informações dos períodos e valores decorrentes de processo trabalhista e 
                //ainda não declarados no eSocial.

                $competenciaInicial = $parametros->mesInicialProcesso . '-' . $parametros->anoInicialProcesso;
                $contrato->setCompetenciaInicial($competenciaInicial);
                $competenciaFinal = $parametros->mesFinalProcesso . '-' . $parametros->anoFinalProcesso;
                $contrato->setCompetenciaFinal($competenciaFinal);
                $contrato->setIndicativoRepercussao($parametros->indicativoRepercussao);
                $contrato->setIndicativoIndenizacaoSD($parametros->idenizacaoSD);
                $contrato->setSequencialProcessoServidor($servidorProcesso->getSequencial());
                $contrato->setIndicativoNaturezaAtividade($parametros->novaAtividade);
                $contrato->setIndenizacaoAbono($parametros->idenizacaoAbono);
                $contratoService = new ContratoService($contrato);

                $registroContrato = $contratoService->salvar($contrato);

                if ($parametros->tipoContrato != 6) {
                    $vinculoRepository = new VinculoRepository();
                    $vinculoExiste = $vinculoRepository
                        ->scopeSequencialServidor($servidorProcesso->getSequencial())
                        ->get();
                    $vinculo = new Vinculo();
                    if(!empty($vinculoExiste)) {
                        $vinculo->setSequencial($vinculoExiste[0]->getSequencial());
                    }
                    if ((int) $servidor->getTabelaPrevidencia() != 0) {
                        $vinculo->setRegimePrevidenciario((int) $servidor->isRgps() ? 1 : 2);
                    } else {
                        $registroCedencia = new Cedencia($servidor->getMatricula());
                        if ($registroCedencia->getTipoCedencia() == 'A') {
                            $vinculo->setRegimePrevidenciario(2);
                        }
                    }
                    $deParaRegimeTrabalho = [
                        1 => 2,
                        2 => 1,
                        3 => 2
                    ];
                    $regimeTrabalho = $deParaRegimeTrabalho[(int) $servidor
                        ->getVinculo()
                        ->getRegime()
                        ->getCodigo()];
                    $vinculo->setRegimeTrabalhista($regimeTrabalho);
                    $contratoJornada = new ContratoJornada($matricula, db_getsession("DB_instit"));
                    $vinculo->setTempoParcial($contratoJornada->getTempoParcial());
                    $vinculo->setDataAdmissao($servidor->getDataAdmissao()->getDate());
                    $vinculo->setSequencialServidor((int) $servidorProcesso->getSequencial());
                    $vinculo->setServidorProcesso($servidorProcesso);

                    $vinculoService = new VinculoService();

                    $vinculo = $vinculoService->salvar($vinculo);

                    if ($vinculo->getRegimeTrabalhista() == 1) {
                        $duracaoRepository = new DuracaoRepository();
                        $duracaoExiste = $duracaoRepository
                            ->scopeSequencialVinculo($vinculo->getSequencial())
                            ->get();
                        $duracao = new Duracao();
                        if(!empty($duracaoExiste)) {
                            $duracao->setSequencial($duracaoExiste[0]->getSequencial());
                        }
                        $duracao->setTipoContrato($contrato->getTipoContrato());
                        $duracao->setSequencialProcessoVinculo($vinculo->getSequencial());
                        $contratoEmergencial = new ContratoEmergencial($matricula);
                        $duracao->setDataTerminoContrato($contratoEmergencial->getDataFim());
                        $duracao->setClausulaAssecuratoria($contratoEmergencial->getAsseCuratoria());
                        $duracao->setTipoRegimeTrabalho($vinculo->getRegimeTrabalhista());
  
                        $admissao = new Admissao($matricula);
                        $duracao->setObjetoDeterminante($admissao->getJustificativaLegal());

                        $duracaoService = new DuracaoService();

                        $registroDuracao = $duracaoService->salvar($duracao);
                    }

                    $desligamentoRepository = new DesligamentoRepository();
                    $desligamentoExiste = $desligamentoRepository
                        ->scopeSequencialVinculo($vinculo->getSequencial())
                        ->get();
                    $desligamento = new Desligamento();
                    if(!empty($desligamentoExiste)) {
                        $desligamento->setSequencial($desligamentoExiste[0]->getSequencial());
                    }
                    $desligamento->setDataDesligamento($servidorProcesso->getDataDemissao());
                    if (!empty($duracao)){
                        $desligamento->setTipoRegimeTrabalho($duracao->getTipoRegimeTrabalho());
                    }
                    $desligamento->setMatriculaServidor($matricula);
                    $desligamento->setNomeServidor($servidorProcesso->getNomeServidor());
                    $dataSetencaoAcordo = 
                        !empty($processo->getDataSentenca()) ?
                        $processo->getDataSentenca() : $processo->getDataCelebracaoAcordo();
                    $desligamento->setDataSentencaAcordo($dataSetencaoAcordo);
                    $codigoMotivoDesligamento = str_pad(
                        $servidor->getDadosRescisao()->r59_motivoesocial,
                        2,
                        '0',
                        STR_PAD_LEFT
                    );
                    $desligamento->setMotivoDesligamento($codigoMotivoDesligamento);

                    if ($servidor->getDadosRescisao()->rh05_recis >=
                        $servidorProcesso->getDataDemissao()) {
                            $desligamento->setDataFimAvisoPrevioIdenizado($servidor->getDadosRescisao()->rh05_recis);
                    }
                    $competencia = new DBCompetencia(
                        $servidor->getAnoCompetencia(),
                        $servidor->getMesCompetencia()
                    );

                    $rubricaPensaoAlimenticia[] = PagamentosRendimentosTrabalho::buscarParametroRubricaPensaoAlimenticia(
                        $competencia
                    );
                    if (!empty($rubricaPensaoAlimenticia)) {
                        $eventosRecisao = $servidor
                            ->getCalculoFinanceiro(CalculoFolha::CALCULO_RESCISAO)
                            ->getEventosFinanceiros();
                        foreach ($eventosRecisao as $evento) {
                            if (in_array($evento->getRubrica()->getCodigo(), $rubricaPensaoAlimenticia)) {
                                $desligamento->setPensaoAlimenticia(2);
                                $desligamento->setValorPensao($evento->getValor());
                            }
                        }
                    }
                    $desligamento->setSequencialProcessoVinculo($vinculo->getSequencial());

                    $desligamentoService = new DesligamentoService();

                    $registroDesligamento = $desligamentoService->salvar($desligamento);

                }

                $sequencialExcluidosMudanca = explode(',',$parametros->sequencialMudancaCategoriaExcluir);

                foreach ($sequencialExcluidosMudanca as $sequencialExcluido) {
                    if ((int) $sequencialExcluido > 0) {
                        $mudancaRepository = new MudancaRepository();
                        $mudancaExcluido =  $mudancaRepository
                            ->scopeSequencial($sequencialExcluido)
                            ->get();
                        if (!empty($mudancaExcluido)) {
                            $mudancaService = new MudancaService($mudancaExcluido[0]);
                            $mudancaService->excluir($mudancaExcluido[0]);
                        }
                    }
                }

                $mudancas = json_decode($parametros->lancamentoMudancaCategoria, true);
                if(!empty($mudancas)) {
                    foreach ($mudancas as $itemMudanca) {
                        $mudancaRepository = new MudancaRepository();
                        $mudancaExiste = $mudancaRepository
                            ->scopeSequencialContrato($contrato->getSequencial())
                            ->scopeCodigoCategoria($itemMudanca['codigoCategoriaMudanca'])
                            ->get();
                        $mudanca = new Mudanca();
                        $mudanca->setSequencial($itemMudanca['sequencial']);
                        if(!empty($mudancaExiste)) {
                            $mudanca->setSequencial($mudancaExiste[0]->getSequencial());
                        }
                        $mudanca->setSequencialProcessoContrato((int) $contrato->getSequencial());
                        $mudanca->setCodigoCategoria($itemMudanca['codigoCategoriaMudanca']);
                        $mudanca->setNaturezaAtividade($itemMudanca['naturezaAtividadeMudanca']);
                        $mudanca->setDataMudancaCategoria($itemMudanca['dataReconhecidoMudanca']);

                        $mudancaService = new MudancaService($mudanca);

                        $mudancaService->salvar($mudanca);
                    }
                }

                $sequencialExcluidosAnoAbono = explode(',',$parametros->sequencialAnoAbonoExcluir);

                foreach ($sequencialExcluidosAnoAbono as $sequencialExcluido) {
                    if ((int) $sequencialExcluido > 0) {
                        $abonoRepository = new AbonoRepository();
                        $abonoExcluido =  $abonoRepository
                            ->scopeSequencial($sequencialExcluido)
                            ->get();
                        if (!empty($abonoExcluido)) {
                            $abonoService = new AbonoService($abonoExcluido[0]);
                            $abonoService->excluir($abonoExcluido[0]);
                        }
                    }
                }

                $anoAbonos = json_decode($parametros->lancamentoAnoAbono, true);

                if(!empty($anoAbonos)) {
                    foreach ($anoAbonos as $itemAnoAbono) {
                        $abonoRepository = new AbonoRepository();
                        $abonoExiste = $abonoRepository
                            ->scopeSequencialContrato($contrato->getSequencial())
                            ->scopeAnoAbono($itemAnoAbono['anoAbono'])
                            ->get();
                        $anoAbono = new Abono();
                        $anoAbono->setSequencial($itemAnoAbono['sequencial']);
                        if(!empty($abonoExiste)) {
                            $anoAbono->setSequencial($abonoExiste[0]->getSequencial());
                        }
                        $anoAbono->setSequencial($itemAnoAbono['sequencial']);
                        $anoAbono->setSequencialProcessoContrato((int) $contrato->getSequencial());
                        $anoAbono->setAnoAbono($itemAnoAbono['anoAbono']);
                        $anoAbonoService = new AbonoService($anoAbono);
                        $anoAbonoService->salvar($anoAbono);
                    }
                }

                $sequencialExcluidosUnicidade = explode(',',$parametros->sequencialUnicidadeExcluir);

                foreach ($sequencialExcluidosUnicidade as $sequencialExcluido) {
                    if ((int) $sequencialExcluido > 0) {
                        $unicidadeRepository = new UnicidadeRepository();
                        $unicidadeExcluido =  $unicidadeRepository
                            ->scopeSequencial($sequencialExcluido)
                            ->get();
                        if (!empty($unicidadeExcluido)) {
                            $unicidadeService = new UnicidadeService($unicidadeExcluido[0]);
                            $unicidadeService->excluir($unicidadeExcluido[0]);
                        }
                    }
                }

                $unicidades = json_decode($parametros->lancamentoUnicidade, true);
                if(!empty($unicidades)) {
                    foreach ($unicidades as $itemUnicidade) {
                        $unicidadeRepository = new UnicidadeRepository();
                        $unicidadeExiste = $unicidadeRepository
                            ->scopeSequencialContrato((int) $contrato->getSequencial())
                            ->scopeMatricula($itemUnicidade['matriculaUnicidade'])
                            ->get();
                        $unicidade = new Unicidade();
                        $unicidade->setSequencial($itemUnicidade['sequencialUnicidade']);
                        if(!empty($duracaoExiste)) {
                            $unicidade->setSequencial($unicidadeExiste[0]->getSequencial());
                        }
                        $unicidade->setSequencial($itemUnicidade['sequencialUnicidade']);
                        $unicidade->setCodigoCategoriaUnicidade($itemUnicidade['codigoCategoriaUnicidade']);
                        $unicidade->setMatriculaUnicidade($itemUnicidade['matriculaUnicidade']);
                        $unicidade->setDataInicioUnicidade($itemUnicidade['dataInicioUnicidade']);
                        $unicidade->setSequencialProcessoContrato((int) $contrato->getSequencial());
    
                        $unicidadeService = new UnicidadeService($unicidade);
                        $unicidadeService->salvar($unicidade);
                    }
                }

                $periodos = json_decode($parametros->lancamentoPrevidenciario, true);

                if(!empty($periodos)) {
                    foreach ($periodos as $itemPeriodo) {
                        $periodoRepository = new PeriodoRepository();
                        $periodoExiste = $periodoRepository
                            ->scopeSequencialContrato((int) $contrato->getSequencial())
                            ->scopePeriodoReferencia($itemPeriodo['periodoApuracao'])
                            ->get();
                        $periodo = new Periodo();
                        $periodo->setSequencial($itemPeriodo['sequencial']);
                        if(!empty($periodoExiste)) {
                            $periodo->setSequencial($periodoExiste[0]->getSequencial());
                        }

                        $periodo->setPeriodo($itemPeriodo['periodoApuracao']);
                        $periodo->setValorBasePrevidenciaMensal((float) $itemPeriodo['mensalContribuicao']);
                        $periodo->setValorBasePrevidenciaMensal13((float) $itemPeriodo['contribuicao13']);
                        $periodo->setGrauExposicao((int) $itemPeriodo['grauExposicao']);
                        $periodo->setSequencialProcessoContrato((int) $contrato->getSequencial());
                        $periodo->setCodigoCategoria($itemPeriodo['codigoMudancaoCategoria']);
                        $periodo->setValorFinsPrevidenciarios($itemPeriodo['valorBaseMudancaoCategoria']);
                        $peridoService = new PeriodoService($periodo);
                        $peridoService->salvar($periodo);
                    }
                }

                $fgts = json_decode($parametros->lancamentoFGTS, true);
                if(!empty($fgts)) {
                    foreach ($fgts as $itemFgts) {
                        $periodoRepository = new PeriodoRepository();
                        $periodoExiste = $periodoRepository
                            ->scopeSequencialContrato((int) $contrato->getSequencial())
                            ->scopePeriodoReferencia($itemFgts['periodoApuracao'])
                            ->get();
                        if (is_null($periodo)) {
                            $periodo = new Periodo();
                        }
                        $periodo->setSequencial(0);
                        if(!empty($periodoExiste)) {
                            $periodo->setSequencial($periodoExiste[0]->getSequencial());
                            $valorBasePrevidenciaMensal = (float) $periodoExiste[0]->getValorBasePrevidenciaMensal();
                            $periodo->setValorBasePrevidenciaMensal((float) $valorBasePrevidenciaMensal);
                            $valorBasePrevidenciaMensal13 = 
                                (float) $periodoExiste[0]->getValorBasePrevidenciaMensal13();
                            $periodo->setValorBasePrevidenciaMensal13((float) $valorBasePrevidenciaMensal13);
                            $periodo->setGrauExposicao((int) $periodoExiste[0]->getGrauExposicao());
                            $periodo->setSequencialProcessoContrato((int) $contrato->getSequencial());
                            $periodo->setCodigoCategoria($periodoExiste[0]->getCodigoCategoria());
                            $valorFinsPrevidenciarios = $periodoExiste[0]->getValorFinsPrevidenciarios();
                            $periodo->setValorFinsPrevidenciarios($valorFinsPrevidenciarios);
                        }
                        $periodo->setSequencialProcessoContrato((int) $contrato->getSequencial());
                        $periodo->setPeriodo($itemFgts['periodoApuracao']);
                        $periodo->setValorBaseFGTSProcesso((double) $itemFgts['valorFGTSSemSEFIP']);
                        $periodo->setValorBaseFGTSSefip((double) $itemFgts['valorFGTSComSEFIP']);
                        $periodo->setValorBaseFGTSDeclaradaAnteriormente($itemFgts['valorFGTSAnterior']);
                        $peridoService = new PeriodoService($periodo);
                        $peridoService->salvar($periodo);
                    }
                }
            }

            $retorno->mensagem = "Vinculação de processo realizada com sucesso.";
            if ($retorno->erro) {
                $retorno->mensagem = "Atenção! Nenhum funcionário vinculado ao processo.";
            }

            break;
        case 'listaServidoresVinculados':
            $servidorVinculoService = new ServidorServiceProcesso($parametros->sequencialProcesso);
            $listaVinculoProcesso = $servidorVinculoService->listaServidorProcesso();
            $retorno->dados = $listaVinculoProcesso;
            break;
        case 'excluirVinculo':
            $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
            $servidorRepositoryProcesso->setServidorVinculadoExcluir($parametros->sequencialVinculo,
                $parametros->sequencialProcesso,
                $parametros->matriculaMenssagem
            );
            $retorno->mensagem = "Exclusão do servidor vinculado <strong>" . 
                $parametros->matriculaMenssagem . "-" . $parametros->nomeMenssagem .
                "</strong> realizada com sucesso.";
            if ($retorno->erro) {
                $retorno->mensagem = "Atenção! Servidor vinculado  <strong>" . 
                $parametros->matriculaMenssagem . "-" . $parametros->nomeMenssagem .
                "</strong> não excluído. Favor revisar.";
            }
            $servidorVinculoService = new ServidorServiceProcesso($parametros->sequencialProcesso);
            $listaVinculoProcesso = $servidorVinculoService->listaServidorProcesso();
            $retorno->dados = $listaVinculoProcesso;
	        break;
        case 'editarVinculo':
            $dados = new stdClass();
            $where = 'rh273_sequencialprocessoservidor = ' . $parametros->sequencial;
            $contrato = ContratoRepository::find(null,['*'], null, $where);
            if ($contrato) {
                $contratoJson = JSON::create()->parse($contrato->serialize());
                foreach ($contratoJson as $chave => $valor) {
                    if ($chave == 'sequencial') {
                        $chave = 'sequencialContrato';
                    }
                    $dados->{$chave} = $valor;
                }
            }

            $mudancaRepository = new MudancaRepository();
            $lancamentos = $mudancaRepository
                ->scopeSequencialContrato($contrato->getSequencial())
                ->get();
            $mudanca =[];
            foreach ($lancamentos as $objetoMudanca) {
                $propriedadeMudanca = new stdClass;
                $propriedadeMudanca->sequencial = $objetoMudanca->getSequencial();
                $propriedadeMudanca->sequencialProcessoContrato  = $objetoMudanca->getSequencialProcessoContrato();
                $propriedadeMudanca->codigoCategoriaMudanca  = $objetoMudanca->getCodigoCategoria();
                $propriedadeMudanca->naturezaAtividadeMudanca  = $objetoMudanca->getNaturezaAtividade();
                $propriedadeMudanca->dataReconhecidoMudanca  = $objetoMudanca->getDataMudancaCategoria();

                $mudanca[]=  $propriedadeMudanca;
            }

            $dados->lancamentoMudanca = $mudanca;

            $anoAbonoRepository = new AbonoRepository();
            $lancamentos = $anoAbonoRepository
                ->scopeSequencialContrato($contrato->getSequencial())
                ->get();
            $anoAbono =[];
            foreach ($lancamentos as $objetoAnoAbono) {
                $propriedadeAnoAbono = new stdClass;
                $propriedadeAnoAbono->sequencial = $objetoAnoAbono->getSequencial();
                $propriedadeAnoAbono->sequencialContrato  = $contrato->getSequencial();
                $propriedadeAnoAbono->anoAbono  = $objetoAnoAbono->getAnoAbono();
                $anoAbono[]=  $propriedadeAnoAbono;
            }

            $dados->lancamentoAnoAbono =  $anoAbono;

            $unicidadeRepository = new UnicidadeRepository();
            $lancamentos = $unicidadeRepository
                ->scopeSequencialContrato($contrato->getSequencial())
                ->get();
            $unicidade =[];
            foreach ($lancamentos as $objetoUnicidade) {
                $propriedadeUnicidade = new stdClass;
                $propriedadeUnicidade->sequencial = $objetoUnicidade->getSequencial();
                $propriedadeUnicidade->sequencialContrato  = $contrato->getSequencial();
                $propriedadeUnicidade->matriculaUnicidade  = $objetoUnicidade->getMatriculaUnicidade();
                $propriedadeUnicidade->codigoCategoriaUnicidade  = $objetoUnicidade->getCodigoCategoriaUnicidade();
                $propriedadeUnicidade->dataInicioUnicidade  = $objetoUnicidade->getDataInicioUnicidade();
                $unicidade[]=  $propriedadeUnicidade;
            }

            $dados->lancamentoUnicidade =  $unicidade;

            $periodoRepository = new PeriodoRepository();
            $lancamentos = $periodoRepository
                ->scopeSequencialContrato($contrato->getSequencial())
                ->get();

            $previdenciario = [];
            $fgts = [];
            foreach ($lancamentos as $objetoPeriodo) {

                $periodoPrevidenciario = new stdClass;
                $periodoPrevidenciario->sequencial = $objetoPeriodo->getSequencial();
                $periodoPrevidenciario->sequencialContrato = $objetoPeriodo->getSequencialProcessoContrato();
                $periodoPrevidenciario->periodo  = $objetoPeriodo->getPeriodo();
                $periodoPrevidenciario->valorBasePrevidenciaMensal  = $objetoPeriodo->getValorBasePrevidenciaMensal();
                $periodoPrevidenciario->valorBasePrevidenciaMensal13  = $objetoPeriodo->getValorBasePrevidenciaMensal13();
                $periodoPrevidenciario->grauExposicao  = $objetoPeriodo->getGrauExposicao();
                $periodoPrevidenciario->valorBaseMudancaoCategoria = $objetoPeriodo->getValorFinsPrevidenciarios();
                $periodoPrevidenciario->codigoMudancaoCategoria = $objetoPeriodo->getCodigoCategoria();
                $previdenciario[]=  $periodoPrevidenciario;

                $periodoFGTS = new stdClass;
                $periodoFGTS->sequencial = $objetoPeriodo->getSequencial();
                $periodoFGTS->sequencialContrato = $objetoPeriodo->getSequencialProcessoContrato();
                $periodoFGTS->periodo  = $objetoPeriodo->getPeriodo();
                $periodoFGTS->valorBaseFGTSProcesso = $objetoPeriodo->getValorBaseFGTSProcesso();
                $periodoFGTS->valorBaseFGTSSefip = $objetoPeriodo->getValorBaseFGTSSefip();
                $periodoFGTS->valorBaseFGTSDeclaradaAnteriormente = 
                    $objetoPeriodo->getValorBaseFGTSDeclaradaAnteriormente();
                $fgts[] = $periodoFGTS;
            }

            $dados->lancamentoPrevidenciario =  $previdenciario;
            $dados->lancamentoFGTS =  $fgts;

            $retorno->dados = $dados;
            break;
        case 'buscarProcessosMatricula':
            $dados = new stdClass();
            $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
            $servidorRepositoryProcesso
                ->resetScopes();
            $processosServidor = $servidorRepositoryProcesso
                ->scopeMatricula($parametros->codigoMatricula)
                ->get();
            $processoJudicial = [];
            $dadoProcessoJudicial = [];
            $dadosProcessos = [];
            $dados = new stdClass();
            foreach ($processosServidor as $processoServidor) {
                $processoJudicalRepository = new ProcessoJudicialRepository();
                $processoJudicalRepository->resetScopes();
                $processoJudicial = $processoJudicalRepository
                    ->scopeSequencial($processoServidor->getSequencialProcesso())
                    ->get();

                $dadosProcesso = new stdClass();
                foreach ($processoJudicial as $indice => $dadoProcesso) {
                    $dataSentenca = implode("/",array_reverse(explode("-",$dadoProcesso->getDataSentenca())));
                    $dataAcordo = implode("/",array_reverse(explode("-",$dadoProcesso->getDataCelebracaoAcordo())));
                    $dataDecisao = !empty($dataSentenca) ? $dataSentenca : $dataAcordo;
                    $dadosProcesso->sequencialProcesso = $dadoProcesso->getSequencial();
                    $dadosProcesso->numeroProcesso = $dadoProcesso->getNumeroProcesso() . ' - ' .
                        'Data decisão: ' . $dataDecisao;
                    $dadosProcessos[] = $dadosProcesso;
                }
            }

            $retorno->dados = $dadosProcessos;
            break;
        case 'buscarProcessosExclusao':
            $dados = new stdClass();
            if (empty($parametros->codigoMatricula)) {
                $numeroProcessoNaoValido = true;
                switch (strlen($parametros->porNumeroProcesso)) {
                    case 15:
                        $numeroProcessoNaoValido = false;
                    case 20:
                        $numeroProcessoNaoValido = false;
                        break;
                    default:
                        $numeroProcessoNaoValido = true;
                }
                if ($numeroProcessoNaoValido) {
                    $retorno->mensagem = "O número do processo dever ser 15(quinze) ou 20(vinte) algarismos.";
                    $retorno->erro = true;
                    break;
                }
                $processoRepository = new ProcessoJudicialRepository;
                $processoRepository->resetScopes();
                $processo = $processoRepository
                    ->scopeNumeroProcesso($parametros->porNumeroProcesso)
                    ->get();
                if (empty($processo)) {
                    $retorno->mensagem = "O número do processo <strong>{$parametros->porNumeroProcesso} </strong> não foi encontrado. Favor revisar.";
                    $retorno->erro = true;
                    break;
                }
                $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
                $servidorRepositoryProcesso
                    ->resetScopes();

                $processosServidor = $servidorRepositoryProcesso
                    ->scopeSequencialProcesso($processo[0]->getSequencial())
                    ->get();

            } else {
                $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
                $servidorRepositoryProcesso
                    ->resetScopes();
                $processosServidor = $servidorRepositoryProcesso
                    ->scopeMatricula($parametros->codigoMatricula)
                    ->get();
                if (empty($processosServidor)) {
                    $servidor = \ServidorRepository::getInstanciaByCodigo($parametros->codigoMatricula);
                    $nomeServidor = $servidor->getCgm()->getNomeCompleto();
                    $retorno->mensagem = "Não foi encontrado processo para o servidor <strong>{$parametros->codigoMatricula} - $nomeServidor </strong>. Favor revisar.";
                    $retorno->erro = true;
                    break;
                }
            }
            $processoJudicial = [];
            $dadoProcessoJudicial = [];
            $dadosProcessos = [];
            $dadosProcessosTributo = [];

            $dadosEmpregador = CgmFactory::getInstanceByCgm($parametros->empregador);
            $cnpjEmpregador = $dadosEmpregador->getCnpj();
            $dados = new stdClass();
            foreach ($processosServidor as $processoServidor) {
                $servidor = \ServidorRepository::getInstanciaByCodigo($processoServidor->getMatricula());
                $cpfServidor = $servidor->getCgm()->getCpf();
                $nomeServidor = $servidor->getCgm()->getNomeCompleto();
                $processoJudicalRepository = new ProcessoJudicialRepository();
                $processoJudicalRepository->resetScopes();
                $processoJudicial = $processoJudicalRepository
                    ->scopeSequencial($processoServidor->getSequencialProcesso())
                    ->get();
                $dadosProcesso = new stdClass();
                foreach ($processoJudicial as $indice => $dadoProcesso) {
                    $dataSentenca = implode("/",array_reverse(explode("-",$dadoProcesso->getDataSentenca())));
                    $dataAcordo = implode("/",array_reverse(explode("-",$dadoProcesso->getDataCelebracaoAcordo())));
                    $dataDecisao = !empty($dataSentenca) ? $dataSentenca : $dataAcordo;
                    $dadosProcesso->sequencialProcesso = $dadoProcesso->getSequencial();
                    $dadosProcesso->numeroProcesso = $dadoProcesso->getNumeroProcesso() . ' - ' .
                        'Data decisão: ' . $dataDecisao;
                    $dadosProcesso->nome = $nomeServidor;
                    $dadosProcesso->cpf = $cpfServidor;
                    $dadosProcesso->matricula = $processoServidor->getMatricula();
                    $dadosProcesso->tpEvento = 'S-2500';
                    $dadosProcesso->nrProcTrab = $dadoProcesso->getNumeroProcesso();
                    $dadosProcesso->sequencialServidor = $processoServidor->getSequencial();
                    $dadosProcesso->referencia = $processoServidor->getMatricula() . "-" . $dadoProcesso->getNumeroProcesso();
                    // $dadosProcesso->identificacao
                    $dadosEsocial = new ESocial(Registry::get('app.config'), Recurso::CONSULTA_RECIBO);
                    $parametroRecibo = new stdClass();
                    $parametroRecibo->idEvento = "S-2500";
                    $parametroRecibo->tipoEvento = "2";
                    $parametroRecibo->idReferencia = $dadosProcesso->referencia;
                    $parametroRecibo->inscricaoEmpregador = $cnpjEmpregador;
                    $dadosEsocial->setDados($parametroRecibo);
                    $dadosEnviados = $dadosEsocial->request("GET");

                    foreach ($dadosEnviados[0]->recibo as $recibo) {
                        if($recibo->ultimoRecibo) {
                            $dadosProcesso->nrRecEvt = $recibo->numero;
                            $tributoBaseRepository = new TributoBaseRepository();
                            $tributoBaseRepository
                                ->resetScopes();
                            $tributoBase = $tributoBaseRepository
                                ->scopeSequencialServidor($processoServidor->getSequencial())
                                ->get();
                            $dadosProcessoTributo = new stdClass();
                            foreach ($tributoBase as $tributo) {
                                $parametroRecibo = new stdClass();
                                $parametroRecibo->idEvento = "S-2501";
                                $parametroRecibo->tipoEvento = "2";
                                $parametroRecibo->idReferencia =
                                    $cpfServidor . '-' .
                                    str_replace('-','',$tributo->getPagamento()) . '-' .
                                    str_replace('-','',$tributo->getCompetencia());
                                $parametroRecibo->inscricaoEmpregador = $cnpjEmpregador;

                                $dadosEsocial->setDados($parametroRecibo);
                                $dadosEnviadosTributos = $dadosEsocial->request("GET");
                                if (!empty($dadosEnviadosTributos)) {
                                    foreach ($dadosEnviadosTributos[0]->recibo as $reciboTributo) {
                                        $dadosTributo = new stdClass();
                                        $dadosTributo->sequencialTributo = $tributo->getSequencial();
                                        $dadosTributo->sequencialServidor = $tributo->getSequencialProcessoServidor();
                                        $dadosTributo->nrProcTrab = $dadoProcesso->getNumeroProcesso();
                                        $dadosTributo->tpEvento = 'S-2501';
                                        $dadosTributo->nrRecEvt = $reciboTributo->numero;
                                        $dadosTributo->perApurPgto = $tributo->getPagamento();
                                        $dadosTributo->referencia = $parametroRecibo->idReferencia;
    
                                        if (!empty($dadosTributo->nrRecEvt) && $reciboTributo->ultimoRecibo) {
                                            $dadosProcessosTributo[] = $dadosTributo;
                                        }
                                    }
                                }

                            }
                        }
                    }

                    if (!empty($dadosProcesso->nrRecEvt)) {
                        $dadosProcessos[] = $dadosProcesso;
                    }
                }
            }
            $retorno->dados = new stdClass();
            $retorno->dados->processos = $dadosProcessos;
            $retorno->dados->tributos = $dadosProcessosTributo;
 
            break;
        case 'buscarDadosProcesso':
            $dadosProcesso = new stdClass();
            $processoJudicalRepository = new ProcessoJudicialRepository();
            $processoJudicalRepository->resetScopes();
            $processoJudicial = $processoJudicalRepository
                    ->scopeSequencial($parametros->sequencialProcesso)
                    ->get();
            $dadosProcesso->numeroProcesso = $processoJudicial[0]->getNumeroProcesso();
            $dadosProcesso->dataSentenca = $processoJudicial[0]->getDataSentenca();
            $dadosProcesso->dataAcordo = $processoJudicial[0]->getDataCelebracaoAcordo();

            $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
            $servidorRepositoryProcesso
                ->resetScopes();
            $processosServidor = $servidorRepositoryProcesso
                ->scopeSequencialProcesso($parametros->sequencialProcesso)
                ->scopeMatricula($parametros->matricula)
                ->get();

            $servidor = \ServidorRepository::getInstanciaByCodigo($parametros->matricula);
            $dadosProcesso->cpf = $servidor->getCgm()->getCpf();
            $dadosProcesso->matricula = $parametros->matricula;
            $dadosProcesso->sequencialProcessoServidor = $processosServidor[0]->getSequencial();

            $contratoRepository = New ContratoRepository();
            $contratoRepository->resetScopes();
            $contrato = $contratoRepository
                ->scopeSequencialServidor($processosServidor[0]->getSequencial())
                ->get();
            $periodoRepository = new PeriodoRepository();
            $periodoRepository->resetScopes();
            $lancamentos = $periodoRepository
                ->scopeSequencialContrato($contrato[0]->getSequencial())
                ->get();
            $dadosLancamento = new stdClass();
            $listaLancamentos = [];
            foreach ($lancamentos as $lancamento) {
                $listaLancamentos[] = $lancamento->getPeriodo();
            }
            $dadosProcesso->lancamentos = $listaLancamentos;
            $retorno->dados = $dadosProcesso;
            break;
        case 'salvarTributos':

            $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
            $servidorRepositoryProcesso
                ->resetScopes();
            $processosServidor = $servidorRepositoryProcesso
                ->scopeSequencialProcesso($parametros->sequencialNumeroProcesso)
                ->scopeMatricula($parametros->codigoMatricula)
                ->get();
            if ($processosServidor[0]->getSequencial() <= 0) {
                throw new Exception("Não existe servidor vinculado ao processo. Favor revisar.");
            }

            $servidorProcesso = $processosServidor[0]->getSequencial();
            $tributoBase = new TributoBase();

            $lancamentoTributosPagamento = json_decode($parametros->lancamentosTributosPagamento);

            if (empty($lancamentoTributosPagamento) && empty($parametros->sequencialBaseExcluir)) {
                throw new Exception("Não há registros de <strong>Identifição do período e da base de cálculo dos tributos</strong> lançados. Favor revisar.");
            }

            $lancamentoTributosPrevidencial = json_decode($parametros->lancamentosTributosPrevidencial);
            
            $sequencialBaseExcluidos = explode(',',$parametros->sequencialBaseExcluir);

            foreach ($lancamentoTributosPagamento as $lancamento) {

                $tributoBaseRepository = new TributoBaseRepository();
                $tributoBaseRepository
                    ->resetScopes();
                $tributoBaseExiste = $tributoBaseRepository
                    ->scopeSequencial($lancamento->sequencialBaseCalculo)
                    ->get();
                $sequencialBaseCalculo = 0;
                if (!empty($tributoBaseExiste)) {
                    $sequencialBaseCalculo = $tributoBaseExiste[0]->getSequencial();
                }

                if ($lancamento->sequencialBaseCalculo > 0 ) {
                    if (in_array($lancamento->sequencialBaseCalculo, $sequencialBaseExcluidos)) {
                        continue;
                    }
                }

                $tributoBase->setSequencial($sequencialBaseCalculo);
                $tributoBase->setSequencialProcessoServidor($servidorProcesso);
                $tributoBase->setCompetencia($lancamento->periodoContemplado);
                $tributoBase->setPagamento($lancamento->periodoPagamento);
                $tributoBase->setObservacao($parametros->observacao);
                $tributoBase->setValorBaseMensal($lancamento->mensalContribuicao);
                $tributoBase->setValorBaseMensal13($lancamento->contribuicao13);
                $tributoBase->setObservacao($lancamento->observacao);

                $tributoBaseService = new TributoBaseService($tributoBase);
                $tributoBaseService->salvar($tributoBase);

                //Ðefinir sequencial da base de calculo para ser usado em tributação
                if (!empty($lancamentoTributosPrevidencial)) {
                    foreach ($lancamentoTributosPrevidencial as $indice => $lancamento) {
                        if ($lancamentoTributosPrevidencial[$indice]->idContempladoPagamento == 
                            str_replace("-","",$tributoBase->getPagamento()) . 
                            str_replace("-","",$tributoBase->getCompetencia())) {
                                $lancamentoTributosPrevidencial[$indice]->sequencialBaseCalculo =
                                    $tributoBase->getSequencial();
                        }
                    }
                }
            }
            //Início registro(s) excluído(s) em tela e salvo na base de dados
            $sequencialPrevidenciaExcluidos = explode(',',$parametros->sequencialPrevidenciaExcluir);

            foreach ($sequencialPrevidenciaExcluidos as $sequencialPrevidenciaExcluido) {
                if ((int) $sequencialPrevidenciaExcluido > 0) {
                    $tributoContribuicaoRepository = new TributoContribuicaoRepository();
                    $tributoContribuicaoRepository
                        ->resetScopes();
                    $tributoContribuicao =  $tributoContribuicaoRepository
                        ->scopeSequencial($sequencialPrevidenciaExcluido)
                        ->get();
                    if (!empty($tributoContribuicao)) {
                        $tributoContribuicaoService = new TributoContribuicaoService();
                        $tributoContribuicaoService->excluir($tributoContribuicao[0]); 
                    }
                }
            }

            foreach ($sequencialBaseExcluidos as $sequencialBaseExcluido) {
                if ((int) $sequencialBaseExcluido > 0) {
                    $tributoBaseRepository = new TributoBaseRepository();
                    $tributoBaseRepository
                        ->resetScopes();
                    $tributoBase =  $tributoBaseRepository
                        ->scopeSequencial($sequencialBaseExcluido)
                        ->get();
                    if (!empty($tributoBase)) {
                        $tributoBaseService = new TributoBaseService($tributoBase);
                        $tributoBaseService->excluir($tributoBase[0]);
                    }
                }
            }

            $sequencialExcluidosIRRF = explode(',',$parametros->sequencialExcluirIRRF);

            foreach ($sequencialExcluidosIRRF as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $advogadoRepository = new AdvogadoRepository();
                    $advogadoRepository
                        ->resetScopes();
                    $advogados =  $advogadoRepository
                        ->scopeSequencialTributoIRRF($sequencialExcluido)
                        ->get();

                    foreach ($advogados as $advogado) {
                        if (!empty($advogado)) {
                            $advogadoService = new AdvogadoService();
                            $advogadoService->excluir($advogado);
                        }
                    }

                    $dependenteRepository = new DependenteRepository();
                    $dependenteRepository
                        ->resetScopes();
                    $dependentes = $dependenteRepository
                        ->scopeSequencialTributoIRRF($sequencialExcluido)
                        ->get();
                    foreach ($dependentes as $dependente) {
                        if (!empty($dependente)) {
                            $dependenteService = new DependenteService();
                            $dependenteService->excluir($dependente);
                        }
                    }

                    $pensaoRepository = new PensaoRepository();
                    $pensaoRepository
                        ->resetScopes();
                    $pensoes = $pensaoRepository
                        ->scopeSequencialTributoIRRF($sequencialExcluido)
                        ->get();
                    foreach ($pensoes as $pensao) {
                        if (!empty($pensao)) {
                            $pensaoService = new PensaoService();
                            $pensaoService->excluir($pensao);
                        }
                    }
                    $retencaoRepository = new RetencaoRepository();
                    $retencaoRepository
                        ->resetScopes();
                    $retencoes = $retencaoRepository
                        ->scopeSequencialTributoIRRF($sequencialExcluido)
                        ->get();
                    foreach ($retencoes as $retencao) {
                        $valorRetencaoRepository = new ValorRetencaoRepository();
                        $valorRetencaoRepository
                            ->resetScopes();
                        $valorRetencoes = $valorRetencaoRepository
                            ->scopeSequencialRetencao($retencao->getSequencial())
                            ->get();
                        if (!empty($valorRetencoes)) {
                            foreach ($valorRetencoes as $valorRetencao) {
                                $deducaoRetencaoRepository = new DeducaoSuspensaRepository();
                                $deducaoRetencaoRepository
                                    ->resetScopes();
                                $deducoes = $deducaoRetencaoRepository
                                    ->scopeSequencialValorRetencao($valorRetencao->getSequencial())
                                    ->get();
                                foreach ($deducoes as $deducao) {
                                    $suspensaPensaoRepository = new SuspensaoPensaoRepository();
                                    $suspensaPensaoRepository
                                        ->resetScopes();
                                    $suspenaPensoes = $suspensaPensaoRepository
                                        ->scopeSequencialDeducaoSuspensa($deducao->getSequencial())
                                        ->get();
                                    foreach ($suspenaPensoes as $suspenaPensao) {
                                        if (!empty($suspenaPensao)) {
                                            $suspenaPensaoService = new SuspensaoPensaoService();
                                            $suspenaPensaoService->excluir($suspenaPensao);
                                        }
                                    }
                                    if (!empty($deducao)) {
                                        $deducaoService = new DeducaoSuspensaService();
                                        $deducaoService->excluir($deducao);
                                    }
                                }
                                if (!empty($valorRetencao)) {
                                    $valorRetencaoService = new ValorRetencaoService();
                                    $valorRetencaoService->excluir($valorRetencao);
                                }
                            }
                        }
                        if (!empty($retencao)) {
                            $retencaoService = new RetencaoService();
                            $retencaoService->excluir($retencao);
                        }
                    }
                    $tributoIRRFRepository = new TributoIRRFRepository();
                    $tributoIRRFRepository
                        ->resetScopes();
                    $tributoIRRF =  $tributoIRRFRepository
                        ->scopeSequencial($sequencialExcluido)
                        ->get();
                    if (!empty($tributoIRRF)) {
                        $tributoIRRFService = new TributoIRRFService();
                        $tributoIRRFService->excluir($tributoIRRF[0]);
                    }
                }
            }

            $sequencialExcluidosCodigoIRRF = explode(',',$parametros->sequencialCodigoIRRFExcluir);
            $sequencialExcluidoCodigoIRRF = [];
            foreach ($sequencialExcluidosCodigoIRRF as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $tributoIRRFRepository = new TributoIRRFRepository();
                    $tributoIRRFRepository
                        ->resetScopes();
                    $tributosIRRF =  $tributoIRRFRepository
                        ->scopeSequencial($sequencialExcluido)
                        ->get();
                    if (!empty($tributosIRRF)) {
                        $tributoIRRF = $tributosIRRF[0];
                        $tributoIRRF->setValorRendimentoTributavel(0);
                        $tributoIRRF->setValorRendimentoTributavel13(0);
                        $tributoIRRF->setValorRendimentoMolestia(0);
                        $tributoIRRF->setValorIsenta65(0);
                        $tributoIRRF->setValorJurosMora(0);
                        $tributoIRRF->setValorRendimentoIsento(0);
                        $tributoIRRF->setDescricaoIsento('');
                        $tributoIRRF->setValorPrevidenciaOficial(0);
                        $tributoIRRF->setDescricaoRendimentoAcumula('');
                        $tributoIRRF->setQuantidadeMesAcumula(0);
                        $tributoIRRF->setValorDespesaCusta(0);
                        $tributoIRRF->setValorDespesaAdvogados(0);
                        $TributoIRRFService = new TributoIRRFService();
                        $tributoIRRF = $TributoIRRFService->salvar($tributoIRRF);
                        $sequencialExcluidoCodigoIRRF[] = $sequencialExcluido;
                    }
                }
            }

            $sequencialExcluidosAdvogado = explode(',',$parametros->sequencialAdvogadoExcluir);

            foreach ($sequencialExcluidosAdvogado as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $advogadoRepository = new AdvogadoRepository();
                    $advogadoRepository
                        ->resetScopes();
                    $advogado =  $advogadoRepository
                        ->scopeSequencial($sequencialExcluido)
                        ->get();
                    if (!empty($advogado)) {
                        $advogadoService = new AdvogadoService();
                        $advogadoService->excluir($advogado[0]);
                    }
                }
            }

            $sequencialExcluidosDependente = explode(',',$parametros->sequencialDependenteExcluir);

            foreach ($sequencialExcluidosDependente as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $dependenteRepository = new DependenteRepository();
                    $dependenteRepository
                        ->resetScopes();
                    $dependente = $dependenteRepository
                        ->scopeSequencial($sequencialExcluido)
                        ->get();
                    if (!empty($dependente)) {
                        $dependenteService = new DependenteService();
                        $dependenteService->excluir($dependente[0]);
                    }
                }
            }

            $sequencialExcluidosRetencao = explode(',',$parametros->sequencialRetencaoExcluir);

            foreach ($sequencialExcluidosRetencao as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $retencaoRepository = new RetencaoRepository();
                    $retencaoRepository
                        ->resetScopes();
                    $retencoes = $retencaoRepository
                        ->scopeSequencialTributoIRRF($sequencialExcluido)
                        ->get();
                    foreach ($retencoes as $retencao) {
                        $valorRetencaoRepository = new ValorRetencaoRepository();
                        $valorRetencaoRepository
                            ->resetScopes();
                        $valorRetencoes = $valorRetencaoRepository
                            ->scopeSequencialRetencao($retencao->getSequencial())
                            ->get();
                        if (!empty($valorRetencoes)) {
                            foreach ($valorRetencoes as $valorRetencao) {
                                $deducaoRetencaoRepository = new DeducaoSuspensaRepository();
                                $deducaoRetencaoRepository
                                    ->resetScopes();
                                $deducoes = $deducaoRetencaoRepository
                                    ->scopeSequencialValorRetencao($valorRetencao->getSequencial())
                                    ->get();
                                foreach ($deducoes as $deducao) {
                                    $suspensaPensaoRepository = new SuspensaoPensaoRepository();
                                    $suspensaPensaoRepository
                                        ->resetScopes();
                                    $suspenaPensoes = $suspensaPensaoRepository
                                        ->scopeSequencialDeducaoSuspensa($deducao->getSequencial())
                                        ->get();
                                    foreach ($suspenaPensoes as $suspenaPensao) {
                                        if (!empty($suspenaPensao)) {
                                            $suspenaPensaoService = new SuspensaoPensaoService();
                                            $suspenaPensaoService->excluir($suspenaPensao);
                                        }
                                    }
                                    if (!empty($deducao)) {
                                        $deducaoService = new DeducaoSuspensaService();
                                        $deducaoService->excluir($deducao);
                                    }
                                }
                                if (!empty($valorRetencao)) {
                                    $valorRetencaoService = new ValorRetencaoService();
                                    $valorRetencaoService->excluir($valorRetencao);
                                }
                            }
                        }
                        if (!empty($retencao)) {
                            $retencaoService = new RetencaoService();
                            $retencaoService->excluir($retencao);
                        }
                    }
                }
            }

            $sequencialExcluidosValorRetencao = explode(',',$parametros->sequencialValorRetencaoExcluir);

            foreach ($sequencialExcluidosValorRetencao as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $valorRetencaoRepository = new ValorRetencaoRepository();
                    $valorRetencaoRepository
                        ->resetScopes();
                    $valorRetencoes = $valorRetencaoRepository
                        ->scopeSequencialRetencao($sequencialExcluido)
                        ->get();
                    if (!empty($valorRetencoes)) {
                        foreach ($valorRetencoes as $valorRetencao) {
                            $deducaoRetencaoRepository = new DeducaoSuspensaRepository();
                            $deducaoRetencaoRepository
                                ->resetScopes();
                            $deducoes = $deducaoRetencaoRepository
                                ->scopeSequencialValorRetencao($valorRetencao->getSequencial())
                                ->get();
                            foreach ($deducoes as $deducao) {
                                $suspensaPensaoRepository = new SuspensaoPensaoRepository();
                                $suspensaPensaoRepository
                                    ->resetScopes();
                                $suspenaPensoes = $suspensaPensaoRepository
                                    ->scopeSequencialDeducaoSuspensa($deducao->getSequencial())
                                    ->get();
                                foreach ($suspenaPensoes as $suspenaPensao) {
                                    if (!empty($suspenaPensao)) {
                                        $suspenaPensaoService = new SuspensaoPensaoService();
                                        $suspenaPensaoService->excluir($suspenaPensao);
                                    }
                                }
                                if (!empty($deducao)) {
                                    $deducaoService = new DeducaoSuspensaService();
                                    $deducaoService->excluir($deducao);
                                }
                            }
                            if (!empty($valorRetencao)) {
                                $valorRetencaoService = new ValorRetencaoService();
                                $valorRetencaoService->excluir($valorRetencao);
                            }
                        }
                    }

                }
            }

            $sequencialExcluidosDeducaoSuspensa = explode(',',$parametros->sequencialValorDeducaoSuspensaExcluir);

            foreach ($sequencialExcluidosDeducaoSuspensa as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $deducaoRetencaoRepository = new DeducaoSuspensaRepository();
                    $deducaoRetencaoRepository
                        ->resetScopes();
                    $deducoes = $deducaoRetencaoRepository
                        ->scopeSequencialValorRetencao($sequencialExcluido)
                        ->get();
                    foreach ($deducoes as $deducao) {
                        $suspensaPensaoRepository = new SuspensaoPensaoRepository();
                        $suspensaPensaoRepository
                            ->resetScopes();
                        $suspenaPensoes = $suspensaPensaoRepository
                            ->scopeSequencialDeducaoSuspensa($deducao->getSequencial())
                            ->get();
                        foreach ($suspenaPensoes as $suspenaPensao) {
                            if (!empty($suspenaPensao)) {
                                $suspenaPensaoService = new SuspensaoPensaoService();
                                $suspenaPensaoService->excluir($suspenaPensao);
                            }
                        }
                        if (!empty($deducao)) {
                            $deducaoService = new DeducaoSuspensaService();
                            $deducaoService->excluir($deducao);
                        }
                    }
                }
            }

            $sequencialExcluidosValorSuspensaPensao = explode(',',$parametros->sequencialValorSuspensaPensaoExcluir);

            foreach ($sequencialExcluidosValorSuspensaPensao as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $deducaoSuspensaRepository = new SuspensaoPensaoRepository();
                    $deducaoSuspensaRepository
                        ->resetScopes();
                    $deducaoSuspensa = $deducaoSuspensaRepository
                        ->scopeSequencial($sequencialExcluido)
                        ->get();
                    if (!empty($deducaoSuspensa)) {
                        $deducaoSuspensaService = new SuspensaoPensaoService();
                        $deducaoSuspensaService->excluir($deducaoSuspensa[0]);
                    }
                }
            }

            $sequencialIRComplementarExcluir = explode(',',$parametros->sequencialIRComplementarExcluir);

            foreach ($sequencialIRComplementarExcluir as $sequencialExcluido) {
                if ((int) $sequencialExcluido > 0) {
                    $IRComplementarRepository = new TributoIRRFComplementarRepository();
                    $IRComplementarRepository
                        ->resetScopes();
                    $IRComplementar = $IRComplementarRepository
                        ->scopeSequencial($sequencialExcluido)
                        ->get();
                    if (!empty($IRComplementar)) {
                        $IRComplementarService = new TributoIRRFComplementarService();
                        $IRComplementarService->excluir($IRComplementar[0]);
                    }
                }
            }
            //Fim registro(s) excluído(s) em tela e salvo na base de dados

            $tributoContribuicao = new TributoContribuicao();
                if (!empty($lancamentoTributosPrevidencial)) {
                foreach ($lancamentoTributosPrevidencial as $lancamento) {
                    $tributoContribuicaoRepository = new TributoContribuicaoRepository();
                    $tributoContribuicaoRepository
                        ->resetScopes();
                    $tributoContribuicaoExiste = $tributoContribuicaoRepository
                        ->scopeBase((int) $lancamento->sequencialBaseCalculo, (int) $lancamento->codigoReceita)
                        ->get();
                    $sequencialPrevidencia = 
                        !empty($tributoContribuicaoExiste) ? $tributoContribuicaoExiste[0]->getSequencial() : null ;
                    if ($lancamento->sequencialCodigoReceita > 0) {
                        if (in_array($lancamento->sequencialCodigoReceita, $sequencialPrevidenciaExcluidos)) {
                            continue;
                        }
                    }

                    $tributoContribuicao->setSequencial($sequencialPrevidencia);
                    $tributoContribuicao->setCodigoReceita($lancamento->codigoReceita);
                    $tributoContribuicao->setSequencialTributoBase((int) $lancamento->sequencialBaseCalculo);
                    $tributoContribuicao->setValorContribuicao($lancamento->valorCodigoReceita);

                    $TributoContribuicaoService = new TributoContribuicaoService();
                    $TributoContribuicaoService->salvar($tributoContribuicao);  
                }
            }

            $lancamentosTributosIRRF = json_decode($parametros->lancamentosTributosIRRF);

            $tributoIRRF = new TributoIRRF();
            $sequencialTributoIRRF = [];
            if (!empty($lancamentosTributosIRRF)) {
                foreach ($lancamentosTributosIRRF as $lancamento) {
                    $tributoIRRFRepository = new TributoIRRFRepository();
                    $tributoIRRFRepository
                        ->resetScopes();
                    $tributoIRRFExiste = $tributoIRRFRepository
                        ->scopeSequencial($lancamento->sequencialIRRF)
                        ->get();
                    $sequencialIRRF = 
                        !empty($tributoIRRFExiste) ? $tributoIRRFExiste[0]->getSequencial() : null ;
                    if ($lancamento->sequencialIRRF > 0) {
                        if (in_array($lancamento->sequencialIRRF, $sequencialExcluidosIRRF)) {
                            continue;
                        }
                    }

                    $tributoIRRF->setSequencial($sequencialIRRF);
                    $tributoIRRF->setSequencialProcessoServidor($servidorProcesso);
                    $tributoIRRF->setPeriodoPagamento($lancamento->periodoPagamento);
                    $tributoIRRF->setCodigoReceita($lancamento->codigoIRRF);
                    $tributoIRRF->setValorIRRF($lancamento->valorIRRF);
                    
                    $TributoIRRFService = new TributoIRRFService();
                    $tributoIRRF = $TributoIRRFService->salvar($tributoIRRF);

                    $sequencialTributoIRRF[$tributoIRRF->getSequencial()] = $tributoIRRF->getPeriodoPagamento() + '|' +
                        $tributoIRRF->getCodigoReceita();
                }
            }

            $lancamentosCodigoIRRF = json_decode($parametros->lancamentosCodigoIRRF);

            if (isset($lancamentosCodigoIRRF) && !empty($lancamentosCodigoIRRF)) { 

                foreach ($lancamentosCodigoIRRF as $lancamento) {
                    $pagamentoCodigo = explode('|',$lancamento->codigoRelativoIRRF);
                    $mesAnoPagamento = explode('-',$pagamentoCodigo[0]);

                    $tributoIRRFRepository = new TributoIRRFRepository();
                    $tributoIRRFRepository
                        ->resetScopes();
                    $tributoIRRFExiste = $tributoIRRFRepository
                        ->scopeCodigoReceita((int) $pagamentoCodigo[1])
                        ->scopePeriodoContemplado($mesAnoPagamento[0], $mesAnoPagamento[1])
                        ->get();

                    $sequencialIRRF = 
                        !empty($tributoIRRFExiste) ? $tributoIRRFExiste[0]->getSequencial() : null ;

                    if (empty($sequencialIRRF) && !empty($sequencialTributoIRRF)) {
                        $sequencialIRRF = array_search($lancamento->codigoRelativoIRRF, $sequencialTributoIRRF);
                    }

                    if (!empty($sequencialIRRF) && !in_array($sequencialIRRF, $sequencialExcluidoCodigoIRRF)) {
                        $tributosIRRF = $tributoIRRFRepository
                            ->scopeSequencial($sequencialIRRF)
                            ->get();
                        if (!empty($tributosIRRF)) {
                            $tributoIRRF = $tributosIRRF[0];
                        }

                        $tributoIRRF->setSequencial($sequencialIRRF);
                        $tributoIRRF->setSequencialProcessoServidor($servidorProcesso);
                        $tributoIRRF->setValorRendimentoTributavel($lancamento->valorRendimentoMensal);
                        $tributoIRRF->setValorRendimentoTributavel13($lancamento->valorRendimento13Mensal);
                        $tributoIRRF->setValorRendimentoMolestia($lancamento->valorMolestiaGrave);
                        $tributoIRRF->setValorIsenta65($lancamento->valorIsenta65);
                        $tributoIRRF->setValorJurosMora($lancamento->valorJuroMora);
                        $tributoIRRF->setValorRendimentoIsento($lancamento->valorNaoTributavel);
                        $tributoIRRF->setDescricaoIsento($lancamento->descricaoNaoTributavel);
                        $tributoIRRF->setValorPrevidenciaOficial($lancamento->valorPrevidenciaOficial);
                        $tributoIRRF->setDescricaoRendimentoAcumula($lancamento->descricaoRRA);
                        $tributoIRRF->setQuantidadeMesAcumula($lancamento->quantidadeRRA);
                        $tributoIRRF->setValorDespesaCusta($lancamento->despCustas);
                        $tributoIRRF->setValorDespesaAdvogados($lancamento->despAdvogados);

                        $TributoIRRFService = new TributoIRRFService();
                        $tributoIRRF = $TributoIRRFService->salvar($tributoIRRF);

                    }
                }
            }

            $lancamentosAdvogado = json_decode($parametros->lancamentosAdvogado);
            $advogado = new Advogado();
            if (isset($lancamentosAdvogado) && !empty($lancamentosAdvogado)) { 
                foreach ($lancamentosAdvogado as $lancamento) {
                    $numeroInscricao = !empty($lancamento->cpfADV) ? $lancamento->cpfADV : $lancamento->cnpjADV;
                    if ((int) $lancamento->sequencial == 0) {
                        $advogadoRepository = new AdvogadoRepository();
                        $advogadoRepository
                            ->resetScopes();
                        $advogadoExiste = $advogadoRepository
                            ->scopeTipoInscricao((int) $lancamento->tipoInscricaoADV)
                            ->scopeNumeroInscricao($numeroInscricao)
                            ->scopeSequencialTributoIRRF($tributoIRRF->getSequencial())
                            ->get();
                        if (!empty($advogadoExiste)) {
                            $lancamento->sequencial = (int) $advogadoExiste[0]->getSequencial();
                        }
                    }
                    $advogado->setSequencial((int) $lancamento->sequencial);
                    $advogado->setTipoInscricao($lancamento->tipoInscricaoADV);
                    $advogado->setNumeroInscricao($numeroInscricao);
                    $advogado->setValorDespesa($lancamento->valorDespesaADV);

                    $advogado->setSequencialTributoIRRF((int) $tributoIRRF->getSequencial());

                    $advogadoService = new AdvogadoService();
                    $advogadoService->salvar($advogado);
                }
            }

            $lancamentosDependente = json_decode($parametros->lancamentosDependente);
            $dependente = new Dependente();
            if (isset($lancamentosDependente) && !empty($lancamentosDependente)) { 
                foreach ($lancamentosDependente as $lancamento) {
                    if ((int) $lancamento->sequencial == 0) {
                        $dependenteRepository = new DependenteRepository();
                        $dependenteRepository
                            ->resetScopes();
                        $dependenteExiste = $dependenteRepository
                            ->scopeTipoRendimento((int) $lancamento->tipoRendimentoDEP)
                            ->scopeCPFDependente($lancamento->cpfDEP)
                            ->get();
                        if (!empty($dependenteExiste)) {
                            $lancamento->sequencial = (int) $dependenteExiste[0]->getSequencial();
                        }
                    }
                    $dependente->setSequencial((int) $lancamento->sequencial);
                    $dependente->setSequencialTributoIRRF((int) $tributoIRRF->getSequencial());
                    $dependente->setTipoRendimento($lancamento->tipoRendimentoDEP);
                    $dependente->setCpfDependente($lancamento->cpfDEP);
                    $dependente->setValorDeducao($lancamento->valorDEP);

                    $dependenteService = new DependenteService();
                    $dependenteService->salvar($dependente);
                }
            }

            $lancamentosPensao = json_decode($parametros->lancamentosPensao);
            $pensao = new Pensao();
            if (isset($lancamentosPensao) && !empty($lancamentosPensao)) { 
                foreach ($lancamentosPensao as $lancamento) {
                    if ((int) $lancamento->sequencial == 0) {
                        $pensaoRepository = new PensaoRepository();
                        $pensaoRepository
                            ->resetScopes();
                        $pensaoExiste = $pensaoRepository
                            ->scopeTipoRendimento((int) $lancamento->tipoRendimentoPEN)
                            ->scopeCPF($lancamento->cpfPEN)
                            ->get();
                        if (!empty($pensaoExiste)) {
                            $lancamento->sequencial = (int) $pensaoExiste[0]->getSequencial();
                        }
                    }
                    $pensao->setSequencial((int) $lancamento->sequencial);
                    $pensao->setSequencialTributoIRRF((int) $tributoIRRF->getSequencial());
                    $pensao->setTipoRendimento($lancamento->tipoRendimentoPEN);
                    $pensao->setCpfPensao($lancamento->cpfPEN);
                    $pensao->setValorPensao($lancamento->valorPEN);

                    $pensaoService = new PensaoService();
                    $pensaoService->salvar($pensao);
                }
            }

            $lancamentosRetencao = json_decode($parametros->lancamentosRetencao);

            $retencao = new Retencao();
            $idRetencao = [];
            if (isset($lancamentosRetencao) && !empty($lancamentosRetencao)) { 
                foreach ($lancamentosRetencao as $lancamento) {
                    if ((int) $lancamento->sequencial == 0) {
                        $retencaoRepository = new RetencaoRepository();
                        $retencaoRepository
                            ->resetScopes();
                        $retencaoExiste = $retencaoRepository
                            ->scopeNumeroRetencao($lancamento->numeroRetencao)
                            ->get();
                        if (!empty($retencaoExiste)) {
                            $lancamento->sequencial = (int) $retencaoExiste[0]->getSequencial();
                        }
                    }
                    $retencao->setSequencial((int) $lancamento->sequencial);
                    $retencao->setSequencialTributoIRRF((int) $tributoIRRF->getSequencial());
                    $retencao->setNumeroProcesso($lancamento->numeroRetencao);
                    $retencao->setTipoProcesso($lancamento->tipoRetencao);
                    $retencao->setCodigoIndicativoSuspensao($lancamento->codigoSuspensao);

                    $retencaoService = new RetencaoService();
                    $retencaoService->salvar($retencao);

                    $idRetencao[$retencao->getSequencial()] = $lancamento->numeroRetencao;
                }
            }

            $lancamentosValorRetencao = json_decode($parametros->lancamentosValorRetencao);
            $valorRetencao = new ValorRetencao();
            $idValorRetencao = [];
            if (isset($lancamentosValorRetencao) && !empty($lancamentosValorRetencao)) { 
                foreach ($lancamentosValorRetencao as $lancamento) {
                    $sequencialRetencao = array_search($lancamento->processoRetencao, $idRetencao);
                    if ((int) $lancamento->sequencial == 0) {
                        $retencaoValorRepository = new ValorRetencaoRepository();
                        $retencaoValorRepository
                            ->resetScopes();
                        $retencaoValorExiste = $retencaoValorRepository
                            ->scopeSequencialRetencao($sequencialRetencao)
                            ->scopeTipoApuracao((int) $lancamento->periodoApuracao)
                            ->get();
                        if (!empty($retencaoValorExiste)) {
                            $lancamento->sequencial = (int) $retencaoValorExiste[0]->getSequencial();
                        }
                    }
                    $valorRetencao->setSequencial((int) $lancamento->sequencial);
                    $valorRetencao->setSequencialRetencao((int) $sequencialRetencao);
                    $valorRetencao->setIndicativoApuracao($lancamento->periodoApuracao);
                    $valorRetencao->setValorRetencao($lancamento->valorRetencao);
                    $valorRetencao->setValorDepositoJudicial($lancamento->valorDeposito);
                    $valorRetencao->setValorCompensacaoAno($lancamento->valorAnoCalendario);
                    $valorRetencao->setValorCompensacaoAnoAnterior($lancamento->valorAnoAnterior);
                    $valorRetencao->setValorRendimentoSuspenso($lancamento->valorRendimentoSuspenso);

                    $valorRetencaoService = new ValorRetencaoService();
                    $valorRetencaoService->salvar($valorRetencao);

                    $idValorRetencao[$valorRetencao->getSequencial()] = $lancamento->id ;
                }
            }

            $lancamentosValorDeducaoSuspensa = json_decode($parametros->lancamentosDeducaoSuspensa);

            $deducaoSuspensa = new DeducaoSuspensa();

            if (isset($lancamentosValorDeducaoSuspensa) && !empty($lancamentosValorDeducaoSuspensa)) { 
                foreach ($lancamentosValorDeducaoSuspensa as $lancamento) {
                    $idLancamento = substr($lancamento->id,1);
                    $sequencialValorRetencao = array_search($idLancamento, $idValorRetencao);
                    if ((int) $lancamento->sequencial == 0) {
                        $deducaoSuspensaRepository = new DeducaoSuspensaRepository();
                        $deducaoSuspensaRepository
                            ->resetScopes();
                        $deducaoSuspensaExiste = $deducaoSuspensaRepository
                            ->scopeSequencialValorRetencao($sequencialValorRetencao)
                            ->scopeTipoDeducao((int) $lancamento->tipoDeducao)
                            ->get();
                        if (!empty($deducaoSuspensaExiste)) {
                            $lancamento->sequencial = (int) $deducaoSuspensaExiste[0]->getSequencial();
                        }
                    }

                    $deducaoSuspensa->setSequencial((int) $lancamento->sequencial);
                    $deducaoSuspensa->setSequencialValorRetencao((int) $sequencialValorRetencao);
                    $deducaoSuspensa->setTipoDeducao($lancamento->tipoDeducao);
                    $deducaoSuspensa->setValorDeducao($lancamento->valorDeducaoSuspensa);

                    $deducaoSuspensaService = new DeducaoSuspensaService();
                    $deducaoSuspensaService->salvar($deducaoSuspensa);
                    $idDeducaoSuspensa[$deducaoSuspensa->getSequencial()] = $lancamento->id ;
                }
            }

            $lancamentosSuspensaPensao = json_decode($parametros->lancamentosSuspensaPensao);

            $valorSuspensaPensao = new SuspensaPensao();

            if (isset($lancamentosSuspensaPensao) && !empty($lancamentosSuspensaPensao)) { 
                foreach ($lancamentosSuspensaPensao as $lancamento) {
                    $sequencialDeducaoSuspensa = array_search($lancamento->processoSuspensaPensao, $idDeducaoSuspensa);

                    if ((int) $lancamento->sequencial == 0) {
                        $deducaoSuspensaRepository = new SuspensaoPensaoRepository();
                        $deducaoSuspensaRepository
                            ->resetScopes();
                        $deducaoSuspensaExiste = $deducaoSuspensaRepository
                            ->scopeSequencialDeducaoSuspensa($sequencialDeducaoSuspensa)
                            ->scopeCPFPensao($lancamento->CPFSuspensaPensao)
                            ->get();
                        if (!empty($deducaoSuspensaExiste)) {
                            $lancamento->sequencial = (int) $deducaoSuspensaExiste[0]->getSequencial();
                        }
                    }

                    $valorSuspensaPensao->setSequencial((int) $lancamento->sequencial);
                    $valorSuspensaPensao->setSequencialDeducaoSuspensa((int) $sequencialDeducaoSuspensa);
                    $valorSuspensaPensao->setCpfDependente($lancamento->CPFSuspensaPensao);
                    $valorSuspensaPensao->setValorDeducao($lancamento->valorSuspensaPensao);

                    $suspensaPensaoService = new SuspensaoPensaoService();
                    $suspensaPensaoService->salvar($valorSuspensaPensao);
                }
            }

            $lancamentosIRComplementar = json_decode($parametros->lancamentosIRComplementar);

            $valorIRComplementar = new TributoIRRFComplementar();

            if (isset($lancamentosIRComplementar) && !empty($lancamentosIRComplementar)) { 
                foreach ($lancamentosIRComplementar as $lancamento) {

                    if ((int) $lancamento->sequencial == 0) {
                        $IRComplementarRepository = new TributoIRRFComplementarRepository();
                        $IRComplementarRepository
                            ->resetScopes();
                        $IRComplementarExiste = $IRComplementarRepository
                            ->scopeSequencialServidor($servidorProcesso)
                            ->scopeCPFDependente($lancamento->CPFIRComplementar)
                            ->get();
                        if (!empty($IRComplementarExiste)) {
                            $lancamento->sequencial = (int) $IRComplementarExiste[0]->getSequencial();
                        }
                    }
                    $valorIRComplementar->setSequencial((int) $lancamento->sequencial);
                    $valorIRComplementar->setSequencialProcessoServidor((int) $servidorProcesso); 
                    $valorIRComplementar->setCpfDependente($lancamento->CPFIRComplementar);
                    $valorIRComplementar->setDataLaudo($lancamento->dataLaudo);
                    $valorIRComplementar->setDataNascimento($lancamento->dataNascimento);
                    $valorIRComplementar->setNome($lancamento->nomeDependente);
                    $valorIRComplementar->setIRRFDependenteTributavel($lancamento->depIRRF);
                    $valorIRComplementar->setTipoDependente($lancamento->tipoDependente);
                    $valorIRComplementar->setDescricaoDependencia($lancamento->descricaoDependencia);

                    $tributoIRRFComplementarService = new TributoIRRFComplementarService();
                    $tributoIRRFComplementarService->salvar($valorIRComplementar);
                }
            }

            $retorno->mensagem = "Registro atualizado com sucesso!";
            $retorno->sequencialProcessoServidor = $servidorProcesso;
            $retorno->matricula = $parametros->codigoMatricula;
            break;
        case 'preencheDadosTabelaTributos':
            $servidorRepositoryProcesso = new ServidorRepositoryProcesso();
            $servidorRepositoryProcesso
                ->resetScopes();
            $processosServidor = $servidorRepositoryProcesso
                ->scopeSequencial($parametros->sequencialProcessoServidor)
                ->scopeMatricula($parametros->matricula)
                ->get();
            $tributoBaseRepository = new TributoBaseRepository();
            $tributoBaseRepository
                ->resetScopes();
            $tributoBase = $tributoBaseRepository
                ->scopeSequencialServidor($processosServidor[0]->getSequencial())
                ->get();

            $IRComplementarRepository = new TributoIRRFComplementarRepository();
            $IRComplementarRepository
                ->resetScopes();
            $IRComplementar = $IRComplementarRepository
                ->scopeSequencialServidor($processosServidor[0]->getSequencial())
                ->get();


            $listaTributos = [];
            $sequenciaisBase = [];
            $sequencialIRRF = [];
            $listaAdvogados = [];
            $listaDependentes = [];
            $listaPensoes = [];
            $listaRetencoes = [];
            $listaValorRetencoes = [];
            $listaDeducoes = [];
            $listaDeducoesSuspensa = [];
            $listaIRComplementar = [];

            foreach ($tributoBase as $lancamento) {
                $dadosTributos = new stdClass();
                $dadosTributos->base = new stdClass();
                $dadosTributos->base->sequencial = 0;
                if (!empty($lancamento->getSequencial())) {
                    $dadosTributos->base->sequencial = $lancamento->getSequencial();
                }
                $dadosTributos->base->sequencialProcessoServidor = $lancamento->getSequencialProcessoServidor();
                $dadosTributos->base->competencia = $lancamento->getCompetencia();
                $dadosTributos->base->valorBaseMensal = $lancamento->getValorBaseMensal();
                $dadosTributos->base->valorBaseMensal13 = $lancamento->getValorBaseMensal13();
                $dadosTributos->base->pagamento = $lancamento->getPagamento();
                $dadosTributos->base->observacao = $lancamento->getObservacao();
                $listaTributos[] = $dadosTributos;
                $sequenciaisBase[$dadosTributos->base->sequencial] = 
                    $dadosTributos->base->pagamento . '/' .
                    $dadosTributos->base->competencia;
            }

            if (!empty($IRComplementar)) {
                foreach ($IRComplementar as $lancamento) {
                    $dadosIRComplementar = new stdClass();
                    $dadosIRComplementar->sequencial = 0;
                    if (!empty($lancamento->getSequencial())) {
                        $dadosIRComplementar->sequencial = $lancamento->getSequencial();
                    }
                    $dadosIRComplementar->sequencialProcessoServidor = $lancamento->getSequencialProcessoServidor();
                    $dadosIRComplementar->dataLaudo = $lancamento->getDataLaudo();
                    $dadosIRComplementar->cpfDependente = $lancamento->getCpfDependente();
                    $dadosIRComplementar->dataNascimento = $lancamento->getDataNascimento();
                    $dadosIRComplementar->nome = $lancamento->getNome();
                    $dadosIRComplementar->IRRFDependenteTributavel = $lancamento->getIRRFDependenteTributavel();
                    $dadosIRComplementar->tipoDependente = $lancamento->getTipoDependente();
                    $dadosIRComplementar->descricaoDependencia = $lancamento->getDescricaoDependencia();
    
                    $listaIRComplementar[] = $dadosIRComplementar;
                }
            }

            if (!empty($sequenciaisBase)) {
                foreach ($sequenciaisBase as $sequencialBase => $periodo) {
                    $tributoContribuicaoRepository = new TributoContribuicaoRepository();
                    $tributoContribuicaoRepository
                        ->resetScopes();
                    $tributoContribuicao = $tributoContribuicaoRepository
                        ->scopeSequencialBase($sequencialBase)
                        ->get();
                    foreach ($tributoContribuicao as $lancamento) {
                        $dadosContribuicao = new stdClass();
                        $dadosContribuicao->contribuicao = new stdClass();
                        $dadosContribuicao->contribuicao->sequencial = 0;
                        if (!empty($lancamento->getSequencial())) {
                            $dadosContribuicao->contribuicao->sequencial = $lancamento->getSequencial();
                        }
                        $dadosContribuicao->contribuicao->sequencialTributoBase = $lancamento
                            ->getSequencialTributoBase();
                        $dadosContribuicao->contribuicao->codigoReceita = $lancamento->getCodigoReceita();
                        $dadosContribuicao->contribuicao->valorContribuicao = $lancamento->getValorContribuicao();
                        $dadosContribuicao->contribuicao->periodoContempladoPagamento = $periodo;
                        $idPeriodo = str_replace("/","",str_replace("-","",$periodo));
                        $dadosContribuicao->contribuicao->periodos = $idPeriodo;
                        $listaTributos[] = $dadosContribuicao;
                    }
                }

                $tributoIRRFRepository = new TributoIRRFRepository();
                $tributoIRRFRepository
                    ->resetScopes();
                $tributoIRRF = $tributoIRRFRepository
                    ->scopeSequencialServidor($processosServidor[0]->getSequencial())
                    ->get();

                foreach ($tributoIRRF as $lancamento) {
                    $dadosIRRF = new stdClass();
                    $dadosIRRF->irrf = new stdClass();
                    $dadosIRRF->irrf->sequencial = 0;
                    if (!empty($lancamento->getSequencial())) {
                        $dadosIRRF->irrf->sequencial = $lancamento->getSequencial();
                    }
                    $dadosIRRF->irrf->sequencialProcessoServidor = $lancamento->getSequencialProcessoServidor();
                    $dadosIRRF->irrf->codigoReceita =
                        str_pad($lancamento->getCodigoReceita() , 6 , '0' , STR_PAD_LEFT);
                    $dadosIRRF->irrf->valorIRRF = $lancamento->getValorIRRF();
                    $dadosIRRF->irrf->contemplado = $lancamento->getPeriodoPagamento();

                    $dadosIRRF->complementar = new stdClass();
                    $dadosIRRF->complementar->codigoReceita = str_pad($lancamento->getCodigoReceita() , 6 , '0' , STR_PAD_LEFT);
                    $dadosIRRF->complementar->valorRendimentoTributavel = $lancamento->getValorRendimentoTributavel();
                    $dadosIRRF->complementar->valorRendimentoTributavel13 = $lancamento->getValorRendimentoTributavel13();
                    $dadosIRRF->complementar->valorRendimentoMolestia = $lancamento->getValorRendimentoMolestia();
                    $dadosIRRF->complementar->valorIsenta65 = $lancamento->getValorIsenta65();
                    $dadosIRRF->complementar->valorJurosMora = $lancamento->getValorJurosMora();
                    $dadosIRRF->complementar->valorRendimentoIsento = $lancamento->getValorRendimentoIsento();
                    $dadosIRRF->complementar->descricaoIsento = $lancamento->getDescricaoIsento();
                    $dadosIRRF->complementar->valorPrevidenciaOficial = $lancamento->getValorPrevidenciaOficial();
                    $dadosIRRF->complementar->descricaoRendimentoAcumula = $lancamento->getDescricaoRendimentoAcumula();
                    $dadosIRRF->complementar->quantidadeMesAcumula = $lancamento->getQuantidadeMesAcumula();
                    $dadosIRRF->complementar->valorDespesaCusta = $lancamento->getValorDespesaCusta();
                    $dadosIRRF->complementar->valorDespesaAdvogados = $lancamento->getValorDespesaAdvogados();
                    $dadosIRRF->complementar->sequencial = 0;
                    if (!empty($lancamento->getSequencial())) {
                        $dadosIRRF->complementar->sequencial = $lancamento->getSequencial();
                    }
                    $listaTributos[] = $dadosIRRF;
                    $sequencialIRRF[] = $dadosIRRF->irrf->sequencial;
                }
            }

            if (!empty($sequencialIRRF)) {
                foreach ($sequencialIRRF as $sequencial) {
                    $advogadoRepository = new AdvogadoRepository();
                    $advogadoRepository
                        ->resetScopes();
                    $advogados = $advogadoRepository
                        ->scopeSequencialTributoIRRF($sequencial)
                        ->get();
                    if (!empty($advogados)) {
                        foreach ($advogados as $indice => $advogado) {
                            $dadosADV = new stdClass();
                            $dadosADV->sequencial = $advogado->getSequencial();
                            $dadosADV->tipoInscricao = $advogado->getTipoInscricao();
                            $dadosADV->cpf = "";
                            $dadosADV->cnpj = "";
                            if ((int) $advogado->getTipoInscricao() == 1) {
                                $dadosADV->cnpj = $advogado->getNumeroInscricao();
                            }
                            if ((int) $advogado->getTipoInscricao() == 2) {
                                $dadosADV->cpf = $advogado->getNumeroInscricao();
                            }
                            $dadosADV->valorDespesa = $advogado->getValorDespesa();
                            $listaAdvogados[] = $dadosADV;
                        }
                    }
                }

                foreach ($sequencialIRRF as $sequencial) {
                    $dependenteRepository = new DependenteRepository();
                    $dependenteRepository
                        ->resetScopes();
                    $dependentes = $dependenteRepository
                        ->scopeSequencialTributoIRRF($sequencial)
                        ->get();
                    if (!empty($dependentes)) {
                        foreach ($dependentes as $indice => $dependente) {
                            $dadosDependente = new stdClass();
                            $dadosDependente->sequencial = $dependente->getSequencial();
                            $dadosDependente->tipoRendimentoDEP = $dependente->getTipoRendimento();
                            $dadosDependente->cpfDEP = $dependente->getCpfDependente();
                            $dadosDependente->valorDEP = $dependente->getValorDeducao();

                            $listaDependentes[] = $dadosDependente;
                        }
                    }
                }

                foreach ($sequencialIRRF as $sequencial) {
                    $pensaoRepository = new PensaoRepository();
                    $pensaoRepository
                        ->resetScopes();
                    $pensoes = $pensaoRepository
                        ->scopeSequencialTributoIRRF($sequencial)
                        ->get();
                    if (!empty($pensoes)) {
                        foreach ($pensoes as $indice => $pensao) {
                            $dadosPensao = new stdClass();
                            $dadosPensao->sequencial = $pensao->getSequencial();
                            $dadosPensao->tipoRendimentoPEN = $pensao->getTipoRendimento();
                            $dadosPensao->cpfPEN = $pensao->getCpfPensao();
                            $dadosPensao->valorPEN = $pensao->getValorPensao();

                            $listaPensoes[] = $dadosPensao;
                        }
                    }
                }

                $listaSequencialRetencao = [];
                foreach ($sequencialIRRF as $sequencial) {
                    $retencaoRepository = new RetencaoRepository();
                    $retencaoRepository
                        ->resetScopes();
                    $retencoes = $retencaoRepository
                        ->scopeSequencialTributoIRRF($sequencial)
                        ->get();
                    if (!empty($retencoes)) {
                        foreach ($retencoes as $indice => $retencao) {
                            $dadosRetencao = new stdClass();
                            $dadosRetencao->sequencial = $retencao->getSequencial();
                            $dadosRetencao->tipoRetencao = $retencao->getTipoProcesso();
                            $dadosRetencao->numeroRetencao = $retencao->getNumeroProcesso();
                            $dadosRetencao->codigoSuspensao = $retencao->getCodigoIndicativoSuspensao();

                            $listaRetencoes[] = $dadosRetencao;
                            $listaSequencialRetencao[$dadosRetencao->numeroRetencao] = $dadosRetencao->sequencial;
                        }
                    }
                }

                $listaSequencialValorRetencao = [];
                foreach ($listaSequencialRetencao as $numeroProcesso => $sequencial) {
                    $valorRetencaoRepository = new ValorRetencaoRepository();
                    $valorRetencaoRepository
                        ->resetScopes();
                    $valoRetencoes = $valorRetencaoRepository
                        ->scopeSequencialRetencao((int) $sequencial)
                        ->get();
                    if (!empty($valoRetencoes)) {
                        foreach ($valoRetencoes as $valorRetencao) {
                            $dadosValorRetencao = new stdClass();
                            $dadosValorRetencao->sequencial = $valorRetencao->getSequencial();
                            $dadosValorRetencao->processoRetencao = $numeroProcesso;
                            $dadosValorRetencao->periodoApuracao = $valorRetencao->getIndicativoApuracao();
                            $dadosValorRetencao->valorRetencao = $valorRetencao->getValorRetencao();
                            $dadosValorRetencao->valorDeposito = $valorRetencao->getValorDepositoJudicial();
                            $dadosValorRetencao->valorAnoCalendario = $valorRetencao->getValorCompensacaoAno();
                            $dadosValorRetencao->valorAnoAnterior = $valorRetencao->getValorCompensacaoAnoAnterior();
                            $dadosValorRetencao->valorRendimentoSuspenso = $valorRetencao->getValorRendimentoSuspenso();

                            $listaValorRetencoes[] = $dadosValorRetencao;
                            $listaSequencialValorRetencao[$dadosValorRetencao->periodoApuracao . $numeroProcesso] = $dadosValorRetencao->sequencial;
                        }
                    }
                }

                $listaSequencialDeducoes = [];
                foreach ($listaSequencialValorRetencao as $numeroProcesso => $sequencial) {
                    $deducaoRepository = new DeducaoSuspensaRepository();
                    $deducaoRepository
                        ->resetScopes();
                    $deducoes = $deducaoRepository
                        ->scopeSequencialValorRetencao((int) $sequencial)
                        ->get();
                    if (!empty($deducoes)) {
                        foreach ($deducoes as $deducao) {
                            $dadosDeducao = new stdClass();
                            $dadosDeducao->sequencial = $deducao->getSequencial();
                            $dadosDeducao->processoDeducaoSuspensa = $numeroProcesso;
                            $dadosDeducao->tipoDeducao = $deducao->getTipoDeducao();
                            $dadosDeducao->valorDeducaoSuspensa = $deducao->getValorDeducao();

                            $listaDeducoes[] = $dadosDeducao;
                            $listaSequencialDeducoes[$deducao->getTipoDeducao() . $numeroProcesso] = $deducao->getSequencial();
                        }
                    }
                }
                $listaSuspensaoPensao = [];
                foreach ($listaSequencialDeducoes as $numeroProcesso => $sequencial) {
                    $suspensaoPensaoRepository = new SuspensaoPensaoRepository();
                    $suspensaoPensaoRepository
                        ->resetScopes();
                    $suspensoesPensao = $suspensaoPensaoRepository
                        ->scopeSequencialDeducaoSuspensa((int) $sequencial)
                        ->get();
                    if (!empty($suspensoesPensao)) {
                        foreach ($suspensoesPensao as $suspensaoPensao) {
                            $cpfSuspensao = array_search($numeroProcesso,$listaSuspensaoPensao);

                            if ($cpfSuspensao == $suspensaoPensao->getCpfDependente()) {
                                continue;
                            }
                            $dadosSuspensaoPensao = new stdClass();
                            $dadosSuspensaoPensao->sequencial = $suspensaoPensao->getSequencial();
                            $dadosSuspensaoPensao->processoDeducaoSuspensa = $numeroProcesso;
                            $dadosSuspensaoPensao->CPFSuspensaPensao = $suspensaoPensao->getCpfDependente();
                            $dadosSuspensaoPensao->valorSuspensaPensao = $deducao->getValorDeducao();
                            $listaDeducoesSuspensa[] = $dadosSuspensaoPensao;
                            $listaSuspensaoPensao[$suspensaoPensao->getCpfDependente()] = $numeroProcesso;

                        }
                    }
                }
            }
           
            $retorno->dados = $listaTributos;
            $retorno->advogados = $listaAdvogados;
            $retorno->dependentes = $listaDependentes;
            $retorno->pensoes = $listaPensoes;
            $retorno->retencoes = $listaRetencoes;
            $retorno->valorRetencoes = $listaValorRetencoes;
            $retorno->deducoes = $listaDeducoes;
            $retorno->deducoesSuspensa = $listaDeducoesSuspensa;
            $retorno->IRComplementar = $listaIRComplementar;
            break;
        case 'salvarExclusao':
            if (!isset($parametros->recibo) ||
                $parametros->recibo == '0') {
                $retorno->mensagem = "Exclusão não realizada. número de recibo não definido. Favor revisar.";
                $retorno->erro = true;
                break;
            }
            if ($parametros->layout == 'S-2500') {
                $lancamentos = JSON::create()->parse($parametros->lancamentosProcessos);
                foreach ($lancamentos as $lancamento) {
                    $exclusaoProcesso = new Exclusao();
                    $exclusaoProcesso->setTipoEvento($lancamento->tpEvento);
                    $exclusaoProcesso->setRecibo($lancamento->nrRecEvt);
                    $exclusaoProcesso->setCpf($lancamento->cpf);
                    $exclusaoProcesso->setNumeroProcesso($lancamento->nrProcTrab);
                    $exclusaoProcesso->setSequencialProcessoServidor($lancamento->sequencialServidor);
                    $exclusaoProcesso->setDataExclusao(date('d-m-Y'));
                    $exclusaoProcesso->setReferencia($lancamento->referencia);
                    $exclusaoService = new ExclusaoService();
                    $exclusaoService->salvar($exclusaoProcesso);
                }
            }
            if ($parametros->layout == 'S-2501') {
                $lancamentos = JSON::create()->parse($parametros->lancamentosTributos);
                foreach ($lancamentos as $lancamento) {
                    $exclusaoProcesso = new Exclusao();
                    $exclusaoProcesso->setTipoEvento($lancamento->tpEvento);
                    $exclusaoProcesso->setRecibo($lancamento->nrRecEvt);
                    $exclusaoProcesso->setNumeroProcesso($lancamento->nrProcTrab);
                    $exclusaoProcesso->setPeriodoPagamento($lancamento->perApurPgto);
                    $exclusaoProcesso->setSequencialProcessoServidor($lancamento->sequencialServidor);
                    $exclusaoProcesso->setDataExclusao(date('d-m-Y'));
                    $exclusaoProcesso->setReferencia($lancamento->referencia);
                    $exclusaoService = new ExclusaoService();
                    $exclusaoService->salvar($exclusaoProcesso);
                    
                }
            }
            $retorno->mensagem = "Exclusão salva com sucesso!";
            break;

    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);

echo JSON::create()->stringify($retorno);
