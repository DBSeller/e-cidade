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

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Factories;

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout\FluxoCaixa2020Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout\FluxoCaixaIPC082020Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout\FluxoCaixaLayout;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout\FluxoCaixaMCASP2020Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\FluxoCaixa;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\FluxoCaixaIPC82020;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\FluxoCaixaMCASP2020;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Service\FluxoCaixaService;
use Exception;

/**
 * Class FluxoCaixaFactory
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Factories
 */
class FluxoCaixaFactory
{
    const MODELO_MCASP = 1;
    const MODELO_IPC08 = 2;

    /**
     * @param integer $modelo
     * @param string $ano
     * @param integer $codigoPeriodo
     * @return FluxoCaixa
     * @throws Exception
     */
    public static function getModel($modelo, $ano, $codigoPeriodo)
    {
        switch ($modelo) {
            case self::MODELO_MCASP:
                return self::getModeloMCASP($ano, $codigoPeriodo);
                break;
            case self::MODELO_IPC08:
                return self::getModeloIPC08($ano, $codigoPeriodo);
                break;
            default:
                throw new Exception("Classe de processamento do Fluxo de Caixa não encontrado.");
        }
    }

    /**
     * @param integer $modelo
     * @param string $ano
     * @param FluxoCaixa $relatorio
     * @return FluxoCaixaLayout
     * @throws Exception
     */
    public static function getLayout($modelo, $ano, FluxoCaixa $relatorio)
    {
        switch ($modelo) {
            case self::MODELO_MCASP:
                return self::getLayoutMCASP($ano, $relatorio);
                break;
            case self::MODELO_IPC08:
                return self::getLayoutIPC08($ano, $relatorio);
                break;
            default:
                throw new Exception("Classe de processamento do Fluxo de Caixa não encontrado.");
        }
    }

    /**
     * @param string $ano
     * @param integer $codigoPeriodo
     * @return FluxoCaixaMCASP2020
     * @throws Exception
     */
    private static function getModeloMCASP($ano, $codigoPeriodo)
    {
        if ($ano >= 2020) {
            return new FluxoCaixaMCASP2020($ano, $codigoPeriodo);
        }
        throw new Exception("Classe de processamento do Fluxo de Caixa MCASP não encontrado.");
    }

    /**
     * @param string $ano
     * @param integer $codigoPeriodo
     * @return FluxoCaixaIPC82020
     * @throws Exception
     */
    private static function getModeloIPC08($ano, $codigoPeriodo)
    {
        if ($ano >= 2020) {
            return new FluxoCaixaIPC82020($ano, $codigoPeriodo);
        }
        throw new Exception("Classe de processamento do Fluxo de Caixa MCASP IPC08 não encontrado.");
    }

    /**
     * @param string $ano
     * @param FluxoCaixa $relatorio
     * @return FluxoCaixaMCASP2020Layout
     * @throws Exception
     */
    private static function getLayoutMCASP($ano, FluxoCaixa $relatorio)
    {
        if ($ano >= 2020) {
            return new FluxoCaixaMCASP2020Layout($relatorio);
        }
        throw new Exception("Modelo de layout para Fluxo de Caixa não encontrado.");
    }

    /**
     * @param string $ano
     * @param FluxoCaixa $relatorio
     * @return FluxoCaixaIPC082020Layout
     * @throws Exception
     */
    private static function getLayoutIPC08($ano, FluxoCaixa $relatorio)
    {
        if ($ano >= 2020) {
            return new FluxoCaixaIPC082020Layout($relatorio);
        }
        throw new Exception("Modelo de layout para Fluxo de Caixa IPC08 não encontrado.");
    }
}
