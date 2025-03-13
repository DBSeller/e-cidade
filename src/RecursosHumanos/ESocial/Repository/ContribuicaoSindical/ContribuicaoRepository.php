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


use cl_contribuicaosindicalperiodosidicatos;
use ECidade\RecursosHumanos\ESocial\Model\ContribuicaoSindical\Contribuicao;
use Exception;

class ContribuicaoRepository
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
     * @param Contribuicao|null $contribuicao
     * @throws Exception
     */
    public function delete(Contribuicao $contribuicao = null)
    {
        $id = $contribuicao instanceof Contribuicao ? $contribuicao->getSequencial() : null;

        $dao = new cl_contribuicaosindicalperiodosidicatos;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o período da contribuicao sindical.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Contribuicao
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_contribuicaosindicalperiodosidicatos;
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período da contribuicao sindical.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Contribuicao::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Contribuicao[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_contribuicaosindicalperiodosidicatos;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $contribuicao = array();

        if (pg_num_rows($rs) === 0) {
            return $contribuicao;
        }

        while ($contribuicaoSindicalPatronal = pg_fetch_array($rs)) {
            $contribuicao[] = Contribuicao::fromState($contribuicaoSindicalPatronal);
        }

        return $contribuicao;
    }

    /**
     * @return Contribuicao[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_contribuicaosindicalperiodosidicatos;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período da contribuicao sindical");
        }

        $contribuicao = array();

        if (pg_num_rows($rs) === 0) {
            return $contribuicao;
        }

        while ($contribuicaoSindicalPatronal = pg_fetch_array($rs)) {
            $contribuicao[] = Contribuicao::fromState($contribuicaoSindicalPatronal);
        }

        return $contribuicao;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_contribuicaosindicalperiodosidicatos;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as contribuições sindicais patronais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param Contribuicao $contribuicao
     * @return Contribuicao
     * @throws Exception
     */
    public function save(Contribuicao $contribuicao)
    {
        $dao = new cl_contribuicaosindicalperiodosidicatos;
        $dao->eso31_sequencial = $contribuicao->getSequencial();
        $dao->eso31_rhsindicato = $contribuicao->getSindicato()->getSequencial();
        $dao->eso31_tipo = $contribuicao->getTipoContribuicao();
        $dao->eso31_valor = $contribuicao->getValor();
        $dao->eso31_contribuicaosindicalperiodo = $contribuicao->getPeriodo()->getSequencial();

        $dao->eso31_sequencial ? $dao->alterar($contribuicao->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar a contribuição sindical." . $dao->erro_msg);
        }

        $contribuicao->setSequencial($dao->eso31_sequencial);

        return $contribuicao;
    }

    /**
     * @param $sequencial
     * @param $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator)
    {
        $this->scopes['sequencial'] = "eso31_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $sindicatoId
     * @param string $operator
     * @return $this
     */
    public function scopeSindicato($sindicatoId, $operator = '=')
    {
        $this->scopes['sindicato'] = "eso31_rhsindicato {$operator} {$sindicatoId}";
        return $this;
    }

    /**
     * @param $periodo
     * @param string $operator
     * @return $this
     */
    public function scopePeriodo($periodo, $operator = '=')
    {
        $this->scopes['periodo'] = "eso31_contribuicaosindicalperiodo {$operator} '{$periodo}'";
        return $this;
    }

    /**
     * @param $tipoContribuicao
     * @param string $operator
     * @return $this
     */
    public function scopeTipoContribuicao($tipoContribuicao, $operator = '=')
    {
        $this->scopes['tipoContribuicao'] = "eso31_tipo {$operator} {$tipoContribuicao}";
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
     * @return ContribuicaoRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }


}
