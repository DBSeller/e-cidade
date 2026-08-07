<?php
/**
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao;

use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo;
use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho as DiaTrabalhoModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository\Evento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Repository\Justificativa as JustivicativaRepository;

/**
 * Classe para montagem dos dados do ponto de um servidor em uma data
 * Class EspelhoPonto
 * @package ECidade\RecursosHumanos\RH\PontoEletronico
 */
class EspelhoPonto
{

    /**
     * @var \Servidor
     */
    private $oServidor;

    /**
     * @var Periodo
     */
    private $oPeriodoEfetividade;

    /**
     * @var \Instituicao
     */
    private $oInstituicao;

    /**
     * @var array
     */
    private $aDados;

    /**
     * @var bool
     */
    private $lTotalizadores = false;

    /**
     * @var bool
     */
    private $lTemTodasMarcacoes = true;

    /**
     * @var array
     */
    private $aObservacoes = array();

    /**
     * @var array
     */
    private $aControleObservacoes = array();

    /**
     * @var Periodo[]
     */
    private $aPeriodos = array();

    /**
     * @var array
     */
    private $totalHorasAssentamentos = array();

    /**
     * @var DiaTrabalhoModel
     */
    private $diaTrabalhoCache;

    /**
     * EspelhoPonto constructor.
     * @param \Servidor $oServidor
     * @param Periodo[] $aPeriodos
     * @param \Instituicao $oInstituicao
     * @throws \BusinessException
     */
    public function __construct(\Servidor $oServidor, $aPeriodos, \Instituicao $oInstituicao)
    {

        $this->oServidor = $oServidor;
        if ($this->oServidor->isRescindido()) {
            $periodo = reset($aPeriodos);
            if ($this->oServidor->getDataRescisao()->getTimestamp() < $periodo->getDataInicio()->getTimestamp()) {
                throw new \Exception("Servidor {$this->oServidor->getCgm()->getNome()} foi rescindo em {$this->oServidor->getDataRescisao()->format("d/m/Y")}");
            }
        }
        $this->aPeriodos = $aPeriodos;
        $this->oInstituicao = $oInstituicao;

        $this->getEstruturaDadosBasico();
    }

    /**
     * Seta se os totalizadores devem ser calculados
     */
    public function calcularTotalizadores()
    {
        $this->lTotalizadores = true;
    }

    /**
     * Monta a estrutura básica dos dados
     */
    private function getEstruturaDadosBasico()
    {

        $this->aDados = array(
            'dados' => $this->getDadosServidor(),
            'dadosGerais' => array(),
            'datas' => array(),
            'datasSemMarcacao' => array(),
            'aHorasJornada' => array(),
            'observacoes' => array(),
            'nTotalHorasNormais' => array('0:00'),
            'nTotalHorasFaltas' => array('0:00'),
            'nTotalHorasFaltasNoturna' => array('0:00'),
            'nTotalHorasExt50diurnas' => array('0:00'),
            'nTotalHorasExt50noturnas' => array('0:00'),
            'nTotalHorasExt75diurnas' => array('0:00'),
            'nTotalHorasExt75noturnas' => array('0:00'),
            'nTotalHorasExt100diurnas' => array('0:00'),
            'nTotalHorasExt100noturnas' => array('0:00'),
            'nTotalHorasExt50NaoAutorizadasdiurnas' => array('0:00'),
            'nTotalHorasExt50NaoAutorizadasnoturnas' => array('0:00'),
            'nTotalHorasExt75NaoAutorizadasdiurnas' => array('0:00'),
            'nTotalHorasExt75NaoAutorizadasnoturnas' => array('0:00'),
            'nTotalHorasExt100NaoAutorizadasdiurnas' => array('0:00'),
            'nTotalHorasExt100NaoAutorizadasnoturnas' => array('0:00'),
            'nTotalHorasExtNaoAutorizadasnoturnas' => array('0:00'),
            'nTotalHorasExtNaoAutorizadasdiurnas' => array('0:00'),
            'nTotalHorasAdicional' => array('0:00'),
            'nTotalHorasAtraso' => array('0:00'),
            'nTotalHorasAtrasoDesmembrado' => array('0:00'),
            'nTotalHorasAtrasoNoturno' => array('0:00'),
            'nTotalHorasSaidaAtencipada' => array('0:00'),
            'nTotalHorasSaidaAtencipadaNoturna' => array('0:00'),
            'totalHorasAssentamento' => array('0:00'),
        );
    }

    /**
     * Retorna um array de todas as datas de um período de efetividade
     * @return \DBDate[]
     */
    private function getDatasEfetividade()
    {
        return \DBDate::getDatasNoIntervalo($this->oPeriodoEfetividade->getDataInicio(),
            $this->oPeriodoEfetividade->getDataFim());
    }

    /**
     * Retorna a escala do servidor em uma data
     * @param \DBDate $oDataEfetividade
     * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor
     * @throws \BusinessException
     */
    private function getEscalaServidorNaData(\DBDate $oDataEfetividade)
    {

        if ($this->oServidor->getEscalas() == null) {

            $sMensagem = "Servidor {$this->oServidor->getMatricula()} - {$this->oServidor->getCgm()->getNome()} não possui";
            $sMensagem .= " escala de trabalho configurada.";
            $sMensagem .= ' Para configurá-la, acesse o menu:';
            $sMensagem .= "\n- RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários";

            throw new \BusinessException($sMensagem);
        }

        $oEscala = ProcessamentoPontoEletronico::getEscalaNaData($this->oServidor->getEscalas(), $oDataEfetividade);

        if (is_null($oEscala)) {

            $sMensagem = "Servidor {$this->oServidor->getMatricula()} - {$this->oServidor->getCgm()->getNome()} não possui";
            $sMensagem .= " escala de trabalho configurada no dia {$oDataEfetividade->getDate(\DBDate::DATA_PTBR)}.";
            $sMensagem .= ' Para verificar as escalas, acesse o menu:';
            $sMensagem .= "\n- RH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários";

            throw new \BusinessException($sMensagem);
        }

        return $oEscala;
    }

    /**
     * Retorna uma instância de DiaTrabalho
     * @param $oDataEfetividade
     * @return DiaTrabalhoModel
     * @throws \BusinessException
     * @throws \DBException
     */
    private function getDiaTrabalho($oDataEfetividade)
    {
        $diaTrabalho = $this->getDiaTrabalhoCache();
        if (!empty($diaTrabalho) && $oDataEfetividade->getTimeStamp() == $diaTrabalho->getData()->getTimeStamp()) {
            return $this->getDiaTrabalhoCache();
        }

        $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
        $oDiaTrabalhoRepository->setEscalaServidor($this->getEscalaServidorNaData($oDataEfetividade));
        $oDiaTrabalhoRepository->setBuscaJustificativaMarcacoes(true);

        return $oDiaTrabalhoRepository->getDiaTrabalhoProcessadoServidor($this->oServidor, $oDataEfetividade);
    }

    /**
     * Preenche os dados do servidor
     * @return object
     * @throws \BusinessException
     * @throws \DBException
     */
    private function getDadosServidor()
    {

        $oParametrosLotacao = ParametrosRepository::create()->getConfiguracoesLotacao($this->oServidor->getCodigoLotacao());

        if (empty($oParametrosLotacao)) {

            $sMensagem = "A lotação ({$this->oServidor->getCodigoLotacao()}) do servidor: {$this->oServidor->getMatricula()} - {$this->oServidor->getCgm()->getNome()} não está configurada.\n";
            $sMensagem .= "Para o funcionamento correto do espelho ponto são necessárias as configurações de Tolerância e Horas Extras da lotação do servidor. \n";
            $sMensagem .= "Para configurá-las acesse:\nRH > Procedimentos > Ponto Eletrônico > Configurações > Lotação";

            throw new \BusinessException($sMensagem);
        }

        $localTrabalho = "";
        if ($this->oServidor->getLocalTrabalhoPrincial() != null) {
            $localTrabalho = $this->oServidor->getLocalTrabalhoPrincial()->getDescricao();
        }

        $supervisor = $oParametrosLotacao->getSupervisor();

        return (object)array(
            'nome' => $this->oServidor->getCgm()->getNome(),
            'matricula' => $this->oServidor->getMatricula(),
            'admissao' => $this->oServidor->getDataAdmissao()->getDate(\DBDate::DATA_PTBR),
            'pispasep' => $this->oServidor->getPISPASEP(),
            'lotacao' => $this->oServidor->getCodigoLotacao(),
            'rescisao'   => $this->oServidor->getDataRescisao(),
            'supervisor' => $supervisor->getCgm()->getNome(),
            'matriculaSupervisor' => $supervisor->getMatricula(),
            'localTrabalho' => $localTrabalho
        );
    }

    /**
     * @return object
     */
    private function getDadosGerais()
    {
        return (object)array(
            'lTemTodasMarcacoes' => $this->lTemTodasMarcacoes,
            'configuracoesGerais' => ParametrosRepository::create()->getConfiguracoesGerais($this->oInstituicao->getCodigo())
        );
    }

    /**
     * Preenche os valores referentes ao dia de trabalho
     * @param DiaTrabalhoModel $oDiaTrabalho
     * @return \stdClass
     * @throws \DBException
     */
    private function montarValoresGrade(DiaTrabalhoModel $oDiaTrabalho)
    {

        $diaSemana = new \DateTime($oDiaTrabalho->getData()->getDate());
        $oDados = new \stdClass;
        $oDados->possuiEvento = false;
        $oDados->dadosEvento = new \stdClass();
        $oDados->dadosEvento->descricao = '';

        $oJornada = $oDiaTrabalho->getJornada();

        $oDados->oPeriodoEfetividade = new \stdClass();
        $oDados->oPeriodoEfetividade->iExercicio = $this->oPeriodoEfetividade->getExercicio();
        $oDados->oPeriodoEfetividade->iCompetencia = $this->oPeriodoEfetividade->getCompetencia();

        $oDados->codigo_data = $oDiaTrabalho->getCodigo();
        $oDados->data = $oDiaTrabalho->getData()->getDate(\DBDate::DATA_PTBR);
        $oDados->data_dia = $oDiaTrabalho->getData()->getDate(\DBDate::DATA_PTBR) . ' - ' . $this->diaSemana($diaSemana->format('w'),
                true);
        $oDados->lTemMarcacoes      = !$oDiaTrabalho->getMarcacoes()->isEmpty();
        $oDados->lFeriado           = $oDiaTrabalho->getFeriado() != null;
        $oDados->lEscalaRevezamento = ($oDiaTrabalho->getServidor()->getEscala($oDiaTrabalho->getData()) instanceof EscalaServidor ? $oDiaTrabalho->getServidor()->getEscala($oDiaTrabalho->getData())->getEscalaTrabalho()->isRevezamento() : false);
        $oDados->afastamento        = (object)array('isAfastado' => false);

        if ($oDiaTrabalho->isAfastado()) {
            $oDados->afastamento->isAfastado = true;
            $oDados->afastamento->descricao = $oDiaTrabalho->getAfastamento() ? $oDiaTrabalho->getAfastamento()->getInstanciaTipoAssentamento()->getDescricao() : '';
            $oDados->afastamento->abreviacao = $oDiaTrabalho->getAfastamento() ? $oDiaTrabalho->getAfastamento()->getInstanciaTipoAssentamento()->getCodigo() : '';

            if (empty($this->aControleObservacoes['afastamentos']) || !in_array($oDados->afastamento->abreviacao,
                    $this->aControleObservacoes['afastamentos'])) {

                $sObservacao = "{$oDados->afastamento->abreviacao}: {$oDados->afastamento->descricao}";
                $this->aObservacoes[] = $sObservacao;
                $this->aControleObservacoes['afastamentos'][] = $oDados->afastamento->abreviacao;
            }
        }

        $oDados->oJornada = new \stdClass();
        $oDados->oJornada->codigo = $oJornada->getCodigo();
        $oDados->oJornada->descricao = $oJornada->getDescricao();
        $oDados->oJornada->dsr_folga = !$oJornada->isDiaTrabalhado();
        $oDados->oJornada->tipo_descricao = $oJornada->getTipoDescricao();

        $oEntradaSaida1 = new \stdClass();
        $oEntradaSaida2 = new \stdClass();
        $oEntradaSaida3 = new \stdClass();

        for ($iContador = 1; $iContador <= 6; $iContador++) {
            $oMarcacao = $oDiaTrabalho->getMarcacoesSemAlteracao()->getMarcacao($iContador);

            $oDadosMarcacao = new \stdClass();
            $oDadosMarcacao->codigo = null;
            $oDadosMarcacao->hora = '';
            $oDadosMarcacao->tipo = $iContador;
            $oDadosMarcacao->manual = false;
            $oDadosMarcacao->data = '';
            $oDadosMarcacao->oJustificativa = null;

            if ($oMarcacao != null) {
                $oDadosMarcacao->codigo = $oMarcacao->getCodigo();
                $oDadosMarcacao->hora = $this->validaHoraZerada($oMarcacao->getMarcacao());
                $oDadosMarcacao->manual = $oMarcacao->isManual();
                $oDadosMarcacao->data = $oMarcacao->getData()->getDate();

                if ($oMarcacao->getJustificativa() != null) {
                    $oDadosMarcacao->oJustificativa = new \stdClass();
                    $oDadosMarcacao->oJustificativa->codigo = $oMarcacao->getJustificativa()->getCodigo();
                    $oDadosMarcacao->oJustificativa->descricao = $oMarcacao->getJustificativa()->getDescricao();
                    $oDadosMarcacao->oJustificativa->abreviacao = $oMarcacao->getJustificativa()->getAbreviacao();

                    if (empty($this->aControleObservacoes['justificativas']) || !in_array($oMarcacao->getJustificativa()->getCodigo(),
                            $this->aControleObservacoes['justificativas'])) {
                        $sObservacao = "{$oMarcacao->getJustificativa()->getAbreviacao()}: {$oMarcacao->getJustificativa()->getDescricao()}";

                        $this->aObservacoes[] = $sObservacao;
                        $this->aControleObservacoes['justificativas'][] = $oMarcacao->getJustificativa()->getCodigo();
                    }
                }
            }

            switch ($iContador) {
                case MarcacaoPonto::ENTRADA_1;
                    $oEntradaSaida1->oEntrada = $oDadosMarcacao;
                    break;
                case MarcacaoPonto::SAIDA_1;
                    $oEntradaSaida1->oSaida = $oDadosMarcacao;
                    break;
                case MarcacaoPonto::ENTRADA_2;
                    $oEntradaSaida2->oEntrada = $oDadosMarcacao;
                    break;
                case MarcacaoPonto::SAIDA_2;
                    $oEntradaSaida2->oSaida = $oDadosMarcacao;
                    break;
                case MarcacaoPonto::ENTRADA_3;
                    $oEntradaSaida3->oEntrada = $oDadosMarcacao;
                    break;
                case MarcacaoPonto::SAIDA_3;
                    $oEntradaSaida3->oSaida = $oDadosMarcacao;
                    break;
            }
        }

        $aMarcacoesOriginais = $this->getMarcacoesOriginais($oDiaTrabalho->getData(),
            $oDiaTrabalho->getServidor()->getMatricula());

        $oDados->aMarcacoes = array($oEntradaSaida1, $oEntradaSaida2, $oEntradaSaida3);
        $oDados->aMarcacoesOriginais = $aMarcacoesOriginais;
        $oDados->normais = $this->validaHoraZerada($oDiaTrabalho->getHorasTrabalho() != '' ? new \DateTime($oDiaTrabalho->getHorasTrabalho()) : '',
            true);
        $oDados->faltas = $this->validaHoraZerada($oDiaTrabalho->getHorasFalta() != '' ? new \DateTime($oDiaTrabalho->getHorasFalta()) : '',
            true);
        $oDados->faltasNoturna = $this->validaHoraZerada($oDiaTrabalho->getHorasFaltaNoturna() != '' ? new \DateTime($oDiaTrabalho->getHorasFaltaNoturna()) : '',
            true);
        $oDados->ext50diurnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra50() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra50()) : '');
        $oDados->ext50noturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra50Noturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra50Noturna()) : '');
        $oDados->ext75diurnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra75() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra75()) : '');
        $oDados->ext75noturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra75Noturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra75Noturna()) : '');
        $oDados->ext100diurnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra100() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra100()) : '');
        $oDados->ext100noturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra100Noturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra100Noturna()) : '');
        $oDados->ext50 = $this->validaHoraZerada(new \DateTime($this->somarTotalizador(array(
            $oDados->ext50diurnas,
            $oDados->ext50noturnas
        ))), true);
        $oDados->ext75 = $this->validaHoraZerada(new \DateTime($this->somarTotalizador(array(
            $oDados->ext75diurnas,
            $oDados->ext75noturnas
        ))), true);
        $oDados->ext100 = $this->validaHoraZerada(new \DateTime($this->somarTotalizador(array(
            $oDados->ext100diurnas,
            $oDados->ext100noturnas
        ))), true);
        $oDados->adicional = $this->validaHoraZerada($oDiaTrabalho->getHorasAdicionalNoturno() != '' ? new \DateTime($oDiaTrabalho->getHorasAdicionalNoturno()) : '',
            true);
        $oDados->saidaAntecipada = $this->validaHoraZerada($oDiaTrabalho->getHorasSaidaAntecipada() != '' ? new \DateTime($oDiaTrabalho->getHorasSaidaAntecipada()) : '',
            true);
        $oDados->saidaAntecipadaNoturna = $this->validaHoraZerada($oDiaTrabalho->getHorasSaidaAntecipadaNoturna() != '' ? new \DateTime($oDiaTrabalho->getHorasSaidaAntecipadaNoturna()) : '',
            true);

        $oDados->atrasoDesmembrado = $this->validaHoraZerada($oDiaTrabalho->getHorasAtraso() != '' ? new \DateTime($oDiaTrabalho->getHorasAtraso()) : '',
            true);
        $oDados->atrasoNoturno = $this->validaHoraZerada($oDiaTrabalho->getHorasAtrasoNoturno() != '' ? new \DateTime($oDiaTrabalho->getHorasAtrasoNoturno()) : '',
            true);

        $oDados->totalHorasExt50NaoAutorizadasdiurnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra50NaoAutorizadas() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra50NaoAutorizadas()) : '',
            true);
        $oDados->totalHorasExt50NaoAutorizadasnoturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra50NaoAutorizadasNoturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra50NaoAutorizadasNoturna()) : '',
            true);
        $oDados->totalHorasExt75NaoAutorizadasdiurnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra75NaoAutorizadas() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra75NaoAutorizadas()) : '',
            true);
        $oDados->totalHorasExt75NaoAutorizadasnoturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra75NaoAutorizadasNoturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra75NaoAutorizadasNoturna()) : '',
            true);
        $oDados->totalHorasExt100NaoAutorizadasdiurnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra100NaoAutorizadas() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra100NaoAutorizadas()) : '',
            true);
        $oDados->totalHorasExt100NaoAutorizadasnoturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtra100NaoAutorizadasNoturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtra100NaoAutorizadasNoturna()) : '',
            true);
        $oDados->totalHorasExtNaoAutorizadasnoturnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtraNaoAutorizadas() != '' ? new \DateTime($oDiaTrabalho->getHorasExtraNaoAutorizadas()) : '',
            true);
        $oDados->totalHorasExtNaoAutorizadasdiurnas = $this->validaHoraZerada($oDiaTrabalho->getHorasExtraNaoAutorizadasNoturna() != '' ? new \DateTime($oDiaTrabalho->getHorasExtraNaoAutorizadasNoturna()) : '',
            true);

        $minutosSaidaAntecipada = $this->converteHorasEmMinutos($oDiaTrabalho->getHorasSaidaAntecipada());
        $minutosAtraso = $this->converteHorasEmMinutos($oDiaTrabalho->getHorasAtraso());
        $horasAtrasoSaidaAntecipada = BaseHora::converterMinutosEmHoraMinuto($minutosSaidaAntecipada + $minutosAtraso);

        if ($horasAtrasoSaidaAntecipada == '00:00') {
            $horasAtrasoSaidaAntecipada = '';
        }

        $oDados->atraso = $horasAtrasoSaidaAntecipada;
        $oDados->extAutorizadas = $this->getHorasExtrasAutorizadasNaData($oDiaTrabalho);
        $oDados->extManual = $this->hasHorasExtrasManuais($oDiaTrabalho);

        if (!$oDiaTrabalho->getMarcacoes()->temTodasMarcacoes() && !$oDiaTrabalho->getJornada()->isFixo() && $oDiaTrabalho->getFeriado() == null) {
            $this->lTemTodasMarcacoes = false;
        }

        $this->verificarExistenciaDeEvento($oDados);
        return $oDados;
    }

    /**
     * Seta os dados do evento para serem apresentados em tela
     * @param \stdClass $oDados
     * @return bool|\stdClass
     */
    private function verificarExistenciaDeEvento(\stdClass $oDados)
    {

        $dia = new \DBDate($oDados->data);
        $evento = Evento::getInstance()->possuiEventoNoDiaParaServidor($dia, $this->oServidor);
        if (!$evento) {
            return false;
        }

        $servidoresDoEvento = $evento->getServidores();
        if (array_key_exists($this->oServidor->getMatricula(), $servidoresDoEvento)) {
            $oDados->possuiEvento = true;
            $oDados->dadosEvento->descricao = $evento->getTitulo();
        }
        return $oDados;
    }

    /**
     * Retorna o dia da semana por extenso ou abreviado
     * @param $iNumeroDia
     * @param bool $lAbreviado
     * @return string
     */
    private function diaSemana($iNumeroDia, $lAbreviado = false)
    {
        switch ($iNumeroDia) {
            case 0:
                $diaSemanaAbreviado = "Dom";
                $diaSemanaExtenso = "Domingo";
                break;
            case 1:
                $diaSemanaAbreviado = "Seg";
                $diaSemanaExtenso = "Segunda-feira";
                break;
            case 2:
                $diaSemanaAbreviado = "Ter";
                $diaSemanaExtenso = "Terça-feira";
                break;
            case 3:
                $diaSemanaAbreviado = "Qua";
                $diaSemanaExtenso = "Quarta-feira";
                break;
            case 4:
                $diaSemanaAbreviado = "Qui";
                $diaSemanaExtenso = "Quinta-feira";
                break;
            case 5:
                $diaSemanaAbreviado = "Sex";
                $diaSemanaExtenso = "Sexta-feira";
                break;
            default:
                $diaSemanaAbreviado = "Sáb";
                $diaSemanaExtenso = "Sábado";
                break;
        }
        return $lAbreviado ? $diaSemanaAbreviado : $diaSemanaExtenso;
    }

    /**
     * Atualiza os valores dos totalizadores
     * @param $oStdDiaTrabalho
     */
    private function getTotalizadores($oStdDiaTrabalho)
    {
        $this->aDados['nTotalHorasNormais'][] = $oStdDiaTrabalho->normais;
        $this->aDados['nTotalHorasFaltas'][] = $oStdDiaTrabalho->faltas;
        $this->aDados['nTotalHorasFaltasNoturna'][] = $oStdDiaTrabalho->faltasNoturna;
        $this->aDados['nTotalHorasExt50diurnas'][] = $oStdDiaTrabalho->ext50diurnas;
        $this->aDados['nTotalHorasExt50noturnas'][] = $oStdDiaTrabalho->ext50noturnas;
        $this->aDados['nTotalHorasExt75diurnas'][] = $oStdDiaTrabalho->ext75diurnas;
        $this->aDados['nTotalHorasExt75noturnas'][] = $oStdDiaTrabalho->ext75noturnas;
        $this->aDados['nTotalHorasExt100diurnas'][] = $oStdDiaTrabalho->ext100diurnas;
        $this->aDados['nTotalHorasExt100noturnas'][] = $oStdDiaTrabalho->ext100noturnas;
        $this->aDados['nTotalHorasAdicional'][] = $oStdDiaTrabalho->adicional;
        $this->aDados['nTotalHorasAtraso'][] = $oStdDiaTrabalho->atraso;
        $this->aDados['nTotalHorasAtrasoNoturno'][] = $oStdDiaTrabalho->atrasoNoturno;
        $this->aDados['nTotalHorasAtrasoDesmembrado'][] = $oStdDiaTrabalho->atrasoDesmembrado;
        $this->aDados['nTotalHorasSaidaAtencipada'][] = $oStdDiaTrabalho->saidaAntecipada;
        $this->aDados['nTotalHorasSaidaAtencipadaNoturna'][] = $oStdDiaTrabalho->saidaAntecipadaNoturna;
        $this->aDados['nTotalHorasExt50NaoAutorizadasdiurnas'][] = $oStdDiaTrabalho->totalHorasExt50NaoAutorizadasdiurnas;
        $this->aDados['nTotalHorasExt50NaoAutorizadasnoturnas'][] = $oStdDiaTrabalho->totalHorasExt50NaoAutorizadasnoturnas;
        $this->aDados['nTotalHorasExt75NaoAutorizadasdiurnas'][] = $oStdDiaTrabalho->totalHorasExt75NaoAutorizadasdiurnas;
        $this->aDados['nTotalHorasExt75NaoAutorizadasnoturnas'][] = $oStdDiaTrabalho->totalHorasExt75NaoAutorizadasnoturnas;
        $this->aDados['nTotalHorasExt100NaoAutorizadasdiurnas'][] = $oStdDiaTrabalho->totalHorasExt100NaoAutorizadasdiurnas;
        $this->aDados['nTotalHorasExt100NaoAutorizadasnoturnas'][] = $oStdDiaTrabalho->totalHorasExt100NaoAutorizadasnoturnas;
        $this->aDados['nTotalHorasExtNaoAutorizadasnoturnas'][] = $oStdDiaTrabalho->totalHorasExtNaoAutorizadasnoturnas;
        $this->aDados['nTotalHorasExtNaoAutorizadasdiurnas'][] = $oStdDiaTrabalho->totalHorasExtNaoAutorizadasdiurnas;

        if (empty($oStdDiaTrabalho->ext50noturnas)) {
            $oStdDiaTrabalho->ext50noturnas = '00:00';
        }
        if (empty($oStdDiaTrabalho->ext75noturnas)) {
            $oStdDiaTrabalho->ext75noturnas = '00:00';
        }
        if (empty($oStdDiaTrabalho->ext100noturnas)) {
            $oStdDiaTrabalho->ext100noturnas = '00:00';
        }
        if (empty($oStdDiaTrabalho->ext50diurnas)) {
            $oStdDiaTrabalho->ext50diurnas = '00:00';
        }
        if (empty($oStdDiaTrabalho->ext75diurnas)) {
            $oStdDiaTrabalho->ext75diurnas = '00:00';
        }
        if (empty($oStdDiaTrabalho->ext100diurnas)) {
            $oStdDiaTrabalho->ext100diurnas = '00:00';
        }

        $oExtra50Noturna = new \DateTime($oStdDiaTrabalho->ext50noturnas);
        $oExtra75Noturna = new \DateTime($oStdDiaTrabalho->ext75noturnas);
        $oExtra100Noturna = new \DateTime($oStdDiaTrabalho->ext100noturnas);

        $oInterval50 = new \DateInterval("PT{$oExtra50Noturna->format('H')}H{$oExtra50Noturna->format('i')}M");
        $oInterval75 = new \DateInterval("PT{$oExtra75Noturna->format('H')}H{$oExtra75Noturna->format('i')}M");
        $oInterval100 = new \DateInterval("PT{$oExtra100Noturna->format('H')}H{$oExtra100Noturna->format('i')}M");

        $oTotalExtra50 = new \DateTime($oStdDiaTrabalho->ext50diurnas);
        $oTotalExtra75 = new \DateTime($oStdDiaTrabalho->ext75diurnas);
        $oTotalExtra100 = new \DateTime($oStdDiaTrabalho->ext100diurnas);

        $oTotalExtra50->add($oInterval50);
        $oTotalExtra75->add($oInterval75);
        $oTotalExtra100->add($oInterval100);


        $this->aDados['nTotalHorasExt50'][] = $oTotalExtra50->format('H:i');
        $this->aDados['nTotalHorasExt75'][] = $oTotalExtra75->format('H:i');
        $this->aDados['nTotalHorasExt100'][] = $oTotalExtra100->format('H:i');
    }

    /**
     * Retorna a estrutura com as informações do ponto do servidor em um período de efetividade
     * @return array
     */
    public function retornaDados($lApenasDiasComHorasExtras = false)
    {
        $aHorasJornada = array();

        foreach ($this->aPeriodos as $oPeriodoEfetividade) {
            $this->oPeriodoEfetividade = $oPeriodoEfetividade;

            foreach ($this->getDatasEfetividade() as $oDataEfetividade) {
                $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
                if ($this->oServidor->isRescindido() && $oDataEfetividade->getTimeStamp() > $this->oServidor->getDataRescisao()->getTimestamp()) {
                    continue;
                }

                $oDiaTrabalho = $this->getDiaTrabalho($oDataEfetividade);
                $oJornada = $oDiaTrabalho->getJornada();

                if ($oDiaTrabalhoRepository->getCodigoData($oDiaTrabalho) === null) {
                    $this->aDados['datasSemMarcacao'][] = $oDiaTrabalho->getData()->getDate();
                }

                $aHorasJornada[$oJornada->getCodigo()] = (object)array(
                    'fixo' => $oJornada->isFixo(),
                    'folga' => $oJornada->isFolga(),
                    'DSR' => $oJornada->isDSR(),
                    'diaTrabalhado' => $oJornada->isDiaTrabalhado(),
                    'horas' => $oJornada->getHoras()
                );

                $oStdDiaTrabalho = $this->montarValoresGrade($oDiaTrabalho);
                
                $lIgnorarData = false;

                if ($lApenasDiasComHorasExtras) {
                    if (empty($oStdDiaTrabalho->ext50)) {
                        if (empty($oStdDiaTrabalho->ext75)) {
                            if (empty($oStdDiaTrabalho->ext100)) {
                                $lIgnorarData = true;
                            }
                        }
                    }
                }

                if (!$lIgnorarData) {
                    $this->aDados['datas'][] = $oStdDiaTrabalho;
                }

                $this->aDados['aHorasJornada'] = $aHorasJornada;

                if ($this->lTotalizadores) {
                    if (!$lIgnorarData) {
                        $this->getTotalizadores($oStdDiaTrabalho);
                    }
                }
            
                $this->aDados['totalHorasAssentamento'] = $this->totalizarHorasAssentamentosServidorNaData($oDiaTrabalho);
            }
        }

        $this->aDados['dadosGerais'] = $this->getDadosGerais();
        $this->aDados['observacoes'] = $this->aObservacoes;

        return $this->aDados;
    }

    /**
     * Retorna o total de um totalizador
     * @return string
     */
    public static function somarTotalizador($aTotalizador)
    {
        $nTotalMinutos = 0;

        if(is_array($aTotalizador) && !empty($aTotalizador)) {
            foreach ($aTotalizador as $horario) {
                if (empty($horario)) {
                    continue;
                }

                list($iHora, $iMinute) = explode(':', $horario);
                $nTotalMinutos += $iHora * 60;
                $nTotalMinutos += $iMinute;
            }
        }

        $iHoras = floor($nTotalMinutos / 60);
        $nTotalMinutos -= $iHoras * 60;

        return sprintf('%02d:%02d', $iHoras, $nTotalMinutos);
    }

    /**
     * @param $oHora
     * @return string
     */
    private function validaHoraZerada($oHora, $lTotalizador = false)
    {
        if ($oHora === null || $oHora === '') {
            return '';
        }

        $sHora = $oHora->format('H:i');

        if ($sHora == '00:00' && $lTotalizador) {
            return '';
        }

        return $sHora;
    }

    /**
     * @param $data
     * @param $matricula
     * @return array
     * @throws \DBException
     */
    private function getMarcacoesOriginais($data, $matricula)
    {
        $camposRegistros = array(
            'rh229_pis as pis',
            'rh229_matricula as matricula',
            'to_char(rh229_data, \'DD/MM/YYYY\'::text) as data',
            'to_char(rh229_hora, \'HH24:MI\'::text) as hora',
            'rh229_serial as serial',
        );

        $whereRegistros = array(
            "rh229_matricula  = {$matricula}",
            "rh229_data       = '{$data->getDate()}'"
        );

        $oDaoPontoEletronicoArquivoImportacaoRegistro = new \cl_pontoeletronicoarquivoimportacaoregistro;

        $sqlRegistros = $oDaoPontoEletronicoArquivoImportacaoRegistro->sql_query_file(null,
            ' DISTINCT ' . implode(', ', $camposRegistros), 'data, hora', implode(' AND ', $whereRegistros));
        $rsRegistros = db_query($sqlRegistros);

        if (!$rsRegistros) {
            throw new \DBException("Ocorreu um erro ao consultar as marcações originais do servidor. ({$matricula})");
        }

        if (pg_num_rows($rsRegistros) == 0) {
            return array();
        }

        return \db_utils::makeCollectionFromRecord($rsRegistros, function ($retorno) {
            return (object)array(
                'pis' => $retorno->pis,
                'matricula' => $retorno->matricula,
                'data' => $retorno->data,
                'hora' => $retorno->hora,
                'serial' => $retorno->serial
            );
        });
    }

    /**
     * @param $diaTrabalho
     * @return null|string
     * @throws \DBException
     * @throws \ParameterException
     */
    private function getHorasExtrasAutorizadasNaData($diaTrabalho)
    {
        $assentamentos = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($diaTrabalho->getServidor(),
            'S', $diaTrabalho->getData(), \Assentamento::NATUREZA_AUTORIZA_HORA_EXTRA);
        $horasAutorizadas = array();

        if (!empty($assentamentos)) {
            foreach ($assentamentos as $assentamento) {
                $horasAutorizadas[] = $assentamento->getHora();
                return $this->somarTotalizador($horasAutorizadas);
            }
        }

        return null;
    }

    /**
     * @param $diaTrabalho
     * @return bool
     * @throws \DBException
     * @throws \ParameterException
     */
    private function hasHorasExtrasManuais($diaTrabalho)
    {

        $assentamentos = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($diaTrabalho->getServidor(),
            'S', $diaTrabalho->getData(), \Assentamento::NATUREZA_HE_MANUAL);

        if (!empty($assentamentos)) {
            return true;
        }

        return false;
    }

    /**
     * @param $hora
     * @return float|int
     * @throws \Exception
     */
    private function converteHorasEmMinutos($hora)
    {
        $intervalo = new \DateInterval('PT0H0M');
        $minutos = 0;

        if ($hora != '') {
            $intervalo = BaseHora::converterStringHoraEmDateInterval($hora);
            $minutos = ($intervalo->h * 60) + $intervalo->i;
        }

        return $minutos;
    }

    /**
     * @param DiaTrabalhoModel $diaTrabalho
     * @return \stdClass[]
     */
    public function totalizarHorasAssentamentosServidorNaData(DiaTrabalho $diaTrabalho)
    {
        if (empty($this->totalHorasAssentamentos)) {
            $this->totalHorasAssentamentos = array_merge($this->totalizarHorasGeraisNaData($diaTrabalho), $this->totalizarAssentamentoNaDataPorNatureza($diaTrabalho), $this->totalizarAfastamentoNaData($diaTrabalho));
        }

        return $this->totalHorasAssentamentos;
    }

    /**
     * @param DiaTrabalhoModel $diaTrabalho
     * @param array $naturezas
     * @return \stdClass[]
     * @throws \DBException
     * @throws \ParameterException
     */
    private function totalizarAssentamentoNaDataPorNatureza(DiaTrabalho $diaTrabalho, $naturezas = array(\Assentamento::NATUREZA_JUSTIFICATIVA, \Assentamento::NATUREZA_ABONO_FALTA, \Assentamento::NATUREZA_AUTORIZA_HORA_EXTRA))
    {
        $justificativaRepository = new JustivicativaRepository();
        $tipoAssentamentosJustificativas = $justificativaRepository->getTipoAssentamentosConfigurados();

        $assentamentos = array();
        foreach ($naturezas as $natureza) {
            $assentamentosFiltrados = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($this->oServidor,
                'S',
                $diaTrabalho->getData(),
                $natureza);

            $assentamentosFiltrados = is_null($assentamentosFiltrados) ? array() : $assentamentosFiltrados;

            $assentamentos = array_merge($assentamentos, $assentamentosFiltrados);
        }

        if (count($assentamentos) > 0) {
            $assentamentos = array_filter($assentamentos, function ($assentamento) use ($tipoAssentamentosJustificativas) {
                if ($assentamento->getInstanciaTipoAssentamento()->getNatureza() != \Assentamento::NATUREZA_JUSTIFICATIVA) {
                    return $assentamento;
                }

                foreach ($tipoAssentamentosJustificativas as $tipoAssentamento) {
                    if ($tipoAssentamento->getCodigo() == $assentamento->getInstanciaTipoAssentamento()->getCodigo()) {
                        return $assentamento;
                    }
                }
            });
        }


        $assentamentosAgrupados = array();
        foreach ($assentamentos as $assentamento) {
            $assentamento->calcularHorasDiurnasNoturnasNoDia($diaTrabalho);

            if (!array_key_exists($assentamento->getTipoAssentamento(), $assentamentosAgrupados)) {
                $totalizadorAssentamento = new \stdClass();
                $totalizadorAssentamento->sequencial = $assentamento->getInstanciaTipoAssentamento()->getSequencial();
                $totalizadorAssentamento->descricao = $assentamento->getInstanciaTipoAssentamento()->getDescricao();
                $totalizadorAssentamento->natureza = $assentamento->getInstanciaTipoAssentamento()->getNatureza();
                $totalizadorAssentamento->horasDiurnas = $assentamento->getHoraDiurna();
                $totalizadorAssentamento->horasNoturnas = $assentamento->getHoraNoturna();

                $assentamentosAgrupados[$assentamento->getTipoAssentamento()] = $totalizadorAssentamento;
            } else {
                $horasDiurnas = $assentamentosAgrupados[$assentamento->getTipoAssentamento()]->horasDiurnas;
                $horasNoturnas = $assentamentosAgrupados[$assentamento->getTipoAssentamento()]->horasNoturnas;

                $assentamentosAgrupados[$assentamento->getTipoAssentamento()]->horasDiurnas = $this->somarTotalizador(array($horasDiurnas, $assentamento->getHoraDiurna()));
                $assentamentosAgrupados[$assentamento->getTipoAssentamento()]->horasNoturnas = $this->somarTotalizador(array($horasNoturnas, $assentamento->getHoraNoturna()));
            }
        }

        return $assentamentosAgrupados;
    }

    /**
     * @param DiaTrabalhoModel $diaTrabalho
     * @return \stdClass[]
     * @throws \DBException
     * @throws \ParameterException
     */
    private function totalizarAfastamentoNaData(DiaTrabalho $diaTrabalho)
    {
        $afastamentos = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($this->oServidor,
            'A',
            $diaTrabalho->getData());
        $afastamentos = is_null($afastamentos) ? array() : $afastamentos;

        $afastamentosAgrupados = array();
        foreach ($afastamentos as $afastamento) {
            $afastamento->calcularHorasDiurnasNoturnasNoDia($diaTrabalho);

            $totalizadorAssentamento = new \stdClass();
            $totalizadorAssentamento->sequencial = $afastamento->getInstanciaTipoAssentamento()->getSequencial();
            $totalizadorAssentamento->descricao = $afastamento->getInstanciaTipoAssentamento()->getDescricao();
            $totalizadorAssentamento->natureza = $afastamento->getInstanciaTipoAssentamento()->getNatureza();
            $totalizadorAssentamento->horasDiurnas = $afastamento->getHoraDiurna();
            $totalizadorAssentamento->horasNoturnas = $afastamento->getHoraNoturna();

            $assentamentosAgrupados[$afastamento->getTipoAssentamento()] = $totalizadorAssentamento;
        }

        return $afastamentosAgrupados;
    }

    /**
     * @return array
     */
    public function getTotalHorasAssentamentos()
    {
        return $this->totalHorasAssentamentos;
    }

    /**
     * @return array
     */
    public function getDados()
    {
        return $this->aDados;
    }

    /**
     * @return array
     */
    private function mapearParametrosGeraisTotalizadores()
    {
        return array(
            (object) array(
                'totalizador'=>'nTotalHorasNormais',
                'totalizadorNoturno'=>'nTotalHorasAdicional',
                'sequencial'=>'HorasNormais',
                'descricao'=>'Total Horas Trabalhadas',
            ),
            (object) array(
                'totalizador'=>'nTotalHorasFaltas',
                'totalizadorNoturno'=>'nTotalHorasFaltasNoturna',
                'sequencial'=>'HorasFaltas',
                'descricao'=>'Total Horas Faltas'),
            (object) array(
                'totalizador'=>'nTotalHorasExt50diurnas',
                'totalizadorNoturno'=>'nTotalHorasExt50noturnas',
                'sequencial'=>'HorasExt50',
                'descricao'=>'Total Horas Extra 50'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasExt75diurnas',
                'totalizadorNoturno'=>'nTotalHorasExt75noturnas',
                'sequencial'=>'HorasExt75',
                'descricao'=>'Total Horas Extra 75'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasExt100diurnas',
                'totalizadorNoturno'=>'nTotalHorasExt100noturnas',
                'sequencial'=>'HorasExt100',
                'descricao'=>'Total Horas Extra 100'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasAtrasoDesmembrado',
                'totalizadorNoturno'=>'nTotalHorasAtrasoNoturno',
                'sequencial'=>'HorasAtraso',
                'descricao'=>'Total Horas Atraso'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasSaidaAtencipada',
                'totalizadorNoturno'=>'nTotalHorasSaidaAtencipadaNoturna',
                'sequencial'=>'HorasSaidaAntecipada',
                'descricao'=>'Total Horas Saida Antecipada'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasExt50NaoAutorizadasdiurnas',
                'totalizadorNoturno'=>'nTotalHorasExt50NaoAutorizadasnoturnas',
                'sequencial'=>'HorasExt50NaoAutorizadas',
                'descricao'=>'Horas Extras Não Autorizadas 50%'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasExt75NaoAutorizadasdiurnas',
                'totalizadorNoturno'=>'nTotalHorasExt75NaoAutorizadasnoturnas',
                'sequencial'=>'HorasExt75NaoAutorizadas',
                'descricao'=>'Horas Extras Não Autorizadas 75%'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasExt100NaoAutorizadasdiurnas',
                'totalizadorNoturno'=>'nTotalHorasExt100NaoAutorizadasnoturnas',
                'sequencial'=>'HorasExt100NaoAutorizadas',
                'descricao'=>'Horas Extras Não Autorizadas 100%'
            ),
            (object) array(
                'totalizador'=>'nTotalHorasExtNaoAutorizadasnoturnas',
                'totalizadorNoturno'=>'nTotalHorasExtNaoAutorizadasdiurnas',
                'sequencial'=>'HorasExtNaoAutorizadas',
                'descricao'=>'Horas Extras Não Autorizadas'
            ),
        );
    }

    /**
     * @return array
     */
    private function totalizarHorasGeraisNaData()
    {
        $totalizador = array();

        $mapeadorParametrosGerais = $this->mapearParametrosGeraisTotalizadores();
        foreach ($mapeadorParametrosGerais as $mapeador) {
            $horas = $this->aDados[$mapeador->totalizador];
            $horasNoturnas = $this->aDados[$mapeador->totalizadorNoturno];

            $totalizadorAssentamento = new \stdClass();

            $totalizadorAssentamento->sequencial = $mapeador->sequencial;
            $totalizadorAssentamento->descricao = $mapeador->descricao;
            $totalizadorAssentamento->horasDiurnas = static::somarTotalizador($horas);
            $totalizadorAssentamento->horasNoturnas = static::somarTotalizador($horasNoturnas);

            $totalizador[$totalizadorAssentamento->sequencial] = $totalizadorAssentamento;
        }

        return $totalizador;
    }

    /**
     * @return DiaTrabalhoModel
     */
    public function getDiaTrabalhoCache()
    {
        return $this->diaTrabalhoCache;
    }

    /**
     * @param DiaTrabalhoModel $diaTrabalhoCache
     */
    public function setDiaTrabalhoCache($diaTrabalhoCache)
    {
        $this->diaTrabalhoCache = $diaTrabalhoCache;
    }
}
