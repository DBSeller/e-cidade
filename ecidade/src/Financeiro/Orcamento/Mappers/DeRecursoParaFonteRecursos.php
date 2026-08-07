<?php

namespace ECidade\Financeiro\Orcamento\Mappers;

use ECidade\Financeiro\Orcamento\Model\Recurso;

/**
 * Class DeRecursoParaFonteRecursos
 *
 * Esse mapper foi criado para auxiliar nos filtros dos relatórios legais.
 * O array dePara tem por index o id da tabela orctiporec e o valor é a fonte de recurso.
 *
 * @package ECidade\Financeiro\Orcamento\Mappers
 */
class DeRecursoParaFonteRecursos
{
    private $deIdParaFonte = [];
    private $deFonteParaId = [];

    public function set(Recurso $recurso)
    {
        $this->deIdParaFonte[$recurso->getCodigo()] = $recurso->getRecurso();
        $this->deFonteParaId[$recurso->getRecurso()][] = $recurso->getCodigo();
    }

    public function getById($id)
    {
        return $this->deIdParaFonte[$id];
    }

    public function getByFonteRecurso($fonte)
    {
        return $this->deFonteParaId[$fonte];
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
