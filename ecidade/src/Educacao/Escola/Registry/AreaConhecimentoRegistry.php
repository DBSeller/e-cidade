<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\AreaConhecimento;
use ECidade\Educacao\Escola\Repository\AreaConhecimentoRepository;
use Exception;

/**
 * Class AreaConhecimentoRegistry
 * @package ECidade\Educacao\Escola\Registry
 */
class AreaConhecimentoRegistry
{
    /**
     * @var AreaConhecimento[]
     */
    private static $storage = array();

    /**
     * @param AreaConhecimento $areaConhecimento
     */
    public static function set(AreaConhecimento $areaConhecimento)
    {
        self::$storage[$areaConhecimento->getCodigo()] = $areaConhecimento;
    }

    /**
     * @param $key
     * @return AreaConhecimento|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $areaConhecimento = AreaConhecimentoRepository::find($key);
            if (is_null($areaConhecimento)) {
                return null;
            }

            self::set($areaConhecimento);
        }

        return self::$storage[$key];
    }
}
