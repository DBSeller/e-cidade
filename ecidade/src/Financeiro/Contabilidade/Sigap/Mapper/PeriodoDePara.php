<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
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

namespace ECidade\Financeiro\Contabilidade\Sigap\Mapper;

use Periodo;

/**
 * Class PeriodoDePara
 * @package ECidade\Financeiro\Contabilidade\Sigap\Mapper
 */
abstract class PeriodoDePara
{
    /**
     * @var int[]
     */
    protected static $deParaBimestre = [
        6 => '01',
        7 => '02',
        8 => '03',
        9 => '04',
        10 => '05',
        11 => '06',
    ];

    /**
     * @var int[]
     */
    protected static $deParaQuadrimestre = [
        7 => '01',
        14 => '01',
        9 => '02',
        15 => '02',
        11 => '03',
        16 => '03',
    ];

    /**
     * @param Periodo $periodo
     * @return int
     */
    public static function bimestre(Periodo $periodo)
    {
        return static::$deParaBimestre[$periodo->getCodigo()];
    }

    /**
     * @param Periodo $periodo
     * @return int
     */
    public static function quadrimestre(Periodo $periodo)
    {
        return static::$deParaQuadrimestre[$periodo->getCodigo()];
    }
}
