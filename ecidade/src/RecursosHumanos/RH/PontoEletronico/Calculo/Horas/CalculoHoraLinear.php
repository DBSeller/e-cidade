<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas;

use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\RegraCalculo;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\V3\Extension\Logger;

/**
 * Classe para cálculo de horas extras em dias de eventos
 * Class CalculoExtraLinear
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class CalculoHoraLinear extends BaseHora
{

    /**
     * @var \DateTime
     */
    protected $horaInicio = null;

    /**
     * @var \DateTime
     */
    protected $horaFim    = null;

    /**
     * @var \DateTime
     */
    protected $oHorasExtra50                = null;

    /**
     * @var \DateTime
     */
    protected $oHorasExtra50Noturna         = null;

    /**
     * @var \DateTime
     */
    protected $oHorasExtra75                = null;

    /**
     * @var \DateTime
     */
    protected $oHorasExtra75Noturna         = null;

    /**
     * @var \DateTime
     */
    protected $oHorasExtra100               = null;

    /**
     * @var \DateTime
     */
    protected $oHorasExtra100Noturna        = null;

    /**
     * @var \DateTime
     */
    protected $oHorasAdicionalNoturno       = null;

    /**
     * @var \DateTime
     */
    protected $oHorasNoturnas  = null;

    /**
     * @var \DateTime
     */
    protected $oHorasDiurnas   = null;

    /**
     * @var Integer
     */
    protected $horaNoturnaInicio = null;

    /**
     * @var Integer
     */
    protected $horaNoturnaFim = null;

    /**
     * @var Integer
     */
    protected $horaNoturnaFimNoMesmoDia = null;

    /**
     *
     * @var Boolean
     */
    protected $lRevezamento = null;
  
    /**
     *
     * @var Boolean
     */
    protected $lExtraAutomatica = null;
  
    /**
     *
     * @var Boolean
     */
    protected $lAssentamentoAutorizaHE = null;

    /**
     * CalculoHoraLinear constructor.
     * @param DiaTrabalho $oDiaTrabalho
     */
    public function __construct(DiaTrabalho $oDiaTrabalho)
    {

        $this->setDiaTrabalho($oDiaTrabalho);
        parent::__construct();

        $iAnoDataAtual = $this->getDiaTrabalho()->getData()->getAno();
        $iMesDataAtual = $this->getDiaTrabalho()->getData()->getMes();
        $iDiaDataAtual = $this->getDiaTrabalho()->getData()->getDia();
        $marcacoes = $this->getDiaTrabalho()->getMarcacoes();
        $marcacoesSemAlteracao = $this->getDiaTrabalho()->getMarcacoesSemAlteracao();

        $this->lRevezamento = $oDiaTrabalho
            ->getServidor()
            ->getEscalas($oDiaTrabalho->getData())->getEscalaTrabalho()->isRevezamento();
        $this->lExtraAutomatica = $oDiaTrabalho->getServidor()
            ->getEscala($oDiaTrabalho->getData())
            ->getEscalaTrabalho()
            ->isExtraAutomaticaFeriado();
        $this->lAssentamentoAutorizaHE = (bool)$oDiaTrabalho->getHorasExtrasAutorizadas();

        if ($marcacoes->getMarcacaoEntrada1()->getMarcacao()) {
            $marcacaoEntrada = $marcacoes->getMarcacaoEntrada1()->getMarcacao();
        } else {
            $marcacaoEntrada = $marcacoes->getMarcacaoEntrada2()->getMarcacao();
        }

        if (!empty($marcacoesSemAlteracao)) {
            if ($marcacoesSemAlteracao->getMarcacaoEntrada1()->getMarcacao()) {
                $marcacaoEntradaSemAlteracao = $marcacoesSemAlteracao->getMarcacaoEntrada1()->getMarcacao();
            } else {
                $marcacaoEntradaSemAlteracao = $marcacoesSemAlteracao->getMarcacaoEntrada2()->getMarcacao();
            }
        }

        if ($marcacoes instanceof MarcacoesPontoCollection
            && $marcacoes->getMarcacaoEntrada1() != null
            && $marcacaoEntrada
        ) {
            $this->horaInicio = clone $marcacaoEntrada;

            if (!empty($marcacoesSemAlteracao)
                && $marcacoesSemAlteracao->getMarcacaoEntrada1() != null
                && $marcacoesSemAlteracao->getMarcacaoEntrada1()->getMarcacao() != null
            ) {
                if ($oDiaTrabalho->getJornada()->isDSR() || $oDiaTrabalho->getJornada()->isFolga()) {
                    $this->horaInicio = clone $marcacaoEntradaSemAlteracao;
                }

                if ($oDiaTrabalho->getFeriado() != null && !$this->diaGari()) {
                    if (($this->lAssentamentoAutorizaHE) || ($this->lExtraAutomatica && !$this->lRevezamento)) {
                        $this->horaInicio = clone $marcacaoEntradaSemAlteracao;
                    }
                }
            }
        }

        if ($marcacoes instanceof MarcacoesPontoCollection && $marcacoes->getUltimaMarcacaoComRegistro()) {
            $this->horaFim = clone $marcacoes->getUltimaMarcacaoComRegistro();

            if (!empty($marcacoesSemAlteracao) && $marcacoesSemAlteracao->getUltimaMarcacaoComRegistro() != null) {
                if ($oDiaTrabalho->getJornada()->isDSR() || $oDiaTrabalho->getJornada()->isFolga()) {
                    $this->horaFim = clone $marcacoesSemAlteracao->getUltimaMarcacaoComRegistro();
                }

                if ($oDiaTrabalho->getFeriado() != null) {
                    if (($this->lAssentamentoAutorizaHE) || ($this->lExtraAutomatica && !$this->lRevezamento)) {
                        $this->horaFim = clone $marcacoesSemAlteracao->getUltimaMarcacaoComRegistro();
                    }
                }
            }
        }

        $this->horaNoturnaInicio        = \DateTime::createFromFormat('H:i', '22:00');
        $this->horaNoturnaFim           = \DateTime::createFromFormat('H:i', '04:59');
        $this->horaNoturnaFimNoMesmoDia = \DateTime::createFromFormat('H:i', '04:59');

        $this->horaNoturnaInicio->setDate($iAnoDataAtual, $iMesDataAtual, $iDiaDataAtual);
        $this->horaNoturnaFim->setDate($iAnoDataAtual, $iMesDataAtual, $iDiaDataAtual);
        $this->horaNoturnaFimNoMesmoDia->setDate($iAnoDataAtual, $iMesDataAtual, $iDiaDataAtual);
        $this->horaNoturnaFim->modify('+1 day');

        $this->oHorasExtra50Noturna = \DateTime::createFromFormat(
            'Y-m-d H:i',
            $oDiaTrabalho->getData()->getDate() .' 0:00'
        );
        $this->oHorasExtra75Noturna = \DateTime::createFromFormat(
            'Y-m-d H:i',
            $oDiaTrabalho->getData()->getDate() .' 0:00'
        );
        $this->oHorasExtra100Noturna = \DateTime::createFromFormat(
            'Y-m-d H:i',
            $oDiaTrabalho->getData()->getDate() .' 0:00'
        );
        $this->oHorasNoturnas = \DateTime::createFromFormat('Y-m-d H:i', $oDiaTrabalho->getData()->getDate() .' 0:00');
        $this->oHorasExtra50 = \DateTime::createFromFormat('Y-m-d H:i', $oDiaTrabalho->getData()->getDate() .' 0:00');
        $this->oHorasExtra75 = \DateTime::createFromFormat('Y-m-d H:i', $oDiaTrabalho->getData()->getDate() .' 0:00');
        $this->oHorasExtra100 = \DateTime::createFromFormat('Y-m-d H:i', $oDiaTrabalho->getData()->getDate() .' 0:00');
        $this->oHorasDiurnas = \DateTime::createFromFormat('Y-m-d H:i', $oDiaTrabalho->getData()->getDate() .' 0:00');
    }

    protected function adicionarHorasExtras(\DateTime $momentoAtual, $tipoExtra)
    {

        /**
         * Verifica se está no intervalo noturno
         */
        if ($this->horaEstaNoIntervalo($momentoAtual, $this->horaNoturnaInicio, $this->horaNoturnaFim)
            || $momentoAtual->getTimestamp() <= $this->horaNoturnaFimNoMesmoDia->getTimestamp()) {
            switch ($tipoExtra) {
                case BaseHora::HORAS_EXTRA50:
                case BaseHora::HORAS_EXTRA50_NOTURNA:
                    $this->oHorasExtra50Noturna->add(new \DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA75:
                case BaseHora::HORAS_EXTRA75_NOTURNA:
                    $this->oHorasExtra75Noturna->add(new \DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA100:
                case BaseHora::HORAS_EXTRA100_NOTURNA:
                    $this->oHorasExtra100Noturna->add(new \DateInterval('PT1M'));
                    break;

                default:
                    $this->oHorasNoturnas->add(new \DateInterval('PT1M'));
                    break;
            }
        } else {
            switch ($tipoExtra) {
                case BaseHora::HORAS_EXTRA50:
                case BaseHora::HORAS_EXTRA50_NOTURNA:
                    $this->oHorasExtra50->add(new \DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA75:
                case BaseHora::HORAS_EXTRA75_NOTURNA:
                    $this->oHorasExtra75->add(new \DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA100:
                case BaseHora::HORAS_EXTRA100_NOTURNA:
                    $this->oHorasExtra100->add(new \DateInterval('PT1M'));
                    break;

                default:
                    $this->oHorasDiurnas->add(new \DateInterval('PT1M'));
                    break;
            }
        }
    }

    protected function popularHorasCalculadas()
    {

        $diaTrabalho = $this->getDiaTrabalho();

        $diaTrabalho->setHorasExtra50($this->oHorasExtra50->format('H:i'));
        $diaTrabalho->setHorasExtra75($this->oHorasExtra75->format('H:i'));
        $diaTrabalho->setHorasExtra100($this->oHorasExtra100->format('H:i'));

        $diaTrabalho->setHorasExtra50Noturna(
            BaseHora::converterEmHorasNoturnas($this->oHorasExtra50Noturna->format('H:i'))
        );
        $diaTrabalho->setHorasExtra75Noturna(
            BaseHora::converterEmHorasNoturnas($this->oHorasExtra75Noturna->format('H:i'))
        );
        $diaTrabalho->setHorasExtra100Noturna(
            BaseHora::converterEmHorasNoturnas($this->oHorasExtra100Noturna->format('H:i'))
        );
    }

    public function executarCalculo(MarcacoesPontoCollection $marcacoesCollection, RegraCalculo $regraCalculo)
    {
        $this->logger->debug("------------------------------------------------------");
        $this->logger->debug("----------------- CALCULO HORA LINEAR ----------------");
        $this->logger->debug("-- Executando regra: ". get_class($regraCalculo));

        $debug = "-- MarcacoesCollection....: ";
        $entrada1 = $marcacoesCollection->getMarcacaoEntrada1()->getMarcacao();
        $saida1   = $marcacoesCollection->getMarcacaoSaida1()->getMarcacao();
        $entrada2 = $marcacoesCollection->getMarcacaoEntrada2()->getMarcacao();
        $saida2   = $marcacoesCollection->getMarcacaoSaida2()->getMarcacao();
        $entrada3 = $marcacoesCollection->getMarcacaoEntrada3()->getMarcacao();
        $saida3   = $marcacoesCollection->getMarcacaoSaida3()->getMarcacao();

        if ($marcacoesCollection instanceof MarcacoesPontoCollection) {
            $debug .= implode('|', $marcacoesCollection->toArray());
        } else {
            $debug .= '[]';
        }
        $this->logger->debug($debug);
        
        if ($this->getDiaTrabalho()->getJornada() instanceof Jornada) {
            $debug .= implode('|', $this->getDiaTrabalho()->getJornada()->toArray());
        } else {
            $debug .= '[]';
        }
        $this->logger->debug($debug);

        $momentoAtual = clone $entrada1;
        $horaFim      = !empty($saida1) ? clone $saida1 : null;

        if (!empty($entrada2)) {
            $horaFim = clone $entrada2;
            
            if (!empty($saida2)) {
                $horaFim = clone $saida2;

                if (!empty($entrada3)) {
                    $horaFim = clone $entrada3;
            
                    if (!empty($saida3)) {
                        $horaFim = clone $saida3;
                    }
                }
            }
        }

        $debug = "-- MomentoAtual...........:";
        if ($momentoAtual instanceof \DateTime) {
            $debug .= $momentoAtual->format('H:i');
        } else {
            $debug .= '__:__';
        }
        $this->logger->debug($debug);

        $debug = "-- HoraFim................:";
        $debug .= ($horaFim instanceof \DateTime ? $horaFim->format('H:i') : '__:__');
        
        $this->logger->debug($debug);

        if (empty($momentoAtual) || empty($horaFim)) {
            $this->logger->debug('-- Não foi possível percorrer o intervalo para o cálculo de horas extras.');
            throw new BusinessException('Não foi possível percorrer o intervalo para o cálculo de horas extras.');
        }

        $momentoCalculado = array();

        do {
            $lCalculou = $regraCalculo->processar($momentoAtual);
            
            if ($lCalculou && $this->logger->getVerbosity() == Logger::DEBUG_5) {
                $momentoCalculado[$momentoAtual->format('H')][] = $momentoAtual->format('H:i');
            }

            $momentoAtual->modify('+1 minute');
        } while ($momentoAtual->getTimestamp() < $horaFim->getTimestamp());

        if ($this->logger->getVerbosity() == Logger::DEBUG_5) {
            $tempoCalculado = '';
            $this->logger->debug("-- Tempo Calculado --");

            foreach ($momentoCalculado as $horaCalculada => $minutoCalculado) {
                $tempoCalculado = implode(', ', $minutoCalculado);
                $this->logger->debug("-- ". ($tempoCalculado));
            }
        }
    }

    /**
     * @return \DateTime
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param \DateTime $horaInicio
     *
     * @return self
     */
    public function setHoraInicio(\DateTime $horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHoraFim()
    {
        return $this->horaFim;
    }

    /**
     * @param \DateTime $horaFim
     *
     * @return self
     */
    public function setHoraFim(\DateTime $horaFim)
    {
        $this->horaFim = $horaFim;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasExtra50()
    {
        return $this->oHorasExtra50;
    }

    /**
     * @param \DateTime $oHorasExtra50
     *
     * @return self
     */
    public function setHorasExtra50(\DateTime $oHorasExtra50)
    {
        $this->oHorasExtra50 = $oHorasExtra50;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasExtra50Noturna()
    {
        return $this->oHorasExtra50Noturna;
    }

    /**
     * @param \DateTime $oHorasExtra50Noturna
     *
     * @return self
     */
    public function setHorasExtra50Noturna(\DateTime $oHorasExtra50Noturna)
    {
        $this->oHorasExtra50Noturna = $oHorasExtra50Noturna;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasExtra75()
    {
        return $this->oHorasExtra75;
    }

    /**
     * @param \DateTime $oHorasExtra75
     *
     * @return self
     */
    public function setHorasExtra75(\DateTime $oHorasExtra75)
    {
        $this->oHorasExtra75 = $oHorasExtra75;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasExtra75Noturna()
    {
        return $this->oHorasExtra75Noturna;
    }

    /**
     * @param \DateTime $oHorasExtra75Noturna
     *
     * @return self
     */
    public function setHorasExtra75Noturna(\DateTime $oHorasExtra75Noturna)
    {
        $this->oHorasExtra75Noturna = $oHorasExtra75Noturna;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasExtra100()
    {
        return $this->oHorasExtra100;
    }

    /**
     * @param \DateTime $oHorasExtra100
     *
     * @return self
     */
    public function setHorasExtra100(\DateTime $oHorasExtra100)
    {
        $this->oHorasExtra100 = $oHorasExtra100;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasExtra100Noturna()
    {
        return $this->oHorasExtra100Noturna;
    }

    /**
     * @param \DateTime $oHorasExtra100Noturna
     *
     * @return self
     */
    public function setHorasExtra100Noturna(\DateTime $oHorasExtra100Noturna)
    {
        $this->oHorasExtra100Noturna = $oHorasExtra100Noturna;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasAdicionalNoturno()
    {
        return $this->oHorasAdicionalNoturno;
    }

    /**
     * @param \DateTime $oHorasAdicionalNoturno
     *
     * @return self
     */
    public function setHorasAdicionalNoturno(\DateTime $oHorasAdicionalNoturno)
    {
        $this->oHorasAdicionalNoturno = $oHorasAdicionalNoturno;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasNoturnas()
    {
        return $this->oHorasNoturnas;
    }

    /**
     * @param \DateTime $oHorasNoturnas
     *
     * @return self
     */
    public function setHorasNoturnas(\DateTime $oHorasNoturnas)
    {
        $this->oHorasNoturnas = $oHorasNoturnas;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHorasDiurnas()
    {
        return $this->oHorasDiurnas;
    }

    /**
     * @param \DateTime $oHorasDiurnas
     *
     * @return self
     */
    public function setHorasDiurnas(\DateTime $oHorasDiurnas)
    {
        $this->oHorasDiurnas = $oHorasDiurnas;

        return $this;
    }

    /**
     * @return Integer
     */
    public function getHoraNoturnaInicio()
    {
        return $this->horaNoturnaInicio;
    }

    /**
     * @param Integer $horaNoturnaInicio
     *
     * @return self
     */
    public function setHoraNoturnaInicio(Integer $horaNoturnaInicio)
    {
        $this->horaNoturnaInicio = $horaNoturnaInicio;

        return $this;
    }

    /**
     * @return Integer
     */
    public function getHoraNoturnaFim()
    {
        return $this->horaNoturnaFim;
    }

    /**
     * @param Integer $horaNoturnaFim
     *
     * @return self
     */
    public function setHoraNoturnaFim(Integer $horaNoturnaFim)
    {
        $this->horaNoturnaFim = $horaNoturnaFim;

        return $this;
    }

    /**
     * @return Integer
     */
    public function getHoraNoturnaFimNoMesmoDia()
    {
        return $this->horaNoturnaFimNoMesmoDia;
    }

    /**
     * @param Integer $horaNoturnaFimNoMesmoDia
     *
     * @return self
     */
    public function setHoraNoturnaFimNoMesmoDia(Integer $horaNoturnaFimNoMesmoDia)
    {
        $this->horaNoturnaFimNoMesmoDia = $horaNoturnaFimNoMesmoDia;

        return $this;
    }

    private function diaGari()
    {
        if ($this->getDiaTrabalho()->getServidor()->getCodigoRegime() != 1810) {
            return false;
        }
        $data = $this->getDiaTrabalho()->getData()->getMes() . '-' . $this->getDiaTrabalho()->getData()->getDia();
        if ($data != '05-16') {
            return false;
        }

        return true;
    }
}
