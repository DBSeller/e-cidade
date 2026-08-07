<?php

namespace ECidade\Enum\Educacao\Escola;

use ECidade\Enum\Enum;
use Exception;

class ZonaResidenciaEnum extends Enum
{
    const URBANA = 1;
    const RURAL = 2;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::URBANA => "Urbana",
            self::RURAL => "Rural",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Zona de residência não encontrada.');
        }

        return $data[$this->getValue()];
    }
}
