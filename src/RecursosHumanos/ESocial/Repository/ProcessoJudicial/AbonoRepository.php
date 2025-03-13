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
use cl_rhpessoalprocessoabono;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Abono;
use DBDate;

class AbonoRepository
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
        $this->scopes['sequencial'] = "rh302_sequencial {$operator} {$sequencial}";
        return $this;
    }
 
     /**
     * @param int $sequencialContrato
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialContrato($sequencialContrato, $operator = '=')
    {
        $this->scopes['sequencialContrato'] = "rh302_sequencialprocessocontrato {$operator} {$sequencialContrato}";
        return $this;
    }

         /**
     * @param int $anoAbono
     * @param string $operator
     * @return $this
     */
    public function scopeAnoAbono($anoAbono, $operator = '=')
    {
        $this->scopes['anoAbono'] = "rh302_anobase {$operator} '{$anoAbono}'";
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
     * @param Abono|null $abono
     * @throws BusinessException
     */
    public function delete(Abono $abono = null)
    {
        $id = $abono instanceof Abono ? $abono->getSequencial() : null;

        $dao = new cl_rhpessoalprocessoabono;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir o período e valores do processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Abono
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoabono;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar o ano de abono.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Abono::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Abono[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoabono;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $abono = [];

        if (pg_num_rows($rs) === 0) {
            return $abono;
        }

        while ($abonoItem = pg_fetch_array($rs)) {
            $abono[] = Abono::fromState($abonoItem);
        }
        
        return $abono;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Abono[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessoabono;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $abono = [];

        if (pg_num_rows($rs) === 0) {
            return $abono;
        }

        while ($abonoItem = pg_fetch_array($rs)) {
            $abono[] = Abono::fromState($abonoItem);
        }
        
        return $abono;
    }


    /**
     * @return Abono[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessoabono;
        $campos = [
            'rh302_sequencial',
            'rh302_sequencialprocessocontrato',
            'rh302_anobase'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar o ano do abono.");
        }

        $abono = [];

        if (pg_num_rows($rs) === 0) {
            return $abono;
        }

        while ($abonoPessoalProcesso = pg_fetch_array($rs)) {
            $abono[] = Abono::fromState($abonoPessoalProcesso);
        }

        return $abono;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessoabono;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar os abonos do processo judicial.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param PeriodoProcessual $abono
     * @return PeriodoProcessual
     * @throws BusinessException
     */
    public function save(Abono $abono)
    {
        $dao = new cl_rhpessoalprocessoabono;
        $dao->rh302_sequencial = $abono->getSequencial();
        $dao->rh302_sequencialprocessocontrato = $abono->getSequencialProcessoContrato();
        $dao->rh302_anobase = $abono->getAnoAbono();

        $dao->rh302_sequencial ? $dao->alterar($abono->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro relacionado ao novo ano de abono."
                . $dao->erro_msg);
        }

        $abono->setSequencial($dao->rh302_sequencial);

        return $abono;
    }
}
