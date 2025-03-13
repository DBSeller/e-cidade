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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Factory;

use BusinessException;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento\HoraEvento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Evento\HoraTrabalhada;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\ExtraDiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\RegraCalculo as RegraCalculoAbstract;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Collection\Horas;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Model\Extra100;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Model\Extra50;
use ECidade\RecursosHumanos\RH\PontoEletronico\Horas\Model\Extra75;
use ParameterException;

/**
 * Class RegraCalculo
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\RegrasCalculo\Factory
 */
class RegraCalculo
{
    /**
     * @param $tipoRegra
     * @param DiaTrabalho $diaTrabalho
     * @return HoraEvento|HoraTrabalhada|ExtraDiaTrabalho|null
     * @throws BusinessException
     * @throws ParameterException
     */
    public static function getRegra($tipoRegra, DiaTrabalho $diaTrabalho)
    {
        if (empty($tipoRegra) || !is_int($tipoRegra)) {
            throw new ParameterException('Tipo de Regra inválido.');
        }

        $regra = null;

        switch ($tipoRegra) {
            case RegraCalculoAbstract::EXTRA_DIA_TRABALHO:
                $regra = new ExtraDiaTrabalho($diaTrabalho);

                break;

            case RegraCalculoAbstract::HORA_EVENTO:
                $horasCollection = new Horas();
                $horasCollection->add(new Extra50());
                $horasCollection->add(new Extra75());
                $horasCollection->add(new Extra100());

                $regra = new HoraEvento($diaTrabalho, $horasCollection);

                break;

            case RegraCalculoAbstract::HORA_TRABALHADA:
                $horasCollection = new Horas();
                $horasCollection->add(new Extra50());
                $horasCollection->add(new Extra75());
                $horasCollection->add(new Extra100());

                $regra = new HoraTrabalhada($diaTrabalho, $horasCollection);

                break;

            default:
                throw new BusinessException('Nenhuma regra de cálculo encontrada.');

                break;
        }

        return $regra;
    }
}
