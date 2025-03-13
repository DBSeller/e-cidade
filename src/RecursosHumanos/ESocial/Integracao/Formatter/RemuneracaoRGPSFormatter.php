<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use ECidade\RecursosHumanos\ESocial\Entity\RemuneracaoRGPS;
use ECidade\RecursosHumanos\ESocial\Repository\ESocialRubricasRepository;
use ECidade\RecursosHumanos\ESocial\Service\RemuneracaoRGPSService;
use ECidade\RecursosHumanos\ESocial\Repository\TrabalhadorSemVinculoInicio;
use cl_avaliacaogruporespostarhpessoal;
use cl_avaliacaogruporespostalotacao;
use Rubrica;
use RubricaRepository;
use stdClass;
use Exception;
use db_utils;
use CgmRepository;
use ServidorRepository;
use AdmissaoDado;
use Lotacao;
use CgmJuridico;

/**
 * Class RemuneracaoRGPSFormatter
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 */
class RemuneracaoRGPSFormatter extends Formatter
{
    /**
     * @var null|RemuneracaoRGPS
     */
    private $remuneracaoRGPS;

    /**
     * @var null|RemuneracaoRGPSService
     */
    private $remuneracaoRGPSService;

    private $inscricaoEmpregador;

    private $rubricas = array();

    private $anoCompetencia;

    private $mesCompetencia;

    private $codigoCategoria;

    private $isDecimoTerceiro = false;

    private $cgmNaoEnviado  = [];

    private $isUltimaParcelaDecimoTerceiro = false;

    /**
     * @var ESocialRubricasRepository
     */
    private $rubricasRepository;

    /**
     * @var array
     */
    private $rubricasValidas;

    /**
     * @var boolean
     */
    private $possuiNaturezaSaude;

    /**
     * @var \Instituicao
     */
    private $instituicao;

    /**
     * @var CompetenciaHelper
     */
    private $competencia;

    /**
     * @var [] Servidor
     */
    private $servidores = [];

    /**
     * @var integer
     */

    private $numeroLotacaoTributaria;

    /**
     * @return mixed
     */
    public function getServidores()
    {
        return $this->servidores;
    }

    /**
     * @param mixed $servidores
     */
    public function setServidores($servidores)
    {
        $this->servidores = $servidores;
    }

    /**
     * @var int
     */
    private $cgmAtual;

    /**
     * @var bool
     * informa se o cmg possui matricula com e sem vinculo simultaneamente
     * nos casos de varias matriculas do mesmo cgm
     */
    private $possuiVinculoNaoVinculo = false;

    /**
     * @var bool
     * Variavel de controle do grupo dados complementares
     */
    private $enviaDadosComplementares = true;

    /**
     * @var bool
     * Variável que define é rescisão.
     */
    private $isRescisao = false;

    /**
     * @var string
     * Data base servidor.
     */
    private $dataBaseServidor = '';

        /**
     * @var string
     * Rubrica de diferença de salarial.
     */
    private $rubricaDiferenca = '';

    /**
     * @var []
     */
    private $listaLotacaoTributaria;

    /**
     * @var bool
     */
    private $servidorPossuiVariasMatriculas = false;

    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param array $dados
     * @return array|stdClass[]
     * @throws \BusinessException
     * @throws \DBException
     */
    public function formatar($dados)
    {
        $dados = (object) $dados;
        $this->inscricaoEmpregador = $dados->inscricao_empregador;
        $this->anoCompetencia = $dados->anoCompetencia;
        $this->mesCompetencia = $dados->mesCompetencia;
        $this->rubricasRepository = new ESocialRubricasRepository();
        $this->rubricasValidas = $this->rubricasRepository->validarRubricas('1200');
        $this->instituicao = \InstituicaoRepository::getInstituicaoSessao();
        $this->possuiNaturezaSaude = false;
        $this->competencia = CompetenciaHelper::get($this->anoCompetencia, $this->mesCompetencia);
        $this->rubricaDiferenca = $dados->rubricaDiferenca;

        $dadosFormatados = [];

        foreach ($dados->cgms as $cgm) {
            $dado = $this->buscarDadosECidade($cgm);
            if ($dado) {
                $dadosFormatados[] = $dado;
            } else {
                $this->cgmNaoEnviado[] = $cgm;
            }
        }
        return $dadosFormatados;
    }

    /**
     * @param $cgm
     * @return stdClass
     * @throws \BusinessException
     * @throws \DBException
     */
    private function buscarDadosECidade($cgm)
    {
        $this->cgmAtual = $cgm;
        $this->remuneracaoRGPSService = new RemuneracaoRGPSService($this->anoCompetencia, $this->mesCompetencia);
        $this->validaVinculo();

        if (!$this->remuneracaoRGPSService->validaRGPSPorCGM(CgmRepository::getByCodigo($cgm))) {
            return false;
        }

        $dadoFormatado = new stdClass();
        $dadoFormatado->dmDev = [];
        $dadoFormatado->referencia = $cgm . $this->remuneracaoRGPSService->getAnoCompetencia()
            . str_pad($this->remuneracaoRGPSService->getMesCompetencia(), 2, '0', STR_PAD_LEFT);

        if ($this->isDecimoTerceiro) {
            $dadoFormatado->referencia .= '2';
            $dadoFormatado->indApuracao = 2;
            $dadoFormatado->perApur = $this->remuneracaoRGPSService->getAnoCompetencia();
        } else {
            $dadoFormatado->indApuracao = 1;
            $dadoFormatado->referencia .= '1';
            $dadoFormatado->perApur = $this->remuneracaoRGPSService->getAnoCompetencia() . "-"
                . $this->remuneracaoRGPSService->getMesCompetencia();
        }

        $dadoFormatado->inscricao_empregador = $this->inscricaoEmpregador;
        $remuneracoesRGPS = $this->remuneracaoRGPSService->buscarPorCGM(CgmRepository::getByCodigo($cgm));
        /**
         * Caso exista servidores, o filtro envia somente a folha informada daquela matricula do cgm
         * ignorando outras matriculas daquele cgm
         */
        if (sizeof($this->servidores) > 0) {
            $remuneracoesRGPSTemp = [];
            foreach ($remuneracoesRGPS as $remuneracaoRGPS) {
                if ($remuneracaoRGPS->qtdServidores > 1) {
                    $this->servidorPossuiVariasMatriculas = true;
                }
                foreach ($this->servidores as $servidor) {
                    if ($remuneracaoRGPS->getServidor()->getMatricula() == $servidor->getMatricula()) {
                        $remuneracoesRGPSTemp[] = $remuneracaoRGPS;
                        if ($servidor->temVinculoEmpregaticio()) {
                            $this->enviaDadosComplementares = false;
                        }
                    }
                }
            }
            $remuneracoesRGPS = $remuneracoesRGPSTemp;
        }

        $indiceDmDev = 0;
        if (!empty($remuneracoesRGPS)) {
            foreach ($remuneracoesRGPS as $index => $remuneracaoRGPS) {
                $this->servidorPossuiVariasMatriculas = false;
                if ($remuneracaoRGPS->qtdServidores > 1) {
                    $this->servidorPossuiVariasMatriculas = true;
                }
                $pagamentos = $remuneracaoRGPS->getPagamentos();
                foreach ($pagamentos as $indexFolha => $tipoFolha) {
                    $this->remuneracaoRGPS = $remuneracaoRGPS;

                    if (!$remuneracaoRGPS->getServidor()->isRgps()) {
                        if (!$remuneracaoRGPS->getServidor()->is1200()) {
                            continue;
                        }
                    }
                    $this->isRescisao = ($tipoFolha[0]->nomePagamento === 'RESCISAOPOSTERIOR');
                    if ($remuneracaoRGPS->getServidor()->isRescindido() && !$this->isRescisao) {
                        if (!$remuneracaoRGPS->getServidor()->validaCategoriaRescisao()
                            && !$remuneracaoRGPS->getServidor()->validaCategoriaRescisaoSemVinculo()
                        ) {
                            continue;
                        }
                    }
                    $this->montarRemuneracaoPeriodoApuracao(
                        $dadoFormatado,
                        $dadoFormatado->indApuracao,
                        $indiceDmDev,
                        $tipoFolha[0]->nomePagamento,
                        $remuneracaoRGPS->getServidor()->getMatricula()
                    );
                    $this->organizarDadosServidor($dadoFormatado, $indexFolha, $indiceDmDev);
                    $this->organizarDadosTrabalhador($dadoFormatado);
                    $this->organizarDadosComplementares($dadoFormatado);
                    $this->organizarDadosPagamentos($dadoFormatado, $tipoFolha, $indexFolha, $indiceDmDev);
                    //verifica se existem informacoes de pagamentos
                    $removeIndice = $this->validaRemocaoIndiceDmDev($dadoFormatado, $indiceDmDev);

                    if ($removeIndice) {
                        unset($dadoFormatado->dmDev[$indiceDmDev]);
                    } else {
                        $this->organizarDadosOutrosVinculos($dadoFormatado);
                        $this->organizarDadosProcessosJudiciais($dadoFormatado);

                        /**
                         * Validamos se existe remuneracao dentro do demonstrativo, caso contrario deletamos o
                         * demonstrativo
                         * ou se a geracao do arquivo ANUAL é diferente de 12
                         */
                        $removeIndice = $this->validaRemocaoIndiceDmDev($dadoFormatado, $indiceDmDev);
                        if ($removeIndice || ($this->isDecimoTerceiro && !$this->isUltimaParcelaDecimoTerceiro)
                        ) {
                            unset($dadoFormatado->dmDev[$indiceDmDev]);
                        }
                    }
                    $indiceDmDev += 1;
                }
            }
            $dadoFormatado->dmDev = array_values($dadoFormatado->dmDev);
        }
        //Validando se existe demonstrativo (dmDev), caso não exista ele não é enviado.
        if (empty($dadoFormatado->dmDev)) {
            $dadoFormatado = false;
        }
        return $dadoFormatado;
    }

    /**
     * @param $dadoFormatado
     * @throws Exception
     */
    private function montarRemuneracaoPeriodoApuracao(
        &$dadoFormatado,
        $indApuracao = 1,
        $indice = 0,
        $tipoPagamento = '',
        $matricula = ''
    ) {
        if ($indApuracao == 1) {
            $indApuracao = "";
        }
        $adiantamento = false;
        // Montamos conforme o demonstrativo se e salario/adiantamento13/13
        if (empty($indApuracao)) {
            $sigla = 'SAL';
            switch ($tipoPagamento) {
                case RemuneracaoRGPSService::RESCISAOENVIADA:
                    $sigla = 'RESC';
                    break;
                case RemuneracaoRGPSService::COMPLEMENTAR:
                    $sigla = 'COMP';
                    break;
                case RemuneracaoRGPSService::DECIMO:
                    $sigla = 'ADIANT13';
                    $adiantamento = true;
                    break;
            }
            //Validamos se é salario ou adiantamento do decimo pela primeira rubrica da remuneracao atual
            $identificadorDemonstrativo = $matricula . $sigla . $this->remuneracaoRGPSService->getAnoCompetencia()
                . $this->remuneracaoRGPSService->getMesCompetencia() . $indApuracao;
        } else {
            $identificadorDemonstrativo = $matricula . "DECIMO" . $this->remuneracaoRGPSService->getAnoCompetencia()
                . $this->remuneracaoRGPSService->getMesCompetencia() . $indApuracao;
        }

        $dmDev = new stdClass();
        $dmDev->ideDmDev = $identificadorDemonstrativo;
        $dmDev->codCateg = $this->codigoCategoria = (int) $this->buscarCodigoCategoria();
        /**
         * Verificamos se o servidor possui 2 ou mais matriculas
         * sendo pelo menos 1 com vinculo e 1 sem vinculo ao mesmo tempo
         */

        $this->organizarDadosComplementaresContrato($dmDev);

        if (count($this->remuneracaoRGPS->getPagamentos()) > 0) {
            $this->setQuantidadeLotacaoTributaria($this->getQuantidadeLotacaoTributaria());
            if ($this->isRescisao) {
                $dmDev->infoPerAnt = new stdClass();
                $dmDev->infoPerAnt->remunOrgSuc = 'S';
                $dmDev->infoPerAnt->ideADC = [];
                $grupoideADC = new stdClass();
                $this->setDataBaseServidor(
                    $this->remuneracaoRGPS->getServidor(),
                    $this->anoCompetencia
                );
                $grupoideADC->tpAcConv = 'F';
                $grupoideADC->dsc = 'Outras verbas de natureza salarial ou não salarial devidas após o desligamento';
                $grupoideADC->remunSuc = 'S';
                $grupoideADC->idePeriodo = [];
                $dmDev->infoPerAnt->ideADC[0] = $grupoideADC;
                $grupoIdePeriodo = new stdClass();
                $dataRecisao = (string) $this->remuneracaoRGPS->getServidor()->getDataRescisao()->format('Y-m-d');
                $competenciaRescisao = explode('-', $dataRecisao);
                $grupoIdePeriodo->perRef = '';
                if (!empty($competenciaRescisao[1])) {
                    $grupoIdePeriodo->perRef  = $competenciaRescisao[0] . '-' . $competenciaRescisao[1];
                }
                $grupoIdePeriodo->ideEstabLot = [];
                $dmDev->infoPerAnt->ideADC[0]->idePeriodo[0] = $grupoIdePeriodo;
                $grupoIdeEstabLot = new stdClass();
                $grupoIdeEstabLot->tpInsc = 1;
                $grupoIdeEstabLot->nrInsc = $this->inscricaoEmpregador;
                $grupoIdeEstabLot->codLotacao = $this->remuneracaoRGPS
                    ->getServidor()
                    ->codigoLotacaoTributariaEsocial();
                $grupoIdeEstabLot->remunPerAnt =[];
                $dmDev->infoPerAnt->ideADC[0]->idePeriodo[0]->ideEstabLot[0] = $grupoIdeEstabLot;
            } else {
                $dmDev->infoPerApur = new stdClass();
                $dmDev->infoPerApur->ideEstabLot = [];
                if ($this->getQuantidadeLotacaoTributaria() == 1) {
                    $ideEstabLot = new stdClass();
                    $ideEstabLot->tpInsc = 1;
                    $ideEstabLot->nrInsc = $this->inscricaoEmpregador;
                    $ideEstabLot->codLotacao = $this->remuneracaoRGPS
                        ->getServidor()
                        ->codigoLotacaoTributariaEsocial();
                    $ideEstabLot->remunPerApur = [];
                    $dmDev->infoPerApur->ideEstabLot[0] = $ideEstabLot;
                } else {
                    $this->setListaLotacaoTributaria($this->remuneracaoRGPSService
                        ->buscarLotacaoTributaria($this->remuneracaoRGPS
                                                    ->getServidor()));
                    $lotacaoTributaria = $this->getListaLotacaoTributaria();
                    foreach ($lotacaoTributaria as $key => $lotacao) {
                        $ideEstabLot = new stdClass();
                        $ideEstabLot->tpInsc = 1;
                        $ideEstabLot->nrInsc = $this->inscricaoEmpregador;
                        $ideEstabLot->codLotacao = $lotacao->lotacaoTributaria;
                        $ideEstabLot->remunPerApur = [];
                        $dmDev->infoPerApur->ideEstabLot[$key] = $ideEstabLot;
                    }
                }
            }
        }
        if (empty($dadoFormatado->dmDev[$indice])) {
            $dadoFormatado->dmDev[$indice] = $dmDev;
        }
    }

    private function montarOutrosVinculos(&$dadoFormatado)
    {
        $dadoFormatado->ideTrabalhador->infoMV = new stdClass();
        $dadoFormatado->ideTrabalhador->infoMV->indMV = null;
        $dadoFormatado->ideTrabalhador->infoMV->remunOutrEmpr = array();
    }

    /**
     * @param $dadoFormatado
     * @param $index
     * @throws \BusinessException
     */
    private function organizarDadosPagamentos(&$dadoFormatado, $folha, $index = 0, $indexDmDev = 0)
    {
        $itensRemun = [];
        $itensRemunDiferenca = [];
        $categoriasAgentesNocivos = [
            '101','102','103','104','105','106','107','108','111','201','202','301','302','303','304','305','306',
            '307','308','309','310','311','312','313','731','734','738'
        ];
        $categoriasAgentesNocivosOrigem = ['401','410'];
        $validaAgente = false;
        if (in_array($this->codigoCategoria, $categoriasAgentesNocivos)) {
            $validaAgente = true;
        }

        // caso seja cedencia
        if (in_array($this->codigoCategoria, $categoriasAgentesNocivosOrigem)) {
            // buscamos os dados de cedencia
            $cedencia = new \Cedencia($this->remuneracaoRGPS->getServidor()->getMatricula());
            //validamos se a categoria de origem pertence a configuracao dos agentes nocivos
            if (in_array($cedencia->getCodCategoriaOrigem(), $categoriasAgentesNocivos)) {
                $validaAgente = true;
            }
        }

        $indiceLotacao = 0;
        if ($this->getQuantidadeLotacaoTributaria() == 1) {
            foreach ($folha as $key => $pagamento) {
                $item = new stdClass();
                $item->codRubr = $pagamento->codigo;
                if (!array_key_exists($pagamento->codigo, $this->rubricasValidas)) {
                    continue;
                }

                /**
                 * Quando selecionado arquivo anual, sera enviado somente os dados do mes 12 e rubricas de 13
                 * Quando for outro mes diferente do mes 12, as rubricas de 13 vao no mesmo arquivo do salario,
                 * separadas pelo demonstrativo DmDev
                 */
                if ($this->isUltimaParcelaDecimoTerceiro) {
                    if ($this->isDecimoTerceiro) {
                        if (!$pagamento->decimoTerceiro) {
                            continue;
                        }
                    } else {
                        if ($pagamento->decimoTerceiro) {
                            continue;
                        }
                    }
                }
                $rubricaSistema = RubricaRepository::getInstanciaByCodigo(
                    $pagamento->codigo,
                    $this->instituicao->getCodigo()
                );
                if ($rubricaSistema->getTipo() == Rubrica::TIPO_BASE) {
                    continue;
                }

                $rubrica = $this->rubricasValidas[$pagamento->codigo];

                if ($rubrica->natrubr == '9219') {
                    $this->possuiNaturezaSaude = true;
                }
                $codigoRubrica = $pagamento->codigo;
                $tabrubrica = $rubrica->idetabrubr;

                $this->rubricas[$codigoRubrica] = $tabrubrica;

                $item->ideTabRubr = $this->rubricas[$codigoRubrica];
                $item->codRubr = $codigoRubrica;
                $item->qtdRubr = $this->truncar((float) $pagamento->quantidade);
                $item->vrRubr = $this->truncar((float) $pagamento->valor);
                if (($this->anoCompetencia >= 2021 && $this->mesCompetencia >= 7)
                    or ($this->anoCompetencia >= 2021 && $dadoFormatado->indApuracao == 2)
                    or ($this->anoCompetencia >= 2022)
                ) {
                    $item->indApurIR = 0;
                }
                if ($this->rubricaDiferenca == $pagamento->codigo) {
                    $itensRemunDiferenca[] = $item;
                } else {
                    $itensRemun[] = $item;
                }
            }
            //Grupo infoPerAnt Rescisao
            if ($this->isRescisao) {
                $dadoFormatado
                    ->dmDev[$indexDmDev]
                    ->infoPerAnt
                    ->ideADC[0]
                    ->idePeriodo[0]
                    ->ideEstabLot[0]
                    ->remunPerAnt[$index]
                    ->itensRemun= $itensRemun;
                if ($validaAgente) {
                    $agenteNocivo = (int) $this->remuneracaoRGPS->getServidor()->getTipoExposicaoAgentesNocivos();
                    $agenteNocivo = in_array($agenteNocivo, array(0, 1, 5)) ? 1 : $agenteNocivo;

                    $dadoFormatado
                        ->dmDev[$indexDmDev]
                        ->infoPerAnt
                        ->ideADC[0]
                        ->idePeriodo[0]
                        ->ideEstabLot[0]
                        ->remunPerAnt[$index]
                        ->infoAgNocivo = new stdClass();
                    $dadoFormatado
                        ->dmDev[$indexDmDev]
                        ->infoPerAnt
                        ->ideADC[0]
                        ->idePeriodo[0]
                        ->ideEstabLot[0]
                        ->remunPerAnt[$index]
                        ->infoAgNocivo
                        ->grauExp = $agenteNocivo;
                }
            } else {
                //Grupo infoPerAnt Rubrica
                if (!empty($itensRemunDiferenca)) {
                    $dadoFormatado
                    ->dmDev[$indexDmDev]
                    ->infoPerAnt
                    ->ideADC[0]
                    ->idePeriodo[0]
                    ->ideEstabLot[0]
                    ->remunPerAnt[$index]
                    ->itensRemun= $itensRemunDiferenca;
                    if (in_array($this->codigoCategoria, $categoriasAgentesNocivos)) {
                        $agenteNocivo = (int) $this->remuneracaoRGPS->getServidor()->getTipoExposicaoAgentesNocivos();
                        $agenteNocivo = in_array($agenteNocivo, array(0, 1, 5)) ? 1 : $agenteNocivo;

                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerAnt
                            ->ideADC[0]
                            ->idePeriodo[0]
                            ->ideEstabLot[0]
                            ->remunPerAnt[$index]
                            ->infoAgNocivo = new stdClass();
                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerAnt
                            ->ideADC[0]
                            ->idePeriodo[0]
                            ->ideEstabLot[0]
                            ->remunPerAnt[$index]
                            ->infoAgNocivo
                            ->grauExp = $agenteNocivo;
                    }
                }
                $dadoFormatado
                    ->dmDev[$indexDmDev]
                    ->infoPerApur
                    ->ideEstabLot[0]
                    ->remunPerApur[$index]
                    ->itensRemun = $itensRemun;
                if ($validaAgente) {
                    $agenteNocivo = (int) $this->remuneracaoRGPS->getServidor()->getTipoExposicaoAgentesNocivos();
                    $agenteNocivo = in_array($agenteNocivo, array(0, 1, 5)) ? 1 : $agenteNocivo;

                    $dadoFormatado->dmDev[$indexDmDev]
                        ->infoPerApur
                        ->ideEstabLot[0]
                        ->remunPerApur[$index]
                        ->infoAgNocivo = new stdClass();
                    $dadoFormatado
                        ->dmDev[$indexDmDev]
                        ->infoPerApur
                        ->ideEstabLot[0]
                        ->remunPerApur[$index]
                        ->infoAgNocivo
                        ->grauExp = $agenteNocivo;
                }
            }
        } else {
            foreach ($folha as $key => $pagamento) {
                $item = new stdClass();
                $item->codRubr = $pagamento->codigo;
                $indiceLotacao = $this->retornaInidiceLotacao($pagamento->lotacao, $this->getListaLotacaoTributaria());

                if (!array_key_exists($pagamento->codigo, $this->rubricasValidas)) {
                    continue;
                }

                /**
                 * Quando selecionado arquivo anual, sera enviado somente os dados do mes 12 e rubricas de 13
                 * Quando for outro mes diferente do mes 12, as rubricas de 13 vao no mesmo arquivo do salario,
                 * separadas pelo demonstrativo DmDev
                 */
                if ($this->isUltimaParcelaDecimoTerceiro) {
                    if ($this->isDecimoTerceiro) {
                        if (!$pagamento->decimoTerceiro) {
                            continue;
                        }
                    } else {
                        if ($pagamento->decimoTerceiro) {
                            continue;
                        }
                    }
                }
                $rubricaSistema = RubricaRepository::getInstanciaByCodigo(
                    $pagamento->codigo,
                    $this->instituicao->getCodigo()
                );
                if ($rubricaSistema->getTipo() == Rubrica::TIPO_BASE) {
                    continue;
                }

                $rubrica = $this->rubricasValidas[$pagamento->codigo];

                if ($rubrica->natrubr == '9219') {
                    $this->possuiNaturezaSaude = true;
                }
                $codigoRubrica = $pagamento->codigo;
                $tabrubrica = $rubrica->idetabrubr;

                $this->rubricas[$codigoRubrica] = $tabrubrica;

                $item->ideTabRubr = $this->rubricas[$codigoRubrica];
                $item->codRubr = $codigoRubrica;
                $item->qtdRubr = $this->truncar((float) $pagamento->quantidade);
                $item->vrRubr = $this->truncar((float) $pagamento->valor);
                if (($this->anoCompetencia >= 2021 && $this->mesCompetencia >= 7)
                    or ($this->anoCompetencia >= 2021 && $dadoFormatado->indApuracao == 2)
                    or ($this->anoCompetencia >= 2022)
                ) {
                    $item->indApurIR = 0;
                }
                if (empty($itensRemun[$indiceLotacao])) {
                    $itensRemun[$indiceLotacao] = [];
                }
                if ($this->rubricaDiferenca == $pagamento->codigo) {
                    $itensRemun[$indiceLotacao][] = $item;
                } else {
                    $itensRemunDiferenca[$indiceLotacao][] = $item;
                }
                if ($this->isRescisao) {
                    $dadoFormatado
                        ->dmDev[$indexDmDev]
                        ->infoPerAnt
                        ->ideADC[0]
                        ->idePeriodo[0]
                        ->ideEstabLot[0]
                        ->remunPerAnt[$index]
                        ->itensRemun= $itensRemun;
                    if ($validaAgente) {
                        $agenteNocivo = (int) $this->remuneracaoRGPS->getServidor()->getTipoExposicaoAgentesNocivos();
                        $agenteNocivo = in_array($agenteNocivo, array(0, 1, 5)) ? 1 : $agenteNocivo;

                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerAnt
                            ->ideADC[0]
                            ->idePeriodo[0]
                            ->ideEstabLot[0]
                            ->remunPerAnt[$index]
                            ->infoAgNocivo = new stdClass();
                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerAnt
                            ->ideADC[0]
                            ->idePeriodo[0]
                            ->ideEstabLot[0]
                            ->remunPerAnt[$index]
                            ->infoAgNocivo
                            ->grauExp = $agenteNocivo;
                    }
                } else {
                    if (!empty($itensRemunDiferenca[$indiceLotacao])) {
                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerAnt
                            ->ideADC[0]
                            ->idePeriodo[0]
                            ->ideEstabLot[0]
                            ->remunPerAnt[$index]
                            ->itensRemun= $itensRemunDiferenca[$indiceLotacao];
                        if (in_array($this->codigoCategoria, $categoriasAgentesNocivos)) {
                            $agenteNocivo = (int) $this->remuneracaoRGPS
                                ->getServidor()
                                ->getTipoExposicaoAgentesNocivos();
                            $agenteNocivo = in_array($agenteNocivo, array(0, 1, 5)) ? 1 : $agenteNocivo;

                            $dadoFormatado
                                ->dmDev[$indexDmDev]
                                ->infoPerAnt
                                ->ideADC[0]
                                ->idePeriodo[0]
                                ->ideEstabLot[0]
                                ->remunPerAnt[$index]
                                ->infoAgNocivo = new stdClass();
                            $dadoFormatado
                                ->dmDev[$indexDmDev]
                                ->infoPerAnt
                                ->ideADC[0]
                                ->idePeriodo[0]
                                ->ideEstabLot[0]
                                ->remunPerAnt[$index]
                                ->infoAgNocivo
                                ->grauExp = $agenteNocivo;
                        }
                    }
                    if (!empty($itensRemun[$indiceLotacao])) {
                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerApur
                            ->ideEstabLot[$indiceLotacao]
                            ->remunPerApur[$index]
                            ->itensRemun = $itensRemun[$indiceLotacao];
                    }
                    if ($validaAgente) {
                        $agenteNocivo = (int) $this->remuneracaoRGPS->getServidor()->getTipoExposicaoAgentesNocivos();
                        $agenteNocivo = in_array($agenteNocivo, array(0, 1, 5)) ? 1 : $agenteNocivo;

                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerApur
                            ->ideEstabLot[$indiceLotacao]
                            ->remunPerApur[$index]
                            ->infoAgNocivo = new stdClass();
                        $dadoFormatado
                            ->dmDev[$indexDmDev]
                            ->infoPerApur
                            ->ideEstabLot[$indiceLotacao]
                            ->remunPerApur[$index]
                            ->infoAgNocivo
                            ->grauExp = $agenteNocivo;
                    }
                }
            }
        }
        if (empty($dadoFormatado
                    ->dmDev[$indexDmDev]
                    ->infoPerAnt
                    ->ideADC[0]
                    ->idePeriodo[0]
                    ->ideEstabLot[0]
                    ->remunPerAnt[0]
                    ->itensRemun)) {
                        unset($dadoFormatado
                                ->dmDev[$indexDmDev]
                                ->infoPerAnt);
        }
    }

    private function organizarDadosServidor(&$dadoFormatado, $index, $indexDmDev = 0)
    {
        $matriculaInfoPerAnt = $this->remuneracaoRGPS->getServidor()->getMatricula();
        //Grupo infoPerAnt
        $subGrupoinfoperant = new stdClass();
        $subGrupoinfoperant->ideADC = [];
        $dadoFormatado
            ->dmDev[$indexDmDev]
            ->infoPerAnt = $subGrupoinfoperant;
        $grupoideADC = new stdClass();
        $grupoideADC = $this->remuneracaoRGPSService->buscarRemuneracaoPeriodoAnterior($matriculaInfoPerAnt);
        $grupoideADC->remunSuc = 'S';
        $grupoideADC->idePeriodo = [];
        $dadoFormatado
            ->dmDev[$indexDmDev]
            ->infoPerAnt
            ->ideADC[0] = $grupoideADC;
        $idePeriodo = new stdClass();
        $idePeriodo = $this->remuneracaoRGPSService->buscarPeriodoAnterior($matriculaInfoPerAnt);
        $dadoFormatado
            ->dmDev[$indexDmDev]
            ->infoPerAnt
            ->ideADC[0]
            ->idePeriodo[0] = $idePeriodo;
        $anoMesCompetencia =[];
        $anoMesCompetencia[1] = '';
        $anoMesCompetencia[0] = '';
        if (!empty($idePeriodo->perRef)) {
            $anoMesCompetencia = explode('-', $idePeriodo->perRef);
        }
        $grupoIdeEstabLot =  $this->remuneracaoRGPSService
            ->buscarEstabelicimentoLotacao($matriculaInfoPerAnt, $anoMesCompetencia[1], $anoMesCompetencia[0]);
        $grupoIdeEstabLot->remunPerAnt =[];
        $dadoFormatado
            ->dmDev[$indexDmDev]
            ->infoPerAnt
            ->ideADC[0]
            ->idePeriodo[0]
            ->ideEstabLot[0] = $grupoIdeEstabLot;
            //remunPerAnt
        $remunPerAnt = new stdClass();
        if ($this->validaEnvioMatricula(
            $this->remuneracaoRGPS->getServidor()->getMatricula(),
            $this->getEmpregador()
        )) {
            if ($this->validaEnvioMatricula(
                $this->remuneracaoRGPS->getServidor()->getMatricula(),
                $this->getEmpregador()
            )) {
                if (!$this->remuneracaoRGPS->getServidor()->isEstagiario()) {
                    $remunPerAnt->matricula = $this->remuneracaoRGPS->getServidor()->getMatricula();
                }
            }
        }
        $remunPerAnt->itensRemun = [];
        $dadoFormatado
            ->dmDev[$indexDmDev]
            ->infoPerAnt
            ->ideADC[0]
            ->idePeriodo[0]
            ->ideEstabLot[0]
            ->remunPerAnt[0] = $remunPerAnt;
        //Grupo infoPerApur
        if ($this->getQuantidadeLotacaoTributaria() == 1) {
            $remunPerApur = new stdClass();
            if ($this->validaEnvioMatricula(
                $this->remuneracaoRGPS->getServidor()->getMatricula(),
                $this->getEmpregador()
            )) {
                if ($this->validaEnvioMatricula(
                    $this->remuneracaoRGPS->getServidor()->getMatricula(),
                    $this->getEmpregador()
                )) {
                    $remunPerApur->matricula = $this->remuneracaoRGPS->getServidor()->getMatricula();
                }
            }
            $remunPerApur->itensRemun = [];
            $dadoFormatado->dmDev[$indexDmDev]->infoPerApur->ideEstabLot[0]->remunPerApur[$index] = $remunPerApur;
        } else {
            foreach ($this->getListaLotacaoTributaria() as $indice => $lotacao) {
                $remunPerApur = new stdClass();
                if ($this->validaEnvioMatricula(
                    $this->remuneracaoRGPS->getServidor()->getMatricula(),
                    $this->getEmpregador()
                )) {
                    $remunPerApur->matricula = $this->remuneracaoRGPS->getServidor()->getMatricula();
                }
                if (empty($itens[$indice])) {
                    $itens[$indice] = [];
                }
                $remunPerApur->itensRemun = $itens[$indice];
                    $dadoFormatado
                        ->dmDev[$indexDmDev]
                        ->infoPerApur
                        ->ideEstabLot[$indice]
                        ->remunPerApur[$index] = $remunPerApur;
            }
        }
    }


    private function organizarDadosOutrosVinculos(&$dadoFormatado)
    {

        foreach ($this->remuneracaoRGPS->getServidorOutrosVinculos() as $indice => $outroVinculo) {
            if ($indice == 0) {
                $this->montarOutrosVinculos($dadoFormatado);
            }

            $dadoFormatado->ideTrabalhador->infoMV->indMV = $outroVinculo->getTipoContribuicao();
            $remunOutrEmpr = new stdClass();
            $remunOutrEmpr->tpInsc = $outroVinculo->getTipoInscricao();
            $remunOutrEmpr->nrInsc = $outroVinculo->getNumeroInscricao();
            $remunOutrEmpr->codCateg = $outroVinculo->getCodigoCategoria();
            $remunOutrEmpr->vlrRemunOE = $outroVinculo->getValorRemuneracao();
            $dadoFormatado->ideTrabalhador->infoMV->remunOutrEmpr[] = $remunOutrEmpr;
        }
    }

    private function organizarDadosProcessosJudiciais(&$dadoFormatado)
    {

        foreach ($this->remuneracaoRGPS->getProcessosJudiciais() as $indice => $processoJudicial) {
            if ($indice == 0) {
                $dadoFormatado->ideTrabalhador->procJudTrab = array();
            }

            $procJudTrab = new stdClass();
            $procJudTrab->tpTrib = $processoJudicial->getTipoProcesso();
            $procJudTrab->nrProcJud = $processoJudicial->getNumeroProcesso();

            $procJudTrab->codSusp = $processoJudicial->getCodigoIndicativoSuspensao();
            if (empty($procJudTrab->codSusp)) {
                unset($procJudTrab->codSusp);
            }

            $dadoFormatado->ideTrabalhador->procJudTrab[] = $procJudTrab;
        }
    }

    private function organizarDadosTrabalhador(&$dadoFormatado)
    {
        $dadoFormatado->ideTrabalhador = new stdClass();
        $dadoFormatado->ideTrabalhador->cpfTrab = $this->remuneracaoRGPS->getDadosTrabalhador()->cpf;
    }

    private function buscarCodigoCategoria()
    {
        return $this->remuneracaoRGPS->getServidor()->getVinculo()->getCodigoCategoria();
    }

    private function buscarCodigoLotacao($inscricaoEmpregador)
    {
        $codigoLotacao = null;

        $daoAvaliacaoGrupoRespostaLotacao = new cl_avaliacaogruporespostalotacao();
        $order  = "eso04_avaliacaogruporesposta desc limit 1";
        $where  = " db103_sequencial = 3000860 ";
        $where .= " AND eso04_cgm in (SELECT DISTINCT z01_numcgm FROM cgm INNER JOIN rhlota ON rhlota.r70_numcgm "
            . "= cgm.z01_numcgm WHERE z01_cgccpf = '{$inscricaoEmpregador}') ";
        $sql = $daoAvaliacaoGrupoRespostaLotacao->buscaRespostasPorPergunta(
            3000860,
            null,
            'db106_resposta',
            $order,
            $where
        );
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o código da categoria do servidor.");
        }

        if (pg_num_rows($rs) > 0) {
            $codigoLotacao = db_utils::fieldsMemory($rs, 0)->db106_resposta;
        }

        return $codigoLotacao;
    }

    public function setDecimoTerceiro()
    {
        $this->isDecimoTerceiro = true;
    }

    public function setUltimaParcelaDecimoTerceiro()
    {
        $this->isUltimaParcelaDecimoTerceiro = true;
    }

    public function getCgmsNaoEnviados()
    {
        return $this->cgmNaoEnviado;
    }

    // Função responsavel por validar se o indice será removido dos dados
    private function validaRemocaoIndiceDmDev($dadoFormatado, $indiceDmDev)
    {
        $removeIndice = false;
        if ($this->isRescisao) {
            $grupoIdeEstabLot = $dadoFormatado
                ->dmDev[$indiceDmDev]
                ->infoPerAnt
                ->ideADC[0]
                ->idePeriodo[0]
                ->ideEstabLot;
            //Caso nao exista ideEstabLot ou ideEstabLot = 0, remove o indice
            if (!isset($grupoIdeEstabLot)
                || (isset($grupoIdeEstabLot) && sizeof($grupoIdeEstabLot) == 0)) {
                $removeIndice = true;
            } else {
                // caso não exista remunPerAnt ou remunPerAnt = 0, remove o indice
                $grupoRemunPerAnt = $dadoFormatado
                    ->dmDev[$indiceDmDev]
                    ->infoPerAnt
                    ->ideADC[0]
                    ->idePeriodo[0]
                    ->ideEstabLot[0]
                    ->remunPerAnt;
                if (!isset($grupoRemunPerAnt)
                    || (isset($grupoRemunPerAnt) && sizeof($grupoRemunPerAnt) == 0)) {
                    $removeIndice = true;
                } else {
                    // Caso não exista itensRemun ou itensRemun = 0, remove o indice
                    //Varremos o remunPerAnt
                    $remunPerAnts = $dadoFormatado
                        ->dmDev[$indiceDmDev]
                        ->infoPerAnt
                        ->ideADC[0]
                        ->idePeriodo[0]
                        ->ideEstabLot[0]
                        ->remunPerAnt[0];
                    foreach ($remunPerAnts->itensRemun as $remunPerAnt) {
                        if (!isset($remunPerAnt)
                            || isset($remunPerAnt) && (sizeof($remunPerAnt) == 0)) {
                            $removeIndice = true;
                        }
                    }
                }
            }
        } else {
            if ($this->getQuantidadeLotacaoTributaria() == 1) {
                //Caso nao exista ideEstabLot ou ideEstabLot = 0, remove o indice
                if (!isset($dadoFormatado
                        ->dmDev[$indiceDmDev]
                        ->infoPerApur
                        ->ideEstabLot)
                    || (isset($dadoFormatado
                            ->dmDev[$indiceDmDev]
                            ->infoPerApur
                            ->ideEstabLot) && sizeof($dadoFormatado
                        ->dmDev[$indiceDmDev]
                        ->infoPerApur
                        ->ideEstabLot) == 0)) {
                    $removeIndice = true;
                } else {
                    // caso não exista remunPerApur ou remunPerApur = 0, remove o indice
                    if (!isset($dadoFormatado
                                ->dmDev[$indiceDmDev]
                                ->infoPerApur
                                ->ideEstabLot[0]->remunPerApur)
                        || (isset($dadoFormatado
                                    ->dmDev[$indiceDmDev]
                                    ->infoPerApur
                                    ->ideEstabLot[0]->remunPerApur) && sizeof($dadoFormatado
                            ->dmDev[$indiceDmDev]
                            ->infoPerApur
                            ->ideEstabLot[0]->remunPerApur) == 0)) {
                        $removeIndice = true;
                    } else {
                        // Caso não exista itensRemun ou itensRemun = 0, remove o indice
                        //Varremos o remunPerApur
                        $remunPerApurs = $dadoFormatado->dmDev[$indiceDmDev]->infoPerApur->ideEstabLot[0]->remunPerApur;

                        foreach ($remunPerApurs as $remunPerApur) {
                            if (!isset($remunPerApur->itensRemun)
                                || isset($remunPerApur->itensRemun) && (sizeof($remunPerApur->itensRemun) == 0)) {
                                $removeIndice = true;
                            }
                        }
                    }
                }
            } else {
                foreach ($this->getListaLotacaoTributaria() as $indiceLotacao => $lotacao) {
                    //Caso nao exista ideEstabLot ou ideEstabLot = 0, remove o indice
                    if (!isset($dadoFormatado
                            ->dmDev[$indiceDmDev]
                            ->infoPerApur
                            ->ideEstabLot)
                        || (isset($dadoFormatado
                                ->dmDev[$indiceDmDev]
                                ->infoPerApur
                                ->ideEstabLot) && sizeof($dadoFormatado
                            ->dmDev[$indiceDmDev]
                            ->infoPerApur
                            ->ideEstabLot) == 0)) {
                        unset($dadoFormatado->dmDev[$indiceDmDev]);
                        $removeIndice = false;
                    } else {
                        // caso não exista remunPerApur ou remunPerApur = 0, remove o indice
                        if (!isset($dadoFormatado
                                    ->dmDev[$indiceDmDev]
                                    ->infoPerApur
                                    ->ideEstabLot[$indiceLotacao]->remunPerApur)
                            || (isset($dadoFormatado
                                        ->dmDev[$indiceDmDev]
                                        ->infoPerApur
                                        ->ideEstabLot[$indiceLotacao]->remunPerApur) && sizeof($dadoFormatado
                                ->dmDev[$indiceDmDev]
                                ->infoPerApur
                                ->ideEstabLot[$indiceLotacao]->remunPerApur) == 0)) {
                            unset($dadoFormatado->dmDev[$indiceDmDev]);
                            $removeIndice = false;
                        } else {
                            // Caso não exista itensRemun ou itensRemun = 0, remove o indice
                            //Varremos o remunPerApur
                            $remunPerApurs = $dadoFormatado
                                ->dmDev[$indiceDmDev]
                                ->infoPerApur
                                ->ideEstabLot[$indiceLotacao]
                                ->remunPerApur;

                            foreach ($remunPerApurs as $remunPerApur) {
                                if (!isset($remunPerApur->itensRemun)
                                    || isset($remunPerApur->itensRemun) && (sizeof($remunPerApur->itensRemun) == 0)) {
                                    unset($dadoFormatado->dmDev[$indiceDmDev]);
                                    $removeIndice = false;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $removeIndice;
    }

    /**
     * Validamos se o cgm possui + de 1 matricula sendo pelo menos 1 com vinculo e 1 sem vinculo simultaneamente
     * e no loop esta na matricula sem vinculo
     */
    private function organizarDadosComplementares(&$dadoFormatado)
    {
        if ($this->possuiVinculoNaoVinculo && $this->enviaDadosComplementares) {
            $infoComplem = new stdClass();
            $infoComplem->nmTrab = $this->remuneracaoRGPS->getDadosTrabalhador()->nome;
            $infoComplem->dtNascto = $this->remuneracaoRGPS->getDadosTrabalhador()->nascimento;
            $dadoFormatado->ideTrabalhador->infoComplem = $infoComplem;
        }
    }

    /**
     * Validamos se o cgm possui + de 1 matricula sendo pelo menos 1 com vinculo e 1 sem vinculo simultaneamente
     * e no loop esta na matricula sem vinculo
     */
    private function organizarDadosComplementaresContrato(&$dmDev)
    {
        if ($this->possuiVinculoNaoVinculo && !$this->remuneracaoRGPS->getServidor()->temVinculoEmpregaticio()) {
            $infoComplCont = new stdClass;
            $infoComplCont->codCBO = $this->remuneracaoRGPS->getServidor()->getDadosCargo()->rh37_cbo;
            $infoComplCont->natAtividade = 1; // rural
            $infoComplCont->qtdDiasTrab = null;
            $dmDev->infoComplCont = $infoComplCont;
        }
    }

    /**
     * Validamos se o cgm possui + de 1 matricula sendo pelo menos 1 com vinculo e 1 sem vinculo simultaneamente
     */
    private function validaVinculo()
    {
        $cgm = CgmRepository::getByCodigo($this->cgmAtual);
        if ($cgm instanceof CgmJuridico) {
            $this->possuiVinculoNaoVinculo = false;
        } else {
            if (ServidorRepository::isServidorComESemVinculoByCgm($cgm, $this->competencia)) {
                $this->possuiVinculoNaoVinculo = true;
            } else {
                $this->possuiVinculoNaoVinculo = false;
            }
        }
    }

    public function truncar($valor)
    {
        $valor = abs(round($valor, 6));
        $novoValor = (string) $valor;
        $novoValor = explode(".", $novoValor);
        if (sizeof($novoValor) > 1) {
            if (strlen($novoValor[1]) > 2) {
                $novoValor[1] = substr($novoValor[1], 0, 2);
                $valor = (float)($novoValor[0] . "." . $novoValor[1]);
            }
        }
        return $valor;
    }
    /**
     * Retorna da Base do servidor.
     * Primeiro dia mês da data base do ano corrente.
     */
    public function setDataBaseServidor($servidor = null, $ano = null)
    {
        $dadoAdmissao = new AdmissaoDado($servidor->getMatricula());
        if (!empty($dadoAdmissao->getMesDataBase())) {
            $this->dataBaseServidor =
                '01/' . str_pad($dadoAdmissao->getMesDataBase(), 2, '0', STR_PAD_LEFT) . '/' . $ano;
        }
    }

    public function getDataBaseServidor()
    {
        return $this->dataBaseServidor;
    }

    private function getQuantidadeLotacaoTributaria()
    {
        $totalLotacao = count($this->remuneracaoRGPSService
            ->buscarLotacaoTributaria($this->remuneracaoRGPS
                                        ->getServidor()));
        return  $totalLotacao == 0 ? 1 : $totalLotacao;
    }

    /**
     * Set the value of numeroLotacaoTributaria
     *
     * @return  self
     */
    public function setQuantidadeLotacaoTributaria($numeroLotacaoTributaria)
    {
        $this->numeroLotacaoTributaria = $numeroLotacaoTributaria;
    }

    /**
     * Get the value of listaLotacaoTributaria
     *
     * @return  []
     */
    public function getListaLotacaoTributaria()
    {
        return $this->listaLotacaoTributaria;
    }

    /**
     * Set the value of listaLotacaoTributaria
     *
     * @param  []  $listaLotacaoTributaria
     *
     * @return  self
     */
    public function setListaLotacaoTributaria($listaLotacaoTributaria)
    {
        $this->listaLotacaoTributaria = $listaLotacaoTributaria;
    }

    private function retornaInidiceLotacao($lotacao, $array)
    {
        foreach ($array as $key => $val) {
            if ($val->lotacaoTributaria === $lotacao) {
                return $key;
            }
        }
        return null;
    }
}
