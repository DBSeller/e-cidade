<?php


namespace ECidade\Educacao\Escola\Registry;

use ECidade\Educacao\Escola\Model\HabilidadeDesenvolvida;
use ECidade\Educacao\Escola\Repository\HabilidadeDesenvolvidaRepository;
use Exception;

class HabilidadeDesenvolvidaRegistry
{
    /**
     * @var HabilidadeDesenvolvida[]
     */
    private static $storage = array();

    /**
     * @param HabilidadeDesenvolvida $habilidadeDesenvolvida
     */
    public static function set(HabilidadeDesenvolvida $habilidadeDesenvolvida)
    {
        self::$storage[$habilidadeDesenvolvida->getCodigo()] = $habilidadeDesenvolvida;
    }

    /**
     * @param $key
     * @return HabilidadeDesenvolvida|null
     * @throws Exception
     */
    public static function get($key)
    {
        if (!array_key_exists($key, self::$storage)) {
            $repository = new HabilidadeDesenvolvidaRepository();
            $aluno = $repository->find($key);
            if (is_null($aluno)) {
                return null;
            }

            self::set($aluno);
        }

        return self::$storage[$key];
    }
}
