<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\ComponenteCurricular;
use ECidade\Educacao\Escola\Repository\ComponenteCurricularRepository;
use Exception;

/**
 * Class ComponenteCurricularRegistry
 * @package ECidade\Educacao\Escola\Registry
 */
class ComponenteCurricularRegistry
{
    /**
     * @var ComponenteCurricular[]
     */
    private static $storage = array();

    /**
     * @param ComponenteCurricular $componenteCurricular
     */
    public static function set(ComponenteCurricular $componenteCurricular)
    {
        self::$storage[$componenteCurricular->getCodigo()] = $componenteCurricular;
    }

    /**
     * @param $key
     * @return ComponenteCurricular|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $componenteCurricular = ComponenteCurricularRepository::find($key);
            if (is_null($componenteCurricular)) {
                return null;
            }

            self::set($componenteCurricular);
        }

        return self::$storage[$key];
    }
}
