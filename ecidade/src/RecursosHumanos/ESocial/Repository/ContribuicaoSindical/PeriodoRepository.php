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


namespace ECidade\RecursosHumanos\ESocial\Repository\ContribuicaoSindical;


use cl_contribuicaosindicalperiodo;
use ECidade\RecursosHumanos\ESocial\Model\ContribuicaoSindical\Periodo;
use Exception;

class PeriodoRepository
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
     * @param Periodo|null $periodo
     * @throws Exception
     */
    public function delete(Periodo $periodo = null)
    {
        $id = $periodo instanceof Periodo ? $periodo->getSequencial() : null;

        $dao = new cl_contribuicaosindicalperiodo();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o período da contribuicao sindical.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Periodo
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_contribuicaosindicalperiodo();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período da contribuicao sindical.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Periodo::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Periodo[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_contribuicaosindicalperiodo();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $periodos = array();

        if (pg_num_rows($rs) === 0) {
            return $periodos;
        }

        while ($contribuicaoSindicalPatronal = pg_fetch_array($rs)) {
            $periodos[] = Periodo::fromState($contribuicaoSindicalPatronal);
        }

        return $periodos;
    }

    /**
     * @return Periodo[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_contribuicaosindicalperiodo();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período da contribuicao sindical");
        }

        $periodos = array();

        if (pg_num_rows($rs) === 0) {
            return $periodos;
        }

        while ($contribuicaoSindicalPatronal = pg_fetch_array($rs)) {
            $periodos[] = Periodo::fromState($contribuicaoSindicalPatronal);
        }

        return $periodos;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_contribuicaosindicalperiodo();
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as contribuições sindicais patronais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param Periodo $periodo
     * @return Periodo
     * @throws Exception
     */
    public function save(Periodo $periodo)
    {
        $dao = new cl_contribuicaosindicalperiodo();
        $dao->eso30_sequencial = $periodo->getSequencial();
        $dao->eso30_empregador = $periodo->getEmpregador()->getCodigo();
        $dao->eso30_indicativo_periodo = $periodo->getIndicativoPeriodo();
        $dao->eso30_periodo = $periodo->getPeriodo();

        $dao->eso30_sequencial ? $dao->alterar($periodo->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações");
        }

        $periodo->setSequencial($dao->eso30_sequencial);

        return $periodo;
    }

    /**
     * @param $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator)
    {
        $this->scopes['sequencial'] = "eso30_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $empregadorId
     * @param string $operator
     * @return $this
     */
    public function scopeEmpregador($empregadorId, $operator = '=')
    {
        $this->scopes['empregador'] = "eso30_empregador {$operator} {$empregadorId}";
        return $this;
    }

    /**
     * @param $indicativoPeriodo
     * @param string $operator
     * @return $this
     */
    public function scopeIndicativoPeriodo($indicativoPeriodo, $operator = '=')
    {
        $this->scopes['indicativo_periodo'] = "eso30_indicativo_periodo {$operator} {$indicativoPeriodo}";
        return $this;
    }

    /**
     * @param $periodo
     * @param string $operator
     * @return $this
     */
    public function scopePeriodo($periodo, $operator = '=')
    {
        $this->scopes['periodo'] = "eso30_periodo {$operator} '{$periodo}'";
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
     * @return PeriodoRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}