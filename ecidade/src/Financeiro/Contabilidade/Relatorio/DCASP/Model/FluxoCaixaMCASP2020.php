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
class FluxoCaixaMCASP2020 extends FluxoCaixa2020
{
    const CODIGO_RELATORIO = 226;

    /**
     * As linhas retornadas por esse metodo tem que somar não apenas os valores do bancete ao qual são vínculadas
     * Mas também todos as despesas pagar no(s) exercício(s) anterior(es).
     * @return int[]
     */
    protected function linhasQuePrecisamCalcularDespesasExercicioAnterior()
    {
        $linhasCalculo = array(23, 24, 25, 33, 34, 49, 50, 51, 52, 53, 84, 85, 86);
        for ($linha = 55; $linha <= 82; $linha++) {
            $linhasCalculo[] = $linha;
        }
        sort($linhasCalculo);
        return $linhasCalculo;
    }
}
