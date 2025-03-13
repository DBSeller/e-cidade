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
use cl_rhpessoalprocessoremuneracao;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Remuneracao;
use DBDate;

class RemuneracaoRepository
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
        $this->scopes['sequencial'] = "rh272_sequencial {$operator} {$sequencial}";
        return $this;
    }
 
     /**
     * @param int $sequencialContrato
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialVinculo($sequencialContrato, $operator = '=')
    {
        $this->scopes['sequencialContrato'] = "rh272_sequencialprocessocontrato {$operator} {$sequencialContrato}";
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
     * @param Remuneracao|null $remuneracao
     * @throws BusinessException
     */
    public function delete(Remuneracao $remuneracao = null)
    {
        $id = $remuneracao instanceof Remuneracao ? $remuneracao->getSequencial() : null;

        $dao = new cl_rhpessoalprocessoremuneracao;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir a remuneração do processo.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Remuneracao
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoremuneracao;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar a remuneração do processo.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Remuneracao::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Remuneracao[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoremuneracao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $remuneracao = [];

        if (pg_num_rows($rs) === 0) {
            return $remuneracao;
        }

        while ($remuneracaoItem = pg_fetch_array($rs)) {
            $remuneracao[] = Remuneracao::fromState($remuneracaoItem);
        }
        
        return $remuneracao;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Remuneracao[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessoremuneracao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $remuneracao = [];

        if (pg_num_rows($rs) === 0) {
            return $remuneracao;
        }

        while ($remuneracaoItem = pg_fetch_array($rs)) {
            $remuneracao[] = Remuneracao::fromState($remuneracaoItem);
        }
        
        return $remuneracao;
    }


    /**
     * @return Remuneracao[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessoremuneracao;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar a remuneração do processo.");
        }

        $remuneracao = [];

        if (pg_num_rows($rs) === 0) {
            return $remuneracao;
        }

        while ($remuneracaoPessoalProcesso = pg_fetch_array($rs)) {
            $remuneracao[] = Remuneracao::fromState($remuneracaoPessoalProcesso);
        }

        return $remuneracao;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessoremuneracao;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar os processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param PeriodoProcessual $remuneracao
     * @return PeriodoProcessual
     * @throws BusinessException
     */
    public function save(Remuneracao $remuneracao)
    {
        $dao = new cl_rhpessoalprocessoremuneracao;
        $dao->rh272_sequencial = $remuneracao->getSequencial();
        $dao->rh272_sequencialprocessocontrato = $remuneracao->getSequencialProcessoVinculo();
        $dao->rh272_dtremun  = $remuneracao->getAnoRemuneracao();
        $dao->rh272_vrsalfx = $remuneracao->getAnoRemuneracao();
        $dao->rh272_undSalFixo = $remuneracao->getAnoRemuneracao();
        $dao->rh272_dscSalVar = $remuneracao->getAnoRemuneracao();

        $dao->rh272_sequencial ? $dao->alterar($remuneracao->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro relacionado a remuneração do processo."
                . $dao->erro_msg);
        }

        $remuneracao->setSequencial($dao->rh272_sequencial);

        return $remuneracao;
    }
}
