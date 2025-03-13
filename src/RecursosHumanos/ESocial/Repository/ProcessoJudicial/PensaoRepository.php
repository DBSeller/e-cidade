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
use cl_rhprocessopensao;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Pensao;

class PensaoRepository
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
        $this->scopes['sequencial'] = "rh305_sequencial {$operator} {$sequencial}";
        return $this;
    }
 
     /**
     * @param int $sequencialTributoIRRF
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialTributoIRRF($sequencialTributoIRRF, $operator = '=')
    {
        $this->scopes['valorRetencao'] =
            "rh305_sequencialtributoirrf {$operator} {$sequencialTributoIRRF}";
        return $this;
    }

     
     /**
     * @param int $tipoRendimento
     * @param string $operator
     * @return $this
     */
    public function scopeTipoRendimento($tipoRendimento, $operator = '=')
    {
        $this->scopes['tipoRendimento'] =
            "rh305_tprend {$operator} {$tipoRendimento}";
        return $this;
    }

     /**
     * @param string $cpf
     * @param string $operator
     * @return $this
     */
    public function scopeCPF($cpf, $operator = '=')
    {
        $this->scopes['tipoRendimento'] =
            "rh305_cpfdep {$operator} '{$cpf}'";
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
     * @param  Pensao|null $pensao
     * @throws BusinessException
     */
    public function delete(Pensao $pensao = null)
    {
        $id = $pensao instanceof Pensao ? $pensao->getSequencial() : null;

        $dao = new cl_rhprocessopensao;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir pensão.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool| Pensao
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessopensao;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar pensão ");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return  Pensao::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return  Pensao[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhprocessopensao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $retencao = [];

        if (pg_num_rows($rs) === 0) {
            return $retencao;
        }

        while ($retencaoItem = pg_fetch_array($rs)) {
            $retencao[] =  Pensao::fromState($retencaoItem);
        }
        
        return $retencao;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Pensao[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessopensao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $retencao = [];

        if (pg_num_rows($rs) === 0) {
            return $retencao;
        }

        while ($retencaoItem = pg_fetch_array($rs)) {
            $retencao[] = Pensao::fromState($retencaoItem);
        }
        
        return $retencao;
    }

    /**
     * @return  Pensao[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhprocessopensao;
        $campos = [
            'rh305_sequencial',
            'rh305_sequencialtributoirrf',
            'rh305_tprend',
            'rh305_cpfdep',
            'rh305_vlrpensao'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar pensão.");
        }

        $retencao = [];

        if (pg_num_rows($rs) === 0) {
            return $retencao;
        }

        while ($retencaoProcesso = pg_fetch_array($rs)) {
            $retencao[] =  Pensao::fromState($retencaoProcesso);
        }

        return $retencao;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhprocessopensao;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar pensão.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param  Pensao $pensao
     * @return  Pensao
     * @throws BusinessException
     */
    public function save(Pensao $pensao)
    {
        $dao = new cl_rhprocessopensao;
        $dao->rh305_sequencial = $pensao->getSequencial();
        $dao->rh305_sequencialtributoirrf = $pensao->getSequencialTributoIRRF();
        $dao->rh305_tprend = $pensao->getTipoRendimento();
        $dao->rh305_cpfdep = $pensao->getCpfPensao();
        $dao->rh305_vlrpensao = $pensao->getValorPensao();

        $dao->rh305_sequencial ? $dao->alterar($pensao->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro de pensão.");
        }

        $pensao->setSequencial($dao->rh305_sequencial);

        return $pensao;
    }
}
