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

use cl_rhpessoalprocessoduracao;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Duracao;
use Exception;

class DuracaoRepository
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
        $this->scopes['sequencial'] = "rh276_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param int $sequencialVinculo
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialVinculo($sequencialVinculo, $operator = '=')
    {
        $this->scopes['sequencialVinculo'] = "rh276_sequencialprocessovinculo {$operator} {$sequencialVinculo}";
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
     * @param Duracao|null $duracao
     * @throws Exception
     */
    public function delete(Duracao $duracao = null)
    {
        $id = $duracao instanceof Duracao ? $duracao->getSequencial() : null;

        $dao = new cl_rhpessoalprocessoduracao;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o vínculo duração.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Duracao
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoduracao;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as informações do vínculo duração.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Duracao::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Duracao[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessoduracao;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contratoItem = pg_fetch_array($rs)) {
            $contrato[] = Duracao::fromState($contratoItem);
        }
        
        return $contrato;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Duracao[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessoduracao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $duracao = [];

        if (pg_num_rows($rs) === 0) {
            return $duracao;
        }

        while ($duracaoItens = pg_fetch_array($rs)) {
            $duracao[] = Duracao::fromState($duracaoItens);
        }
        
        return $duracao;
    }

    /**
     * @return Duracao[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessoduracao;
        $campos = [
            'rh276_sequencial',
            'rh276_sequencialprocessovinculo',
            'rh276_tpcontr',
            'rh276_dtterm',
            'rh276_clauassec',
            'rh276_objdet'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);


        $duracao = [];

        if (pg_num_rows($rs) === 0) {
            return $duracao;
        }

        while ($duracaoItens = pg_fetch_array($rs)) {
            $duracao[] = Duracao::fromState($duracaoItens);
        }

        return $duracao;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessoduracao;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar vínculo duração do processo judiciail.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param Duracao $duracao
     * @return Duracao
     * @throws Exeption
     */
    public function save(Duracao $duracao)
    {
        $dao = new cl_rhpessoalprocessoduracao;
        $dao->rh276_sequencial = $duracao->getSequencial();
        $dao->rh276_sequencialprocessovinculo = $duracao->getSequencialProcessoVinculo();
        $dao->rh276_tpcontr  = $duracao->getTipoContrato();
        $dao->rh276_dtterm = $duracao->getDataTerminoContrato();
        $dao->rh276_clauassec = $duracao->getClausulaAssecuratoria();
        $dao->rh276_objdet = $duracao->getObjetoDeterminante();


        $dao->rh276_sequencial ? $dao->alterar($duracao->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar vínculo duração do processo." . $dao->erro_msg);
        }

        $duracao->setSequencial($dao->rh276_sequencial);

        return $duracao;
    }
}
