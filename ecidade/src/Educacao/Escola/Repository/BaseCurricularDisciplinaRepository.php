<?php


namespace ECidade\Educacao\Escola\Repository;

use cl_basemps;
use ECidade\Educacao\Escola\Model\AreaConhecimento;
use ECidade\Educacao\Escola\Model\BaseCurricular;
use ECidade\Educacao\Escola\Model\BaseCurricularDisciplina;
use Exception;
use ProcedimentoAvaliacao;

/**
 * Class BaseCurricularDisciplinaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class BaseCurricularDisciplinaRepository extends Repository
{
    /**
     * @param $id
     * @return BaseCurricularDisciplina
     */
    public static function find($id)
    {
        $dao = new cl_basemps();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Disciplina da Base Curricular.");
        }

        return BaseCurricularDisciplina::fromState(pg_fetch_array($rs));
    }

    /**
     * @return BaseCurricularDisciplina[]
     */
    public function get()
    {
        $dao = new cl_basemps();
        $sql = $dao->sql_query_file(
            null,
            'basemps.*',
            'ed34_i_ordenacao',
            implode(' and ', $this->scopes)
        );
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Disciplinas da Base Curricular.");
        }

        $disciplinas = [];
        while ($state = pg_fetch_array($rs)) {
            $disciplinas[] = BaseCurricularDisciplina::fromState($state);
        }
        return $disciplinas;
    }

    /**
     * @param BaseCurricular $base
     * @return BaseCurricularDisciplinaRepository
     */
    public function scopeBaseCurricular(BaseCurricular $base)
    {
        $this->scopes['base'] = "ed34_i_base = {$base->getCodigo()}";
        return $this;
    }

    /**
     * @param \Etapa $etapa
     * @return BaseCurricularDisciplinaRepository
     */
    public function scopeEtapa(\Etapa $etapa)
    {
        $this->scopes['etapa'] = "ed34_i_serie = {$etapa->getCodigo()}";
        return $this;
    }

    public function salvar(BaseCurricularDisciplina $baseCurricularDisciplina)
    {
        $dao = new cl_basemps();
        $dao->ed34_i_codigo = $baseCurricularDisciplina->getCodigo();
        $dao->ed34_i_base = $baseCurricularDisciplina->getBase()->getCodigo();
        $dao->ed34_i_serie = $baseCurricularDisciplina->getEtapa()->getCodigo();
        $dao->ed34_i_disciplina = $baseCurricularDisciplina->getDisciplina()->getCodigoDisciplina();
        $dao->ed34_i_qtdperiodo = $baseCurricularDisciplina->getHorasAula();
        $dao->ed34_i_chtotal = $baseCurricularDisciplina->getCargaHorariaTotal();
        $dao->ed34_c_condicao = $baseCurricularDisciplina->getTipoMatricula();
        $dao->ed34_i_ordenacao = $baseCurricularDisciplina->getOrdenacao();
        $dao->ed34_lancarhistorico = $baseCurricularDisciplina->isLancarHistorico() ? 'true' : 'false';
        $dao->ed34_disiciplinaglobalizada = $baseCurricularDisciplina->isDisiciplinaglobalizada() ? 'true' : 'false';
        $dao->ed34_caracterreprobatorio = $baseCurricularDisciplina->isPossuiCaracterReprobatorio() ? 'true' : 'false';
        $dao->ed34_basecomum = $baseCurricularDisciplina->isBaseComum() ? 'true' : 'false';
        $dao->ed34_areaconhecimento = null;
        $dao->ed34_procedimento = null;

        $areaConhecimento = $baseCurricularDisciplina->getAreaConhecimento();
        if ($areaConhecimento instanceof AreaConhecimento) {
            $dao->ed34_areaconhecimento = $areaConhecimento->getCodigo();
        }

        $procedimento = $baseCurricularDisciplina->getProcedimento();
        if ($procedimento instanceof  ProcedimentoAvaliacao) {
            $dao->ed34_procedimento = $procedimento->getCodigo();
        }

        if (empty($dao->ed34_i_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed34_i_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Disciplina da Base Curricular.");
        }

        $baseCurricularDisciplina->setCodigo($dao->ed34_i_codigo);

        return $baseCurricularDisciplina;
    }
}
