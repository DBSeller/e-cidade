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

namespace ECidade\Educacao\Escola\Repository;

use cl_censouf;
use ECidade\Educacao\Escola\Model\CensoUf;
use Exception;

class CensoUfRepository extends Repository
{
    /**
     * @param string $uf
     * @return CensoUf
     * @throws Exception
     */
    public static function find($uf)
    {
        $uf = strtoupper($uf);
        $dao = new cl_censouf();
        $sql = $dao->sql_query_file(null, "*", null, "ed260_c_sigla = '$uf'");
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar os Estados.");
        }

        return CensoUf::fromState(pg_fetch_array($rs));
    }

    /**
     * @param integer $id
     * @return CensoUf
     * @throws Exception
     */
    public static function findId($id)
    {
        $dao = new cl_censouf();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar os Estados.");
        }

        return CensoUf::fromState(pg_fetch_array($rs));
    }

    /**
     * @return CensoUf[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_censouf();

        $sql = $dao->sql_query_file(null, "*", null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar os Estados.");
        }

        $ufs = array();
        while ($state = pg_fetch_array($rs)) {
            $ufs[] = CensoUf::fromState($state);
        }

        return $ufs;
    }

    /**
     * @param integer $codigo
     * @param string $operador
     * @return $this
     */
    public function scopeCodigo($codigo, $operador = '=')
    {
        $this->scopes['codigo'] = "ed260_i_codigo {$operador} {$codigo}";
        return $this;
    }
}
