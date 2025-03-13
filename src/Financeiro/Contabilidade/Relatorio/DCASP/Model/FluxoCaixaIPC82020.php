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

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model;

use cl_empresto;
use DBDate;
use Exception;
use RelatoriosLegaisBase;

/**
 * Class FluxoCaixa2020
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model
 */
class FluxoCaixaIPC82020 extends FluxoCaixaMCASP2020
{
    const CODIGO_RELATORIO = 242;

    /**
     * As linhas retornadas por esse metodo tem que somar não apenas os valores do bancete ao qual são vínculadas
     * Mas também todos as despesas pagar no(s) exercício(s) anterior(es).
     * @return int[]
     */
    protected function linhasQuePrecisamCalcularDespesasExercicioAnterior()
    {
        $linhasCalculo = array(26, 27, 28, 36, 37, 52, 53, 54, 55, 56, 87, 88, 89);
        for ($linha = 58; $linha <= 85; $linha++) {
            $linhasCalculo[] = $linha;
        }
        sort($linhasCalculo);
        return $linhasCalculo;
    }

    public function getDados()
    {
        parent::getDados(); // TODO: Realiza o processamento das linhas

        // As linhas abaixo servem para cálculo da linha 11 e não devem ser impressa.
        unset($this->aLinhasConsistencia[12]);
        unset($this->aLinhasConsistencia[13]);

        return $this->aLinhasConsistencia;
    }
}
