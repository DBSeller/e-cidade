<?php

namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\Aluno;
use ECidade\Educacao\Escola\Repository\AlunoRepository;
use Exception;

class AlunoRegistry
{

    /**
     * @var Aluno[]
     */
    private static $storage = array();

    /**
     * @param Aluno $aluno
     */
    public static function set(Aluno $aluno)
    {
        self::$storage[$aluno->getCodigo()] = $aluno;
    }

    /**
     * @param $key
     * @return Aluno|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $aluno = AlunoRepository::find($key);
            if (is_null($aluno)) {
                return null;
            }

            self::set($aluno);
        }

        return self::$storage[$key];
    }
}
