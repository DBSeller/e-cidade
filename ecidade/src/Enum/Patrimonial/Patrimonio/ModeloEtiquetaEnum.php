<?php

namespace ECidade\Enum\Patrimonial\Patrimonio;

use ECidade\Enum\Enum;

class ModeloEtiquetaEnum extends Enum
{
    const MODELO_UM = 1;
    const MODELO_DOIS = 2;

    public function name()
    {
        $nomes = [
            self::MODELO_UM => 'A4351_MODELO01',
            self::MODELO_DOIS => 'A4351_MODELO02'
        ];

        if (empty($nomes[$this->getValue()])) {
            throw new \Exception('Opção Inválida. Modelo não configurado');
        }
        return $nomes[$this->getValue()];
    }
}
