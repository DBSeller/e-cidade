<?php


namespace ECidade\Tributario\Cadastro\Registry;

use ECidade\Tributario\Cadastro\Model\Rua;
use ECidade\Tributario\Cadastro\Repository\RuaRepository;
use Exception;

/**
 * Class RuaRegistry
 * @package ECidade\Tributario\Cadastro\Registry
 */
class RuaRegistry
{
    /**
     * @var Rua[]
     */
    private static $storage = array();

    public static function set(Rua $ruas)
    {
        self::$storage[$ruas->getCodigo()] = $ruas;
    }

    /**
     * @param $key
     * @return Rua
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $rua = RuaRepository::find($key);
            if (is_null($rua)) {
                return null;
            }

            self::set($rua);
        }

        return self::$storage[$key];
    }
}
