<?php
namespace ECidade\Core\Relatorios\Factory;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico;
use ECidade\Core\Relatorios\Interfaces\CampoDinamicoMapper;

/**
 * Class CampoDinamicoFactory
 * @package ECidade\Core\Factory
 */
class CampoDinamicoFactory
{
    /**
     * @param CampoDinamicoMapper $mapper
     * @param array $arrayNomesCampos
     * @return CampoDinamico[]
     */
    public function getCamposDinamicos(CampoDinamicoMapper $mapper, array $arrayNomesCampos)
    {
        $camposDinamicos = array();

        foreach ($arrayNomesCampos as $nomeCampo) {
            $camposDinamicos[] = $mapper->getCampo($nomeCampo);
        }

        return $camposDinamicos;
    }
}
