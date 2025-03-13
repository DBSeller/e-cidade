<?php


namespace ECidade\Educacao\Secretaria\BNCC\Repository;

use cl_caddisciplinabnccdisciplinas;
use ECidade\Educacao\Escola\Model\ComponenteCurricular;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\BNCC\Model\DisciplinaEquivalente;
use ECidade\Educacao\Secretaria\BNCC\Service\EquivalenciaEtapasService;
use ECidade\Enum\Educacao\BNCC\EnsinoEnum;
use Exception;

/**
 * Class DisciplinaEquivalenteResource
 * @package ECidade\Educacao\Secretaria\BNCC\Resource
 */
class DisciplinaEquivalenteRepository extends Repository
{
    private $campos = ["caddisciplinabnccdisciplinas.*"];

    /**
     * @return DisciplinaEquivalente[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_caddisciplinabnccdisciplinas();
        $sql = $dao->sql_query(
            null,
            implode(', ', $this->campos),
            null,
            implode(' and ', $this->scopes)
        );

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar equivalência.");
        }

        $equivalencia = [];
        while ($state = pg_fetch_array($rs)) {
            $equivalencia[] = DisciplinaEquivalente::fromState($state);
        }

        return $equivalencia;
    }

    /**
     * @param Disciplina $disciplina
     * @return $this
     */
    public function scopeDisciplinaBNCC(Disciplina $disciplina)
    {
        $this->scopes['disciplinaBNCC'] = "ed153_bnccdisciplina = {$disciplina->getCodigo()}";
        return $this;
    }

    /**
     * @param ComponenteCurricular $disciplina
     * @return $this
     */
    public function scopeDisciplinaEcidade(ComponenteCurricular $disciplina)
    {
        $this->scopes['disciplinaEcidade'] = "ed153_caddisciplina = {$disciplina->getCodigo()}";
        return $this;
    }

    /**
     * @param EnsinoEnum $ensino
     * @return $this
     */
    public function scopeEnsino(EnsinoEnum $ensino)
    {
        $this->scopes['ensino'] = "ed149_ensino = '{$ensino->value()}'";
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function excluirByScope()
    {
        $dao = new cl_caddisciplinabnccdisciplinas();
        $dao->excluir(null, implode(' and ', $this->scopes));

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir equivalência.");
        }

        return true;
    }

    /**
     * @param DisciplinaEquivalente $disciplinaEquivalente
     * @return DisciplinaEquivalente
     * @throws Exception
     */
    public function salvar(DisciplinaEquivalente $disciplinaEquivalente)
    {
        $dao = new cl_caddisciplinabnccdisciplinas();
        $dao->ed153_sequencial = null;
        $dao->ed153_bnccdisciplina = $disciplinaEquivalente->getDisciplinaBncc()->getCodigo();
        $dao->ed153_caddisciplina = $disciplinaEquivalente->getDisciplinaEcidade()->getCodigo();

        $dao->incluir(null);
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar equivalência.");
        }

        $disciplinaEquivalente->setCodigo($dao->ed153_sequencial);
        return $disciplinaEquivalente;
    }
}
