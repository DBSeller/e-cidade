<?php

namespace ECidade\Financeiro\Orcamento\Registry;

use ECidade\Financeiro\Orcamento\Model\Recurso;
use ECidade\Financeiro\Orcamento\Repository\FonteRecursoRepository;
use Exception;

/**
 * Class RecursoRegistry
 * @package ECidade\Financeiro\Orcamento\Registry
 */
class RecursoRegistry
{
    /**
     * @var Recurso[]
     */
    private static $storage = array();

    /**
     * @param Recurso $complemento
     */
    public static function set(Recurso $complemento)
    {
        self::$storage[$complemento->getCodigo()] = $complemento;
    }

    /**
     * @param $key
     * @return Recurso|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $complemento = FonteRecursoRepository::find($key);
            if (is_null($complemento)) {
                return null;
            }

            self::set($complemento);
        }

        return self::$storage[$key];
    }
}
