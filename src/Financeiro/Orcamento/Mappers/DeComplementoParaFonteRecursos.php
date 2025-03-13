<?php


namespace ECidade\Financeiro\Orcamento\Mappers;

use ECidade\Financeiro\Orcamento\Model\Recurso;

/**
 * Class DeComplementoParaFonteRecursos
 * @package ECidade\Financeiro\Orcamento\Mappers
 */
class DeComplementoParaFonteRecursos
{
    private $deComplementoParaFonteRecurso= [];
    private $deFonteRecursoParaComplemento = [];
    private $deComplementoParaRecursos = [];

    public function set(Recurso $recurso)
    {
        $this->deComplementoParaFonteRecurso[$recurso->getComplemento()->getCodigo()][] = $recurso->getRecurso();
        $this->deComplementoParaRecursos[$recurso->getComplemento()->getCodigo()][] = $recurso->getCodigo();
        $this->deFonteRecursoParaComplemento[$recurso->getRecurso()][] = $recurso->getComplemento()->getCodigo();
    }

    public function getFonteRecursosByIdComplemento($id)
    {
        return $this->deComplementoParaFonteRecurso[$id];
    }

    public function getRecursosByIdComplemento($id)
    {
        if (array_key_exists($id, $this->deComplementoParaRecursos)) {
            return $this->deComplementoParaRecursos[$id];
        }

        return [];
    }

    public function getByFonteRecurso($fonte)
    {
        return $this->deFonteRecursoParaComplemento[$fonte];
    }

    /**
     * @param Recurso[] $recursos
     */
    public static function create($recursos)
    {
        $self = new self();
        foreach ($recursos as $recurso) {
            $self->set($recurso);
        }

        return $self;
    }
}
