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
use cl_rhprocessovalorretencao;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\ValorRetencao;

class ValorRetencaoRepository
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
        $this->scopes['sequencial'] = "rh307_sequencial {$operator} {$sequencial}";
        return $this;
    }

     /**
     * @param int $sequencialRetencao
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialRetencao($sequencialRetencao, $operator = '=')
    {
        $this->scopes['sequencialRetencao'] =
            "rh307_sequencialretencao {$operator} {$sequencialRetencao}";
        return $this;
    }

    /**
     * @param int $indicativoTipoApuracao
     * @param string $operator
     * @return $this
     */
    public function scopeTipoApuracao($indicativoTipoApuracao, $operator = '=')
    {
        $this->scopes['indicativoTipoApuracao'] =
            "rh307_indapuracao {$operator} {$indicativoTipoApuracao}";
        return $this;
    }

    /**
     * @param string $numeroProcesso
     * @param int $tipoProcesso
     * @param int $apuracao
     * @return $this
     */
    public function scopeProcessoTipoApuracao($numeroProcesso = '', $tipoProcesso = 0, $apuracao = 0)
    {
        $this->scopes['processoTipo'] =
            "rh306_nrprocret  = {$numeroProcesso} and " .
            "rh306_tpprocret = {$tipoProcesso} and " .
            "rh307_indapuracao = $apuracao";
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
     * @param ValorRetencao|null $valorRetencao
     * @throws BusinessException
     */
    public function delete(ValorRetencao $valorRetencao = null)
    {
        $id = $valorRetencao instanceof ValorRetencao ? $valorRetencao->getSequencial() : null;

        $dao = new cl_rhprocessovalorretencao;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir valores relacionados a não retenção de tributos.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ValorRetencao
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessovalorretencao;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar valores relacionados a não retenção de tributos.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ValorRetencao::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Abono[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhprocessovalorretencao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $suspensaPensao = [];

        if (pg_num_rows($rs) === 0) {
            return $suspensaPensao;
        }

        while ($suspensaPensaoItem = pg_fetch_array($rs)) {
            $suspensaPensao[] = ValorRetencao::fromState($suspensaPensaoItem);
        }
        
        return $suspensaPensao;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ValorRetencao[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessovalorretencao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $suspensaPensao = [];

        if (pg_num_rows($rs) === 0) {
            return $suspensaPensao;
        }

        while ($suspensaPensaoItem = pg_fetch_array($rs)) {
            $suspensaPensao[] = ValorRetencao::fromState($suspensaPensaoItem);
        }
        
        return $suspensaPensao;
    }

    /**
     * @return ValorRetencao[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhprocessovalorretencao;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar valores relacionados a não retenção de tributos.");
        }

        $suspensaPensao = [];

        if (pg_num_rows($rs) === 0) {
            return $suspensaPensao;
        }

        while ($suspensaPensaoProcesso = pg_fetch_array($rs)) {
            $suspensaPensao[] = ValorRetencao::fromState($suspensaPensaoProcesso);
        }

        return $suspensaPensao;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhprocessovalorretencao;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar valores relacionados a não retenção de tributos.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param ValorRetencao $valorRetencao
     * @return ValorRetencao
     * @throws BusinessException
     */
    public function save(ValorRetencao $valorRetencao)
    {
        $dao = new cl_rhprocessovalorretencao;
        $dao->rh307_sequencial = $valorRetencao->getSequencial();
        $dao->rh307_sequencialretencao = $valorRetencao->getSequencialRetencao();
        $dao->rh307_indapuracao = $valorRetencao->getIndicativoApuracao();
        $dao->rh307_vlrnretido = $valorRetencao->getValorRetencao();
        $dao->rh307_vlrdepjud = $valorRetencao->getValorDepositoJudicial();
        $dao->rh307_vlrcmpanocal = $valorRetencao->getValorCompensacaoAno();
        $dao->rh307_vlrcmpanoant = $valorRetencao->getValorCompensacaoAnoAnterior();
        $dao->rh307_vlrrendsusp = $valorRetencao->getValorRendimentoSuspenso();

        $dao->rh307_sequencial ? $dao->alterar($valorRetencao->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro valores relacionados " .
                "a não retenção de tributos.");
        }

        $valorRetencao->setSequencial($dao->rh307_sequencial);

        return $valorRetencao;
    }
}
