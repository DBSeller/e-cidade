<?php


namespace ECidade\Educacao\Escola\Resource;

use Escola;
use stdClass;

/**
 * Class EscolaResource
 * @package ECidade\Educacao\Escola\Resource
 */
class EscolaResource
{
    /**
     * @param Escola $escola
     * @return stdClass
     */
    public static function toObject(Escola $escola)
    {
        return (object) [
            'codigo' => $escola->getCodigo(),
            'nome' => $escola->getNome(),
        ];
    }
}
