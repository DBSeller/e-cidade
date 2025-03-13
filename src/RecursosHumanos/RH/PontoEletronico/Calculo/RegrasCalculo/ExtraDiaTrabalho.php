<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo;

use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;

/**
 * Classe responsável pelo cálculo de extras em dias de trabalho
 */
class ExtraDiaTrabalho extends RegraCalculo {

    /**
     * Limites de horas extras
     *
     * @var $limitesExtras;
     */
    protected $limitesExtras = array();

    /**
     * Cálculos de horas extras
     *
     * @var $horasExtras
     */
    protected $horasExtras = array();

    /**
     * Cálculo de horas extras não autorizadas
     *
     * @var $horasExtrasNaoAutorizadas
     */
    protected $horasExtrasNaoAutorizadas = array();

    /**
     * Limite de extras autorizadas
     *
     * @var $limiteAutorizado
     */
    protected $limiteAutorizado;

    /**
     * Construtor da classe
     *
     * @param DiaTrabalho $diaTrabalho
     */ 
    public function __construct (DiaTrabalho $diaTrabalho)
    {
        parent::__construct($diaTrabalho);
        $data = $diaTrabalho->getData()->getDate();
        
        if( !is_null($this->configuracoesLotacao->getHoraExtra50()) ) {
            $this->limitesExtras[BaseHora::HORAS_EXTRA50]       = BaseHora::converterDateTimeEmMinutos(new \DateTime($data .' '. $this->configuracoesLotacao->getHoraExtra50()));
            $this->horasExtras[BaseHora::HORAS_EXTRA50]         = 0;
            $this->horasExtras[BaseHora::HORAS_EXTRA50_NOTURNA] = 0;
            $this->horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS]         = 0;
            $this->horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS_NOTURNA] = 0;
        }

        if( !is_null($this->configuracoesLotacao->getHoraExtra75()) ) {
            $this->limitesExtras[BaseHora::HORAS_EXTRA75]       = BaseHora::converterDateTimeEmMinutos(new \DateTime($data .' '. $this->configuracoesLotacao->getHoraExtra75()));
            $this->horasExtras[BaseHora::HORAS_EXTRA75]         = 0;
            $this->horasExtras[BaseHora::HORAS_EXTRA75_NOTURNA] = 0;
            $this->horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS]         = 0;
            $this->horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS_NOTURNA] = 0;
        }

        if( !is_null($this->configuracoesLotacao->getHoraExtra100()) ) {
            $this->limitesExtras[BaseHora::HORAS_EXTRA100]       = BaseHora::converterDateTimeEmMinutos(new \DateTime($data .' '. $this->configuracoesLotacao->getHoraExtra100()));
            $this->horasExtras[BaseHora::HORAS_EXTRA100]         = 0;
            $this->horasExtras[BaseHora::HORAS_EXTRA100_NOTURNA] = 0;
            $this->horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS]         = 0;
            $this->horasExtrasNaoAutorizadas[BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS_NOTURNA] = 0;
        }
    }

    /**
     * Método que processa a regra para cálculo de extras em dias de trabalho
     */
    public function processar(\DateTime $momentoAtual) 
    {
        $entrada1 = $this->diaTrabalho->getMarcacoesSemAlteracao()->getMarcacaoEntrada1()->getMarcacao();
        $entrada2 = $this->diaTrabalho->getMarcacoesSemAlteracao()->getMarcacaoEntrada2()->getMarcacao();
        $entrada3 = $this->diaTrabalho->getMarcacoesSemAlteracao()->getMarcacaoEntrada3()->getMarcacao();

        $jornadaSaida1 = $this->diaTrabalho->getJornada()->getHora(MarcacaoPonto::SAIDA_1)->oHora;

        $jornadaSaida2 = null;
        if( $this->diaTrabalho->getJornada()->getHora(MarcacaoPonto::SAIDA_2) !== null ) {
            $jornadaSaida2 = $this->diaTrabalho->getJornada()->getHora(MarcacaoPonto::SAIDA_2)->oHora;
        }
        
        $horaExtraAutorizada = null;
        if( $this->getDiaTrabalho()->getHorasExtrasAutorizadas() instanceof \DateTime ) {
            $horaExtraAutorizada = BaseHora::converterDateTimeEmMinutos($this->getDiaTrabalho()->getHorasExtrasAutorizadas());
        }

        if( (!is_null($horaExtraAutorizada) && !$this->jornada->estaNaJornada($momentoAtual)) 
             || ($jornadaSaida1 && $momentoAtual->getTimestamp() == $jornadaSaida1->getTimestamp())
             || ($jornadaSaida2 && $momentoAtual->getTimestamp() == $jornadaSaida2->getTimestamp())
        ) {

            if( (!$this->diaTrabalho->getMarcacoesSemAlteracao()->estaNoIntervalo($momentoAtual))
                 || ($entrada1 instanceof \DateTime && $momentoAtual->getTimestamp() == $entrada1->getTimestamp()) 
                 || ($entrada2 instanceof \DateTime && $momentoAtual->getTimestamp() == $entrada2->getTimestamp())
                 || ($entrada3 instanceof \DateTime && $momentoAtual->getTimestamp() == $entrada3->getTimestamp())
            ) {

                foreach ($this->limitesExtras as $tipo => $limite) {

                    if($limite > 0) {

                        $tipoExtra = $tipo;

                        if( BaseHora::verificaHoraEstaNoIntervalo($momentoAtual, $this->horaNoturnaInicio, $this->horaNoturnaFim) 
                            || $momentoAtual->getTimestamp() < $this->horaNoturnaFimNoMesmoDia->getTimestamp()
                        ) {
                        
                            switch ($tipo) {
                                case BaseHora::HORAS_EXTRA50:
                                    $tipoExtra = BaseHora::HORAS_EXTRA50_NOTURNA;
                                    break;
                                
                                case BaseHora::HORAS_EXTRA75:
                                    $tipoExtra = BaseHora::HORAS_EXTRA75_NOTURNA;
                                    break;
                                
                                case BaseHora::HORAS_EXTRA100:
                                    $tipoExtra = BaseHora::HORAS_EXTRA100_NOTURNA;
                                    break;
                            }
                        }
                        
                        $this->limitesExtras[$tipo]--;
                        break;
                    }
                }
                
                if( $horaExtraAutorizada <= 0 ) {

                    switch ($tipo) {
                        case BaseHora::HORAS_EXTRA50:
                            $tipoExtra = BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS;
                            break;

                        case BaseHora::HORAS_EXTRA50_NOTURNA:
                            $tipoExtra = BaseHora::HORAS_EXTRA50_NAO_AUTORIZADAS_NOTURNA;
                            break;
                        
                        case BaseHora::HORAS_EXTRA75:
                            $tipoExtra = BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS;
                            break;

                        case BaseHora::HORAS_EXTRA75_NOTURNA:
                            $tipoExtra = BaseHora::HORAS_EXTRA75_NAO_AUTORIZADAS_NOTURNA;
                            break;
                        
                        case BaseHora::HORAS_EXTRA100:
                            $tipoExtra = BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS;
                            break;
                            
                        case BaseHora::HORAS_EXTRA100_NOTURNA:
                            $tipoExtra = BaseHora::HORAS_EXTRA100_NAO_AUTORIZADAS_NOTURNA;
                            break;
                    }
                    $this->horasExtrasNaoAutorizadas[$tipoExtra]++;    
                    return false;
                }

                $horaExtraAutorizada--;
                $this->horasExtras[$tipoExtra]++;
                return true;
            }
        }

        return false;
    }

    /**
     * @return $limitesExtras;
     */
    public function getLimitesExtras()
    {
        return $this->limitesExtras;
        $limitesExtras = array(
            'HORAS_EXTRA50'   => (!empty($this->limitesExtras[BaseHora::HORAS_EXTRA50])  ? $this->limitesExtras[BaseHora::HORAS_EXTRA50]  : null),
            'HORAS_EXTRA75'   => (!empty($this->limitesExtras[BaseHora::HORAS_EXTRA75])  ? $this->limitesExtras[BaseHora::HORAS_EXTRA75]  : null),
            'HORAS_EXTRA100'  => (!empty($this->limitesExtras[BaseHora::HORAS_EXTRA100]) ? $this->limitesExtras[BaseHora::HORAS_EXTRA100] : null)
        );
        
        return $limitesExtras;
    }

    /**
     * @param $limitesExtras; $limitesExtras
     *
     * @return self
     */
    public function setLimitesExtras($limitesExtras)
    {
        $this->limitesExtras = $limitesExtras;
        return $this;
    }

    /**
     * @return $horasExtras
     */
    public function getHorasExtras()
    {
        return $this->horasExtras;
        $horasExtras = array(
            'HORAS_EXTRA50'           => (!empty($this->horasExtras[BaseHora::HORAS_EXTRA50])          ? $this->horasExtras[BaseHora::HORAS_EXTRA50]          : null),
            'HORAS_EXTRA75'           => (!empty($this->horasExtras[BaseHora::HORAS_EXTRA75])          ? $this->horasExtras[BaseHora::HORAS_EXTRA75]          : null),
            'HORAS_EXTRA100'          => (!empty($this->horasExtras[BaseHora::HORAS_EXTRA100])         ? $this->horasExtras[BaseHora::HORAS_EXTRA100]         : null),
            'HORAS_EXTRA50_NOTURNA'   => (!empty($this->horasExtras[BaseHora::HORAS_EXTRA50_NOTURNA])  ? $this->horasExtras[BaseHora::HORAS_EXTRA50_NOTURNA]  : null),
            'HORAS_EXTRA75_NOTURNA'   => (!empty($this->horasExtras[BaseHora::HORAS_EXTRA75_NOTURNA])  ? $this->horasExtras[BaseHora::HORAS_EXTRA75_NOTURNA]  : null),
            'HORAS_EXTRA100_NOTURNA'  => (!empty($this->horasExtras[BaseHora::HORAS_EXTRA100_NOTURNA]) ? $this->horasExtras[BaseHora::HORAS_EXTRA100_NOTURNA] : null)
        );
        
        return $horasExtras;
    }

    /**
     * @param $horasExtras $horasExtras
     *
     * @return self
     */
    public function setHorasExtras($horasExtras)
    {
        $this->horasExtras = $horasExtras;
        return $this;
    }

    /**
     * @param $horasExtrasNaoAutorizadas $horasExtrasNaoAutorizadas
     *
     * @return self
     */
    public function setHorasExtrasNaoAutorizadas($horasExtrasNaoAutorizadas)
    {
        $this->horasExtrasNaoAutorizadas = $horasExtrasNaoAutorizadas;
        return $this;
    }

    /**
     * @return $horasExtrasNaoAutorizadas
     */
    public function getHorasExtrasNaoAutorizadas()
    {
        return $this->horasExtrasNaoAutorizadas;
    }
}
