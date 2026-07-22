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

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;

/**
 * Classe responsável pelo cálculo do adicional noturno de um servidor em um dia de trabalho
 * Class AdicionalNoturno
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class AdicionalNoturno extends BaseHora implements Horas
{

    /**
     * String
     */
    protected $horasCalculadasSemProporcao;

    /**
     * String
     */
    protected $horasCalculadasComProporcao;

    protected $marcacoes;

    public function __construct(DiaTrabalho $oDiaTrabalho)
    {
        $this->setDiaTrabalho($oDiaTrabalho);
        $this->setTipoHora(BaseHora::HORAS_ADICIONAL_NOTURNO);
        parent::__construct();

        $this->marcacoes = clone $this->getDiaTrabalho()->getMarcacoes();
    }

    /**
     * @return string
     */
    public function calcular()
    {
        $this->logger->debug('------------------------------------------------------');
        $this->logger->debug('------------ CALCULO DE ADICIONAL NOTURNO ------------');

        return $this->calcularHoras();
    }

    protected function calcularHoras()
    {
        $mHora = new \DateTime("00:00");
        $aMarcacoes = $this->marcacoes->getMarcacoes();
        $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

        /**
         * Se não há horas de jornada nem marcação não há valor de adicional noturno
         */
        if (empty($aHorasJornada) && empty($aMarcacoes)) {
            return $mHora->format('H:i');
        }

        if (!$this->getDiaTrabalho()->getJornada()->isDiaTrabalhado()
          && $this->getDiaTrabalho()->isCalculaHoraExtra() == null) {
            if (!$this->getDiaTrabalho()->getHorasTotaisDeTrabalho()) {
                return $mHora->format('H:i');
            }
        }

        if ($this->getDiaTrabalho()->getFeriado() != null && $this->getDiaTrabalho()->isCalculaHoraExtra() == null) {
            $escalaTrabalho = $this->getDiaTrabalho()->getServidor()
                                                     ->getEscala($this->getDiaTrabalho()->getData())
                                                     ->getEscalaTrabalho();
            $jornada = $this->getDiaTrabalho()->getJornada();

            if (!$escalaTrabalho->isRevezamento()
              || ($escalaTrabalho->isRevezamento()
                  && !$escalaTrabalho->isExtraAutomaticaFeriado()
                  && !$jornada->isDiaTrabalhado())) {
                return $mHora->format('H:i');
            }
        }

        if ($this->marcacoes->getMarcacaoEntrada1() != null && $this->marcacoes->getMarcacaoSaida1() != null) {
            if ($this->marcacoes->getMarcacaoEntrada1()->getMarcacao() != null
                && $this->marcacoes->getMarcacaoSaida1()->getMarcacao() != null) {
                $adicionalNoturno = $this->percorreMinutoAMinuto(
                    $this->marcacoes->getMarcacaoEntrada1()->getMarcacao(),
                    $this->marcacoes->getMarcacaoSaida1()->getMarcacao()
                );
                $hora = ($adicionalNoturno instanceof \DateTime ? $adicionalNoturno->format('H:i') : '__:__');
                $this->logger->debug('-- AdicionalNoturno-Periodo1........:' . $hora);

                $mHora->modify("+{$adicionalNoturno->format('H')} hour +{$adicionalNoturno->format('i')} minutes");
                $this->logger->debug('-- percorreMinutoAMinuto-Periodo1...:' . ($mHora->format('H:i')));
            }
        }

        if ($this->marcacoes->getMarcacaoEntrada2() != null && $this->marcacoes->getMarcacaoSaida2() != null) {
            if ($this->marcacoes->getMarcacaoEntrada2()->getMarcacao() != null
              && $this->marcacoes->getMarcacaoSaida2()->getMarcacao() != null) {
                $adicionalNoturno = $this->percorreMinutoAMinuto(
                    $this->marcacoes->getMarcacaoEntrada2()->getMarcacao(),
                    $this->marcacoes->getMarcacaoSaida2()->getMarcacao()
                );
                $hora = ($adicionalNoturno instanceof \DateTime ? $adicionalNoturno->format('H:i') : '__:__');
                $this->logger->debug('-- AdicionalNoturno-Periodo2........:' . $hora);

                $mHora->modify("+{$adicionalNoturno->format('H')} hour +{$adicionalNoturno->format('i')} minutes");
                $this->logger->debug('-- percorreMinutoAMinuto-Periodo2...:' . ($mHora->format('H:i')));
            }
        }

        $this->logger->debug('------------------------------------------------------');

        return $mHora instanceof \DateTime ? $mHora->format('H:i') : $mHora->format('%H:%i');
    }

    /**
     * @todo Deletar função para utilizar apenas a função da classe pai BaseHora. Deve-se tomar cuidado com os impactos.
     * @deprecated
     * @param \DateTime $primeiraMarcacao
     * @param \DateTime $segundaMarcacao
     * @param bool $apenasHorasNoturnas
     * @return \stdClass | \DateTime
     */
    public function percorreMinutoAMinuto(
        \DateTime $primeiraMarcacao,
        \DateTime $segundaMarcacao,
        $diurnasNoturnas = false
    ) {
        $this->logger->debug('-- Metodo percorreMinutoAMinuto()');

        $debug = '-- Parametro primeiraMarcacao.......: ' . $primeiraMarcacao->format('H:i');
        $this->logger->debug($debug);

        $debug = '-- Parametro segundaMarcacao........: ' . $segundaMarcacao->format('H:i');
        $this->logger->debug($debug);

        $this->logger->debug('-- Parametro diurnasNoturnas........: ' . ($diurnasNoturnas ? 'TRUE' : 'FALSE'));

        $horasDiurnasNoturnas = parent::percorreMinutoAMinuto($primeiraMarcacao, $segundaMarcacao);

        return !$diurnasNoturnas ? $horasDiurnasNoturnas->horasNoturnas : $horasDiurnasNoturnas;
    }

    /**
     * @return mixed
     */
    public function getHorasCalculadasSemProporcao()
    {
        return $this->horasCalculadasSemProporcao;
    }

    /**
     * @param mixed $horasCalculadasSemProporcao
     *
     * @return self
     */
    public function setHorasCalculadasSemProporcao($horasCalculadasSemProporcao)
    {
        $this->horasCalculadasSemProporcao = $horasCalculadasSemProporcao;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHorasCalculadasComProporcao()
    {
        return $this->horasCalculadasComProporcao;
    }

    /**
     * @param mixed $horasCalculadasComProporcao
     *
     * @return self
     */
    public function setHorasCalculadasComProporcao($horasCalculadasComProporcao)
    {
        $this->horasCalculadasComProporcao = $horasCalculadasComProporcao;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMarcacoes()
    {
        return $this->marcacoes;
    }

    /**
     * @param mixed $marcacoes
     *
     * @return self
     */
    public function setMarcacoes($marcacoes)
    {
        $this->marcacoes = $marcacoes;

        return $this;
    }
}
