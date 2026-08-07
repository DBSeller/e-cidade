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
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta;

/**
 * Classe responsável pelo cálculo das horas falta de saída antecipada de um servidor em um dia de trabalho
 * Class FaltaSaidaAntecipada
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class FaltaSaidaAntecipada extends BaseHora implements Horas
{
    /**
     * @var \DateTime
     */
    private $oDiaFalta;

    /**
     * @var \DateTime
     */
    private $oDiaFaltaNoturna;

    private $horaAssentamentoAbonoFalta = null;

    private $assentamentoAbonoFaltaGeraFalta = true;

    /**
     * @var AssentamentoAbonoFalta
     */
    private $listaAssentamentosAbonoFaltaGera = null;

    private $temJustificativaPrimeiroPeriodo = false;

    private $temJustificativaSegundoPeriodo = false;

    public function __construct(DiaTrabalho $oDiaTrabalho)
    {

        $this->setDiaTrabalho($oDiaTrabalho);
        $this->setTipoHora(BaseHora::HORAS_FALTA);

        parent::__construct();

        $intervaloFaltaAbonada = $this->getHoraZerada();
        $aAssentamentosAbonofalta = $this->getDiaTrabalho()->getAssentamentosAbonofalta();

        if ($aAssentamentosAbonofalta) {
            foreach ($aAssentamentosAbonofalta as $oAssentamentoAbonofalta) {
                if ($oAssentamentoAbonofalta instanceof AssentamentoAbonoFalta && preg_match('/\d+\:\d+/', $oAssentamentoAbonofalta->getSaldoHoras())) {
                    $intervaloFaltaAbonada->add(BaseHora::converterStringHoraEmDateInterval($oAssentamentoAbonofalta->getSaldoHoras()));
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
        $this->logger->debug("------------ CALCULO DE SAIDA ANTECIPADA -------------");

        $horasFalta = $this->calcularHorasFalta();

        $debug = "-- Hora Calculada..........: ". (($horasFalta instanceof \DateTime) ? $horasFalta->format('H:i') : '_:__');
        $this->logger->debug($debug);

        $horasFalta = $this->posCalcular($horasFalta);

        $this->calcularHorasNoturna();
        $this->logger->debug("------------------------------------------------------");

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
        $cargaHoraria = $this->getDiaTrabalho()->getJornada()->getCargaHoraria();
        $cargaHoraria->setDate(
          $this->getDiaTrabalho()->getData()->getAno(),
          $this->getDiaTrabalho()->getData()->getMes(),
          $this->getDiaTrabalho()->getData()->getDia()
        );

        /**
         * Validação fixa para o dia do gari, que não calcula saída antecipada
         */
        if($this->getDiaTrabalho()->getServidor()->getCodigoRegime() == 1810 && $this->getDiaTrabalho()->getData()->getDate() == '2018-05-16') {

            $this->logger->debug("-- Regime do Servidor............: 1810");
            $this->logger->debug("------------ Data 15/06/2018 ----------");
            return $this->getHoraZerada();
        }

        /**
         * Horas trabalhadas são maiores que as horas da Jornada, nao existe falta;
         * ou
         * Se não possui marcações não tem atraso
         */
        if ($horasTrabalhadas->getTimestamp() > $cargaHoraria->getTimestamp() || $this->getDiaTrabalho()->getMarcacoes()->isEmpty()) {

            $this->logger->debug("-- Horas Trabalhadas.............:" . ($horasTrabalhadas instanceof \DateTime ? $horasTrabalhadas->format('H:i') : ''));
            $this->logger->debug("-- Carga Horária.................:" . ($cargaHoraria instanceof \DateTime ? $cargaHoraria->format('H:i') : ''));
            $this->logger->debug("-- Marcacoes Vazias..............:" . ($this->getDiaTrabalho()->getMarcacoes()->isEmpty() ? 'SIM' : 'NAO'));
            return $this->oDiaFalta;
        }

        if($jornada->isDSR() || $jornada->isFolga()) {

            $this->logger->debug("-- Data nao eh dia de trabalho ou feriado.");
            return $this->getHoraZerada();
        }

        if($marcacoesReais->getQuantidadeMarcacoes() == 1) {

            $this->logger->debug("-- Quantidade de marcacoes.......: ". $marcacoesReais->getQuantidadeMarcacoes());
            return $this->getHoraZerada();
        }

        $this->calcularFaltasPelaMarcacao($marcacoesReais, $jornada);

        return $this->oDiaFalta;
    }

    /**
     * @param MarcacoesPontoCollection $marcacoesReais
     * @param Jornada $jornada
     * @return mixed
     */
    private function calcularFaltasPelaMarcacao(MarcacoesPontoCollection $marcacoesReais, Jornada $jornada)
    {
        $tipoJornadaComparar = $jornada->temIntervalo() ? 4 : 2;
        $ultimaHoraJornada   = $jornada->getFimJornada();
        $ultimaMarcacao      = $marcacoesReais->getUltimaMarcacaoComRegistro();
        $ultimaMarcacaoPonto = $marcacoesReais->getMarcacaoSaida2()->getMarcacao();
        $primeiraMarcacaoPonto = $marcacoesReais->getMarcacaoEntrada1()->getMarcacao();


        if($ultimaMarcacao->estaNaTolerancia($this->getDiaTrabalho(), $tipoJornadaComparar)) {

            $this->logger->debug("--------- Ultima marcacao está na tolerancia -----------");
            return $this->oDiaFalta;
        }

        if($ultimaMarcacao->getMarcacao()->getTimestamp() >= $ultimaHoraJornada->getTimestamp()) {

            $this->logger->debug("----- Ultima marcacao maior ques ultima da jornada -----");
            return $this->oDiaFalta;
        }

        if($this->diaDeTrabalhoComJustificativa()) {

            $this->logger->debug("----- Encontrada justificativa na data ------");

            if($this->temJustificativaPrimeiroPeriodo) {

                $this->logger->debug("--------- Justificativa no periodo 1 --------");
                return $this->calculaComJustificativaNoPrimeiroPeriodo();
            }

            if($this->temJustificativaSegundoPeriodo) {

                $this->logger->debug("--------- Justificativa no periodo 2 --------");
                return $this->calculaComJustificativaNoSegundoPeriodo();
            }
        }

        if ($ultimaMarcacaoPonto == null && $primeiraMarcacaoPonto == null && $marcacoesReais->getQuantidadeMarcacoes() == 2  && $jornada->temIntervalo()) {

            $this->logger->debug("------------ Ultima marcacao vazia ------------");
            $this->logger->debug("----------- Primeira marcacao vazia -----------");
            $this->logger->debug("-- Quantidade de marcações.......: ". $marcacoesReais->getQuantidadeMarcacoes());
            $this->logger->debug("-- Jornada tem intervalo.........: ". ( $jornada->temIntervalo() ? 'SIM' : 'NAO'));
            return $this->oDiaFalta;
        }

        $this->oDiaFalta->add(($ultimaMarcacao->getMarcacao()->diff($ultimaHoraJornada)));
        $this->logger->debug("-- Saida antecipada..............: ". $this->oDiaFalta->format('H:i'));
        $this->logger->debug("-- Ultima marcacao...............: ". ($ultimaMarcacao->getMarcacao() instanceof \DateTime ? $ultimaMarcacao->getMarcacao()->format('H:i') : ''));
        $this->logger->debug("-- Ultima hora da jornada........: ". ($ultimaHoraJornada instanceof \DateTime ? $ultimaHoraJornada->format('H:i') : ''));

        if($jornada->temIntervalo() && $marcacoesReais->getQuantidadeMarcacoes() == 2) {

            $horaPrimeiraMarcacao = $marcacoesReais->getPrimeiraMarcacaoComRegistro()->getMarcacao();
            $horasJornada = $jornada->getHoras();
            $saidaJornada1 = $horasJornada[1]->oHora;
            $entradaJornada1 = $horasJornada[0]->oHora;
            $entradaJornada2 = $horasJornada[2]->oHora;

            if($horaPrimeiraMarcacao == null) {
                return $this->oDiaFalta;
            }

            if(    $horaPrimeiraMarcacao->getTimestamp() < $saidaJornada1->getTimestamp()
              && $horaPrimeiraMarcacao->getTimestamp() > $entradaJornada1->getTimestamp()
              && $ultimaMarcacao->getMarcacao()->getTimestamp() > $entradaJornada2->getTimestamp()
            ) {
                return $this->oDiaFalta;
            }

            if($horaPrimeiraMarcacao->getTimestamp() >= $entradaJornada2->getTimestamp()) {
                return $this->oDiaFalta;
            }

            if(   $horaPrimeiraMarcacao->getTimestamp() > $saidaJornada1->getTimestamp()
              && $horaPrimeiraMarcacao->getTimestamp() < $entradaJornada2->getTimestamp()
            ) {
                return $this->oDiaFalta;
            }

            return $this->oDiaFalta->sub($jornada->getIntervalo());
        }


        return $this->oDiaFalta;
    }

    /**
     * @return bool
     */
    private function diaDeTrabalhoComJustificativa()
    {
        $marcacoes = $this->getDiaTrabalho()->getMarcacoes();

        $temJustificativa = false;

        if($marcacoes->getMarcacaoEntrada1()->getJustificativa() != null && $marcacoes->getMarcacaoEntrada1()->getJustificativa()->isAbono()) {
            $this->temJustificativaPrimeiroPeriodo = $temJustificativa = true;
        }

        if($marcacoes->getMarcacaoEntrada2()->getJustificativa() != null && $marcacoes->getMarcacaoEntrada2()->getJustificativa()->isAbono()) {
            $this->temJustificativaSegundoPeriodo = $temJustificativa = true;
        }

        return $temJustificativa;
    }

    /**
     * @return \DateTime
     */
    private function calculaComJustificativaNoPrimeiroPeriodo()
    {
        $saida2 = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoSaida2()->getMarcacao();
        $horasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

        if($saida2 == null || empty($horasJornada[3]->oHora)) {
            return $this->oDiaFalta;
        }

        if($saida2->getTimestamp() >= $horasJornada[3]->oHora->getTimeStamp()) {
            return $this->oDiaFalta;
        }

        if($this->temJustificativaSegundoPeriodo) {
            return $this->getHoraZerada();
        }

        return $this->oDiaFalta->add($saida2->diff($horasJornada[3]->oHora));
    }

    /**
     * @return \DateTime
     */
    private function calculaComJustificativaNoSegundoPeriodo()
    {
        $saida1 = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoSaida1()->getMarcacao();
        $horasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

        if(isset($saida1) && isset($horasJornada[1]->oHora) && $saida1->getTimestamp() >= $horasJornada[1]->oHora->getTimeStamp()) {
            return $this->oDiaFalta;
        }

        if($this->temJustificativaPrimeiroPeriodo) {
            return $this->getHoraZerada();
        }

        $diferencaJornadaMarcacao = $horasJornada[1]->oHora->diff($saida1);
        $diferencaJornadaMarcacao->invert = 0;

        return $this->oDiaFalta->add($diferencaJornadaMarcacao);
    }

    /**
     * @param $horasFalta
     * @return \DateTime
     * @throws \Exception
     */
    protected function posCalcular(\DateTime $horasFalta)
    {
        $this->logger->debug("-- POS CALCULO DE SAIDA ANTECIPADA");

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
            $horaZerada = $this->getHoraZerada();
            $diaTrabalho = $this->getDiaTrabalho();
            $jornada = $diaTrabalho->getJornada();
            $tipoComparar = $jornada->temIntervalo() ? 4 : 2;

            $this->horaAssentamentoAbonoFalta = $this->listaAssentamentosAbonoFaltaGera->getSaldoHoras();
            $horaAssentamento = new \DateTime($this->horaAssentamentoAbonoFalta);
            $horaAssentamento->setDate(
              $diaTrabalho->getData()->getAno(),
              $diaTrabalho->getData()->getMes(),
              $diaTrabalho->getData()->getDia()
            );
            $this->logger->debug("-- Hora Assentamento encontradas.: ". ($horaAssentamento instanceof \DateTime ? $horaAssentamento->format('H:i') : ''));

            $primeiraHoraMarcacao = $diaTrabalho->getMarcacoesSemAlteracao()->getMarcacaoEntrada1();
            $primeiraHoraJornada = $jornada->getInicioJornada();
            $ultimaHoraMarcacao = $diaTrabalho->getMarcacoesSemAlteracao()->getUltimaMarcacaoComRegistro();
            $ultimaHoraJornada = $jornada->getFimJornada();

            if($horaZerada->getTimestamp() > $horaAssentamento->getTimestamp()) {

                $this->logger->debug("-- Hora Zerada...................: ". ($horaZerada instanceof \DateTime ? $horaZerada->format('H:i') : ''));
                $this->logger->debug("-- Hora Assentamento.............: ". ($horaAssentamento instanceof \DateTime ? $horaAssentamento->format('H:i') : ''));
                $this->logger->debug("-- Hora Saida antecipada.........: ". ($horasFalta instanceof \DateTime ? $horasFalta->format('H:i') : ''));
                return $horasFalta;
            }

            if($ultimaHoraMarcacao == null) {
                return $horasFalta;
            }

            if($diaTrabalho->getMarcacoesSemAlteracao()->getQuantidadeMarcacoes() > 1) {

                if(!$ultimaHoraMarcacao->estaNaTolerancia($diaTrabalho, $tipoComparar) && $ultimaHoraMarcacao->getMarcacao() != null) {
                    if($ultimaHoraMarcacao->getMarcacao()->getTimestamp() < $ultimaHoraJornada->getTimestamp()) {
                        $horaDiferencaMarcacaoJornada = $this->getHoraZerada();
                        $horaDiferencaMarcacaoJornada->add($ultimaHoraMarcacao->getMarcacao()->diff($ultimaHoraJornada));

                        if($jornada->temIntervalo()) {
                            $horaDiferencaMarcacaoJornada->sub($jornada->getIntervalo());
                        }

                        if($horaDiferencaMarcacaoJornada->getTimestamp() <= $horaAssentamento->getTimestamp()) {
                            return $horaZerada;
                        }

                        if(BaseHora::converterDateTimeEmMinutos($horasFalta) != 0) {

                            if($horasFalta->getTimestamp() > $horaAssentamento->getTimestamp()) {

                                $this->logger->debug("-- Diminuindo....: {$horaAssentamento->format('H')} hour, {$horaAssentamento->format('i')} minutes");
                                $this->logger->debug("-- De............: {$horasFalta->format('H:i')}");

                                $horasFalta->modify("-{$horaAssentamento->format('H')} hour -{$horaAssentamento->format('i')} minutes");

                            } else {

                                $diferencaAbonoSaidaAntecipada = $horasFalta->diff($horaAssentamento);
                                $this->listaAssentamentosAbonoFaltaGera->setSaldoHoras($diferencaAbonoSaidaAntecipada->format('%H:%I'));
                                $horasFalta->setTime(0, 0);

                                $this->logger->debug('-- Zerando horas de saida antecipada');
                                $this->logger->debug('-- Setando saldo de horas de saida antecipada para: '. $diferencaAbonoSaidaAntecipada->format('%H:%I'));
                            }
                        }
                    }
                }
            }
        }

        $debug = "-- Hora Saida Antecipada.........: ". (($horasFalta instanceof \DateTime) ? $horasFalta->format('H:i') : '_:__');
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
            $this->oDiaFaltaNoturna = $this->percorreMinutoAMinuto($diaTrabalho->getMarcacoes()->getUltimaMarcacaoComRegistro()->getMarcacao(), $diaTrabalho->getJornada()->getFimJornada())->horasNoturnas;
        }

        return $this->oDiaFaltaNoturna;
    }


    /**
     * @return \DateTime
     */
    public function getHorasSaidaAntecipadaNoturna()
    {
        return $this->oDiaFaltaNoturna;
    }
}
