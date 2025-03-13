<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2019  DBSeller Servicos de Informatica
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
 * Classe responsável pelo cálculo de horas de trabalho
 */
class Trabalho extends RegraCalculo {

    /**
     * Atributo com as horas totais de trabalho
     *
     * @var $minutosTrabalho
     */
    private $minutosTrabalho = 0;
    
    /**
     * Atributo com as horas de trabalho diurnas/noturnas
     *
     * @var $minutosTrabalhoDiurno
     */
    private $minutosTrabalhoDiurno  = 0;

    /**
     * Atributo com as horas de trabalho diurnas/noturnas
     *
     * @var $minutosTrabalhoNoturno
     */
    private $minutosTrabalhoNoturno = 0;

    /**
     * Construtor da classe
     *
     * @param DiaTrabalho $diaTrabalho
     */ 
    public function __construct (DiaTrabalho $diaTrabalho)
    {
        parent::__construct($diaTrabalho);
        $data = $diaTrabalho->getData()->getDate();
    }

    /**
     * Método que processa a regra para cálculo de extras em dias de trabalho
     *
     * @param \DateTime $momentoAtual
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

        if( ($this->jornada->isDiaTrabalhado() && $this->jornada->estaNaJornada($momentoAtual)) 
             || ($jornadaSaida1 && $momentoAtual->getTimestamp() == $jornadaSaida1->getTimestamp())
             || ($jornadaSaida2 && $momentoAtual->getTimestamp() == $jornadaSaida2->getTimestamp())
        ) {

            if( (!$this->diaTrabalho->getMarcacoesSemAlteracao()->estaNoIntervalo($momentoAtual))
                 || ($entrada1 instanceof \DateTime && $momentoAtual->getTimestamp() == $entrada1->getTimestamp()) 
                 || ($entrada2 instanceof \DateTime && $momentoAtual->getTimestamp() == $entrada2->getTimestamp())
                 || ($entrada3 instanceof \DateTime && $momentoAtual->getTimestamp() == $entrada3->getTimestamp())
            ) {

                if( BaseHora::verificaHoraEstaNoIntervalo($momentoAtual, $this->horaNoturnaInicio, $this->horaNoturnaFim) 
                    || $momentoAtual->getTimestamp() < $this->horaNoturnaFimNoMesmoDia->getTimestamp()
                ) {
                    $this->minutosTrabalhoNoturno++;
                } else {
                    $this->minutosTrabalhoDiurno++;
                }

                $this->minutosTrabalho++;
            }
        }

        if(!empty($this->minutosTrabalhoNoturno)) {

            $this->converterMinutosNoturnos();
            $this->minutosTrabalho = $this->minutosTrabalhoNoturno + $this->minutosTrabalhoDiurno;
        }

        if(!empty($this->minutosTrabalho)) {
            return true;
        }

        return false;
    }

    /**
     * Método responsável por converter uma hora diurna em uma corresponde noturna,
     * para cada 1 hora de trabalho noturna considera-se uma hora "cheia"
     * contendo  50 minutos e meio
     */
    private function converterMinutosNoturnos()
    {
        $this->minutosTrabalhoNoturno = ($this->minutosTrabalhoNoturno * 50.5) / 60;
    }

    /**
     * @return $minutosTrabalho
     */
    public function getMinutosTrabalho()
    {
        return $this->minutosTrabalho;
    }

    /**
     * @return $minutosTrabalhoDiurno
     */
    public function getMinutosTrabalhoDiurno()
    {
        return $this->minutosTrabalhoDiurno;
    }

    /**
     * @return $minutosTrabalhoNoturno
     */
    public function getMinutosTrabalhoNoturno()
    {
        return $this->minutosTrabalhoNoturno;
    }
}
