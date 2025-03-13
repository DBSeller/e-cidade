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

namespace ECidade\Financeiro\Contabilidade\Sigap\Repository;

use cl_meiocomunicacaosigap;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Contabilidade\Sigap\Model\MeioComunicacao;
use Exception;

class MeioComunicacaoRespository extends Repository
{
    public static function find($id)
    {
        $dao = new cl_meiocomunicacaosigap();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar meio de comunicação.");
        }

        return MeioComunicacao::fromState(pg_fetch_array($rs));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $where = implode(' and ', $this->scopes);
        $dao = new cl_meiocomunicacaosigap();
        $sql = $dao->sql_query_file(null, '*', 'c49_descricao', $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar os meios de comunicação.");
        }

        $meios = [];
        while ($state = pg_fetch_array($rs)) {
            $meios[] = MeioComunicacao::fromState($state);
        }

        return $meios;
    }

    /**
     * @param $uf
     * @return $this
     */
    public function scopeUf($uf)
    {
        $this->scopes[] = "c49_uf = '{$uf}'";
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function scopeId($id)
    {
        $this->scopes[] = "c49_sequencial = {$id}";
        return $this;
    }
}
