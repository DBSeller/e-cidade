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
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\CalculoExtraLinear;

/**
 * Classe para cálculo de horas extras em dias de eventos
 * Class ExtraEvento
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class ExtraEvento extends CalculoExtraLinear implements HorasLinear {

  /**
   * @var \DateTime
   */
  private $eventoEntrada1 = null;

  /**
   * @var \DateTime
   */
  private $eventoSaida1   = null;

  /**
   * @var \DateTime
   */
  private $eventoEntrada2 = null;

  /**
   * @var \DateTime
   */
  private $eventoSaida2   = null;

  /**
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento
   */
  private $evento = null;

  /**
   * ExtraEvento constructor.
   * @param DiaTrabalho $oDiaTrabalho
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {
    
    parent::__construct($oDiaTrabalho);

    $this->evento  = $this->getDiaTrabalho()->getEvento();

    $this->eventoEntrada1 = clone $this->evento->getEntradaUm(); 
    $this->eventoSaida1   = clone $this->evento->getSaidaUm(); 
    
    if(!is_null($this->evento->getEntradaDois())) {
      $this->eventoEntrada2 = clone $this->evento->getEntradaDois(); 
    }

    if(!is_null($this->evento->getSaidaDois())) {
      $this->eventoSaida2   = clone $this->evento->getSaidaDois(); 
    }
  }

  public function calcular(\DateTime $oHorasExtra50, \DateTime $oHorasExtra50Noturna, \DateTime $oHorasExtra75, \DateTime $oHorasExtra75Noturna, \DateTime $oHorasExtra100, \DateTime $oHorasExtra100Noturna, \DateTime $oHorasAdicionalNoturno) {

    $momentoAtual  = clone $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao();

    $horaEventoInicio = $this->eventoEntrada1;
    $horaEventoFim    = !empty($this->eventoSaida2) ? $this->eventoSaida2 : $this->eventoSaida1;

    do{

      $lExtra = null;

      /**
       * Verifica se está no intervalo de horas do evento
       */
      if($this->horaEstaNoIntervalo($momentoAtual, $horaEventoInicio, $horaEventoFim)) {

        if(   $this->horaEstaNoIntervalo($momentoAtual, $this->eventoEntrada1, $this->eventoSaida1)
           && $momentoAtual->getTimestamp() < $this->eventoSaida1->getTimestamp()
          ) 
        {
          $lExtra = $this->evento->getTipoHoraExtraUm();
        }

        if(!empty($this->eventoEntrada2) && !empty($this->eventoSaida2)) {
        
          if(   $this->horaEstaNoIntervalo($momentoAtual, $this->eventoEntrada2, $this->eventoSaida2) 
             && $momentoAtual->getTimestamp() < $this->eventoSaida2->getTimestamp()
            ) 
          {
            $lExtra = $this->evento->getTipoHoraExtraDois();
          }
        }
      }

      $this->adicionarHorasExtras($momentoAtual, $lExtra);
      $momentoAtual->modify('+1 minute');

    } while ($momentoAtual->getTimestamp() < $this->getDiaTrabalho()->getMarcacoes()->getUltimaMarcacaoComRegistro()->getMarcacao()->getTimestamp());

    $this->popularHorasCalculadas();
  }
}
