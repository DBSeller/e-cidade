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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Evento;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\CalculoHoraLinear;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Factory\RegraCalculo as RegraCalculoFactory;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento\HoraEvento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento\HoraTrabalhada;

/**
 * Classe para cálculo de horas extras em dias de eventos
 * Class ExtraEvento
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
abstract class ExtraEvento
{
    const HORA_EVENTO = 1;
    const HORA_TRABALHADA = 2;

    /**
     * @var DiaTrabalho
     */
    protected $diaTrabalho;

    /**
     * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection
     */
    protected $marcacoesCollection;

    /**
     * @var HoraEvento|HoraTrabalhada|null
     */
    protected $regraCalculo;

    /**
     * ExtraEvento constructor.
     * @param DiaTrabalho $oDiaTrabalho
     * @param $tipoRegra
     * @throws \BusinessException
     * @throws \ParameterException
     */
    public function __construct(DiaTrabalho $oDiaTrabalho, $tipoRegra)
    {
        $this->diaTrabalho = $oDiaTrabalho;
        $this->marcacoesCollection = clone $this->diaTrabalho->getMarcacoes();
        $this->regraCalculo = RegraCalculoFactory::getRegra($tipoRegra, $oDiaTrabalho);
    }

    public function calcular()
    {
        $calculoHoraLinear = new CalculoHoraLinear($this->diaTrabalho);
        $calculoHoraLinear->executarCalculo($this->marcacoesCollection, $this->regraCalculo);
    }
}
