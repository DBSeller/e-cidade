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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\AnexoIV as Layout2018;

class AnexoIV extends Layout2018
{
    /**
     * Código do relatório no E-Cidade
     * @type integer
     */
    const CODIGO_RELATORIO = 196;

    const LINHAS_IGNORAR_DESPESA = array(52);
    const LINHAS_IGNORAR_RECEITA = array(51);

    public function getDados($trazerConfiguracaoPadrao = true)
    {
        if (empty($this->aLinhasConsistencia)) {
            parent::getDados($trazerConfiguracaoPadrao);
        }
        return $this->aLinhasConsistencia;
    }

    /**
     * Executa o cálculo entre receita e despesa
     */
    protected function totalizarResultadosPrevidenciarios()
    {

        $prev_ini = $this->aLinhasConsistencia[33]->prev_ini;
        $this->aLinhasConsistencia[50]->dot_ini = ($prev_ini - $this->aLinhasConsistencia[49]->dot_ini);
        $prev_atual = $this->aLinhasConsistencia[33]->prev_atual;
        $this->aLinhasConsistencia[50]->dot_atual = ($prev_atual - $this->aLinhasConsistencia[49]->dot_atual);
        $rec_atebim = $this->aLinhasConsistencia[33]->rec_atebim;
        $this->aLinhasConsistencia[50]->liq_atebim = ($rec_atebim - $this->aLinhasConsistencia[49]->liq_atebim);
        $recbiexant = $this->aLinhasConsistencia[33]->recbiexant;
        $liq_atebimexant1 = $this->aLinhasConsistencia[49]->liq_atebimexant;
        $this->aLinhasConsistencia[50]->liq_atebimexant = ($recbiexant - $liq_atebimexant1);

        $prev_ini1 = $this->aLinhasConsistencia[91]->prev_ini;
        $this->aLinhasConsistencia[108]->dot_ini = ($prev_ini1 - $this->aLinhasConsistencia[107]->dot_ini);
        $prev_atual1 = $this->aLinhasConsistencia[91]->prev_atual;
        $this->aLinhasConsistencia[108]->dot_atual = ($prev_atual1 - $this->aLinhasConsistencia[107]->dot_atual);
        $rec_atebim1 = $this->aLinhasConsistencia[91]->rec_atebim;
        $this->aLinhasConsistencia[108]->liq_atebim = ($rec_atebim1 - $this->aLinhasConsistencia[107]->liq_atebim);
        $recbiexant1 = $this->aLinhasConsistencia[91]->recbiexant;
        $liq_atebimexant = $this->aLinhasConsistencia[107]->liq_atebimexant;
        $this->aLinhasConsistencia[108]->liq_atebimexant = ($recbiexant1 - $liq_atebimexant);
    }

    /**
     * Retorna os dados do relatórios simplificado
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {
        $this->getDados();
        $oDadosSimplificado = new \stdClass();
        $oDadosSimplificado->receitasRealizadasPlanoPrevidenciario = $this->aLinhasConsistencia[33]->rec_atebim;
        $oDadosSimplificado->despesasLiquidadasPlanoPrevidenciario = $this->aLinhasConsistencia[49]->liq_atebim;
        $oDadosSimplificado->despesasEmpenhadasPlanoPrevidenciario = $this->aLinhasConsistencia[49]->emp_atebim;

        $oDadosSimplificado->receitasRealizadasPlanoFinanceiro = $this->aLinhasConsistencia[91]->rec_atebim;
        $oDadosSimplificado->despesasLiquidadasPlanoFinanceiro = $this->aLinhasConsistencia[107]->liq_atebim;
        $oDadosSimplificado->despesasEmpenhadasPlanoFinanceiro = $this->aLinhasConsistencia[107]->emp_atebim;

        return $oDadosSimplificado;
    }
}
