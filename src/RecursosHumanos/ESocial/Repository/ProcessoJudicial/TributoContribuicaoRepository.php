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

use cl_rhprocessotributocontribuicao;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoContribuicao;
use Exception;
use DBDate;

class TributoContribuicaoRepository
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
        $this->scopes['sequencial'] = "rh298_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param int $sequencial
     * @return $this
     */
    public function scopeBase($sequencial = 0, $codigoReceita = 0)
    {
        $this->scopes['sequencialBase'] =
            "rh298_sequencialtributobase = {$sequencial} and rh298_tpcr = {$codigoReceita}";
        return $this;
    }

    /**
     * @param $sequencialtributobase
     * @return $this
     */
    public function scopeSequencialBase($sequencialTributoBase, $operator = '=')
    {
        $this->scopes['tributoBase'] = "
            rh298_sequencialtributobase {$operator} {$sequencialTributoBase}
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
     * @param TributoContribuicao|null $tributoContribuicao
     * @throws Exception
     */
    public function delete(TributoContribuicao $tributoContribuicao = null)
    {
        $id = $tributoContribuicao instanceof TributoContribuicao ? $tributoContribuicao->getSequencial() : null;

        $dao = new cl_rhprocessotributocontribuicao;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o tributo base mensal de contribuição do servidor.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|TributoContribuicao
     * @throws Exception
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessoTributoContribuicao;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);
 
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o tributo base de contribuição mensal do servidor.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return TributoContribuicao::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return TributoContribuicao[]
     * @throws Exception
     */
    public function all($columns = ['*'])
    {
        $dao = new cl_rhprocessotributocontribuicao;
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $tributoContribuicao = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoContribuicao;
        }

        while ($tributoContribuicaoItem = pg_fetch_array($rs)) {
            $unicidade[] = TributoContribuicao::fromState($tributoContribuicaoItem);
        }
        
        return $tributoContribuicao;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return ProcessoJudicial[]
     * @throws Exception
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessotributocontribuicao;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $tributoContribuicao = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoContribuicao;
        }

        while ($tributoContribuicaoItem = pg_fetch_array($rs)) {
            $tributoContribuicao[] = TributoContribuicao::fromState($tributoContribuicaoItem);
        }
        
        return $tributoContribuicao;
    }


    /**
     * @return tributoContribuicao[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhprocessotributocontribuicao;
        $campos =  [
            'rh298_sequencial',
            'rh298_sequencialtributobase',
            'rh298_tpcr',
            'rh298_vrcr'
        ];
        $sql = $dao->sql_query(null, implode(' , ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        $tributoContribuicao = [];

        if (pg_num_rows($rs) === 0) {
            return $tributoContribuicao;
        }

        while ($tributoContribuicaoProcesso = pg_fetch_array($rs)) {
            $tributoContribuicao[] = TributoContribuicao::fromState($tributoContribuicaoProcesso);
        }

        return $tributoContribuicao;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhprocessoTributoContribuicao;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) tributo(s) base de contribuição do(s) servidor(es).");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param TributoContribuicao $tributoContribuicao
     * @return TributoContribuicao
     * @throws Exception
     */
    public function save(TributoContribuicao $tributoContribuicao)
    {
        $dao = new cl_rhprocessoTributoContribuicao;
        $dao->rh298_sequencial                  = $tributoContribuicao->getSequencial();
        $dao->rh298_sequencialtributobase  = $tributoContribuicao->getSequencialTributoBase();
        $dao->rh298_tpcr                      = $tributoContribuicao->getCodigoReceita();
        $dao->rh298_vrcr                = $tributoContribuicao->getValorContribuicao();

        $dao->rh298_sequencial ? $dao->alterar($tributoContribuicao->getSequencial()) : $dao->incluir(null);
        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar registro relacionado a tributo base de contribuição " .
            "do servidor."
                . $dao->erro_msg);
        }

        $tributoContribuicao->setSequencial($dao->rh298_sequencial);

        return $tributoContribuicao;
    }
}
