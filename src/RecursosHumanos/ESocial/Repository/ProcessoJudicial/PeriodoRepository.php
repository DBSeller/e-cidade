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

use cl_rhpessoalprocessoperiodo;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Periodo as PeriodoProcessual;
use Exception;
use DBDate;

class PeriodoRepository
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
        $this->scopes['sequencial'] = "rh282_sequencial {$operator} {$sequencial}";
        return $this;
    }
 
     /**
     * @param int $sequencialContrato
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialContrato($sequencialContrato, $operator = '=')
    {
        $this->scopes['sequencialContrato'] = "rh282_sequencialprocessocontrato {$operator} {$sequencialContrato}";
        return $this;
    }

    /**
     * @param string $periodoReferencia
     * @param string $operator
     * @return $this
     */
    public function scopePeriodoReferencia($periodoReferencia, $operator = '=')
    {
        $this->scopes['periodoReferencia'] = "rh282_perref {$operator} '{$periodoReferencia}'";
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
     * @param PeriodoProcessual|null $periodo
     * @throws Exception
     */
    public function delete(PeriodoProcessual $periodo = null)
    {
        $id = $periodo instanceof PeriodoProcessual ? $periodo->getSequencial() : null;

        $dao = new cl_rhpessoalprocessoperiodo;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o período e valores do processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|PeriodoProcessual
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoperiodo;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return PeriodoProcessual::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return PeriodoProcessual[]
     * @throws Exception
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoperiodo;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $periodo = [];

        if (pg_num_rows($rs) === 0) {
            return $periodo;
        }

        while ($periodoItem = pg_fetch_array($rs)) {
            $periodo[] = PeriodoProcessual::fromState($periodoItem);
        }
        
        return $periodo;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessoperiodo;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $periodo = [];

        if (pg_num_rows($rs) === 0) {
            return $periodo;
        }

        while ($periodoItem = pg_fetch_array($rs)) {
            $periodo[] = PeriodoProcessual::fromState($periodoItem);
        }
        
        return $periodo;
    }


    /**
     * @return PeriodoProcessual[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessoperiodo;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período do processo.");
        }

        $periodo = [];

        if (pg_num_rows($rs) === 0) {
            return $periodo;
        }

        while ($periodoPessoalProcesso = pg_fetch_array($rs)) {
            $periodo[] = PeriodoProcessual::fromState($periodoPessoalProcesso);
        }

        return $periodo;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessoperiodo;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os período de processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param PeriodoProcessual $periodo
     * @return PeriodoProcessual
     * @throws Exception
     */
    public function save(PeriodoProcessual $periodo)
    {
        $dao = new cl_rhpessoalprocessoperiodo;
        $dao->rh282_sequencial = $periodo->getSequencial();
        $dao->rh282_sequencialprocessocontrato = $periodo->getSequencialProcessoContrato();
        $dao->rh282_perref = $periodo->getPeriodo();
        $dao->rh282_vrbccpmensal = $periodo->getValorBasePrevidenciaMensal();
        $dao->rh282_vrbccp13 = $periodo->getValorBasePrevidenciaMensal13();
        $dao->rh282_grauexp = $periodo->getGrauExposicao();
        $dao->rh282_codcateg  = $periodo->getCodigoCategoria();
        $dao->rh282_vrbccprev = $periodo->getValorFinsPrevidenciarios();
        $dao->rh282_vrbcfgtsproctrab = $periodo->getValorBaseFGTSProcesso();
        $dao->rh282_vrbcfgtssefip = $periodo->getValorBaseFGTSSefip();
        $dao->rh282_vrbcfgtsdecant = $periodo->getValorBaseFGTSDeclaradaAnteriormente();

        $dao->rh282_sequencial ? $dao->alterar($periodo->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar registro relacionado ao novo período no processo."
                . $dao->erro_msg);
        }

        $periodo->setSequencial($dao->rh282_sequencial);

        return $periodo;
    }

    /**
     * @return PeriodoProcessual[]
     * @throws Exception
     */
    public function getPeriodo()
    {
        $dao = new cl_rhpessoalprocessoperiodo;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        $periodo = [];

        if (pg_num_rows($rs)) {
            while ($periodoPessoalProcesso = pg_fetch_array($rs)) {
                $periodo[] = PeriodoProcessual::fromState($periodoPessoalProcesso);
            }
        }

        return $periodo;
    }
}
