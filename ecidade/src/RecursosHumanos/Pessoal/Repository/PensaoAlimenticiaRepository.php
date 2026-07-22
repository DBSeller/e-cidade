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

use cl_pensao;
use ECidade\RecursosHumanos\Pessoal\Model\PensaoAlimenticia;
use Exception;
use Servidor;
use CgmFisico;

/**
 * Class PensaoAlimenticiaRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class PensaoAlimenticiaRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param array|int $ids
     * @return int
     * @throws Exception
     */
    public static function destroy($ids)
    {
        $count = 0;
        $ids = is_array($ids) ? $ids : func_get_args();

        $self = new self();

        foreach ($ids as $id) {
            $self->delete(self::find($id));
            $count++;
        }

        return $count;
    }

    /**
     * @param PensaoAlimenticia|null $pensaoAlimenticia
     * @throws Exception
     */
    public function delete(PensaoAlimenticia $pensaoAlimenticia = null)
    {
        $id = $pensaoAlimenticia instanceof PensaoAlimenticia ? $pensaoAlimenticia->getSequencial() : null;

        $dao = new cl_pensao();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a pensão alimentícia.\nContate o suporte.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|PensaoAlimenticia
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_pensao();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a(s) pensão(ões) alimentícia(s) do servidor.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return PensaoAlimenticia::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return PensaoAlimenticia[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_pensao();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $pensoesAlimenticias = array();

        if (pg_num_rows($rs) === 0) {
            return $pensoesAlimenticias;
        }

        while ($pensaoAlimenticia = pg_fetch_array($rs)) {
            $pensoesAlimenticias[] = PensaoAlimenticia::fromState($pensaoAlimenticia);
        }

        return $pensoesAlimenticias;
    }

    /**
     * @return PensaoAlimenticia[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_pensao();
        $sql = $dao->sql_query_dados(null, null, null, null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a(s) pensão(ões) alimentícia(s) do servidor.\nContate o suporte.");
        }

        $pensoesAlimenticias = array();

        if (pg_num_rows($rs) === 0) {
            return $pensoesAlimenticias;
        }

        while ($pensaoAlimenticia = pg_fetch_array($rs)) {
            $pensoesAlimenticias[] = PensaoAlimenticia::fromState($pensaoAlimenticia);
        }

        return $pensoesAlimenticias;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_pensao();
        $sql = $dao->sql_query_dados(null, null, null, null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o total de pensões alimentícias do servidor.\nContate o suporte.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=') {
        $this->scopes['sequencial'] = "r52_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $ano
     * @param string $operator
     * @return $this
     */
    public function scopeAno($ano, $operator = '=') {
        $this->scopes['ano'] = "r52_anousu {$operator} {$ano}";
        return $this;
    }

    /**
     * @param $mes
     * @param string $operator
     * @return $this
     */
    public function scopeMes($mes, $operator = '=') {
        $this->scopes['mes'] = "r52_mesusu {$operator} {$mes}";
        return $this;
    }

    /**
     * @param $servidor
     * @param string $operator
     * @return $this
     */
    public function scopeServidor(Servidor $servidor, $operator = '=') {
        $this->scopes['servidor'] = "r52_regist {$operator} {$servidor->getMatricula()}";
        return $this;
    }

    /**
     * @param $cgmPensionista
     * @param string $operator
     * @return $this
     */
    public function scopeCgmPensionista(CgmFisico $cgmPensionista, $operator = '=') {
        $this->scopes['cgmPensionista'] = "r52_numcgm {$operator} {$cgmPensionista->getCodigo()}";
        return $this;
    }

    /**
     * @param $valorRescisao
     * @param string $operator
     * @return $this
     */
    public function scopeValorRescisao($valorRescisao, $operator = '=') {
        $this->scopes['valorRescisao'] = "r52_valres {$operator} {$valorRescisao}";
        return $this;
    }

    /**
     * @return $this
     */
    public function resetScopes()
    {
        $this->scopes = array();
        return $this;
    }

    /**
     * @param $key
     * @return PensaoAlimenticiaRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}
