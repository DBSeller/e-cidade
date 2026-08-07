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

use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\V3\Extension\Logger;

/**
 * Classe para cálculo de horas extras em dias de eventos
 * Class CalculoExtraLinear
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class CalculoExtraLinear extends CalculoHoraLinear implements HorasLinear {

    /**
     *
     * @var \DateTime
     */
    private $marcacaoInicioAjustado = null;

    /**
     *
     * @var \DateTime
     */
    private $marcacaoFinalAjustado = null;


    /**
     * ExtraEvento constructor.
     * @param DiaTrabalho $oDiaTrabalho
     */
    public function __construct(DiaTrabalho $oDiaTrabalho) {

        parent::__construct($oDiaTrabalho);

        $marcacoes          = $this->getDiaTrabalho()->getMarcacoesSemAlteracao();
        $marcacoesAjustadas = $this->getDiaTrabalho()->getMarcacoes();

        $this->logger->debug("-- MARCACOES AJUSTADAS.....: ". (!empty($marcacoesAjustadas) ? implode('|', $marcacoesAjustadas->toArray()) : ''));
        $this->logger->debug("-- MARCACOES...............: ". (!empty($marcacoes)          ? implode('|', $marcacoes->toArray())          : ''));

    $marcacaoEntrada1         = ($marcacoes->getMarcacaoEntrada1()->getMarcacao()          ? $marcacoes->getMarcacaoEntrada1()->getMarcacao()          : $marcacoes->getMarcacaoEntrada2()->getMarcacao());
    $marcacaoEntrada1Ajustada = ($marcacoesAjustadas->getMarcacaoEntrada1()->getMarcacao() ? $marcacoesAjustadas->getMarcacaoEntrada1()->getMarcacao() : $marcacoesAjustadas->getMarcacaoEntrada2()->getMarcacao());

    if($marcacoes instanceof MarcacoesPontoCollection && $marcacaoEntrada1) {
        $this->marcacaoInicioAjustado = clone $marcacaoEntrada1Ajustada;

            if($oDiaTrabalho->getJornada()->isDSR() || $oDiaTrabalho->getJornada()->isFolga()) {
            $this->marcacaoInicioAjustado = clone $marcacaoEntrada1;
            }

            if($oDiaTrabalho->getFeriado() != null) {

                if(($this->lRevezamento && $this->lAssentamentoAutorizaHE) || ($this->lExtraAutomatica && !$this->lRevezamento)) {

            $this->marcacaoInicioAjustado = clone $marcacaoEntrada1;
                }
            }
        }

        if($marcacoes instanceof MarcacoesPontoCollection && $marcacoes->getUltimaMarcacaoComRegistro()) {
            $this->marcacaoFinalAjustado = clone $marcacoesAjustadas->getUltimaMarcacaoComRegistro();

            if($oDiaTrabalho->getJornada()->isDSR() || $oDiaTrabalho->getJornada()->isFolga()) {
                $this->marcacaoFinalAjustado = clone $marcacoes->getUltimaMarcacaoComRegistro();
            }

            if($oDiaTrabalho->getFeriado() != null && $oDiaTrabalho->getHorasExtrasAutorizadas() != null) {
                $this->marcacaoFinalAjustado = clone $marcacoes->getUltimaMarcacaoComRegistro();
            }
        }

        /**
         * Caso a jornada for de revezametno,e for feriado, contamos como dia trabalhado normalmente.
         * Devemos levar em conta as tolerancias
         */
        if ($this->lRevezamento && $this->getDiaTrabalho()->getJornada()->isDiaTrabalhado()) {

            if(!empty($this->horaInicio)) {

                $diferencaInicio = $this->horaInicio->diff($this->marcacaoInicioAjustado);
                if (BaseHora::converterIntervaloEmMinutos($diferencaInicio) <= 10) {
                    $this->horaInicio = $this->marcacaoInicioAjustado;
                }
            }

            if(!empty($this->horaFim)) {

                $diferencaFim = $this->horaFim->getMarcacao()->diff($this->marcacaoFinalAjustado->getMarcacao());
                if (BaseHora::converterIntervaloEmMinutos($diferencaFim) <= 10) {
                    $this->horaFim = $this->marcacaoFinalAjustado;
                }
            }
        }
    }

    public function calcular(\DateTime $oHorasExtra50, \DateTime $oHorasExtra50Noturna, \DateTime $oHorasExtra75, \DateTime $oHorasExtra75Noturna, \DateTime $oHorasExtra100, \DateTime $oHorasExtra100Noturna, \DateTime $oHorasAdicionalNoturno) {

        $horasdaJornada      = $this->getDiaTrabalho()->getJornada()->getHoras();
        $horaInicioJornada   = $horasdaJornada[0]->oHora;
        $horaFimJornada      = $horasdaJornada[count($horasdaJornada) -1]->oHora;

        $horaInicio   = $this->horaInicio;
        $horaFim      = $this->horaFim;

        if(empty($horaInicio) || empty($horaFim)) {

            $this->logger->debug("-- Hora Inicio.............: ". (empty($horaInicio) ? ' Vazio' : ($horaInicio instanceof \DateTime                  ? $horaInicio->format('H:i')                : '')));
            $this->logger->debug("-- Hora Fim................: ". (empty($horaFim)    ? ' Vazio' : ($horaFim->getMarcacao() instanceof \DateTime      ? $horaFim->getMarcacao()->format('H:i')    : '')));
            return;
        }

        $momentoAtual            = clone $horaInicio;
        $oDiaTrabalho            = $this->getDiaTrabalho();
        $lRevezamento            = $this->lRevezamento;
        $lExtraAutomatica        = $this->lExtraAutomatica;
        $lAssentamentoAutorizaHE = $this->lAssentamentoAutorizaHE;

        $this->oHorasExtra50          = $oHorasExtra50;
        $this->oHorasExtra50Noturna   = $oHorasExtra50Noturna;
        $this->oHorasExtra75          = $oHorasExtra75;
        $this->oHorasExtra75Noturna   = $oHorasExtra75Noturna;
        $this->oHorasExtra100         = $oHorasExtra100;
        $this->oHorasExtra100Noturna  = $oHorasExtra100Noturna;
        $this->oHorasAdicionalNoturno = $oHorasAdicionalNoturno;
        $lHasIntervalo  = false;
        $lHasIntervalo2 = false;

        $marcacoes = $oDiaTrabalho->getMarcacoesSemAlteracao();
        if ($this->getDiaTrabalho()->getFeriado() !== null && ($lRevezamento && $lExtraAutomatica)) {
            $marcacoes = $oDiaTrabalho->getMarcacoes();
        }

        $this->logger->debug("-- Hora Inicio Jornada.....: ". ($horaInicioJornada instanceof \DateTime           ? $horaInicioJornada->format('H:i')                             : ''));
        $this->logger->debug("-- Hora Fim Jornada........: ". ($horaFimJornada instanceof \DateTime              ? $horaFimJornada->format('H:i')                                : ''));
        $this->logger->debug("-- Hora Inicio.............: ". ($horaInicio instanceof \DateTime                  ? $horaInicio->format('H:i')                                    : ''));
        $this->logger->debug("-- Hora Fim................: ". ($horaFim->getMarcacao() instanceof \DateTime      ? $horaFim->getMarcacao()->format('H:i')                        : ''));
        $this->logger->debug("-- Hora Fim Tipo...........: ". ($horaFim instanceof MarcacaoPonto                 ? MarcacaoPonto::getDescricaoTipoMarcacao($horaFim->getTipo())  : ''));

        if($horaFim->isMarcacaoSaida() || $horaFim->getTipo() != MarcacaoPonto::ENTRADA_1) {

            if($horaFim->getTipo() != MarcacaoPonto::SAIDA_1) {

                $saida1        = $marcacoes->getMarcacaoSaida1()->getMarcacao();
                $entrada2      = $marcacoes->getMarcacaoEntrada2()->getMarcacao();

                if (!empty($saida1) && !empty($entrada2)) {

                    $this->logger->debug("-- Tem intervalo 1.........: ". $saida1->format('H:i') .'|'. $entrada2->format('H:i'));
                    $lHasIntervalo = true;
                }

                if($horaFim->isMarcacaoSaida() || $horaFim->getTipo() == MarcacaoPonto::ENTRADA_3) {

                    /**
                     *Se a hora fim é a saída 3, logo tem mais de um intervalo
                     */
                    if($horaFim->getTipo() == MarcacaoPonto::SAIDA_3) {

                        $saida2         = $marcacoes->getMarcacaoSaida2()->getMarcacao();
                        $entrada3       = $marcacoes->getMarcacaoEntrada3()->getMarcacao();

                        if (!empty($saida2) && !empty($entrada3)) {

                            $this->logger->debug("-- Tem intervalo 2.........: ". $saida2->format('H:i') .'|'. $entrada3->format('H:i'));
                            $lHasIntervalo2 = true;
                        }
                    }
                }
            }
        }

        $minutosCalculados  = 0;
        $minutosAutorizados = 0;

        $dateTimeExtrasAutorizadas = $oDiaTrabalho->getHorasExtrasAutorizadas();
        if($dateTimeExtrasAutorizadas) {

            list($hora, $minuto) = explode(":", $dateTimeExtrasAutorizadas->format('H:i'));
            $minutosAutorizados = $hora * 60 + $minuto;
        }

        $this->logger->debug("-- Minutos Autorizados.....: ". $minutosAutorizados);
        $momentoIgnorado  = array();
        $momentoCalculado = array();

        do{

            $lExtra = null;

            // if($oDiaTrabalho->getJornada()->isDSR() || $oDiaTrabalho->getFeriado()) {

            if($lHasIntervalo) {

                /**
                 * Se o momento atual que estou percorrendo está no intervalo então ignora
                 */
                if($this->horaEstaNoIntervalo($momentoAtual, $saida1, $entrada2) && $momentoAtual->getTimeStamp() != $entrada2->getTimeStamp()) {

                    if($this->logger->getVerbosity() == Logger::DEBUG_5) {
                        $momentoIgnorado['lHasIntervalo'][$momentoAtual->format('H')][] = $momentoAtual->format('H:i');
                    }

                    $momentoAtual->modify('+1 minute');
                    continue;
                }

                // Se possui mais de um intervalo
                if($lHasIntervalo2) {

                    if($this->horaEstaNoIntervalo($momentoAtual, $saida2, $entrada3) && $momentoAtual->getTimeStamp() != $entrada3->getTimeStamp()) {

                        if($this->logger->getVerbosity() == Logger::DEBUG_5) {
                            $momentoIgnorado['lHasIntervalo2'][$momentoAtual->format('H')][] = $momentoAtual->format('H:i');
                        }

                        $momentoAtual->modify('+1 minute');
                        continue;
                    }
                }
            }

            /**
             * Se feriado e escala de revezamento devo verificar se a hora atual percorrida está fora da jornada,
             * se estiver só paga o limite autorizado, se estiver dentro da jornada paga como extra
             */
            if($oDiaTrabalho->getFeriado() && ($lRevezamento || $lExtraAutomatica)) {

                /**
                 * Verifica se o momento atual está fora da jornada
                 */
                if($lRevezamento && !$lAssentamentoAutorizaHE && !$this->horaEstaNoIntervalo($momentoAtual, $horaInicioJornada, $horaFimJornada)) {

                    if($minutosAutorizados == 0) {

                        if($this->logger->getVerbosity() == Logger::DEBUG_5) {
                            $momentoIgnorado['feriado_escalaRevezamento_ou_extraAutomatica'][$momentoAtual->format('H')][] = $momentoAtual->format('H:i');
                        }

                        $momentoAtual->modify('+1 minute');
                        continue;
                    }
                } else if($lRevezamento && !$lAssentamentoAutorizaHE && !$lExtraAutomatica) {

                    if($this->logger->getVerbosity() == Logger::DEBUG_5) {
                        $momentoIgnorado['feriado_escalaRevezamento_semExtraAutomatica_semAssentamento'][$momentoAtual->format('H')][] = $momentoAtual->format('H:i');
                    }

                    $momentoAtual->modify('+1 minute');
                    continue;
                }

            } else {

                if($minutosAutorizados <= 0) {

                    if($this->logger->getVerbosity() == Logger::DEBUG_5) {
                        $momentoIgnorado['minutosCalculados_maior_autorizados'][$momentoAtual->format('H')][] = $momentoAtual->format('H:i');
                    }

                    $momentoAtual->modify('+1 minute');
                    continue;
                }
            }

            if($oDiaTrabalho->getJornada()->isDSR() || $oDiaTrabalho->getFeriado() || $oDiaTrabalho->getData()->getDiaSemana() == \DBDate::DOMINGO) {
                $lExtra = BaseHora::HORAS_EXTRA100;
            } else {

                if($oDiaTrabalho->getJornada()->isFolga()) {
                    $lExtra = BaseHora::HORAS_EXTRA50;
                }
            }

            $this->adicionarHorasExtras($momentoAtual, $lExtra);

            if($minutosAutorizados > 0) {
                $minutosAutorizados--;
            }

            if($this->logger->getVerbosity() == Logger::DEBUG_5) {
                $momentoCalculado[$momentoAtual->format('H')][] = $momentoAtual->format('H:i');
            }

            $momentoAtual->modify('+1 minute');
            $minutosCalculados++;

        } while ($momentoAtual->getTimestamp() < $horaFim->getMarcacao()->getTimestamp());


        if($this->logger->getVerbosity() == Logger::DEBUG_5) {

            $tempoCalculado = '';
            foreach ($momentoCalculado as $horaCalculada => $minutoCalculado) {

                $tempoCalculado .= implode(', ', $minutoCalculado);
                $tempoCalculado .= PHP_EOL;
            }
            $this->logger->debug("-- Tempo Calculado.........: \n". ($tempoCalculado));
        }

        $this->logger->debug("-- Minutos Calculados......: ". ($minutosCalculados));
        $this->logger->debug("-- Horas Extras Diurnas....: ". ($this->getHorasDiurnas() instanceof \DateTime  ? $this->getHorasDiurnas()->format('H:i')  : '' ));
        $this->logger->debug("-- Horas Extras Noturnas...: ". ($this->getHorasNoturnas() instanceof \DateTime ? $this->getHorasNoturnas()->format('H:i') : '' ));

        if($this->logger->getVerbosity() == Logger::DEBUG_5) {

            $tempoIgnorado = '';
            foreach ($momentoIgnorado as $localIgnorada => $horasIgnoradas) {

                $tempoIgnorado .= $localIgnorada . ': ';

                foreach ($horasIgnoradas as $horaIgnorada => $minutoIgnorado) {

                    $tempoIgnorado .= implode(', ', $minutoIgnorado);
                    $tempoIgnorado .= PHP_EOL;
                }

                $tempoIgnorado .= PHP_EOL;
            }
            $this->logger->debug("-- Tempo IGNORADO .........: \n". ($tempoIgnorado));
        }

        $this->popularHorasCalculadas();
    }
}
