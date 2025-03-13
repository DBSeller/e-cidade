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

namespace ECidade\RecursosHumanos\Pessoal\Repository;

use cl_situacaoafastamento;
use ECidade\RecursosHumanos\Pessoal\Model\AfastamentoSituacao;
use Exception;

/**
 * Class AfastamentoSituacaoRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class AfastamentoSituacaoRepository
{
    /**
     * @var cl_situacaoafastamento
     */
    private $dao;

    /**
     * @var array
     */
    private $scopes = array();

    public function scopeSequencial($sequencial, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh166_sequencial {$operator} $sequencial";
    }

    /**
     * AfastamentoSituacaoRepository constructor.
     * @param cl_situacaoafastamento $dao
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    private function resetScopes()
    {
        $this->scopes = array();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $sql = $this->dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível os tipos de afastamento.");
        }

        $tiposAfastamento = array();

        if (pg_num_rows($rs) === 0) {
            return $tiposAfastamento;
        }

        while ($tipoAfastamento = pg_fetch_array($rs)) {
            $tiposAfastamento[] = AfastamentoSituacao::fromState($tipoAfastamento);
        }

        $this->resetScopes();

        return $tiposAfastamento;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|AfastamentoSituacao
     * @throws Exception
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a situaçao.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $result = pg_fetch_array($rs);

        return AfastamentoSituacao::fromState($result);
    }

    /**
     * @param array $columns
     * @return AfastamentoSituacao[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $sql = $this->dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $results = array();

        if (pg_num_rows($rs) === 0) {
            return $results;
        }

        while ($result = pg_fetch_array($rs)) {
            $results[] = AfastamentoSituacao::fromState($result);
        }

        return $results;
    }
}
