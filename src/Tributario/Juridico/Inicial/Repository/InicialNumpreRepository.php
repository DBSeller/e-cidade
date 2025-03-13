<?php
/**
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

namespace ECidade\Tributario\Juridico\Inicial\Repository;

use cl_inicialnumpre;
use ECidade\Tributario\Juridico\Inicial\InicialNumpre;
use Exception;

/**
 * Class InicialNumpreRepository
 * @package ECidade\Tributario\Juridico\Inicial\Repository
 */
class InicialNumpreRepository
{
    /**
     * @var array
     */
    private $where = array();

    /**
     * @param InicialNumpre $inicialNumpre
     * @return bool
     * @throws Exception
     */
    public function save(InicialNumpre $inicialNumpre)
    {
        $dao = new cl_inicialnumpre();
        $dao->v59_inicial = $inicialNumpre->getInicial();
        $dao->v59_numpre = $inicialNumpre->getNumpre();

        return $this->exists() ? $dao->update($this->where) : $dao->incluir();
    }

    /**
     * @return bool
     */
    private function exists()
    {
        if (count($this->where) === 0) {
            return false;
        }

        $where = array_map(function (array $filter) {
            return "{$filter[0]} {$filter[1]} {$filter[2]}";
        }, $this->where);
        $where = implode(' AND ', $where);

        $query = "SELECT * FROM inicialnumpre WHERE {$where}";

        return pg_num_rows(db_query($query)) > 0;
    }

    /**
     * @param array $where
     * @return InicialNumpreRepository
     */
    public function where(array $where)
    {
        $this->where[] = $where;

        return $this;
    }

    /**
     * @return InicialNumpre[]
     */
    public function get()
    {
        $dao = new \cl_inicialnumpre();

        $where = array_map(function (array $filter) {
            return "{$filter[0]} {$filter[1]} {$filter[2]}";
        }, $this->where);
        $where = implode(' AND ', $where);

        $sql = $dao->sql_query_file(null, '*', null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os numpres dos iniciais.");
        }

        $numpres = array();

        while ($inicial = pg_fetch_array($rs)) {
            $numpres[] = InicialNumpre::fromState($inicial);
        }

        $this->where = array();

        return $numpres;
    }

    /**
     * Filtra por v59_inicial
     *
     * @param mixed $inicial
     * @param mixed $operacao
     * @return InicialNumpreRepository
     */
    public function scopeInicial($inicial, $operacao = '=')
    {
        return $this->where(array(
            'v59_inicial',
            $operacao,
            $inicial
        ));
    }

    public function delete($where)
    {
        $dao = new \cl_inicialnumpre();

        $dao->excluir(null, $where);

        if ($dao->erro_status == 0) {
            throw new \Exception("Erro ao excluir registro da tabela iniicialnumpre: " . $dao->erro_msg);
        }
    }
}
