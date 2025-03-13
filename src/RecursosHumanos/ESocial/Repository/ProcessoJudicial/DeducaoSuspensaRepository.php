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
use cl_rhprocessoreducaosuspensa;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\DeducaoSuspensa;

class DeducaoSuspensaRepository
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
        $this->scopes['sequencial'] = "rh308_sequencial {$operator} {$sequencial}";
        return $this;
    }

     /**
     * @param int $sequencialValorRetencao
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialValorRetencao($sequencialValorRetencao, $operator = '=')
    {
        $this->scopes['valorRetencao'] =
            "rh308_sequencialvalorretencao {$operator} {$sequencialValorRetencao}";
        return $this;
    }

     /**
     * @param int $tipoDeducao
     * @param string $operator
     * @return $this
     */
    public function scopeTipoDeducao($tipoDeducao, $operator = '=')
    {
        $this->scopes['valorRetencao'] =
            "rh308_indtpdeducao {$operator} {$tipoDeducao}";
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
     * @param  DeducaoSuspensa|null $deducaoSuspensa
     * @throws BusinessException
     */
    public function delete(DeducaoSuspensa $deducaoSuspensa = null)
    {
        $id = $deducaoSuspensa instanceof DeducaoSuspensa ? $deducaoSuspensa->getSequencial() : null;

        $dao = new cl_rhprocessoreducaosuspensa;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir deduções com exigibilidade suspensa.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool| DeducaoSuspensa
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessoreducaosuspensa;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar deduções com exigibilidade suspensa.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return  DeducaoSuspensa::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return  DeducaoSuspensa[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhprocessoreducaosuspensa;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $deducaoSuspensa = [];

        if (pg_num_rows($rs) === 0) {
            return $deducaoSuspensa;
        }

        while ($deducaoSuspensaItem = pg_fetch_array($rs)) {
            $deducaoSuspensa[] =  DeducaoSuspensa::fromState($deducaoSuspensaItem);
        }
        
        return $deducaoSuspensa;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return DeducaoSuspensa[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessoreducaosuspensa;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $deducaoSuspensa = [];

        if (pg_num_rows($rs) === 0) {
            return $deducaoSuspensa;
        }

        while ($deducaoSuspensaItem = pg_fetch_array($rs)) {
            $deducaoSuspensa[] = DeducaoSuspensa::fromState($deducaoSuspensaItem);
        }
        
        return $deducaoSuspensa;
    }

    /**
     * @return  DeducaoSuspensa[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhprocessoreducaosuspensa;
        $campos = [
            'rh308_sequencial',
            'rh308_sequencialvalorretencao',
            'rh308_indtpdeducao',
            'rh308_vlrdedsusp'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar deduções com exigibilidade suspensa.");
        }

        $deducaoSuspensa = [];

        if (pg_num_rows($rs) === 0) {
            return $deducaoSuspensa;
        }

        while ($deducaoSuspensaProcesso = pg_fetch_array($rs)) {
            $deducaoSuspensa[] =  DeducaoSuspensa::fromState($deducaoSuspensaProcesso);
        }

        return $deducaoSuspensa;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhprocessoreducaosuspensa;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar deduções com exigibilidade suspensa.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param  DeducaoSuspensa $deducaoSuspensa
     * @return  DeducaoSuspensa
     * @throws BusinessException
     */
    public function save(DeducaoSuspensa $deducaoSuspensa)
    {
        $dao = new cl_rhprocessoreducaosuspensa;
        $dao->rh308_sequencial = $deducaoSuspensa->getSequencial();
        $dao->rh308_sequencialvalorretencao = $deducaoSuspensa->getSequencialValorRetencao();
        $dao->rh308_indtpdeducao = $deducaoSuspensa->getTipoDeducao();
        $dao->rh308_vlrdedsusp = $deducaoSuspensa->getValorDeducao();

        $dao->rh308_sequencial ? $dao->alterar($deducaoSuspensa->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro deduções com exigibilidade suspensa."
                . $dao->erro_msg);
        }

        $deducaoSuspensa->setSequencial($dao->rh308_sequencial);

        return $deducaoSuspensa;
    }
}
