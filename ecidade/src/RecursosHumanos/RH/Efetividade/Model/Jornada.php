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

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;

/**
 * Classe com as informações sobre a jornada de trabalho
 *
 * Class Jornada
 * @package ECidade\RecursosHumanos\RH\Efetividade\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Jornada {

    const DSR   = 1;
    const FOLGA = 2;

    const TIPO_DIA_TRABALHO = 'T';
    const TIPO_FOLGA        = 'F';
    const TIPO_DSR          = 'D';

    const PERIODO_1 = 1;
    const PERIODO_2 = 2;
    const PERIODO_3 = 3;

    public static $aTiposJornada = array(
      Jornada::TIPO_DIA_TRABALHO => 'Dia de Trabalho',
      Jornada::TIPO_FOLGA        => 'Folga',
      Jornada::TIPO_DSR          => 'DSR'
    );

    /**
     * Código da jornada
     * @var int
     */
    private $iCodigo;

    /**
     * Descrição da jornada
     * @var string
     */
    private $sDescricao;

    /**
     * Controla se a jornada é fixa ou configurável
     * @var bool
     */
    private $lFixo;

    /**
     * Controla se a jornada é uma folga
     * @var bool
     */
    private $lFolga;

    /**
     * Controla se a jornada é um dsr
     * @var bool
     */
    private $lDSR;

    /**
     * Controla se a jornada é um dia trabalhado
     * @var bool
     */
    private $lDiaTrabalhado;

    /**
     * Coleção com as horas configuradas para jornada
     * @var array
     */
    private $aHoras = array();

    /**
     * @var string
     */
    private $sTipoDescricao;
    /**
     * A jornada ultrapassa o dia
     * @var boolean
     */
    private $jornadaUltrapassaDia = false;

    /**
     * Retorna o código da jornada
     * @return int
     */
    public function getCodigo() {
        return $this->iCodigo;
    }

    /**
     * Retorna a descrição da jornada
     * @return string
     */
    public function getDescricao() {
        return $this->sDescricao;
    }

    /**
     * Retorna uma coleção de objetos com as informações das horas da jornada
     * @return \stdClass[]
     */
    public function getHoras() {
        return $this->aHoras;
    }

    /**
     * Retorna se a jornada é fixa ou configurável
     * @return bool
     */
    public function isFixo() {
        return $this->lFixo;
    }

    /**
     * Retorna se a jornada é um folga
     * @return bool
     */
    public function isFolga() {
        return $this->lFolga;
    }

    /**
     * Retorna se a jornada é um DSR
     * @return bool
     */
    public function isDSR() {
        return $this->lDSR;
    }

    /**
     * Retorna se a jornada é um dia trabalhado
     * @return bool
     */
    public function isDiaTrabalhado() {
        return $this->lDiaTrabalhado;
    }

    /**
     * @return string
     */
    public function getTipoDescricao() {
        return $this->sTipoDescricao;
    }

    /**
     * @param int $iCodigo
     */
    public function setCodigo($iCodigo) {
        $this->iCodigo = $iCodigo;
    }

    /**
     * @param string $sDescricao
     */
    public function setDescricao($sDescricao) {
        $this->sDescricao = $sDescricao;
    }

    /**
     * @param array $aHoras
     */
    public function setHoras($aHoras) {
        $this->aHoras = $aHoras;
    }

    /**
     * @param bool $lFixo
     */
    public function setFixo($lFixo) {
        $this->lFixo = $lFixo;
    }

    /**
     * Define se a jornada é uma folga
     * @param bool $lFolga
     */
    public function setFolga($lFolga) {
        $this->lFolga = $lFolga;
    }

    /**
     * Define se a jornada é um DSR
     * @param bool $lDSR
     */
    public function setDSR($lDSR) {
        $this->lDSR = $lDSR;
    }

    /**
     * Define se a jornada é um dia trabalhado
     * @param bool @lDiaTrabalhado
     */
    public function setDiaTrabalhado($lDiaTrabalhado) {
        $this->lDiaTrabalhado = $lDiaTrabalhado;
    }

    /**
     * @param $sTipoDescricao
     */
    public function setTipoDescricao($sTipoDescricao) {
        $this->sTipoDescricao = Jornada::$aTiposJornada[$sTipoDescricao];
    }

    /**
     * Ajusta a data dos horários da jornada
     */
    public function ajustarDatasJornada($dataOriginal) {

        if(count($this->aHoras) > 0) {

            foreach ($this->aHoras as $key => $horas) {

                $data = clone $dataOriginal;

                if($key > 0 && $horas->oHora->format('H:i') < $this->aHoras[0]->oHora->format('H:i')) {
                    $data->adiantarPeriodo(1, 'd');
                    $this->jornadaUltrapassaDia = true;
                }

                $horas->oHora->setDate($data->getAno(), $data->getMes(), $data->getDia());
            }
        }
    }

    /**
     * @return bool
     */
    public function temIntervalo() {
        return count($this->aHoras) > 2 ? true : false;
    }


  /**
   * @return bool
   */
  public function temIntervalo2() {
    return count($this->aHoras) > 4 ? true : false;
  }

    /**
     * @return \DateInterval
     */
    public function getIntervalo() {

        $horaFimIntervalo1    = null;
        $horaInicioIntervalo1 = null;
        if(!empty($this->aHoras[2])) {
            $horaFimIntervalo1    = $this->aHoras[2]->oHora;
            $horaInicioIntervalo1 = $this->aHoras[1]->oHora;
        }

        if(empty($horaInicioIntervalo1) || empty($horaFimIntervalo1)) {
            return new \DateInterval('PT0H0M0S');
        }

        $intervalo = $horaInicioIntervalo1->diff($horaFimIntervalo1);
        $intervalo->invert = 0;

        return $intervalo;
    }

    public function getCargaHoraria($periodo = null) {

        if($periodo == self::PERIODO_2 || $periodo == self::PERIODO_3) {

            switch ($periodo) {
                case self::PERIODO_2:

                    if(empty($this->aHoras[2]) || empty($this->aHoras[3])) {
                        $periodo = null;
                    }
                    break;

                case self::PERIODO_3:

                    if(empty($this->aHoras[4]) || empty($this->aHoras[5])) {
                        $periodo = null;
                    }
                    break;
            }
        }

        switch ($periodo) {
            case self::PERIODO_1:
                $momentoAtual = clone $this->aHoras[0]->oHora;
                $horaFim      = clone $this->aHoras[1]->oHora;
                break;

            case self::PERIODO_2:
                $momentoAtual = clone $this->aHoras[2]->oHora;
                $horaFim      = clone $this->aHoras[3]->oHora;
                break;

            case self::PERIODO_3:
                $momentoAtual = clone $this->aHoras[4]->oHora;
                $horaFim      = clone $this->aHoras[5]->oHora;
                break;

            default:
                $momentoAtual = clone $this->aHoras[0]->oHora;
                $horaFim = clone $this->aHoras[count($this->aHoras) -1]->oHora;

                $horaFimIntervalo1    = null;
                $horaInicioIntervalo1 = null;
                if(!empty($this->aHoras[2])) {
                    $horaFimIntervalo1    = clone $this->aHoras[2]->oHora;
                    $horaInicioIntervalo1 = clone $this->aHoras[1]->oHora;
                }
                break;
        }


        $cargaHoraria = new \DateTime();
        $cargaHoraria->setTime(0, 0, 0);
        $cargaHoraria->setDate($momentoAtual->format("Y"), $momentoAtual->format("m"), $momentoAtual->format("d"));

        $horaNoturnaVinteDuas = clone $cargaHoraria;
        $horaNoturnaVinteDuas->setTime(22, 0);

        $horaNoturnaCincoMesmoDia = clone $cargaHoraria;
        $horaNoturnaCincoMesmoDia->setTime(5, 0);

        $horaNoturnaCincoDiaSeguinte = clone $cargaHoraria;
        $horaNoturnaCincoDiaSeguinte->setTime(5, 0);
        $horaNoturnaCincoDiaSeguinte->modify('+1 day');

        $cargaHorariaNoturna = 0;
        $cargaHorariaDiurna  = 0;

        do{

            if(!empty($horaInicioIntervalo1) && !empty($horaFimIntervalo1)) {

                if($this->horaEstaNoIntervalo($momentoAtual, $horaInicioIntervalo1, $horaFimIntervalo1) && $momentoAtual->getTimestamp() != $horaFimIntervalo1->getTimestamp()) {
                    $momentoAtual->modify('+1 minute');
                    continue;
                }
            }

            if(   ($this->horaEstaNoIntervalo($momentoAtual, $horaNoturnaVinteDuas, $horaNoturnaCincoDiaSeguinte) && $momentoAtual->getTimestamp() > $horaNoturnaVinteDuas->getTimestamp())
              || $momentoAtual->getTimestamp() < $horaNoturnaCincoMesmoDia->getTimestamp()
            ){

                $cargaHorariaNoturna++;

            } else {

                $cargaHorariaDiurna++;

            }
            $momentoAtual->modify('+1 minute');

        } while ($momentoAtual->getTimestamp() < $horaFim->getTimestamp());

        $cargaHorariaNoturnaConvertida = BaseHora::converterMinutosEmMinutosNoturnos($cargaHorariaNoturna);

        $cargaHoraria->modify("+ {$cargaHorariaNoturnaConvertida} minutes");
        $cargaHoraria->modify("+ {$cargaHorariaDiurna} minutes");

        return $cargaHoraria;
    }

    protected function horaEstaNoIntervalo(\DateTime $oHoraVerificar, \DateTime $oHoraInicio, \DateTime $oHoraFim) {

        if($oHoraVerificar->diff($oHoraInicio)->invert || ($oHoraVerificar->format('H:i') == $oHoraInicio->format('H:i'))) {
            if($oHoraFim->diff($oHoraVerificar)->invert || ($oHoraVerificar->format('H:i') == $oHoraFim->format('H:i'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se a marcação de entrada ou saída encontra-se dentro do período de Adicional Noturno
     * @return bool
     */
    public function temHorarioNoturno($data = null)
    {
        if (empty($data)) {
            $data = date('Y-m-d');
        }
        $oInicioHorarioNoturno = new \DateTime("{$data} 22:00");
        $oFimHorarioNoturno    = new \DateTime("{$data} 05:00");
        $oFimHorarioNoturno->modify('+1 day');
        $aHorasJornada = $this->getHoras();
        $temJornadaNoturna = false;
        foreach ($aHorasJornada as $oHoraJornada) {

            if ($this->horaEstaNoIntervalo($oHoraJornada->oHora, $oInicioHorarioNoturno, $oFimHorarioNoturno)) {
                $temJornadaNoturna =  true;
            }

            if ($this->horaEstaNoIntervalo($oHoraJornada->oHora, $oInicioHorarioNoturno, $oFimHorarioNoturno)) {
                $temJornadaNoturna =  true;
            }
        }

        return $temJornadaNoturna;
    }

    public function ultrapassaDia()
    {
        return $this->jornadaUltrapassaDia;
    }

    /**
     * @return \DateTime | null
     */
    public function getInicioJornada()
    {
        return (!empty($this->aHoras[0]->oHora)) ? $this->aHoras[0]->oHora : null;
    }

    /**
     * @return \DateTime | null
     */
    public function getFimJornada()
    {
        if(empty($this->aHoras)) {
            return null;
        }

        $ultimoIndice = count($this->aHoras) - 1;
        return (!empty($this->aHoras[$ultimoIndice]->oHora)) ? $this->aHoras[$ultimoIndice]->oHora : null;
    }

    public function toArray()
    {
        $horas = array();

        foreach ($this->aHoras as $oHora) {

            $h = null;

            if($oHora->oHora instanceof \DateTime) {
                $h = $oHora->oHora->format('H:i');
            }

            $horas[$oHora->sTipoRegistro] = $h;
        }

        return $horas;
    }

    /**
   * Retorna a hora da jornada passar por 
   * parâmetro as constantes de entrada e saida
     *
     * @param  int $tipo
     * @return StdClass | null
     */
    public function getHora($tipo) {

        if(!empty($this->aHoras)) {
            return (!empty($this->aHoras[$tipo - 1])) ? $this->aHoras[$tipo - 1] : null;
        }

        return null;
    }

  /**
   * Retorna se a hora informada esta dentro da jornada
   *
   * @param \DateTime $horaVerificar
   * @return Boolean
   */
  public function estaNaJornada(\DateTime $horaVerificar) {

    $horaInicio = $this->getHora(MarcacaoPonto::ENTRADA_1)->oHora;
    $horaFim    = $this->getHora(MarcacaoPonto::SAIDA_1)->oHora;

    if(BaseHora::verificaHoraEstaNoIntervalo($horaVerificar, $horaInicio, $horaFim)) {
  
        return true;
  
    } else {

        if($this->temIntervalo()) {

            $horaInicio = $this->getHora(MarcacaoPonto::ENTRADA_2)->oHora;
            $horaFim    = $this->getHora(MarcacaoPonto::SAIDA_2)->oHora;

            return BaseHora::verificaHoraEstaNoIntervalo($horaVerificar, $horaInicio, $horaFim);
        }
    }

    return false;
  }

  /**
   * Retorna se a hora informada está fora da jornada
   * e em algum intervalo, de almoço por exemplo
   *
   * @param \DateTime $horaVerificar
   * @return Boolean
   */
  public function estaNoIntervalo(\DateTime $horaVerificar) {

    if(!$this->temIntervalo()) {
      return false;
    }

    $horaInicio = $this->getHora(MarcacaoPonto::SAIDA_1);
    $horaFim    = $this->getHora(MarcacaoPonto::ENTRADA_2);

    return BaseHora::verificaHoraEstaNoIntervalo($horaVerificar, $horaInicio, $horaFim);
  }

  /**
   * Retorna a ultima hora da jornada ou
   * false se não tiver nenhuma hora
   *
   * @return StdClass | FALSE
   */
  public function getUltimaHora()
  {
    $ultimaHora = end($this->aHoras);
    reset($ultimaHora);

    return $ultimaHora;
  }
}
