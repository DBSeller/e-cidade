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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV as AnexoIV2017;

/**
 * Class AnexoIV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class AnexoIV extends AnexoIV2017
{
    /**
     * Código do relatório no E-Cidade
     * @type integer
     */
    const CODIGO_RELATORIO = 176;

    public function getDados($trazerConfiguracaoPadrao = true)
    {
        if (empty($this->aLinhasConsistencia)) {
            parent::getDados($trazerConfiguracaoPadrao);
        }
        return $this->aLinhasConsistencia;
    }

    /**
     * Retorna os dados do relatórios simplificado
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {

        $this->getDados();
        $oDadosSimplificado = new \stdClass();
        $oDadosSimplificado->receitasRealizadasPlanoPrevidenciario = $this->aLinhasConsistencia[34]->rec_atebim;
        $oDadosSimplificado->despesasLiquidadasPlanoPrevidenciario = $this->aLinhasConsistencia[50]->liq_atebim;
        $oDadosSimplificado->despesasEmpenhadasPlanoPrevidenciario = $this->aLinhasConsistencia[50]->emp_atebim;

        $oDadosSimplificado->receitasRealizadasPlanoFinanceiro = $this->aLinhasConsistencia[93]->rec_atebim;
        $oDadosSimplificado->despesasLiquidadasPlanoFinanceiro = $this->aLinhasConsistencia[109]->liq_atebim;
        $oDadosSimplificado->despesasEmpenhadasPlanoFinanceiro = $this->aLinhasConsistencia[109]->emp_atebim;

        return $oDadosSimplificado;
    }
}
