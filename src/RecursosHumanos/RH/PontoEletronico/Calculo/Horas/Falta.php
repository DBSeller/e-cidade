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

use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoAbonoFalta;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;

/**
 * Classe responsável pelo cálculo das horas falta de um servidor em um dia de trabalho
 * Class Falta
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Falta extends BaseHora implements Horas
{

    private $oDiaFalta;

    private $oDiaFaltaNoturna;

    private $horaAssentamentoAbonoFalta = null;

    private $assentamentoAbonoFaltaGeraFalta = false;

    private $temJustificativaNoDia = false;

    /**
     * Construtor da classe
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
                }
            }

            $this->horaAssentamentoAbonoFalta = BaseHora::converterMinutosEmHoraMinuto(BaseHora::converterDateTimeEmMinutos($intervaloFaltaAbonada));
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
        $this->logger->debug("-------------- CALCULO DE HORAS FALTAS ---------------");

        $horasFalta = $this->calcularHorasFalta();

        $debug = "-- Hora Falta Calculada....: ". (($horasFalta instanceof \DateTime) ? $horasFalta->format('H:i') : '_:__');
        $this->logger->debug($debug);

        $horasFalta = $this->posCalcular($horasFalta);

        $this->calcularHorasFaltaNoturna();
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
        $horasTrabalhadas = $this->getHorasTrabalhadas();
        $cargaHoraria = $this->getDiaTrabalho()->getJornada()->getCargaHoraria();
        $cargaHoraria->setDate(
            $this->getDiaTrabalho()->getData()->getAno(),
            $this->getDiaTrabalho()->getData()->getMes(),
            $this->getDiaTrabalho()->getData()->getDia()
        );

        $this->logger->debug("-- Horas Trabalhadas.......: "    . ($horasTrabalhadas instanceof \DateTime   ? $horasTrabalhadas->format('H:i') : ''));
        $this->logger->debug("-- Carga Horária...........: "    . ($cargaHoraria instanceof \DateTime       ? $cargaHoraria->format('H:i')     : ''));

        /**
         * Horas trabalhadas são maiores que as horas da Jornada, nao existe falta;
         */
        if ($horasTrabalhadas->getTimestamp() > $cargaHoraria->getTimestamp()) {

            $this->logger->debug("-- Trabalhou mais que a carga horária, logo não tem falta.");
            return $this->oDiaFalta;
        }

        /**
         * Se possui a mesma quantidade (ou mais) marcações que a jornada
         */
        if ($this->getDiaTrabalho()->getMarcacoes()->getQuantidadeMarcacoes() >= count($this->getDiaTrabalho()->getJornada()->getHoras())) {

            $this->logger->debug("-- Tem a mesma quantidade ou mais de marcacoes que a jornada.");
            return $this->oDiaFalta;
        }

        if($this->getDiaTrabalho()->getMarcacoes()->getQuantidadeMarcacoes() > 1) {
            //return $this->getHoraZerada();
        }

        if($this->getDiaTrabalho()->getJornada()->isDSR() || $this->getDiaTrabalho()->getJornada()->isFolga()) {

            $this->logger->debug("-- SEM falta, data nao eh dia de trabalho. ");
            $msgDia = $this->getDiaTrabalho()->getJornada()->isDSR() ? 'Data eh um DSR.'
                : $this->getDiaTrabalho()->getJornada()->isFolga() ? 'Data eh uma FOLGA'
                    : $this->getDiaTrabalho()->getFeriado() ? 'Data eh um FERIADO' : '';
            $this->logger->debug("-- {$msgDia}");

            return $this->getHoraZerada();
        }

        if($marcacoesReais->getQuantidadeMarcacoes() == 1 && !$this->assentamentoAbonoFaltaGeraFalta && !$this->hasJustificativaAbonaOufastamentoAbona($marcacoesReais->getPrimeiraMarcacaoComRegistro())) {

            $this->logger->debug("-- Data so possui 1 marcacao e nao possui assentamento de abono de faltas.");
            $this->logger->debug("-- Quantidade de marcacoes.......: ". $marcacoesReais->getQuantidadeMarcacoes());
            $this->logger->debug("-- Assentamentos abono falta.....: ". ($this->assentamentoAbonoFaltaGeraFalta ? 'SIM' : 'NAO'));
            return $cargaHoraria;
        }

        if($marcacoesReais->isEmpty() || $marcacoesReais->getQuantidadeMarcacoes() == 1) {

            $this->logger->debug("-- Verificando se servidor possui justificativa --");
            $this->diaDeTrabalhoComJustificativa($cargaHoraria);
        }

        /**
         * verificamos se a jornada possui intervalo, e se o servidor possui blocos com jornada valida
         */
        if ($this->getDiaTrabalho()->getJornada()->temIntervalo() && $marcacoesReais->getQuantidadeMarcacoes() == 2) {
            $justificativaSaida1 = $marcacoesReais->getMarcacaoSaida1()->getJustificativa();
            $justificativaEntrada2 = $marcacoesReais->getMarcacaoEntrada2()->getJustificativa();

            $assentamentosJustificativaServidor = $this->getDiaTrabalho()->getAssentamentosJustificativaServidor();
            $assentamentoJustificativaServidor = reset($assentamentosJustificativaServidor);

            if ((!$justificativaSaida1 && !$justificativaEntrada2) || (!empty($assentamentoJustificativaServidor) && $assentamentoJustificativaServidor->getInstanciaTipoAssentamento()->isGerarFaltas())) {
                if ($marcacoesReais->getMarcacaoEntrada1()->getMarcacao() == null && $marcacoesReais->getMarcacaoSaida2()->getMarcacao() == null) {
                    return $cargaHoraria;
                }
            }
        }

        return $this->oDiaFalta;
    }

    /**
     * @param $horasFalta
     * @return mixed
     * @throws \Exception
     */
    protected function posCalcular($horasFalta)
    {

        if (!$this->assentamentoAbonoFaltaGeraFalta) {
            if (!empty($this->horaAssentamentoAbonoFalta) && preg_match('/\d+\:\d+/', $this->horaAssentamentoAbonoFalta)) {
                list($hora, $minuto) = explode(':', $this->horaAssentamentoAbonoFalta);
                $intervaloSubtrair = new \DateInterval("PT{$hora}H{$minuto}M");
                $horasSubtrair = \DateTime::createFromFormat('H:i', "{$hora}:{$minuto}");
                $horasSubtrair->setDate(
                    $this->getDiaTrabalho()->getData()->getAno(),
                    $this->getDiaTrabalho()->getData()->getMes(),
                    $this->getDiaTrabalho()->getData()->getDia()
                );

                if ($horasFalta->format('H') > 0 || $horasFalta->format('i') > 0) {
                    if ($horasFalta->getTimestamp() > $horasSubtrair->getTimestamp()) {
                        $horasFalta->sub($intervaloSubtrair);
                    } else {
                        $horasFalta->setTime(0, 0);
                    }
                }
            }
        }

        $this->logger->debug("-- POS CALCULO DE FALTAS    ");

        $debug = "-- Hora Falta..............: ". (($horasFalta instanceof \DateTime) ? $horasFalta->format('H:i') : '_:__');
        $this->logger->debug($debug);

        return $horasFalta;
    }

    /**
     * @param $cargaHoraria
     * @return \DateTime
     */
    private function diaDeTrabalhoComJustificativa($cargaHoraria)
    {
        $marcacoes = $this->getDiaTrabalho()->getMarcacoes();
        $horasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();
        $temJustificativaPrimeiroPeriodo = false;
        $temJustificativaSegundoPeriodo = false;

        if($this->getDiaTrabalho()->isAfastado()) {

            $this->logger->debug("-- Servidor está AFASTADO --");
            return $this->getHoraZerada();
        }

        if($marcacoes->getMarcacaoEntrada1() != null && $this->hasJustificativaAbonaOufastamentoAbona($marcacoes->getMarcacaoEntrada1())) {
            $cargaHoraria->sub($horasJornada[0]->oHora->diff($horasJornada[1]->oHora));
            $temJustificativaPrimeiroPeriodo = true;
            $this->logger->debug("-- Servidor com justificativa no primeiro período");
        }

        if( $marcacoes->getMarcacaoEntrada2() != null && $this->getDiaTrabalho()->getJornada()->temIntervalo()) {
            if($this->hasJustificativaAbonaOufastamentoAbona($marcacoes->getMarcacaoEntrada2())) {
                $cargaHoraria->sub($horasJornada[2]->oHora->diff($horasJornada[3]->oHora));
                $temJustificativaSegundoPeriodo = true;
                $this->logger->debug("-- Servidor com justificativa no segundo período");
            }
        }

        $this->oDiaFalta = clone $cargaHoraria;

        if ($this->getDiaTrabalho()->getJornada()->temIntervalo()) {
            if ($temJustificativaPrimeiroPeriodo && $temJustificativaSegundoPeriodo) {
                $this->oDiaFalta = $this->getHoraZerada();
            }
        } else {
            if ($temJustificativaPrimeiroPeriodo) {
                $this->oDiaFalta = $this->getHoraZerada();
            }
        }

        $this->logger->debug("-- Faltas calculadas..................: ". ( $this->oDiaFalta instanceof \DateTime ? $this->oDiaFalta->format('H:i') : ''));

    }

    /**
     * @param MarcacaoPonto $marcacaoFalta
     * @return bool
     */
    private function hasJustificativaAbonaOufastamentoAbona(MarcacaoPonto $marcacaoFalta)
    {

        if ($marcacaoFalta->hasJustificativa() || $this->getDiaTrabalho()->isAfastado()) {
            if ($marcacaoFalta->hasJustificativa() && $marcacaoFalta->getJustificativa()->isAbono()) {
                $this->temJustificativaNoDia = true;
                return true;
            } else {
                if ($this->getDiaTrabalho()->isAfastado()) {
                    if ($this->getDiaTrabalho()->getAfastamento()) {
                        if ($this->getDiaTrabalho()->getAfastamento()->getInstanciaTipoAssentamento()) {
                            if (!$this->getDiaTrabalho()->getAfastamento()->getInstanciaTipoAssentamento()->isGerarFaltas()) {
                                $this->temJustificativaNoDia = true;
                                return true;
                            }
                        }
                    }

                }
            }
        }

        return false;
    }

    /**
     * @return \DateTime
     */
    protected function calcularHorasFaltaNoturna()
    {
        $this->oDiaFaltaNoturna = $this->getHoraZerada();

        if ($this->oDiaFalta->getTimestamp() != $this->getHoraZerada()->getTimestamp()) {
            $jornada = $this->getDiaTrabalho()->getJornada();
            $horasJornada = $jornada->getHoras();

            $quantidadePeriodos = count($horasJornada);
            for ($i = 0; $i < $quantidadePeriodos; $i = $i +2) {
                $entrada = clone $horasJornada[$i]->oHora;
                $saida = clone $horasJornada[$i + 1]->oHora;

                $minutosNoturnos = BaseHora::converterDateTimeEmMinutos($this->percorreMinutoAMinuto($entrada, $saida)->horasNoturnas);
                $this->oDiaFaltaNoturna->modify("+{$minutosNoturnos} minutes");
            }

        }

        return $this->oDiaFaltaNoturna;
    }

    /**
     * @return \DateTime
     */
    public function getHorasFaltaNoturna()
    {
        return $this->oDiaFaltaNoturna;
    }
}
