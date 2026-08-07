<?php

namespace ECidade\Core\Relatorios\Layout;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico;

/**
 * Interface Layout
 * @package ECidade\Educacao\MatriculaOnline\Relatorio\Layout
 */
interface Layout
{
    /**
     * @param string $fileName
     * @return string
     */
    public function imprimir($fileName = null);

    /**
     * @param CampoDinamico[] $campos
     * @return $this
     */
    public function setCampos(array $campos);
}
