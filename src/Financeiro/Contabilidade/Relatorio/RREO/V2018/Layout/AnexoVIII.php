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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoVIII as Layout2017;

class AnexoVIII extends Layout2017
{

    /**
     * Código Padrão do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 179;

    protected $aPosicoes = array(
        self::RECEITA_RESULTANTE_IMPOSTO => array('inicio' => 1, 'fim' => 26), //0
        self::RECEITA_ADICIONAL => array('inicio' => 27, 'fim' => 40), //1
        self::RECEITA_FUNDEB => array('inicio' => 41, 'fim' => 52), //2
        self::DESPESA_FUNDEB => array('inicio' => 53, 'fim' => 59), //3
        self::DEDUCAO_FUNDEB => array('inicio' => 60, 'fim' => 66), //4
        self::INDICADOR_FUNDEB => array('inicio' => 67, 'fim' => 70), //5
        self::CONTROLE_USO_RECURSO_SUBSEQUENTE => array('inicio' => 71, 'fim' => 72), //6
        self::DESPESA_MDE => array('inicio' => 73, 'fim' => 87), //7
        self::DEDUCAO_LIMITE_CONSTITUCIONAL => array('inicio' => 88, 'fim' => 96), //8
        self::OUTRAS_DESPESAS_INF_CONTROLE => array('inicio' => 97, 'fim' => 102),//9
        self::RP_VINCULADO_ENSINO => array('inicio' => 103, 'fim' => 105),//10
        self::CONTROLE_FINANCEIRO => array('inicio' => 106, 'fim' => 116),//11
    );
}
