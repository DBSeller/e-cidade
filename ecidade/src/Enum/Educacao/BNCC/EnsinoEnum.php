<?php


namespace ECidade\Enum\Educacao\BNCC;

use ECidade\Enum\Educacao\Escola\TipoEnsinoEnum;
use ECidade\Enum\Enum;
use Exception;

/**
 * Class EnsinoEnum
 * @package ECidade\Enum\Educacao\BNCC
 */
class EnsinoEnum extends Enum
{
    const ENSINO_INFANTIL = 'EI';
    const ENSINO_FUNDAMENTAL = 'EF';
    const ENSINO_MEDIO = 'EM';

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::ENSINO_INFANTIL => "Ensino Infantil",
            self::ENSINO_FUNDAMENTAL => "Ensino Fundamental",
            self::ENSINO_MEDIO => "Ensino Médio",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Ensino não encontrado.');
        }

        return $data[$this->getValue()];
    }

    /**
     * @return TipoEnsinoEnum
     * @throws Exception
     */
    public function getTipoEnsino()
    {
        switch ($this->value) {
            case self::ENSINO_INFANTIL:
                return new TipoEnsinoEnum(TipoEnsinoEnum::ENSINO_INFANTIL);
                break;
            case self::ENSINO_FUNDAMENTAL:
                return new TipoEnsinoEnum(TipoEnsinoEnum::ENSINO_FUNDAMENTAL);
                break;
            case self::ENSINO_MEDIO:
                return new TipoEnsinoEnum(TipoEnsinoEnum::ENSINO_MEDIO);
                break;
        }

        throw new Exception("Ensino não mapeado.");
    }
}
