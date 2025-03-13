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
use cl_rhprocessodependente;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Dependente;

class DependenteRepository
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
        $this->scopes['sequencial'] = "rh304_sequencial {$operator} {$sequencial}";
        return $this;
    }

     /**
     * @param int $sequencialTributoIRRF
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialTributoIRRF($sequencialTributoIRRF, $operator = '=')
    {
        $this->scopes['sequencialTributoIRRF'] =
            "rh304_sequencialtributoirrf {$operator} {$sequencialTributoIRRF}";
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
            "rh304_tprend {$operator} {$tipoRendimento}";
        return $this;
    }

     /**
     * @param string $cpfDependente
     * @param string $operator
     * @return $this
     */
    public function scopeCPFDependente($cpfDependente, $operator = '=')
    {
        $this->scopes['cpfDependente'] =
            "rh304_cpfdep {$operator} '{$cpfDependente}'";
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
     * @param  Dependente|null $dependente
     * @throws BusinessException
     */
    public function delete(Dependente $dependente = null)
    {
        $id = $dependente instanceof Dependente ? $dependente->getSequencial() : null;

        $dao = new cl_rhprocessodependente;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir dedução do rendimento tributável " .
                "relativa a dependentes.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool| Dependente
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessodependente;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar dedução do rendimento tributável " .
                "relativa a dependentes.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return  Dependente::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return  Dependente[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhprocessodependente;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $retencao = [];

        if (pg_num_rows($rs) === 0) {
            return $retencao;
        }

        while ($retencaoItem = pg_fetch_array($rs)) {
            $retencao[] =  Dependente::fromState($retencaoItem);
        }
        
        return $retencao;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Dependente[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessodependente;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $retencao = [];

        if (pg_num_rows($rs) === 0) {
            return $retencao;
        }

        while ($retencaoItem = pg_fetch_array($rs)) {
            $retencao[] = Dependente::fromState($retencaoItem);
        }
        
        return $retencao;
    }

    /**
     * @return  Dependente[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhprocessodependente;
        $campos = [
            'rh304_sequencial',
            'rh304_sequencialtributoirrf',
            'rh304_tprend',
            'rh304_cpfdep',
            'rh304_vlrdeducao'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar dedução do rendimento tributável " .
                "relativa a dependentes");
        }

        $retencao = [];

        if (pg_num_rows($rs) === 0) {
            return $retencao;
        }

        while ($retencaoProcesso = pg_fetch_array($rs)) {
            $retencao[] =  Dependente::fromState($retencaoProcesso);
        }

        return $retencao;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhprocessodependente;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar dedução do rendimento tributável " .
                "relativa a dependentes.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param  Dependente $dependente
     * @return  Dependente
     * @throws BusinessException
     */
    public function save(Dependente $dependente)
    {
        $dao = new cl_rhprocessodependente;
        $dao->rh304_sequencial = $dependente->getSequencial();
        $dao->rh304_sequencialtributoirrf = $dependente->getSequencialTributoIRRF();
        $dao->rh304_tprend = $dependente->getTipoRendimento();
        $dao->rh304_cpfdep = $dependente->getCpfDependente();
        $dao->rh304_vlrdeducao = $dependente->getValorDeducao();

        $dao->rh304_sequencial ? $dao->alterar($dependente->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro relacionado a dedução do rendimento " .
                "tributável relativa a dependentes.");
        }

        $dependente->setSequencial($dao->rh304_sequencial);

        return $dependente;
    }
}
