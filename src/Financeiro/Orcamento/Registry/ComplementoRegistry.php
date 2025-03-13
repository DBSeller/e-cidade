<?php


namespace ECidade\Financeiro\Orcamento\Registry;

use ECidade\Financeiro\Orcamento\Model\Complemento;
use ECidade\Financeiro\Orcamento\Repository\ComplementoRepository;
use Exception;

/**
 * Class ComplementoRegistry
 * @package ECidade\Financeiro\Orcamento\Registry
 */
class ComplementoRegistry
{
    /**
     * @var Complemento[]
     */
    private static $storage = array();

    /**
     * @param Complemento $complemento
     */
    public static function set(Complemento $complemento)
    {
        self::$storage[$complemento->getCodigo()] = $complemento;
    }

    /**
     * @param $key
     * @return Complemento|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $complemento = ComplementoRepository::find($key);
            if (is_null($complemento)) {
                return null;
            }

            self::set($complemento);
        }

        return self::$storage[$key];
    }
}
