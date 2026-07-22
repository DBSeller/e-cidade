<?php

namespace ECidade\Tributario\Issqn\Enum;

use ECidade\Enum\Enum;

class IssCategoriaEnum extends Enum
{
    const MICRO_EMPRESA = '1';
    const PEQUENO_PORTE = '2';
    const MEI = '3';
    const EIRELI = '4';
    const SOCIO = '5';

    /**
     * @return array
     */
    public static function descricoes()
    {
        return array(
            static::MICRO_EMPRESA => 'Micro Empresa',
            static::PEQUENO_PORTE => 'Empresa de pequeno porte',
            static::MEI => 'MEI',
            static::EIRELI => 'EIRELI',
            static::SOCIO => 'Soc. Profissionais'
        );
    }
}
