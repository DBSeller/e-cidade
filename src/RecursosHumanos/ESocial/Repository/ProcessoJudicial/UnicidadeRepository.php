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

use cl_rhpessoalprocessounicidade;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Unicidade as UnicidadeProcessual;
use Exception;
use DBDate;

class UnicidadeRepository
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
        $this->scopes['sequencial'] = "rh281_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $sequencialContrato
     * @param $operator
     * @return $this
     */
    public function scopeSequencialContrato($sequencialContrato, $operator = '=')
    {
        $this->scopes['contrato'] = "
            rh281_sequencialprocessocontrato {$operator} {$sequencialContrato}
        ";

        return $this;
    }

    /**
     * @param $matricula
     * @param $operator
    * @return $this
     */
    public function scopeMatricula($matricula, $operator = '=')
    {
        $this->scopes['matricula'] = "
            rh281_matunic {$operator} {$matricula}
        ";

        return $this;
    }

    /**
     * @param $codigoCategoria
     * @param $operator
    * @return $this
     */
    public function scopeCategoria($codigoCategoria, $operator = '=')
    {
        $this->scopes['categoria'] = "
            rh281_codcateg {$operator} {$codigoCategoria}
        ";

        return $this;
    }

    /**
     * @param $dataInicio
     * @param $operator
    * @return $this
     */
    public function scopeDataInicio($dataInicio, $operator = '=')
    {
        $this->scopes['data'] = "
            rh281_dtinicio {$operator} {$dataInicio}
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
     * @param UnicidadeProcessual|null $unicidade
     * @throws Exception
     */
    public function delete(UnicidadeProcessual $unicidade = null)
    {
        $id = $unicidade instanceof UnicidadeProcessual ? $unicidade->getSequencial() : null;

        $dao = new cl_rhpessoalprocessounicidade;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a unicidade do processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|UnicidadeProcessual
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhpessoalprocessounicidade;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);
 
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return UnicidadeProcessual::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ContratoProcessual[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessounicidade;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $unicidade = [];

        if (pg_num_rows($rs) === 0) {
            return $unicidade;
        }

        while ($unicidadeItem = pg_fetch_array($rs)) {
            $unicidade[] = UnicidadeProcessual::fromState($unicidadeItem);
        }
        
        return $unicidade;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhpessoalprocessounicidade;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $unicidade = [];

        if (pg_num_rows($rs) === 0) {
            return $unicidade;
        }

        while ($unicidadeItem = pg_fetch_array($rs)) {
            $unicidade[] = UnicidadeProcessual::fromState($unicidadeItem);
        }
        
        return $unicidade;
    }


    /**
     * @return UnicidadeProcessual[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessounicidade;
        $campos =  [
            'rh281_sequencial',
            'rh281_sequencialprocessocontrato',
            'rh281_matunic',
            'rh281_codcateg',
            'rh281_dtinicio'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);


        $unicidade = [];

        if (pg_num_rows($rs) === 0) {
            return $unicidade;
        }

        while ($unicidadePessoalProcesso = pg_fetch_array($rs)) {
            $unicidade[] = UnicidadeProcessual::fromState($unicidadePessoalProcesso);
        }

        return $unicidade;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessounicidade;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param UnicidadeProcessual $unicidade
     * @return UnicidadeProcessual
     * @throws Exception
     */
    public function save(UnicidadeProcessual $unicidade)
    {
        $dao = new cl_rhpessoalprocessounicidade;
        $dao->rh281_sequencial = $unicidade->getSequencial();
        $dao->rh281_sequencialprocessocontrato = $unicidade->getSequencialProcessoContrato();
        $dao->rh281_codcateg = $unicidade->getCodigoCategoriaUnicidade();
        $dao->rh281_dtinicio = $unicidade->getDataInicioUnicidade();
        $dao->rh281_matunic  = $unicidade->getMatriculaUnicidade();

        $dao->rh281_sequencial ? $dao->alterar($unicidade->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar registro relacionado a unicidade."
                . $dao->erro_msg);
        }

        $unicidade->setSequencial($dao->rh281_sequencial);

        return $unicidade;
    }

    /**
     * @return UnicidadeProcessual | null
     */
    public function getUnicidade()
    {
        $dao = new cl_rhpessoalprocessounicidade;
        $campos =  [
            'rh281_sequencial',
            'rh281_sequencialprocessocontrato',
            'rh281_matunic',
            'rh281_codcateg',
            'rh281_dtinicio'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        $unicidade = null;

        if (pg_num_rows($rs)) {
            $unicidadePessoalProcesso = pg_fetch_array($rs);
            $unicidade = UnicidadeProcessual::fromState($unicidadePessoalProcesso);
        }

        return $unicidade;
    }
}
