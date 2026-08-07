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

namespace ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial;

use BusinessException;
use cl_rhprocessosuspensapensao;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\SuspensaPensao;

class SuspensaoPensaoRepository
{
    /**
     * @var array
     */
    private $scopes = [];

    /**
     * @param int $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=')
    {
        $this->scopes['sequencial'] = "rh309_sequencial {$operator} {$sequencial}";
        return $this;
    }

     /**
     * @param int $sequencialDeducaoSuspensa
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialDeducaoSuspensa($sequencialDeducaoSuspensa, $operator = '=')
    {
        $this->scopes['sequencialDeducaoSuspensa'] =
            "rh309_sequencialreducaosuspensa {$operator} {$sequencialDeducaoSuspensa}";
        return $this;
    }

     /**
     * @param string $cpfPensao
     * @param string $operator
     * @return $this
     */
    public function scopeCPFPensao($cpfPensao, $operator = '=')
    {
        $this->scopes['cpfPensao'] =
            "rh309_cpfdep {$operator} '{$cpfPensao}'";
        return $this;
    }

    /**
     * @return $this
     */
    public function resetScopes()
    {
        $this->scopes = [];

        return $this;
    }

    /**
     * @param array|int $ids
     * @return int
     * @throws BusinessException
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
     * @param SuspensaPensao|null $suspensaPensao
     * @throws BusinessException
     */
    public function delete(SuspensaPensao $suspensaPensao = null)
    {
        $id = $suspensaPensao instanceof SuspensaPensao ? $suspensaPensao->getSequencial() : null;

        $dao = new cl_rhprocessosuspensapensao;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir o período e valores do processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|SuspensaPensao
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessosuspensapensao;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar o ano de abono.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return SuspensaPensao::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return SuspensaPensao[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhprocessosuspensapensao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $suspensaPensao = [];

        if (pg_num_rows($rs) === 0) {
            return $suspensaPensao;
        }

        while ($suspensaPensaoItem = pg_fetch_array($rs)) {
            $suspensaPensao[] = SuspensaPensao::fromState($suspensaPensaoItem);
        }
        
        return $suspensaPensao;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return SuspensaPensao[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessosuspensapensao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $suspensaPensao = [];

        if (pg_num_rows($rs) === 0) {
            return $suspensaPensao;
        }

        while ($suspensaPensaoItem = pg_fetch_array($rs)) {
            $suspensaPensao[] = SuspensaPensao::fromState($suspensaPensaoItem);
        }
        
        return $suspensaPensao;
    }

    /**
     * @return SuspensaPensao[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhprocessosuspensapensao;
        $campos = [
            'rh309_sequencial',
            'rh309_sequencialreducaosuspensa',
            'rh309_cpfdep',
            'rh309_vlrdepensusp'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar deduções suspensas por dependentes e " .
                "beneficiários da pensão alimentícia.");
        }

        $suspensaPensao = [];

        if (pg_num_rows($rs) === 0) {
            return $suspensaPensao;
        }

        while ($suspensaPensaoProcesso = pg_fetch_array($rs)) {
            $suspensaPensao[] = SuspensaPensao::fromState($suspensaPensaoProcesso);
        }

        return $suspensaPensao;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhprocessosuspensapensao;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar  deduções suspensas por dependentes e " .
                "beneficiários da pensão alimentícia.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param SuspensaPensao $abono
     * @return SuspensaPensao
     * @throws BusinessException
     */
    public function save(SuspensaPensao $suspensaPensao)
    {
        $dao = new cl_rhprocessosuspensapensao;
        $dao->rh309_sequencial = $suspensaPensao->getSequencial();
        $dao->rh309_sequencialreducaosuspensa = $suspensaPensao->getSequencialDeducaoSuspensa();
        $dao->rh309_cpfdep = $suspensaPensao->getCpfDependente();
        $dao->rh309_vlrdepensusp = $suspensaPensao->getValorDeducao();

        $dao->rh309_sequencial ? $dao->alterar($suspensaPensao->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro  deduções suspensas por dependentes e " .
            "beneficiários da pensão alimentícia.");
        }

        $suspensaPensao->setSequencial($dao->rh309_sequencial);

        return $suspensaPensao;
    }
}
