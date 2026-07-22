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
use cl_rhprocessoadvogado;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Advogado;

class AdvogadoRepository
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
        $this->scopes['sequencial'] = "rh303_sequencial {$operator} {$sequencial}";
        return $this;
    }
 
     /**
     * @param int $sequencialTributoIRRF
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialTributoIRRF($sequencialTributoIRRF, $operator = '=')
    {
        $this->scopes['tributoirrf'] =
            "rh303_sequencialtributoirrf {$operator} {$sequencialTributoIRRF}";
        return $this;
    }

    /**
     * @param int $tipoInscricao
     * @param string $operator
     * @return $this
     */
    public function scopeTipoInscricao($tipoInscricao, $operator = '=')
    {
        $this->scopes['tipoInscricao'] =
            "rh303_tpinsc {$operator} {$tipoInscricao}";
        return $this;
    }

    /**
     * @param string $numeroInscricao
     * @param string $operator
     * @return $this
     */
    public function scopeNumeroInscricao($numeroInscricao, $operator = '=')
    {
        $this->scopes['numeroInscricao'] =
            "rh303_nrinsc {$operator} '{$numeroInscricao}'";
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
     * @param  Advogado|null $advogado
     * @throws BusinessException
     */
    public function delete(Advogado $advogado = null)
    {
        $id = $advogado instanceof Advogado ? $advogado->getSequencial() : null;

        $dao = new cl_rhprocessoadvogado;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir identificação dos advogados.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool| Advogado
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessoadvogado;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar identificação dos advogados.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return  Advogado::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return  Advogado[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhprocessoadvogado;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $advogado = [];

        if (pg_num_rows($rs) === 0) {
            return $advogado;
        }

        while ($advogadoItem = pg_fetch_array($rs)) {
            $advogado[] =  Advogado::fromState($advogadoItem);
        }
        
        return $advogado;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Advogado[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessoadvogado;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $advogado = [];

        if (pg_num_rows($rs) === 0) {
            return $advogado;
        }

        while ($advogadoItem = pg_fetch_array($rs)) {
            $advogado[] = Advogado::fromState($advogadoItem);
        }
        
        return $advogado;
    }

    /**
     * @return  Advogado[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhprocessoadvogado;
        $campos = [
            'rh303_sequencial',
            'rh303_sequencialtributoirrf',
            'rh303_tpInsc',
            'rh303_nrInsc',
            'rh303_vlradv'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar identificação dos advogados.");
        }

        $advogado = [];

        if (pg_num_rows($rs) === 0) {
            return $advogado;
        }

        while ($advogadoProcesso = pg_fetch_array($rs)) {
            $advogado[] =  Advogado::fromState($advogadoProcesso);
        }

        return $advogado;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhprocessoadvogado;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar identificação dos advogados.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param  Advogado $advogado
     * @return  Advogado
     * @throws BusinessException
     */
    public function save(Advogado $advogado)
    {
        $dao = new cl_rhprocessoadvogado;
        $dao->rh303_sequencial = $advogado->getSequencial();
        $dao->rh303_sequencialtributoirrf = $advogado->getSequencialTributoIRRF();
        $dao->rh303_tpInsc = $advogado->getTipoInscricao();
        $dao->rh303_nrInsc = $advogado->getNumeroInscricao();
        $dao->rh303_vlradv = $advogado->getValorDespesa();

        $dao->rh303_sequencial ? $dao->alterar($advogado->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro identificação dos advogados.");
        }

        $advogado->setSequencial($dao->rh303_sequencial);

        return $advogado;
    }
}
