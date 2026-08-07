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
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Collection\Horas;

/**
 * Class HoraTrabalhada
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento
 */
class HoraTrabalhada extends Evento
{
    /**
     * @var bool
     */
    private $temHoraExtraAutorizada = false;

    /**
     * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection
     */
    private $marcacoesPontoCollection;

    /**
     * HoraTrabalhada constructor.
     * @param DiaTrabalho|null $diaTrabalho
     * @param Horas $horas
     */
    public function __construct(DiaTrabalho $diaTrabalho = null, Horas $horas)
    {
        parent::__construct($diaTrabalho, $horas);

        $this->temHoraExtraAutorizada = $diaTrabalho->getHorasExtrasAutorizadas() !== null;
        $this->marcacoesPontoCollection = $diaTrabalho->getMarcacoes();
    }

    /**
     * @param \DateTime $momentoAtual
     * @throws \BusinessException
     */
    public function processar(DateTime $momentoAtual)
    {
        do {
            $ultimoRegistro = $this->marcacoesPontoCollection->getUltimaMarcacaoComRegistro();
            $this->getValidacao()->setTipoHoraExtra(null);

            if ($this->validacao->estaEmHorarioDeIntervalo($momentoAtual, $this->marcacoesPontoCollection)) {
                break;
            }

            if ($this->temHoraExtraAutorizada) {
                $this->validacao->validaExtraAnteriorInicioEvento($momentoAtual);
            }

            $this->validacao->validaPrimeiroPeriodoEvento($momentoAtual);
            $this->validacao->validaSegundoPeriodoEvento($momentoAtual);

            if ($this->temHoraExtraAutorizada) {
                $this->validacao->validaExtraPosteriorFimEvento($momentoAtual);
            }

            $this->adicionarHorasExtras($momentoAtual, $this->getValidacao()->getTipoHoraExtra());
            $momentoAtual->modify('+1 minute');
        } while ($momentoAtual->getTimestamp() <= $ultimoRegistro->getMarcacao()->getTimestamp());

        $this->popularHorasCalculadas();
    }
}
