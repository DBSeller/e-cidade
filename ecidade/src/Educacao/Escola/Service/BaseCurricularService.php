<?php


namespace ECidade\Educacao\Escola\Service;

use ECidade\Educacao\Escola\Model\BaseCurricular;
use ECidade\Educacao\Escola\Model\BaseCurricularDisciplina;
use ECidade\Educacao\Escola\Repository\BaseCurricularDisciplinaRepository;
use ECidade\Educacao\Escola\Repository\BaseCurricularRepository;
use Etapa;

/**
 * Class BaseCurricularService
 * @package ECidade\Educacao\Escola\Service
 */
class BaseCurricularService
{
    /**
     * @var BaseCurricularRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new BaseCurricularRepository();
    }

    /**
     * @param BaseCurricular $base
     * @param Etapa $etapa
     * @return BaseCurricularDisciplina[]
     */
    public function getDisciplinasEtapa(BaseCurricular $base, Etapa $etapa)
    {
        $baseCurricularDisciplinaRepository = new BaseCurricularDisciplinaRepository();
        return $baseCurricularDisciplinaRepository->scopeBaseCurricular($base)->scopeEtapa($etapa)->get();
    }
}
