<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 26/04/2019
 * Time: 12:32
 */

namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\CursoFormacao;
use ECidade\Educacao\Escola\Repository\CursoFormacaoRepository;

class CursoFormacaoRegistry
{
    /**
     * @var CursoFormacao[]
     */
    private static $storage = array();

    /**
     * @param CursoFormacao $cursoFormacao
     */
    public static function set(CursoFormacao $cursoFormacao)
    {
        self::$storage[$cursoFormacao->getCodigo()] = $cursoFormacao;
    }

    /**
     * @param $key
     * @return CursoFormacao|null
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $cursoFormacao = CursoFormacaoRepository::find($key);
            if (is_null($cursoFormacao)) {
                return null;
            }

            self::set($cursoFormacao);
        }

        return self::$storage[$key];
    }
}
