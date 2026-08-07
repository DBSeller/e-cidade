<?php

namespace ECidade\Core\Relatorios\Interfaces;

/**
 * Interface CampoDinamicoMapper
 * @package ECidade\Core\Interfaces
 */
interface CampoDinamicoMapper
{
    /**
     * @param string $campo
     * @return CampoDinamico
     */
    public function getCampo($campo);
}
