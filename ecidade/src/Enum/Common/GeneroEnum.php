<?php

namespace ECidade\Enum\Common;

use ECidade\Enum\Enum;
use Exception;

class GeneroEnum extends Enum
{
    const MASCULINO = 'M';
    const FEMININO = 'F';

    /**
     * Retorna descricao do Tipo
     *
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::MASCULINO => 'Masculino',
            self::FEMININO => 'Feminino'
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Tipo de genero não encontrado.');
        }

        return $data[$this->getValue()];
    }
}
