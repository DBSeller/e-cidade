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

use CgmJuridico;
use cl_operadorasaude;
use ECidade\RecursosHumanos\Pessoal\Model\OperadoraSaude;
use Exception;

/**
 * Class OperadoraSaudeRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class OperadoraSaudeRepository
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
     * @param OperadoraSaude|null $operadoraSaude
     * @throws Exception
     */
    public function delete(OperadoraSaude $operadoraSaude = null)
    {
        $sequencial = $operadoraSaude instanceof OperadoraSaude ? $operadoraSaude->getSequencial() : null;

        $dao = new cl_operadorasaude();
        $dao->excluir($sequencial, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a operadora de saúde {$operadoraSaude->getCgm()->getNome()}.\nContate o suporte.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|OperadoraSaude
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_operadorasaude();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a operadora de saúde.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return OperadoraSaude::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return OperadoraSaude[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_operadorasaude();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $operadorasSaude = array();

        if (pg_num_rows($rs) === 0) {
            return $operadorasSaude;
        }

        while ($operadoraSaude = pg_fetch_array($rs)) {
            $operadorasSaude[] = OperadoraSaude::fromState($operadoraSaude);
        }

        return $operadorasSaude;
    }

    /**
     * @param $id
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($id, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh221_sequencial {$operator} {$id}";
        return $this;
    }

    /**
     * @param CgmJuridico $cgm
     * @param string $operator
     * @return $this
     */
    public function scopeCgm(CgmJuridico $cgm, $operator = '=')
    {
        $this->scopes['cgm'] = "rh221_cgm {$operator} {$cgm->getCodigo()}";
        return $this;
    }

    /**
     * @param $ans
     * @param string $operator
     * @return $this
     */
    public function scopeAns($ans, $operator = '=')
    {
        $this->scopes['ans'] = "rh221_ans {$operator} {$ans}";
        return $this;
    }

    /**
     * @return $this
     */
    public function scopeAtivas()
    {
        $this->scopes['ativas'] = "rh221_ativo IS TRUE";
        return $this;
    }

    /**
     * @return $this
     */
    public function scopeInativas()
    {
        $this->scopes['inativas'] = "rh221_ativo IS FALSE";
        return $this;
    }

    /**
     * @param $key
     * @return OperadoraSaudeRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

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
     * @return OperadoraSaude[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_operadorasaude();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as operadoras de saúde.\nContate o suporte.");
        }

        $operadorasSaude = array();

        if (pg_num_rows($rs) === 0) {
            return $operadorasSaude;
        }

        while ($operadoraSaude = pg_fetch_array($rs)) {
            $operadorasSaude[] = OperadoraSaude::fromState($operadoraSaude);
        }

        return $operadorasSaude;
    }

    /**
     * @param OperadoraSaude $operadoraSaude
     * @return OperadoraSaude
     * @throws Exception
     */
    public function save(OperadoraSaude $operadoraSaude)
    {
        $dao = new cl_operadorasaude();
        $dao->rh221_sequencial = $operadoraSaude->getSequencial();
        $dao->rh221_cgm = $operadoraSaude->getCgm()->getCodigo();
        $dao->rh221_ans = $operadoraSaude->getAns();
        $dao->rh221_ativo = $operadoraSaude->isAtivo() ? 'true' : 'false';

        $operadoraSaude->getSequencial() ? $dao->alterar($operadoraSaude->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte.");
        }

        $operadoraSaude->setSequencial($dao->rh221_sequencial);

        return $operadoraSaude;
    }
}
