<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2018  DBSeller Servicos de Informatica
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


/**
 * Class cl_issveiculo
 */
class cl_issveiculo extends DAOBasica {

	public function __construct()
	{
    parent::__construct('issqn.issveiculo');
  }

  public function sqlDados($campos = '*', $where = array())
  {

  	$sql = "SELECT {$campos}
              FROM issqn.issveiculo
             INNER JOIN issqn.issbase ON issbase.q02_inscr = issveiculo.q172_issbase
             INNER JOIN protocolo.cgm ON cgm.z01_numcgm = issbase.q02_numcgm
              LEFT JOIN issqn.issalvara ON issalvara.q123_inscr = issbase.q02_inscr
    ";

    if (!empty($where)) {
    	$where = implode(" AND ", $where);
    	$sql .= " WHERE {$where} ";
    }

    $sql .= " ORDER BY q172_sequencial DESC";
    return $sql;
	}
}

