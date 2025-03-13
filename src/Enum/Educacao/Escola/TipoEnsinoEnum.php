<?php


namespace ECidade\Enum\Educacao\Escola;

use ECidade\Enum\Educacao\BNCC\EnsinoEnum;
use ECidade\Enum\Enum;
use Exception;

/**
 * Class TipoEnsinoEnum
 * @package ECidade\Enum\Educacao\Escola
 */
class TipoEnsinoEnum extends Enum
{
    const ENSINO_INFANTIL = 1;
    const ENSINO_FUNDAMENTAL = 2;
    const ENSINO_MEDIO = 3;
    const ENSINO_PROFISSIONAL = 4;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::ENSINO_INFANTIL => "Educação Infantil",
            self::ENSINO_FUNDAMENTAL => "Ensino Fundamental",
            self::ENSINO_MEDIO => "Ensino Médio",
            self::ENSINO_PROFISSIONAL => "Ensino Profissional",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Tipo de ensino não encontrada.');
        }

        return $data[$this->getValue()];
    }

    /**
     * Retorna os valores com os nomes
     * @return array
     * @throws Exception
     */
    public static function toArrayWithNames()
    {
        $tipos = self::values();
        $return = array();
        foreach ($tipos as $tipo) {
            $return[] = array(
                'value' => $tipo->value(),
                'name' => $tipo->name()
            );
        }

        return $return;
    }

    /**
     * @return EnsinoEnum
     * @throws Exception
     */
    public function getEnsinoBncc()
    {
        switch ($this->value) {
            case self::ENSINO_INFANTIL:
                return new EnsinoEnum(EnsinoEnum::ENSINO_INFANTIL);
                break;
            case self::ENSINO_FUNDAMENTAL:
                return new EnsinoEnum(EnsinoEnum::ENSINO_FUNDAMENTAL);
                break;
            case self::ENSINO_MEDIO:
                return new EnsinoEnum(EnsinoEnum::ENSINO_MEDIO);
                break;
        }

        throw new Exception("Ensino não mapeado.");
    }

    public function isInfantil()
    {
        return $this->value === self::ENSINO_INFANTIL;
    }
}
