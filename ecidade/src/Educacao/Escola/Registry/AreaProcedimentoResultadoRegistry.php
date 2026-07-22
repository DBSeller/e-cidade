<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use ECidade\Educacao\Escola\Repository\AreaProcedimentoResultadoRepository;
use Exception;

/**
 * Class AreaProcedimentoResultadoRegistry
 * @package ECidade\Educacao\Escola\Registry
 */
class AreaProcedimentoResultadoRegistry
{
    /**
     * @var AreaProcedimentoResultado[]
     */
    private static $storage = array();

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     */
    public static function set(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        self::$storage[$areaProcedimentoResultado->getCodigo()] = $areaProcedimentoResultado;
    }

    /**
     * @param $key
     * @return AreaProcedimentoResultado|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $areaConhecimento = AreaProcedimentoResultadoRepository::find($key);
            if (is_null($areaConhecimento)) {
                return null;
            }

            self::set($areaConhecimento);
        }

        return self::$storage[$key];
    }
}
