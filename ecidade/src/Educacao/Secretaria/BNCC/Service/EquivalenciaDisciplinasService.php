<?php


namespace ECidade\Educacao\Secretaria\BNCC\Service;

use ECidade\Educacao\Escola\Model\ComponenteCurricular;
use ECidade\Educacao\Escola\Repository\ComponenteCurricularRepository;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\BNCC\Model\DisciplinaEquivalente;
use ECidade\Educacao\Secretaria\BNCC\Repository\DisciplinaEquivalenteRepository;
use ECidade\Educacao\Secretaria\BNCC\Repository\DisciplinaRepository as DisciplinaRepositoryBNCC;
use Exception;

/**
 * Class EquivalenciaDisciplinasService
 * @package ECidade\Educacao\Secretaria\BNCC\Service
 */
class EquivalenciaDisciplinasService
{

    /**
     * @return Disciplina[]
     * @throws Exception
     */
    public function getDisciplinasBNCC()
    {
        $repository = new DisciplinaRepositoryBNCC();
        return $repository->get();
    }

    public function getDisciplinasEcidade()
    {
        $repository = new ComponenteCurricularRepository();
        return $repository->get();
    }

    /**
     * @param Disciplina $disciplina
     * @return DisciplinaEquivalente[]
     * @throws Exception
     */
    public function equivalenciasDisciplinaBNCC(Disciplina $disciplina)
    {
        $repository = new DisciplinaEquivalenteRepository();
        return $repository->scopeDisciplinaBNCC($disciplina)->get();
    }

    /**
     * @param Disciplina $disciplina
     * @param ComponenteCurricular[] $componentes
     * @throws Exception
     */
    public function salvarEquivalencia(Disciplina $disciplina, array $componentes)
    {
        $repository = new DisciplinaEquivalenteRepository();
        $repository->scopeDisciplinaBNCC($disciplina)->excluirByScope();

        foreach ($componentes as $componenteCurricular) {
            $disciplinaEquivalente = new DisciplinaEquivalente();
            $disciplinaEquivalente->setDisciplinaBncc($disciplina);
            $disciplinaEquivalente->setDisciplinaEcidade($componenteCurricular);

            $repository->salvar($disciplinaEquivalente);
        }
    }
}
