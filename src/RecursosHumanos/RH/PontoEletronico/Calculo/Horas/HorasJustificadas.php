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

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;

/**
 * Classe para cálculo de horas extras em dias de eventos
 * Class ExtraEvento
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class HorasJustificadas extends CalculoHoraLinear implements Horas {

    /**
     * Hora justificada
     * @var \DateTime
     */
    private $horaJustificada;

    /**
     * Informa se no período justificado existe algum minuto trabalhado
     * @var Boolean
     */
    private $temHoraTrabalhadaNoIntervaloJustificado = false;

    /**
     * ExtraEvento constructor.
     * @param DiaTrabalho $oDiaTrabalho
     */
    public function __construct(DiaTrabalho $oDiaTrabalho)
    {
        parent::__construct($oDiaTrabalho);
        $this->horaJustificada = $this->getHoraZerada();
        $this->marcacoes       = array();

        if($this->getDiaTrabalho()->getMarcacoes() instanceof MarcacoesPontoCollection) {
            $this->marcacoes = $this->getDiaTrabalho()->getMarcacoes()->getMarcacoes();
        }
    }

    public function calcular()
    {

        $horaInicio      = $this->horaInicio;
        $horaFim         = $this->horaFim;

        if($horaFim->getTimestamp() <= $horaInicio->getTimestamp()) {
            $horaFim->modify('+1 day');
        }

        $momentoAtual    = clone $this->horaInicio;
        $momentoAtual->modify('+1 minute');

        do {

            $lExtra = null;

            /**
             * Verifica se a hora está em um intervalo de horas trabalhadas
             */
            if($this->isHoraTrabalhada($momentoAtual)) {

                $this->temHoraTrabalhadaNoIntervaloJustificado = true;
                $momentoAtual->modify('+1 minute');
                continue;
            }
      
            $lExtra = BaseHora::HORAS_TRABALHO;

            $this->adicionarHorasExtras($momentoAtual, $lExtra);
            $momentoAtual->modify('+1 minute');

        } while ($momentoAtual->getTimestamp() <= $horaFim->getTimestamp());

        $this->horaJustificada->modify('+'. BaseHora::converterDateTimeEmMinutos($this->oHorasDiurnas)  .' minutes');
        $this->horaJustificada->modify('+'. BaseHora::converterMinutosEmMinutosNoturnos(BaseHora::converterDateTimeEmMinutos($this->oHorasNoturnas)) .' minutes');
    }

    public function isHoraTrabalhada(\DateTime $momentoAtual)
    {
        
        $marcacoes = $this->marcacoes;
            
        if(is_array($marcacoes)) {

            foreach ($marcacoes as $marcacao) {

                $oHoraInicio   = null;
                $oHoraFim      = null;

                if($marcacao->isMarcacaoSaida()) {

                    $oHoraInicio = $marcacao->getMarcacaoEntrada();
                    $oHoraFim    = $marcacao->getMarcacao();

                    if(!empty($oHoraInicio) && !empty($oHoraFim)) {

                        if($this->horaEstaNoIntervalo($momentoAtual, $oHoraInicio, $oHoraFim)) {
                          
                          if($momentoAtual->getTimestamp() != $oHoraInicio->getTimestamp() && $momentoAtual->getTimestamp() != $oHoraFim->getTimestamp()) {
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
     * @param \DateTime $horaJustificada
     *
     * @return self
     */
    public function setHoraJustificada(\DateTime $horaJustificada)
    {
        $this->horaJustificada = $horaJustificada;
        return $this;
    }

    /**
     * @param Boolean $temHoraTrabalhadaNoIntervaloJustificado
     *
     * @return self
     */
    public function setHoraTrabalhadaNoIntervaloJustificado(Boolean $temHoraTrabalhadaNoIntervaloJustificado)
    {
        $this->temHoraTrabalhadaNoIntervaloJustificado = $temHoraTrabalhadaNoIntervaloJustificado;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHoraJustificada()
    {
        return $this->horaJustificada;
    }

    /**
     * @return Boolean
     */
    public function hasHoraTrabalhadaNoIntervaloJustificado()
    {
        return $this->temHoraTrabalhadaNoIntervaloJustificado;
    }
}
