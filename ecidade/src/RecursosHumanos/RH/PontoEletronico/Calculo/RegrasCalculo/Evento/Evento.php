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

use DateInterval;
use DateTime;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\RegraCalculo;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Collection\Horas;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Model\Hora;

/**
 * Class Evento
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento
 */
class Evento extends RegraCalculo
{
    /**
     * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento
     */
    protected $evento = null;

    /**
     * @var Horas
     */
    protected $horas;

    /**
     * @var Validacao
     */
    protected $validacao;

    /**
     * Evento constructor.
     * @param DiaTrabalho|null $diaTrabalho
     * @param Horas $horas
     */
    public function __construct(DiaTrabalho $diaTrabalho = null, Horas $horas)
    {
        parent::__construct($diaTrabalho);

        $this->evento = $this->getDiaTrabalho()->getEvento();
        $this->horas = $horas;
        $this->validacao = new Validacao($this->evento);

        $this->inicializarHoras();
    }

    /**
     * Inicializa as propriedades de hora extra, como um DateTime
     */
    private function inicializarHoras()
    {
        $data = $this->getDiaTrabalho()->getData()->getDate();
        $this->horas->inicializarHoras(DateTime::createFromFormat('Y-m-d H:i', $data . ' 0:00'));
    }

    /**
     * @param DateTime $momentoAtual
     * @throws \BusinessException
     */
    public function processar(DateTime $momentoAtual)
    {
    }

    /**
     * @param DateTime $momentoAtual
     * @param $tipoExtra
     * @throws \BusinessException
     */
    protected function adicionarHorasExtras(DateTime $momentoAtual, $tipoHoraExtra)
    {
        /**
         * Verifica se está no intervalo noturno
         */
        if (BaseHora::verificaHoraEstaNoIntervalo(
            $momentoAtual,
            $this->horaNoturnaInicio,
            $this->horaNoturnaFim
        ) || $momentoAtual->getTimestamp() <= $this->horaNoturnaFimNoMesmoDia->getTimestamp()) {
            switch ($tipoHoraExtra) {
                case BaseHora::HORAS_EXTRA50:
                case BaseHora::HORAS_EXTRA50_NOTURNA:
                    $this->horas->getHoraByTipo(Hora::EXTRA50)->getNoturna()->add(new DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA75:
                case BaseHora::HORAS_EXTRA75_NOTURNA:
                    $this->horas->getHoraByTipo(Hora::EXTRA75)->getNoturna()->add(new DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA100:
                case BaseHora::HORAS_EXTRA100_NOTURNA:
                    $this->horas->getHoraByTipo(Hora::EXTRA100)->getNoturna()->add(new DateInterval('PT1M'));
                    break;
            }
        } else {
            switch ($tipoHoraExtra) {
                case BaseHora::HORAS_EXTRA50:
                case BaseHora::HORAS_EXTRA50_NOTURNA:
                    $this->horas->getHoraByTipo(Hora::EXTRA50)->getDiurna()->add(new DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA75:
                case BaseHora::HORAS_EXTRA75_NOTURNA:
                    $this->horas->getHoraByTipo(Hora::EXTRA75)->getDiurna()->add(new DateInterval('PT1M'));
                    break;

                case BaseHora::HORAS_EXTRA100:
                case BaseHora::HORAS_EXTRA100_NOTURNA:
                    $this->horas->getHoraByTipo(Hora::EXTRA100)->getDiurna()->add(new DateInterval('PT1M'));
                    break;
            }
        }
    }

    /**
     * @throws \BusinessException
     */
    protected function popularHorasCalculadas()
    {
        $this->getDiaTrabalho()->setHorasExtra50(
            $this->horas->getHoraByTipo(Hora::EXTRA50)->getDiurna()->format('H:i')
        );

        $this->getDiaTrabalho()->setHorasExtra75(
            $this->horas->getHoraByTipo(Hora::EXTRA75)->getDiurna()->format('H:i')
        );

        $this->getDiaTrabalho()->setHorasExtra100(
            $this->horas->getHoraByTipo(Hora::EXTRA100)->getDiurna()->format('H:i')
        );

        $this->getDiaTrabalho()->setHorasExtra50Noturna(
            BaseHora::converterEmHorasNoturnas($this->horas->getHoraByTipo(Hora::EXTRA50)->getNoturna()->format('H:i'))
        );

        $this->getDiaTrabalho()->setHorasExtra75Noturna(
            BaseHora::converterEmHorasNoturnas($this->horas->getHoraByTipo(Hora::EXTRA75)->getNoturna()->format('H:i'))
        );

        $this->getDiaTrabalho()->setHorasExtra100Noturna(
            BaseHora::converterEmHorasNoturnas($this->horas->getHoraByTipo(Hora::EXTRA100)->getNoturna()->format('H:i'))
        );
    }

    /**
     * @return Validacao
     */
    protected function getValidacao()
    {
        return $this->validacao;
    }
}
