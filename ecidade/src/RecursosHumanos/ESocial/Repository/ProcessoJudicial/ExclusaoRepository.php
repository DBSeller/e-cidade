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

use cl_rhprocessoexclusao;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Exclusao as ExclusaoProcessual;
use Exception;
use DBDate;

class ExclusaoRepository
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
        $this->scopes['sequencial'] = "rh300_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param int $sequencialServidor
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialServidor($sequencialServidor, $operator = '=')
    {
        $this->scopes['sequencialServidor'] = "rh300_sequencialprocessoservidor {$operator} {$sequencialServidor}";
        return $this;
    }

    /**
     * @param string $recibo
     * @param string $operator
     * @return $this
     */
    public function scopeRecibo($recibo, $operator = '=')
    {
        $this->scopes['recibo'] = "rh300_nrrecevt {$operator} '{$recibo}'";
        return $this;
    }

    /**
     * @param string $evento
     * @param string $operator
     * @return $this
     */
    public function scopeEvento($evento, $operator = '=')
    {
        $this->scopes['evento'] = "rh300_tpevento {$operator} '{$evento}'";
        return $this;
    }

    /**
     * @param string $data
     * @param string $operator
     * @return $this
     */
    public function scopeDataExclusao($dataInicio, $operadorInicio = '=', $dataFim = null, $operadorFim = '=')
    {
        if (!empty($dataInicio) && !empty($dataFim)) {
            $this->scopes['data'] = "rh300_dataexclusao {$operadorInicio} '{$dataInicio}' and " .
                "rh300_dataexclusao {$operadorFim} '{$dataFim}'";
        }
        if (empty($dataFim)) {
             $this->scopes['data'] = "rh300_dataexclusao {$operadorInicio} '{$dataInicio}'";
        }
       
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
     * @param ContratoProcessual|null $contrato
     * @throws Exception
     */
    public function delete(ExclusaoProcessual $contrato = null)
    {
        $id = $contrato instanceof ExclusaoProcessual ? $contrato->getSequencial() : null;

        $dao = new cl_rhprocessoexclusao;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o contrato processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ProcessoJudicial
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessoexclusao;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as informações do contrato processual.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ExclusaoProcessual::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ExclusaoProcessual[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhprocessoexclusao;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contratoItem = pg_fetch_array($rs)) {
            $contrato[] = ExclusaoProcessual::fromState($contratoItem);
        }
        
        return $contrato;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessoexclusao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contratoItem = pg_fetch_array($rs)) {
            $contrato[] = ExclusaoProcessual::fromState($contratoItem);
        }
        
        return $contrato;
    }


    /**
     * @return ExclusaoProcessual[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhprocessoexclusao;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o processo.");
        }

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contribuicaoSindicalPatronal = pg_fetch_array($rs)) {
            $contrato[] = ExclusaoProcessual::fromState($contribuicaoSindicalPatronal);
        }

        return $contrato;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhprocessoexclusao;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param ExclusaoProcessual $exclusao
     * @return ExclusaoProcessual
     * @throws Exception
     */
    public function save(ExclusaoProcessual $exclusao)
    {
        $dao = new cl_rhprocessoexclusao;
        $dao->rh300_sequencial = $exclusao->getSequencial();
        $dao->rh300_sequencialprocessoservidor = $exclusao->getSequencialProcessoServidor();
        $dao->rh300_tpevento = $exclusao->getTipoEvento();
        $dao->rh300_nrrecevt = $exclusao->getRecibo();
        $dao->rh300_nrproctrab = $exclusao->getNumeroProcesso();
        $dao->rh300_cpftrab = $exclusao->getCpf();
        $dao->rh300_perapurpgto = $exclusao->getPeriodoPagamento();
        $dao->rh300_dataexclusao = $exclusao->getDataExclusao();
        $dao->rh300_referencia = $exclusao->getReferencia();

        $dao->rh300_sequencial ? $dao->alterar($exclusao->getSequencial()) : $dao->incluir(null);



        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o processo." . $dao->erro_msg);
        }

        $exclusao->setSequencial($dao->rh300_sequencial);

        return $exclusao;
    }

    /**
     * @param $sequencialProcessoServidor
     * @param array $columns
     * @return bool|ExclusaoProcessual
     * @throws Exception
     */
    public static function getListaContratosProcesso($sequencialProcessoServidor, $columns = array('*'))
    {
        $dao = new cl_rhprocessoexclusao;
        $where = " rh300_sequencialprocessoservidor = {$sequencialProcessoServidor}";
        $sql = $dao->sql_query(null, implode(', ', $columns), null, $where);

        $rs = db_query($sql);

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        if (pg_num_rows($rs) > 1) {
            return $contrato;
        }

        if (pg_num_rows($rs) === 1) {
            return ExclusaoProcessual::fromState(pg_fetch_array($rs));
        }

        return $contrato;
    }

    /**
     * @return ContratoProcessual | null
     */
    public function getContrato()
    {
        $dao = new cl_rhprocessoexclusao;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        $contrato = null;

        if (pg_num_rows($rs)) {
            $contribuicaoSindicalPatronal = pg_fetch_array($rs);
            $contrato = ExclusaoProcessual::fromState($contribuicaoSindicalPatronal);
        }

        return $contrato;
    }
}
