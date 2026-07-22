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

use cl_rhpessoalprocessodesligamento;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Desligamento;
use Exception;
use DBDate;

class DesligamentoRepository
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
        $this->scopes['sequencial'] = "rh279_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param int $sequencialVinculo
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialVinculo($sequencialVinculo, $operator = '=')
    {
        $this->scopes['sequencialVinculo'] = "rh279_sequencialprocessovinculo {$operator} {$sequencialVinculo}";
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
     * @param Desligamento|null $contrato
     * @throws Exception
     */
    public function delete(Desligamento $desligamento = null)
    {
        $id = $desligamento instanceof Desligamento ? $desligamento->getSequencial() : null;

        $dao = new cl_rhpessoalprocessodesligamento;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o vinculo de desligamento.");
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
        $dao = new cl_rhpessoalprocessodesligamento;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as informações do vinculo de desligamento.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Desligamento::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Desligamento[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessodesligamento;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $desligamento = [];

        if (pg_num_rows($rs) === 0) {
            return $desligamento;
        }

        while ($contratoItem = pg_fetch_array($rs)) {
            $desligamento[] = Desligamento::fromState($contratoItem);
        }
        
        return $desligamento;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessodesligamento;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $desligamento = [];

        if (pg_num_rows($rs) === 0) {
            return $desligamento;
        }

        while ($desligamentoItem = pg_fetch_array($rs)) {
            $desligamento[] = Desligamento::fromState($desligamentoItem);
        }
        
        return $desligamento;
    }


    /**
     * @return Desligamento[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessodesligamento;
        $campos = [
            'rh279_sequencial',
            'rh279_sequencialprocessovinculo',
            'rh279_dtdeslig',
            'rh279_mtvdeslig',
            'rh279_dtprojfimapi',
            'rh279_pensalim',
            'rh279_percaliment',
            'rh279_vlralim'
        ];
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar desligamento processual");
        }

        $desligamento = [];

        if (pg_num_rows($rs) === 0) {
            return $desligamento;
        }

        while ($desligamentos = pg_fetch_array($rs)) {
            $desligamento[] = Desligamento::fromState($desligamentos);
        }

        return $desligamento;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessodesligamento;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o desligamento do processo judiciail.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param Desligamento $contrato
     * @return Desligamento
     * @throws Exception
     */
    public function save(Desligamento $desligamento)
    {
        $dao = new cl_rhpessoalprocessodesligamento;
        $dao->rh279_sequencial = $desligamento->getSequencial();
        $dao->rh279_sequencialprocessovinculo = $desligamento->getSequencialProcessoVinculo();
        $dao->rh279_dtdeslig = $desligamento->getDataDesligamento();
        $dao->rh279_mtvdeslig = $desligamento->getMotivoDesligamento();
        $dao->rh279_dtprojfimapi = $desligamento->getDataFimAvisoPrevioIdenizado();
        $dao->rh279_pensalim = $desligamento->getPensaoAlimenticia();
        $dao->rh279_percaliment = $desligamento->getPercentualPensaoAlimenticia();
        $dao->rh279_vlralim = $desligamento->getValorPensao();

        $dao->rh279_sequencial ? $dao->alterar($desligamento->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o delsigamento do processo." . $dao->erro_msg);
        }

        $desligamento->setSequencial($dao->rh279_sequencial);

        return $desligamento;
    }
}
