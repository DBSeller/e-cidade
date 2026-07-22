<?php

namespace ECidade\Educacao\Secretaria\BNCC\Registry;

use ECidade\Educacao\Secretaria\BNCC\Model\Etapa;
use ECidade\Educacao\Secretaria\BNCC\Repository\EtapaRepository;
use Exception;

/**
 * Class EtapaRegistry
 * @package ECidade\Educacao\Secretaria\BNCC\Registry
 */
class EtapaRegistry
{
    /**
     * @var Etapa[]
     */
    private static $storage = array();

    /**
     * @param Etapa $etapa
     */
    public static function set(Etapa $etapa)
    {
        self::$storage[$etapa->getCodigo()] = $etapa;
    }

    /**
     * @param $id
     * @return Etapa|null
     * @throws Exception
     */
    public static function get($id)
    {
        if (!array_key_exists($id, self::$storage)) {
            $etapa = EtapaRepository::find($id);
            if (is_null($etapa)) {
                return null;
            }

            self::set($etapa);
        }

        return self::$storage[$id];
    }
}
