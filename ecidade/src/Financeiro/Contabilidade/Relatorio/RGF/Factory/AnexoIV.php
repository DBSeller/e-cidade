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

use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoIV as Anexo2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018\AnexoIV as Anexo2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\AnexoIV as Anexo2019;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2020\AnexoIV as AnexoIV2020;
use Exception;
use Periodo;

/**
 * Class AnexoIV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory
 */
class AnexoIV
{
    /**
     * @param int $ano
     * @param Periodo $periodo
     * @return Anexo2017|Anexo2018|Anexo2019
     * @throws Exception
     */
    public static function getInstance($ano, Periodo $periodo)
    {
        if ($ano < 2017) {
            throw new Exception("Modelo exclusivo para o ano maior ou igual à 2017.");
        }

        switch ($ano) {
            case 2017:
                return new Anexo2017($ano, $periodo);
            case 2018:
            case 2019:
                return new Anexo2018($ano, $periodo);
            case 2020:
            default:
                return new AnexoIV2020($ano, $periodo);
        }
    }
}
