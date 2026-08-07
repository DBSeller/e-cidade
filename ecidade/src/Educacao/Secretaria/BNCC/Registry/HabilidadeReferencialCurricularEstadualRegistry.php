<?php


namespace ECidade\Educacao\Secretaria\BNCC\Registry;

use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeReferencialCurricularEstadual;
use ECidade\Educacao\Secretaria\BNCC\Repository\HabilidadeReferencialCurricularEstadualRepository;
use Exception;

/**
 * Class HabilidadeReferencialCurricularEstadualRegistry
 * @package ECidade\Educacao\Secretaria\BNCC\Registry
 */
class HabilidadeReferencialCurricularEstadualRegistry
{
    /**
     * @var HabilidadeReferencialCurricularEstadual[]
     */
    private static $storage = array();

    /**
     * @param HabilidadeReferencialCurricularEstadual $habilidadeReferencial
     */
    public static function set(HabilidadeReferencialCurricularEstadual $habilidadeReferencial)
    {
        self::$storage[$habilidadeReferencial->getCodigo()] = $habilidadeReferencial;
    }

    /**
     * @param $id
     * @return HabilidadeReferencialCurricularEstadual|null
     * @throws Exception
     */
    public static function get($id)
    {
        if (!array_key_exists($id, self::$storage)) {
            $repository = new HabilidadeReferencialCurricularEstadualRepository();
            $habilidadeReferencial = $repository->find($id);
            if (is_null($habilidadeReferencial)) {
                return null;
            }

            self::set($habilidadeReferencial);
        }

        return self::$storage[$id];
    }
}
