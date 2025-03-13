<?php


namespace ECidade\Educacao\Escola\Resource;

use ECidade\Educacao\Escola\Model\CensoUf;
use stdClass;

/**
 * Class CensoUfResource
 * @package ECidade\Educacao\Escola\Resource
 */
class CensoUfResource
{
    /**
     * @param CensoUf[] $ufs
     * @return array
     */
    public static function toArray(array $ufs)
    {
        $dados = [];
        foreach ($ufs as $censoUf) {
            $dados[] = static::toObject($censoUf);
        }

        return $dados;
    }

    /**
     * @param CensoUf $censoUf
     * @return stdClass
     */
    public static function toObject(CensoUf $censoUf)
    {
        return (object) [
            "codigo" => $censoUf->getCodigo(),
            "uf" => $censoUf->getSigla(),
            "estado" => $censoUf->getNome()
        ];
    }
}
