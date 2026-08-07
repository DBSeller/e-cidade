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
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoVIII as Layout2018;

class AnexoVIII extends Layout2018
{

    /**
     * Código Padrão do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 195;

    const TOTAL_LINHAS_FINANCEIRO = 13;

    protected $aPosicoes = array(
        self::RECEITA_RESULTANTE_IMPOSTO => array('inicio' => 1, 'fim' => 23), //0
        self::RECEITA_ADICIONAL => array('inicio' => 24, 'fim' => 37), //1
        self::RECEITA_FUNDEB => array('inicio' => 38, 'fim' => 49), //2
        self::DESPESA_FUNDEB => array('inicio' => 50, 'fim' => 56), //3
        self::DEDUCAO_FUNDEB => array('inicio' => 57, 'fim' => 63), //4
        self::INDICADOR_FUNDEB => array('inicio' => 64, 'fim' => 67), //5
        self::CONTROLE_USO_RECURSO_SUBSEQUENTE => array('inicio' => 68, 'fim' => 69), //6
        self::DESPESA_MDE => array('inicio' => 70, 'fim' => 84), //7
        self::DEDUCAO_LIMITE_CONSTITUCIONAL => array('inicio' => 85, 'fim' => 93), //8
        self::OUTRAS_DESPESAS_INF_CONTROLE => array('inicio' => 94, 'fim' => 99),//9
        self::RP_VINCULADO_ENSINO => array('inicio' => 100, 'fim' => 102),//10
        self::CONTROLE_FINANCEIRO => array('inicio' => 103, 'fim' => 115),//11
    );
}
