<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\ConteudoDesenvolvido;
use ECidade\Educacao\Escola\Repository\ConteudoDesenvolvidoRepository;
use Exception;

class ConteudoDesenvolvidoRegistry
{
    /**
     * @var ConteudoDesenvolvido[]
     */
    private static $storage = array();

    /**
     * @param ConteudoDesenvolvido $conteudoDesenvolvido
     */
    public static function set(ConteudoDesenvolvido $conteudoDesenvolvido)
    {
        self::$storage[$conteudoDesenvolvido->getCodigo()] = $conteudoDesenvolvido;
    }

    /**
     * @param $key
     * @return ConteudoDesenvolvido|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $componenteCurricular = ConteudoDesenvolvidoRepository::find($key);
            if (is_null($componenteCurricular)) {
                return null;
            }

            self::set($componenteCurricular);
        }

        return self::$storage[$key];
    }
}
