<?php

namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\BaseCurricular;
use ECidade\Educacao\Escola\Repository\BaseCurricularRepository;
use Exception;

/**
 * Class BaseCurricularRegistry
 * @package ECidade\Educacao\Escola\Registry
 */
class BaseCurricularRegistry
{

    /**
     * @var BaseCurricular[]
     */
    private static $storage = array();

    /**
     * @param BaseCurricular $base
     */
    public static function set(BaseCurricular $base)
    {
        self::$storage[$base->getCodigo()] = $base;
    }

    /**
     * @param $key
     * @return BaseCurricular|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $aluno = BaseCurricularRepository::find($key);
            if (is_null($aluno)) {
                return null;
            }

            self::set($aluno);
        }

        return self::$storage[$key];
    }
}
