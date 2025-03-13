<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Repository\AreaProcedimentoAvaliacaoRepository;
use Exception;

/**
 * Class AreaProcedimentoAvaliacaoRegistry
 * @package ECidade\Educacao\Escola\Registry
 */
class AreaProcedimentoAvaliacaoRegistry
{
    /**
     * @var AreaProcedimentoAvaliacao[]
     */
    private static $storage = array();

    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     */
    public static function set(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        self::$storage[$areaProcedimentoAvaliacao->getCodigo()] = $areaProcedimentoAvaliacao;
    }

    /**
     * @param $key
     * @return AreaProcedimentoAvaliacao|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $areaConhecimento = AreaProcedimentoAvaliacaoRepository::find($key);
            if (is_null($areaConhecimento)) {
                return null;
            }

            self::set($areaConhecimento);
        }

        return self::$storage[$key];
    }
}
