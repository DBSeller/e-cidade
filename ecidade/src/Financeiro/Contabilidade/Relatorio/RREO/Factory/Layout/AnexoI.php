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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoI as Anexo2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoI as Anexo2018;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout\AnexoI as Anexo2019;

/**
 * Factory Layout
 */
class AnexoI {

  /**
   * @param  integer  $ano         Ano atual
   * @param  \Periodo $periodo     Periodo dos relatório contabeis
   * @param  string   $instituicao Nome da instituição
   * @return \Anexo
   */
  public static function getInstance($ano, \Periodo $periodo, $instituicao, $sessao = null) {

    if ($ano >= 2019) {
      return new Anexo2019($ano, $periodo, $instituicao, $sessao);
    }

    if ($ano >= 2018) {
      return new Anexo2018($ano, $periodo, $instituicao);
    }

    return new Anexo2017($ano, $periodo, $instituicao);
  }
}
