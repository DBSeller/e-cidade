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

use cl_rhpessoalprocessomudanca;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Mudanca as MudancaProcessual;
use Exception;
use DBDate;

class MudancaRepository
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
        $this->scopes['sequencial'] = "rh280_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $sequencialContrato
     * @return $this
     */
    public function scopeSequencialContrato($sequencialContrato, $operator = '=')
    {
        $this->scopes['contrato'] = "
            rh280_sequencialprocessocontrato  {$operator} {$sequencialContrato}
        ";

        return $this;
    }

    /**
     * @param $codigoCategoria
     * @return $this
     */
    public function scopeCodigoCategoria($codigoCategoria, $operator = '=')
    {
        $this->scopes['codigoCategoria'] = "
            rh280_codcateg  {$operator} {$codigoCategoria}
        ";

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
     * @param MudancaProcessual|null $mudanca
     * @throws Exception
     */
    public function delete(MudancaProcessual $mudanca = null)
    {
        $id = $mudanca instanceof MudancaProcessual ? $mudanca->getSequencial() : null;

        $dao = new cl_rhpessoalprocessomudanca;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a mudança do processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|MudancaProcessual
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessomudanca;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a mudança no processo judiciail.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return MudancaProcessual::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ContratoProcessual[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessomudanca;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $mudanca = [];

        if (pg_num_rows($rs) === 0) {
            return $mudanca;
        }

        while ($mudancaItem = pg_fetch_array($rs)) {
            $mudanca[] = MudancaProcessual::fromState($mudancaItem);
        }
        
        return $mudanca;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessomudanca;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $mudanca = [];

        if (pg_num_rows($rs) === 0) {
            return $mudanca;
        }

        while ($mudancaItem = pg_fetch_array($rs)) {
            $mudanca[] = MudancaProcessual::fromState($mudancaItem);
        }
        
        return $mudanca;
    }


    /**
     * @return MudancaProcessual[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessomudanca;
        $campos =  [
            'rh280_sequencial',
            'rh280_sequencialprocessocontrato',
            'rh280_codcateg',
            'rh280_natividade',
            'rh280_dtmudcategativ'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a mudança processual.");
        }

        $mudanca = [];

        if (pg_num_rows($rs) === 0) {
            return $mudanca;
        }

        while ($mudancaPessoalProcesso = pg_fetch_array($rs)) {
            $mudanca[] = MudancaProcessual::fromState($mudancaPessoalProcesso);
        }

        return $mudanca;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessomudanca;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param MudancaProcessual $mudanca
     * @return MudancaProcessual
     * @throws Exception
     */
    public function save(MudancaProcessual $mudanca)
    {
        $dao = new cl_rhpessoalprocessomudanca;
        $dao->rh280_sequencial = $mudanca->getSequencial();
        $dao->rh280_sequencialprocessocontrato = $mudanca->getSequencialProcessoContrato();
        $dao->rh280_codcateg = $mudanca->getCodigoCategoria();
        $dao->rh280_natividade = $mudanca->getNaturezaAtividade();
        $dao->rh280_dtmudcategativ = $mudanca->getDataMudancaCategoria();


        $dao->rh280_sequencial ? $dao->alterar($mudanca->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar registro relacionado ao novo codigo de categoria."
                . $dao->erro_msg);
        }

        $mudanca->setSequencial($dao->rh280_sequencial);

        return $mudanca;
    }

    /**
     * @return MudancaProcessual | null
     */
    public function getMudanca()
    {
        $dao = new cl_rhpessoalprocessomudanca;
        $campos =  [
            'rh280_sequencial',
            'rh280_sequencialprocessocontrato',
            'rh280_codcateg',
            'rh280_natividade',
            'rh280_dtmudcategativ'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        $mudanca = null;

        if (pg_num_rows($rs)) {
            $mudancaPessoalProcesso = pg_fetch_array($rs);
            $mudanca = MudancaProcessual::fromState($mudancaPessoalProcesso);
        }

        return $mudanca;
    }
}
