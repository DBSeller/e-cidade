<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta;

/**
 * Classe responsável pelo cálculo das horas falta de atraso de um servidor em um dia de trabalho
 * Class FaltaAtraso
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class FaltaAtraso extends BaseHora implements Horas
{
    /**
     * @var \DateTime
     */
    private $oDiaFalta;

    /**
     * @var \DateTime
     */
    private $oDiaFaltaNoturna;

    /**
     * @var null|string
     */
    private $horaAssentamentoAbonoFalta = null;

    /**
     * @var bool
     */
    private $assentamentoAbonoFaltaGeraFalta = false;


    /**
     * @var AssentamentoAbonoFalta
     */
    private $listaAssentamentosAbonoFaltaGera = null;

    /**
     * FaltaAtraso constructor.
     * @param DiaTrabalho $oDiaTrabalho
     */
    public function __construct(DiaTrabalho $oDiaTrabalho)
    {

        $this->setDiaTrabalho($oDiaTrabalho);
        $this->setTipoHora(BaseHora::HORAS_FALTA);
        parent::__construct();

        $intervaloFaltaAbonada = $this->getHoraZerada();
        $aAssentamentosAbonofalta = $this->getDiaTrabalho()->getAssentamentosAbonofalta();

        if ($aAssentamentosAbonofalta) {
            foreach ($aAssentamentosAbonofalta as $oAssentamentoAbonofalta) {
                if ($oAssentamentoAbonofalta instanceof AssentamentoAbonoFalta && preg_match('/\d+\:\d+/', $oAssentamentoAbonofalta->getHora())) {
                    $intervaloFaltaAbonada->add(BaseHora::converterStringHoraEmDateInterval($oAssentamentoAbonofalta->getHora()));
                    $this->assentamentoAbonoFaltaGeraFalta = $oAssentamentoAbonofalta->getInstanciaTipoAssentamento()->isGerarFaltas();
                    $this->listaAssentamentosAbonoFaltaGera = $oAssentamentoAbonofalta;
                }
            }

            if(!$this->assentamentoAbonoFaltaGeraFalta) {
                $this->horaAssentamentoAbonoFalta = BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterDateTimeEmMinutos($intervaloFaltaAbonada));
            }
        }
    }

    /**
     * Calcula o número de horas falta em determinado dia
     * @return string
     * @throws \Exception
     */
    public function calcular()
    {
        $this->logger->debug("------------------------------------------------------");
        $this->logger->debug("----------- CALCULO DE HORAS DE ATRASO ---------------");

        $horasFalta = $this->calcularHorasFalta();
        $this->calcularHorasNoturna();

        return $horasFalta;
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    protected function calcularHorasFalta()
    {
        $this->oDiaFalta = $this->getHoraZerada();
        $marcacoesReais = $this->getDiaTrabalho()->getMarcacoesSemAlteracao();
        $jornada = $this->getDiaTrabalho()->getJornada();

        $horasTrabalhadas = $this->getHorasTrabalhadas();
        $horasTrabalhadas->setDate(
          $this->getDiaTrabalho()->getData()->getAno(),
          $this->getDiaTrabalho()->getData()->getMes(),
          $this->getDiaTrabalho()->getData()->getDia()
        );

        $cargaHoraria = $this->getDiaTrabalho()->getJornada()->getCargaHoraria();
        $cargaHoraria->setDate(
          $this->getDiaTrabalho()->getData()->getAno(),
          $this->getDiaTrabalho()->getData()->getMes(),
          $this->getDiaTrabalho()->getData()->getDia()
        );

        if($this->getDiaTrabalho()->getFeriado() != null) {

            /**
             * @todo REFATORAR POR FAVOR ISSO
             * Validação fixa para o dia do gari, que não calcula saída antecipada
             */
            if($this->getDiaTrabalho()->getServidor()->getCodigoRegime() != 1810 && $this->getDiaTrabalho()->getData()->getDate() != '2018-05-16') {
                return $this->getHoraZerada();
            }
        }

        if($jornada->isDSR() || $jornada->isFolga()) {
            return $this->getHoraZerada();
        }

        if($marcacoesReais->getQuantidadeMarcacoes() == 1) {
            return $this->getHoraZerada();
        }

        $this->calcularFaltasPelaMarcacao($marcacoesReais, $jornada);

        $debug = "-- Hora Atraso Calculada......: ". (($this->oDiaFalta instanceof \DateTime) ? $this->oDiaFalta->format('H:i') : '_:__');
        $this->logger->debug($debug);

        return $this->posCalcular($this->oDiaFalta);
    }

    /**
     * @param MarcacoesPontoCollection $marcacoesReais
     * @param Jornada $jornada
     * @return \DateTime
     */
    private function calcularFaltasPelaMarcacao(MarcacoesPontoCollection $marcacoesReais, Jornada $jornada)
    {
        $primeiraHoraJornada = $jornada->getInicioJornada();
        $primeiraMarcacao = $marcacoesReais->getPrimeiraMarcacaoComRegistro();
        $toleranciaIntervalo = 5;
        $horasJornada = $jornada->getHoras();

        if($primeiraMarcacao == null || $primeiraMarcacao->getMarcacao() == null) {
            return $this->oDiaFalta;
        }

        if($marcacoesReais->getMarcacaoSaida2() != null && $marcacoesReais->getMarcacaoEntrada1() != null) {
            if($marcacoesReais->getMarcacaoEntrada1()->getJustificativa() != null && $marcacoesReais->getMarcacaoEntrada1()->getJustificativa()->isAbono()) {
                return $this->oDiaFalta;
            }

            $ultimaMarcacaoPonto = $marcacoesReais->getMarcacaoSaida2()->getMarcacao();
            $primeiraMarcacaoPonto = $marcacoesReais->getMarcacaoEntrada1()->getMarcacao();

            if ($ultimaMarcacaoPonto == null && $primeiraMarcacaoPonto == null && $marcacoesReais->getQuantidadeMarcacoes() == 2  && $jornada->temIntervalo()) {
                return $this->oDiaFalta;
            }
        }

        if($primeiraMarcacao->getMarcacao()->getTimestamp() > $primeiraHoraJornada->getTimestamp() && !$primeiraMarcacao->estaNaTolerancia($this->getDiaTrabalho(), MarcacaoPonto::ENTRADA_1))
        {
            $this->logger->debug("-- Entrando no percorreMinutoAMinuto");

            $debug = "-- Marcacao................: ". (($primeiraMarcacao->getMarcacao() instanceof \DateTime)  ? $primeiraMarcacao->getMarcacao()->format('H:i')      : '_:__');
            if($primeiraMarcacao instanceof MarcacaoPonto) {
                $debug .= " - Tipo: ". (MarcacaoPonto::getDescricaoTipoMarcacao($primeiraMarcacao->getTipo()));
            }
            $this->logger->debug($debug);

            $debug = "-- Hora Jornada............: ". (($primeiraHoraJornada instanceof \DateTime) ? $primeiraHoraJornada->format('H:i') : '_:__');
            $this->logger->debug($debug);

            $this->percorreMinutoAMinuto($primeiraMarcacao->getMarcacao(), $primeiraHoraJornada);
        }

        if($jornada->temIntervalo()) {

            if($marcacoesReais->getQuantidadeMarcacoes() == 2) {
                if($primeiraMarcacao->getMarcacao()->getTimestamp() > $horasJornada[1]->oHora->getTimestamp()) {
                    $this->oDiaFalta->sub($jornada->getIntervalo());
                }
            }

            if($marcacoesReais->getQuantidadeMarcacoes() == 4) {

                $marcacaoSaida1 = $marcacoesReais->getMarcacaoSaida1()->getMarcacao();
                $marcacaoEntrada2 = $marcacoesReais->getMarcacaoEntrada2()->getMarcacao();

                $horaIntervaloJornada = new \DateTime('00:00');
                $horaIntervaloJornada->add($jornada->getIntervalo());

                $horaIntervaloMarcacao = new \DateTime('00:00');
                $horaIntervaloMarcacao->add($marcacaoSaida1->diff($marcacaoEntrada2));

                $diferencaEntreIntervaloJornadaMarcacao = $horaIntervaloJornada->diff($horaIntervaloMarcacao);
                $totalMinutosDiferenca = ($diferencaEntreIntervaloJornadaMarcacao->h * 60) + $diferencaEntreIntervaloJornadaMarcacao->i;

                if ($totalMinutosDiferenca > $toleranciaIntervalo) {
                    $horaJornadaAtualizada = clone $marcacaoSaida1;
                    $horaJornadaAtualizada->add($jornada->getIntervalo());

                    $this->logger->debug("-- Entrando no percorreMinutoAMinuto");

                    $debug = "-- Marcacao................: ". (($marcacaoEntrada2 instanceof \DateTime)  ? $marcacaoEntrada2->format('H:i')      : '_:__');
                    if($marcacoesReais->getMarcacaoEntrada2() instanceof MarcacaoPonto) {
                        $debug .= " Tipo: ". (MarcacaoPonto::getDescricaoTipoMarcacao($marcacoesReais->getMarcacaoEntrada2()->getTipo()));
                    }
                    $this->logger->debug($debug);

                    $debug = "-- Hora Jornada............: ". (($horaJornadaAtualizada instanceof \DateTime) ? $horaJornadaAtualizada->format('H:i') : '_:__');
                    $this->logger->debug($debug);

                    $this->percorreMinutoAMinuto($marcacaoEntrada2, $horaJornadaAtualizada);
                }
            }
        }

        return $this->oDiaFalta;
    }

    /**
     * @todo Remover função para utilizar percorreMinutoAMinuto da classe pai BaseHora
     * Percorre cada minuto da marcação, caso seja maior que o horário da jornada, verificando se está dentro do período
     * diurno ou noturno( neste caso, convertendo para cálculo de horas noturnas )
     *
     * @deprecated
     * @param \DateTime $marcacao
     * @param \DateTime $horaJornada
     */
    public function percorreMinutoAMinuto(\DateTime $marcacao, \DateTime $horaJornada)
    {
        if ($marcacao->getTimestamp() <= $horaJornada->getTimestamp()) {
            return;
        }

        $minutosDiurnos = 0;

        $horaAtual = clone $horaJornada;
        $horaFinal = clone $marcacao;

        do {
            $horaAtual->modify('+1 minute');
            $minutosDiurnos++;
        } while ($horaAtual->getTimestamp() < $horaFinal->getTimestamp());

        $debug  = '-- Minutos diurnos.........: '. $minutosDiurnos;
        if($minutosDiurnos > 0) {
            $debug .= ' - ';
            $debug .= BaseHora::converterMinutosEmHoraMinuto($minutosDiurnos);
        }
        $this->logger->debug($debug);

        $this->oDiaFalta->modify("+ {$minutosDiurnos} minutes");
    }

    /**
     * @param $horasFalta
     * @return \DateTime
     * @throws \Exception
     */
    protected function posCalcular(\DateTime $horasFalta)
    {
        $this->logger->debug("-- POS CALCULO DE ATRASO");

        if($this->getDiaTrabalho()->isAfastado()) {

            $afastamento                 = $this->getDiaTrabalho()->getAfastamento();
            $tipoAssentamentoAfastamento = $afastamento->getInstanciaTipoAssentamento();

            $debug  = '-- Servidor afastado: ';
            $debug .= $tipoAssentamentoAfastamento->getSequencial();
            $debug .= ' - '. $tipoAssentamentoAfastamento->getCodigo();
            $debug .= ' - '. $tipoAssentamentoAfastamento->getDescricao();
            $this->logger->debug($debug);

            $msgTipoAssentamentoGeraFaltas = 'SIM';

            if(!$tipoAssentamentoAfastamento->isGerarFaltas()) {

                $msgTipoAssentamentoGeraFaltas = 'NAO';
                $horasFalta->setTime(0, 0);
            }

            $debug  = '-- Afastamento gera faltas....: '. $msgTipoAssentamentoGeraFaltas;
            $this->logger->debug($debug);

        } else if (!$this->assentamentoAbonoFaltaGeraFalta) {

            $debug = '-- Servidor possui assentamento de abono falta';
            $this->logger->debug($debug);

            if (!empty($this->horaAssentamentoAbonoFalta) && preg_match('/\d+\:\d+/', $this->horaAssentamentoAbonoFalta)) {

                $debug = '-- Hora do assentamento de abono......:' . $this->horaAssentamentoAbonoFalta;
                $this->logger->debug($debug);

                list($hora, $minuto) = explode(':', $this->horaAssentamentoAbonoFalta);
                $intervaloSubtrair = new \DateInterval("PT{$hora}H{$minuto}M");
                $horasSubtrair = \DateTime::createFromFormat('H:i', "{$hora}:{$minuto}");
                $horasSubtrair->setDate(
                  $this->getDiaTrabalho()->getData()->getAno(),
                  $this->getDiaTrabalho()->getData()->getMes(),
                  $this->getDiaTrabalho()->getData()->getDia()
                );

                $minutosDeAbono = BaseHora::converterIntervaloEmMinutos($intervaloSubtrair);
                $minutosDeFalta = BaseHora::converterDateTimeEmMinutos($horasFalta);
                $saldoMinutosDeAbono =  $minutosDeAbono - $minutosDeFalta;
                if ($horasFalta->format('H') > 0 || $horasFalta->format('i') > 0) {

                    if ($horasFalta->getTimestamp() > $horasSubtrair->getTimestamp()) {
                        $horasFalta->sub($intervaloSubtrair);
                    } else {
                        $horasFalta->setTime(0, 0);
                    }
                }
                if ($saldoMinutosDeAbono < 0) {
                    $saldoMinutosDeAbono = 0;
                }
                if (!empty($this->listaAssentamentosAbonoFaltaGera)) {
                    $this->listaAssentamentosAbonoFaltaGera->setSaldoHoras(BaseHora::converterMinutosEmHoraMinuto($saldoMinutosDeAbono));
                }
            }
        }

        $debug = "-- Hora Atraso................: ". (($horasFalta instanceof \DateTime) ? $horasFalta->format('H:i') : '_:__');
        $this->logger->debug($debug);

        return $horasFalta;
    }

    /**
     * @return \DateTime
     */
    protected function calcularHorasNoturna()
    {
        $this->oDiaFaltaNoturna = $this->getHoraZerada();

        if ($this->oDiaFalta->getTimestamp() != $this->getHoraZerada()->getTimestamp()) {
            $diaTrabalho = $this->getDiaTrabalho();
            $this->oDiaFaltaNoturna = parent::percorreMinutoAMinuto($diaTrabalho->getJornada()->getInicioJornada(), $diaTrabalho->getMarcacoes()->getPrimeiraMarcacaoComRegistro()->getMarcacao())->horasNoturnas;
        }

        return $this->oDiaFaltaNoturna;
    }

    /**
     * @return \DateTime
     */
    public function getHorasAtrasoNoturno()
    {
        return $this->oDiaFaltaNoturna;
    }
}
