<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\Ensino;
use ECidade\Educacao\Escola\Repository\EnsinoRepository;
use Exception;

/**
 * Class EnsinoRegistry
 * @package ECidade\Educacao\Escola\Registry
 */
class EnsinoRegistry
{
    /**
     * @var Ensino[]
     */
    private static $storage = array();

    /**
     * @param Ensino $ensino
     */
    public static function set(Ensino $ensino)
    {
        self::$storage[$ensino->getCodigo()] = $ensino;
    }

    /**
     * @param $key
     * @return Ensino|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $aluno = EnsinoRepository::find($key);
            if (is_null($aluno)) {
                return null;
            }

            self::set($aluno);
        }

        return self::$storage[$key];
    }
}
