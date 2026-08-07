<?php

namespace ECidade\Core\Relatorios\Adapter;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico;
use Exception;

/**
 * Class Builder
 * @package ECidade\Core\Relatorios\Builder
 */
abstract class Adapter
{
    /**
     * @param CampoDinamico $campo
     * @return CampoDinamico
     * @throws Exception
     */
    public function getCampo(CampoDinamico $campo)
    {
        if (!method_exists(static::class, $campo->getId())) {
            throw new Exception("Metodo {$campo->getId()} não existe.");
        }

        $newCampo = clone $campo;
        $method = "{$newCampo->getId()}";
        $newCampo->setValue($this->$method());
        return $newCampo;
    }
}
