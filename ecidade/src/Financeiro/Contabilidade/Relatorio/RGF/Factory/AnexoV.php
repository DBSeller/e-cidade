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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory;

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoV as AnexoV2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoV as AnexoV2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\AnexoV as AnexoV2019;
use Exception;
use Periodo;

/**
 * Class AnexoV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory
 */
class AnexoV
{
    /**
     * @param $ano
     * @param Periodo $periodo
     * @return AnexoV2017|AnexoV2018
     * @throws Exception
     */
    public static function getInstance($ano, Periodo $periodo)
    {
        switch ($ano) {
            case 2017:
                return new AnexoV2017($ano, $periodo->getCodigo());
                break;
            case 2018:
                return new AnexoV2018($ano, $periodo->getCodigo());
                break;
            case 2019:
            case 2020:
            case 2021:
            case 2022:
                return new AnexoV2019($ano, $periodo->getCodigo());
                break;
            default:
                return new AnexoV2018($ano, $periodo->getCodigo());
        }
    }
}
