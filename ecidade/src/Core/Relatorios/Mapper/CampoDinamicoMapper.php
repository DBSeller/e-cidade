<?php

namespace ECidade\Core\Relatorios\Mapper;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico;
use ECidade\Core\Relatorios\Interfaces\CampoDinamicoMapper as InterfaceMapper;

/**
 * Class CampoDinamicoMapper
 * @package ECidade\Core\Mapper
 */
abstract class CampoDinamicoMapper implements InterfaceMapper
{
    /**
     * @param string $nomeCampo
     * @return CampoDinamico
     */
    abstract public function getCampo($nomeCampo);
}
