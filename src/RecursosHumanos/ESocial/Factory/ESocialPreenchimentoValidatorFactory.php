<?php

namespace ECidade\RecursosHumanos\ESocial\Factory;

use ECidade\RecursosHumanos\ESocial\Validators\ServidorPreenchimentoValidator;

class ESocialPreenchimentoValidatorFactory
{
    public static function getByIdentificador($tipo)
    {
        switch ($tipo) {
            case 's22002190v23':
                return new ServidorPreenchimentoValidator();
        }

        return null;
    }
}
