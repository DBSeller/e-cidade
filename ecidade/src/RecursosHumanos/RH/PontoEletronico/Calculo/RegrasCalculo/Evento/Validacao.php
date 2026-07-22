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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento;

use DateTime;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento as EventoModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;

/**
 * Class Validacao
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento
 */
class Validacao
{
    /**
     * @var DateTime
     */
    protected $horaEventoInicio;
    /**
     * @var DateTime
     */
    protected $horaEventoFim;
    /**
     * @var int
     */
    protected $tipoHoraExtra;
    /**
     * @var Evento
     */
    private $evento;

    /**
     * Validacao constructor.
     * @param EventoModel $evento
     */
    public function __construct(EventoModel $evento)
    {
        $this->evento = $evento;
        $this->horaEventoInicio = clone $this->evento->getEntradaUm();
        $this->horaEventoFim = clone $this->evento->getSaidaUm();

        if ($this->evento->getSaidaDois() !== null) {
            $this->horaEventoFim = clone $this->evento->getSaidaDois();
        }
    }

    /**
     * @param DateTime $momentoAtual
     * @return int|null
     */
    public function validaPrimeiroPeriodoEvento(DateTime $momentoAtual)
    {
        if (!is_null($this->tipoHoraExtra)) {
            return null;
        }

        if ($momentoAtual->getTimestamp() <= $this->horaEventoInicio->getTimestamp()) {
            return null;
        }

        if (!BaseHora::verificaHoraEstaNoIntervalo(
            $momentoAtual,
            $this->evento->getEntradaUm(),
            $this->evento->getSaidaUm()
        )) {
            return null;
        }

        if ($momentoAtual->getTimestamp() === $this->evento->getEntradaUm()->getTimestamp()) {
            return null;
        }

        if ($momentoAtual->getTimestamp() > $this->evento->getSaidaUm()->getTimestamp()) {
            return null;
        }

        $this->tipoHoraExtra = $this->evento->getTipoHoraExtraUm();

        return true;
    }

    /**
     * @param DateTime $momentoAtual
     * @return int|null
     */
    public function validaSegundoPeriodoEvento(DateTime $momentoAtual)
    {
        if (!is_null($this->tipoHoraExtra)) {
            return null;
        }

        if (!BaseHora::verificaHoraEstaNoIntervalo($momentoAtual, $this->horaEventoInicio, $this->horaEventoFim)) {
            return null;
        }

        if ($this->evento->getEntradaDois() === null && $this->evento->getSaidaDois() === null) {
            return null;
        }

        if (!BaseHora::verificaHoraEstaNoIntervalo(
            $momentoAtual,
            $this->evento->getEntradaDois(),
            $this->evento->getSaidaDois()
        )) {
            return null;
        }

        if ($momentoAtual->getTimestamp() == $this->evento->getEntradaDois()->getTimestamp()) {
            return null;
        }

        if ($momentoAtual->getTimestamp() > $this->evento->getSaidaDois()->getTimestamp()) {
            return null;
        }

        $this->tipoHoraExtra = $this->evento->getTipoHoraExtraDois();

        return true;
    }

    /**
     * @param DateTime $momentoAtual
     * @return bool|null
     */
    public function validaExtraAnteriorInicioEvento(DateTime $momentoAtual)
    {
        if (!is_null($this->tipoHoraExtra)) {
            return null;
        }

        if ($momentoAtual->getTimestamp() > $this->horaEventoInicio->getTimestamp()) {
            return null;
        }

        $this->tipoHoraExtra = $this->evento->getTipoHoraExtraUm();

        return true;
    }

    /**
     * @param DateTime $momentoAtual
     * @return bool|null
     */
    public function validaExtraPosteriorFimEvento(DateTime $momentoAtual)
    {
        if (!is_null($this->tipoHoraExtra)) {
            return null;
        }

        if ($momentoAtual->getTimestamp() <= $this->horaEventoFim->getTimestamp()) {
            return null;
        }

        $this->tipoHoraExtra = $this->evento->getTipoHoraExtraDois();

        if ($this->evento->getEntradaDois() === null) {
            $this->tipoHoraExtra = $this->evento->getTipoHoraExtraUm();
        }

        return true;
    }

    /**
     * @return int
     */
    public function getTipoHoraExtra()
    {
        return $this->tipoHoraExtra;
    }

    /**
     * @param int $tipoHoraExtra
     */
    public function setTipoHoraExtra($tipoHoraExtra)
    {
        $this->tipoHoraExtra = $tipoHoraExtra;
    }

    /**
     * @param DateTime $momentoAtual
     * @param MarcacoesPontoCollection $marcacoesPontoCollection
     * @return bool
     */
    public function estaEmHorarioDeIntervalo(DateTime $momentoAtual, MarcacoesPontoCollection $marcacoesPontoCollection)
    {
        return $marcacoesPontoCollection->estaNoIntervalo($momentoAtual);
    }
}
