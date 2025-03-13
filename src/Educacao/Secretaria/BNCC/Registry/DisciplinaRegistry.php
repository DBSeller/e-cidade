<?php


namespace ECidade\Educacao\Secretaria\BNCC\Registry;

use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\BNCC\Repository\DisciplinaRepository;
use Exception;

/**
 * Class DisciplinaRegistry
 * @package ECidade\Educacao\Secretaria\BNCC\Registry
 */
class DisciplinaRegistry
{
    /**
     * @var Disciplina[]
     */
    private static $storage = array();

    /**
     * @param Disciplina $disciplina
     */
    public static function set(Disciplina $disciplina)
    {
        self::$storage[$disciplina->getCodigo()] = $disciplina;
    }

    /**
     * @param $id
     * @return Disciplina|null
     * @throws Exception
     */
    public static function get($id)
    {
        if (!array_key_exists($id, self::$storage)) {
            $disciplina = DisciplinaRepository::find($id);
            if (is_null($disciplina)) {
                return null;
            }

            self::set($disciplina);
        }

        return self::$storage[$id];
    }
}
