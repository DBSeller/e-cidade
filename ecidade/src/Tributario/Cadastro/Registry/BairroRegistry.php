<?php

namespace ECidade\Tributario\Cadastro\Registry;

use ECidade\Tributario\Cadastro\Model\Bairro;
use ECidade\Tributario\Cadastro\Repository\BairroRepository;
use Exception;

/**
 * Class BairroRegistry
 * @package ECidade\Tributario\Cadastro\Registry
 */
class BairroRegistry
{
    /**
     * @var Bairro[]
     */
    private static $storage = array();

    /**
     * @param Bairro $bairro
     */
    public static function set(Bairro $bairro)
    {
        self::$storage[$bairro->getCodigo()] = $bairro;
    }
    /**
     * @param $key
     * @return Bairro
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $bairro = BairroRepository::find($key);
            if (is_null($bairro)) {
                return null;
            }

            self::set($bairro);
        }

        return self::$storage[$key];
    }
}
