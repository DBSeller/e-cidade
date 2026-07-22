<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Repository\AreaProcedimentoRepository;
use Exception;

/**
 * Class AreaProcedimentoRegistry
 * @package ECidade\Educacao\Escola\Registry
 */
class AreaProcedimentoRegistry
{
    /**
     * @var AreaProcedimento[]
     */
    private static $storage = array();

    /**
     * @param AreaProcedimento $areaProcedimento
     */
    public static function set(AreaProcedimento $areaProcedimento)
    {
        self::$storage[$areaProcedimento->getCodigo()] = $areaProcedimento;
    }

    /**
     * @param $key
     * @return AreaProcedimento|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $areaConhecimento = AreaProcedimentoRepository::find($key);
            if (is_null($areaConhecimento)) {
                return null;
            }

            self::set($areaConhecimento);
        }

        return self::$storage[$key];
    }
}
