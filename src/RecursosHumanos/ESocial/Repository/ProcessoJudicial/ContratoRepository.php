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

use cl_rhpessoalprocessocontrato;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Contrato as ContratoProcessual;
use Exception;
use DBDate;

class ContratoRepository
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
        $this->scopes['sequencialServidor'] = "rh273_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param int $sequencialServidor
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialServidor($sequencialServidor, $operator = '=')
    {
        $this->scopes['sequencialServidor'] = "rh273_sequencialprocessoservidor {$operator} {$sequencialServidor}";
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
    public function delete(ContratoProcessual $contrato = null)
    {
        $id = $contrato instanceof ContratoProcessual ? $contrato->getSequencial() : null;
        $dao = new cl_rhpessoalprocessocontrato;
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
        $dao = new cl_rhpessoalprocessocontrato;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as informações do contrato processual.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ContratoProcessual::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ContratoProcessual[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhpessoalprocessocontrato;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contratoItem = pg_fetch_array($rs)) {
            $contrato[] = ContratoProcessual::fromState($contratoItem);
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
        $dao = new cl_rhpessoalprocessocontrato;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contratoItem = pg_fetch_array($rs)) {
            $contrato[] = ContratoProcessual::fromState($contratoItem);
        }
        
        return $contrato;
    }


    /**
     * @return ContratoProcessual[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessocontrato;
        $campos = [
            'rh273_sequencial',
            'rh273_sequencialprocessoservidor',
            'rh273_tpcontr',
            'rh273_indcontr',
            'rh273_dtadmorig',
            'rh273_indreint',
            'rh273_indcateg',
            'rh273_indnatativ',
            'rh273_indmotdeslig',
            'rh273_dinicio',
            'rh273_codcbo',
            'rh273_natatividade',
            'rh273_compini',
            'rh273_compfim',
            'rh273_indreperc',
            'rh273_indenabono',
            'rh273_indensd'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar contrato processual");
        }

        $contrato = [];

        if (pg_num_rows($rs) === 0) {
            return $contrato;
        }

        while ($contribuicaoSindicalPatronal = pg_fetch_array($rs)) {
            $contrato[] = ContratoProcessual::fromState($contribuicaoSindicalPatronal);
        }

        return $contrato;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessocontrato;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os processos judiciais.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param ContratoProcessual $contrato
     * @return ContratoProcessual
     * @throws Exception
     */
    public function save(ContratoProcessual $contrato)
    {
        $dao = new cl_rhpessoalprocessocontrato;
        $dao->rh273_sequencial = $contrato->getSequencial();
        $dao->rh273_sequencialprocessoservidor = $contrato->getSequencialProcessoServidor();
        $dao->rh273_tpcontr = $contrato->getTipoContrato();
        $dao->rh273_indcontr = $contrato->getIndicativoContrato();
        $dao->rh273_dtadmorig = $contrato->getDataAdmissaoOrigem();
        $dao->rh273_indreint = $contrato->getIndicativoReintegracao();
        $dao->rh273_indcateg = $contrato->getIndicativoCategoria();
        $dao->rh273_indnatativ = $contrato->getIndicativoNaturezaAtividade();
        $dao->rh273_indmotdeslig = $contrato->getIndicativoMotivoDesligamento();
        $dao->rh273_dinicio = $contrato->getDataInicioTSVE();
        $dao->rh273_codcbo = $contrato->getCodigoCBO();
        $dao->rh273_natatividade = $contrato->getNaturezaAtividade();
        $dao->rh273_compini = $contrato->getCompetenciaInicial();
        $dao->rh273_compfim = $contrato->getCompetenciaFinal();
        $dao->rh273_indreperc = $contrato->getIndicativoRepercussao();
        $dao->rh273_indensd = $contrato->getIndicativoIndenizacaoSD();
        $dao->rh273_indenabono = $contrato->getIndicativoContrato();

        $dao->rh273_sequencial ? $dao->alterar($contrato->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o contrato.");
        }

        $contrato->setSequencial($dao->rh273_sequencial);

        return $contrato;
    }

    /**
     * @param $sequencialProcessoServidor
     * @param array $columns
     * @return bool|ProcessoJudicial
     * @throws Exception
     */
    public static function getListaContratosProcesso($sequencialProcessoServidor, $columns = array('*'))
    {
        $dao = new cl_rhpessoalprocessocontrato;
        $where = " rh273_sequencialprocessoservidor = {$sequencialProcessoServidor}";
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
            return ContratoProcessual::fromState(pg_fetch_array($rs));
        }

        return $contrato;
    }

    /**
     * @return ContratoProcessual | null
     */
    public function getContrato()
    {
        $dao = new cl_rhpessoalprocessocontrato;
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        $contrato = null;

        if (pg_num_rows($rs)) {
            $contribuicaoSindicalPatronal = pg_fetch_array($rs);
            $contrato = ContratoProcessual::fromState($contribuicaoSindicalPatronal);
        }

        return $contrato;
    }
}
