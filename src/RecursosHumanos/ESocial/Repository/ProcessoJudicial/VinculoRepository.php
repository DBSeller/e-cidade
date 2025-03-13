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

use cl_rhpessoalprocessovinculo;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Vinculo;
use Exception;
use DBDate;

class VinculoRepository
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
        $this->scopes['sequencial'] = "rh274_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $sequencialServidor
     * @param $operator
     * @return $this
     */
    public function scopeSequencialServidor($sequencialServidor, $operator = '=')
    {
        $this->scopes['contrato'] = "
            rh274_sequencialprocessoservidor {$operator} {$sequencialServidor}
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
     * @param Vinculo|null $vinculo
     * @throws Exception
     */
    public function delete(Vinculo $vinculo = null)
    {
        $id = $vinculo instanceof Vinculo ? $vinculo->getSequencial() : null;

        $dao = new cl_rhpessoalprocessovinculo;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a vinculo do processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Vinculo
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessovinculo;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);
 
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Vinculo::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Vinculo[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessovinculo;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $vinculo = [];

        if (pg_num_rows($rs) === 0) {
            return $vinculo;
        }

        while ($vinculoItem = pg_fetch_array($rs)) {
            $vinculo[] = Vinculo::fromState($vinculoItem);
        }
        
        return $vinculo;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Vinculo[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessovinculo;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $vinculo = [];

        if (pg_num_rows($rs) === 0) {
            return $vinculo;
        }

        while ($vinculoItem = pg_fetch_array($rs)) {
            $vinculo[] = Vinculo::fromState($vinculoItem);
        }
        
        return $vinculo;
    }


    /**
     * @return Vinculo[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessovinculo;
        $campos =  [
            'rh274_sequencial',
            'rh274_sequencialprocessoservidor',
            'rh274_tpregtrab',
            'rh274_tpregprev',
            'rh274_dtadm',
            'rh274_tmpparc'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);


        $vinculo = [];

        if (pg_num_rows($rs) === 0) {
            return $vinculo;
        }

        while ($vinculoPessoalProcesso = pg_fetch_array($rs)) {
            $vinculo[] = Vinculo::fromState($vinculoPessoalProcesso);
        }

        return $vinculo;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessovinculo;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os vinculos do processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param Vinculo $vinculo
     * @return Vinculo
     * @throws Exception
     */
    public function save(Vinculo $vinculo)
    {
        $dao = new cl_rhpessoalprocessovinculo;
        $dao->rh274_sequencial = $vinculo->getSequencial();
        $dao->rh274_sequencialprocessoservidor = $vinculo->getSequencialServidor();
        $dao->rh274_tpregtrab = $vinculo->getRegimeTrabalhista();
        $dao->rh274_tpregprev = $vinculo->getRegimePrevidenciario();
        $dao->rh274_dtadm  = $vinculo->getDataAdmissao();
        $dao->rh274_tmpparc  = $vinculo->getTempoParcial();

        $dao->rh274_sequencial ? $dao->alterar($vinculo->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar registro relacionado a vincullo processual."
                . $dao->erro_msg);
        }

        $vinculo->setSequencial($dao->rh274_sequencial);

        return $vinculo;
    }

    /**
     * @return Vinculo | null
     */
    public function getUnicidade()
    {
        $dao = new cl_rhpessoalprocessovinculo;
        $campos =  [
            'rh274_sequencial',
            'rh274_sequencialprocessoservidor',
            'rh274_tpregtrab',
            'rh274_tpregprev',
            'rh274_dtadm',
            'rh274_tmpparc'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        $vinculo = null;

        if (pg_num_rows($rs)) {
            $vinculoPessoalProcesso = pg_fetch_array($rs);
            $vinculo = Vinculo::fromState($vinculoPessoalProcesso);
        }

        return $vinculo;
    }
}
