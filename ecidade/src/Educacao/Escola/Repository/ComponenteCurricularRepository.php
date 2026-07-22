<?php


namespace ECidade\Educacao\Escola\Repository;

use cl_caddisciplina;
use cl_censocaddisciplina;
use ECidade\Educacao\Escola\Model\AreaConhecimento;
use ECidade\Educacao\Escola\Model\ComponenteCurricular;
use Exception;

/**
 * Class ComponenteCurricularRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class ComponenteCurricularRepository extends Repository
{
    /**
     * @var bool
     */
    private $withCenso = false;

    /**
     * @return ComponenteCurricular[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_caddisciplina();
        $sql = $dao->sql_query_file(null, '*', 'ed232_c_descr', implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar componente curricular.");
        }

        $disciplinas = [];
        while ($state = pg_fetch_array($rs)) {
            $disciplina = ComponenteCurricular::fromState($state);
            if ($this->withCenso) {
                $repository = new CensoDisciplinaRepository();
                $disciplina->setCensoDisciplina($repository->scopeComponenteCurricular($disciplina)->get());
            }
            $disciplinas[] = $disciplina;
        }

        return $disciplinas;
    }

    /**
     * @param $id
     * @return ComponenteCurricular
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_caddisciplina();
        $rs = db_query($dao->sql_query_file($id));

        if (!$rs) {
            throw new Exception("Erro ao buscar componente curricular.");
        }

        return ComponenteCurricular::fromState(pg_fetch_array($rs));
    }

    /**
     * @return $this
     */
    public function withDisciplinaCenso()
    {
        $this->withCenso = true;
        return $this;
    }

    public function salvar(ComponenteCurricular $componenteCurricular)
    {
        $daoCensoVinculo = new cl_censocaddisciplina();
        $dao = new cl_caddisciplina();
        $codigo = $componenteCurricular->getCodigo();
        $dao->ed232_i_codigo = $codigo;
        $dao->ed232_c_descr = $componenteCurricular->getNome();
        $dao->ed232_c_abrev = $componenteCurricular->getSigla();
        $dao->ed232_c_descrcompleta = $componenteCurricular->getNomeCompleto();
        $dao->ed232_corhtml = $componenteCurricular->getCorHtml();

        if ($componenteCurricular->getAreaConhecimento() instanceof AreaConhecimento) {
            $dao->ed232_areaconhecimento = $componenteCurricular->getAreaConhecimento()->getCodigo();
        }

        if (empty($codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($codigo);
            $this->removeVinculoDisciplinaCenso($daoCensoVinculo, $componenteCurricular);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar componente curricular." . pg_last_error());
        }
        $componenteCurricular->setCodigo($dao->ed232_i_codigo);

        $censoDisciplinas = $componenteCurricular->getCensoDisciplina();
        foreach ($censoDisciplinas as $censoDisciplina) {
            $daoCensoVinculo->ed294_sequencial = null;
            $daoCensoVinculo->ed294_censodisciplina = $censoDisciplina->getCodigo();
            $daoCensoVinculo->ed294_caddisciplina = $dao->ed232_i_codigo;

            $daoCensoVinculo->incluir(null);
            if ($daoCensoVinculo->erro_status == 0) {
                throw new Exception("Erro ao salvar vínculo com disciplina do censo.");
            }
        }

        return $componenteCurricular;
    }

    /**
     * @param ComponenteCurricular $disciplina
     * @return bool
     * @throws Exception
     */
    public function possueVinculoEnsino(ComponenteCurricular $disciplina)
    {
        $this->resetScopes();
        $this->scopes[] = "ed232_i_codigo = {$disciplina->getCodigo()}";
        $this->scopes[] = "exists (select 1 from disciplina where ed12_i_caddisciplina = ed232_i_codigo)";
        $dao = new cl_caddisciplina();
        $sql = $dao->sql_query_file(null, '1', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar componente curricular.");
        }

        $this->resetScopes();
        return pg_num_rows($rs) > 0;
    }

    /**
     * @param ComponenteCurricular $disciplina
     * @return bool
     * @throws Exception
     */
    public function excluir(ComponenteCurricular $disciplina)
    {
        $this->removeVinculoDisciplinaCenso(new cl_censocaddisciplina(), $disciplina);

        $dao = new cl_caddisciplina();
        $dao->excluir($disciplina->getCodigo());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir disciplina.");
        }

        return true;
    }

    /**
     * @param cl_censocaddisciplina $dao
     * @param ComponenteCurricular $disciplina
     * @return bool
     * @throws Exception
     */
    private function removeVinculoDisciplinaCenso(cl_censocaddisciplina $dao, ComponenteCurricular $disciplina)
    {
        $dao->excluir(null, "ed294_caddisciplina = {$disciplina->getCodigo()}");
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir vínculo com disciplina do censo.");
        }

        return true;
    }
}
