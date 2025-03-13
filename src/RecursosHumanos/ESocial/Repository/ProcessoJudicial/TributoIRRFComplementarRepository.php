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

use BusinessException;
use cl_rhprocessoirrfcomp;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoIRRFComplementar;
use DBDate;

class TributoIRRFComplementarRepository
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
        $this->scopes['sequencial'] = "rh310_sequencial {$operator} {$sequencial}";
        return $this;
    }

     /**
     * @param int $sequencialServidor
     * @param string $operator
     * @return $this
     */
    public function scopeSequencialServidor($sequencialServidor, $operator = '=')
    {
        $this->scopes['sequencialServidor'] = "rh310_sequencialprocessoservidor {$operator} {$sequencialServidor}";
        return $this;
    }

    /**
     * @param string $cpfDependente
     * @param string $operator
     * @return $this
     */
    public function scopeCPFDependente($cpfDependente, $operator = '=')
    {
        $this->scopes['cpfDependente'] = "rh310_cpfdep {$operator} '{$cpfDependente}'";
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
     * @throws BusinessException
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
     * @param TributoIRRFComplementar|null $IRRFcomplmentar
     * @throws BusinessException
     */
    public function delete(TributoIRRFComplementar $IRRFcomplmentar = null)
    {
        $id = $IRRFcomplmentar instanceof TributoIRRFComplementar ? $IRRFcomplmentar->getSequencial() : null;

        $dao = new cl_rhprocessoirrfcomp;
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível excluir o período e valores do processo judicial.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|TributoIRRFComplementar
     * @throws BusinessException
     */
    public static function find($id, $columns = array('*'), $order = null, $where = null)
    {
        $dao = new cl_rhprocessoirrfcomp;
        $sql = $dao->sql_query($id, implode(', ', $columns), $order, $where);

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar o IRRF complementar.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return TributoIRRFComplementar::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return TributoIRRFComplementar[]
     * @throws BusinessException
     */
    public static function all($columns = ['*'], $order = null, $where = null)
    {
        $dao = new cl_rhprocessoirrfcomp;
        $sql = $dao->sql_query(null, implode(', ', $columns), $order, $where);
        $rs = db_query($sql);

        $IRRFcomplmentar = [];

        if (pg_num_rows($rs) === 0) {
            return $IRRFcomplmentar;
        }

        while ($IRRFcomplmentarItem = pg_fetch_array($rs)) {
            $IRRFcomplmentar[] = TributoIRRFComplementar::fromState($IRRFcomplmentarItem);
        }
        
        return $IRRFcomplmentar;
    }

     /**
     * @param array $columns
     * @param string $ordem
     * @return TributoIRRFComplementar[]
     * @throws BusinessException
     */
    public function allOrderBy($columns = ['*'], $ordem = null)
    {
        $dao = new cl_rhprocessoirrfcomp;
        $sql = $dao->sql_query(null, implode(', ', $columns), $ordem);
        $rs = db_query($sql);

        $IRRFcomplementar = [];

        if (pg_num_rows($rs) === 0) {
            return $IRRFcomplementar;
        }

        while ($IRRFcomplmentaronoItem = pg_fetch_array($rs)) {
            $IRRFcomplementar[] = TributoIRRFComplementar::fromState($IRRFcomplmentaronoItem);
        }
        
        return $IRRFcomplementar;
    }


    /**
     * @return TributoIRRFComplementar[]
     * @throws BusinessException
     */
    public function get()
    {
        $dao = new cl_rhprocessoirrfcomp;
        $campos = [
            'rh310_sequencial',
            'rh310_sequencialprocessoservidor',
            'rh310_dtlaudo',
            'rh310_cpfdep',
            'rh310_dtnascto',
            'rh310_nome',
            'rh310_depirrf',
            'rh310_tpdep',
            'rh310_descrdep'
        ];
        $sql = $dao->sql_query(null, implode(', ', $campos), null, implode(' AND ', $this->scopes));

        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar IRRF complementar.");
        }

        $IRRFcomplementar = [];

        if (pg_num_rows($rs) === 0) {
            return $IRRFcomplementar;
        }

        while ($IRRFcomplementaronoProcesso = pg_fetch_array($rs)) {
            $IRRFcomplementar[] = TributoIRRFComplementar::fromState($IRRFcomplementaronoProcesso);
        }

        return $IRRFcomplementar;
    }

    /**
     * @return int
     * @throws BusinessException
     */
    public function count()
    {
        $dao = new cl_rhprocessoirrfcomp;
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new BusinessException("Não foi possível buscar IRRF complementar.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param TributoIRRFComplementar $abono
     * @return TributoIRRFComplementar
     * @throws BusinessException
     */
    public function save(TributoIRRFComplementar $tributoIRRFComplementar)
    {
        $dao = new cl_rhprocessoirrfcomp;
        $dao->rh310_sequencial = $tributoIRRFComplementar->getSequencial();
        $dao->rh310_sequencialprocessoservidor = $tributoIRRFComplementar->getSequencialProcessoServidor();
        $dao->rh310_dtlaudo = $tributoIRRFComplementar->getDataLaudo();
        $dao->rh310_cpfdep = $tributoIRRFComplementar->getCpfDependente();
        $dao->rh310_dtnascto = $tributoIRRFComplementar->getDataNascimento();
        $dao->rh310_nome = $tributoIRRFComplementar->getNome();
        $dao->rh310_depirrf = $tributoIRRFComplementar->getIRRFDependenteTributavel();
        $dao->rh310_tpdep = $tributoIRRFComplementar->getTipoDependente();
        $dao->rh310_descrdep = $tributoIRRFComplementar->getDescricaoDependencia();

        $dao->rh310_sequencial ? $dao->alterar($tributoIRRFComplementar->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new BusinessException("Não foi possível salvar registro relacionado ao novo IRRF complementar."
                . $dao->erro_msg);
        }

        $tributoIRRFComplementar->setSequencial($dao->rh310_sequencial);

        return $tributoIRRFComplementar;
    }
}
