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

use cl_rhpessoalprocessoestatutario;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Estatutario;
use Exception;

class EstatutarioRepository
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
        $this->scopes['sequencial'] = "rh278_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param int $sequencialVinculo
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialVinculo($sequencialVinculo, $operator = '=')
    {
        $this->scopes['sequencialVinculo'] = "rh278_sequencialprocessovinculo {$operator} {$sequencialVinculo}";
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
     * @param Estatutario|null $contrato
     * @throws Exception
     */
    public function delete(Estatutario $estatutario = null)
    {
        $id = $estatutario instanceof Estatutario ? $estatutario->getSequencial() : null;

        $dao = new cl_rhpessoalprocessoestatutario;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o vinculo de desligamento.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Estatutario
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessoestatutario;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as informações do vinculo de desligamento.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Estatutario::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return Estatutario[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessoestatutario;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contratoItem = pg_fetch_array($rs)) {
            $contrato[] = Estatutario::fromState($contratoItem);
        }
        
        return $contrato;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return Estatutario[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessoestatutario;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $estatutario = [];

        if (pg_num_rows($rs) === 0) {
            return $estatutario;
        }

        while ($estatutarioItens = pg_fetch_array($rs)) {
            $estatutario[] = Estatutario::fromState($estatutarioItens);
        }
        
        return $estatutario;
    }


    /**
     * @return Estatutario[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessoestatutario;
        $campos = [
            'rh278_sequencial',
            'rh278_sequencialprocessovinculo',
            'rh278_tplnsc',
            'rh278_nrlnsc',
            'rh278_matricant',
            'rh278_dttransf'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);


        $estatutario = [];

        if (pg_num_rows($rs) === 0) {
            return $estatutario;
        }

        while ($estatutarioItens = pg_fetch_array($rs)) {
            $estatutario[] = Estatutario::fromState($estatutarioItens);
        }

        return $estatutario;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessoestatutario;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar vínculo trabalhista/estatutário do processo judiciail.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param Estatutario $estatutario
     * @return Estatutario
     * @throws Exeption
     */
    public function save(Estatutario $estatutario)
    {
        $dao = new cl_rhpessoalprocessoestatutario;
        $dao->rh278_sequencial = $estatutario->getSequencial();
        $dao->rh278_sequencialprocessovinculo = $estatutario->getSequencialProcessoVinculo();
        $dao->rh278_tplnsc = $estatutario->getTipoInscricao();
        $dao->rh278_nrlnsc = $estatutario->getInscricao();
        $dao->rh278_matricant = $estatutario->getMatriculaAnterior();
        $dao->rh278_dttransf = $estatutario->getDataTransferencia();


        $dao->rh278_sequencial ? $dao->alterar($estatutario->getSequencial()) : $dao->incluir(null);
        //dd( $dao);
        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar vínculo estatutário do processo." . $dao->erro_msg);
        }

        $estatutario->setSequencial($dao->rh278_sequencial);

        return $estatutario;
    }
}
