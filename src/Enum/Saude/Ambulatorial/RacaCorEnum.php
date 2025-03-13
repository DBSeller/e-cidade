<?php

namespace ECidade\Enum\Saude\Ambulatorial;

use ECidade\Enum\Enum;

class RacaCorEnum extends Enum
{
    const BRANCA = '1';
    const PRETA = '2';
    const PARDA = '3';
    const AMARELA = '4';
    const INDIGENA = '5';

    /**
     * @return string
     * @throws \Exception
     */
    public function name()
    {
        $data = [
            self::BRANCA => 'BRANCA',
            self::PRETA => 'PRETA',
            self::PARDA => 'PARDA',
            self::AMARELA => 'AMARELA',
            self::INDIGENA => 'INDÍGENA'
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Opção de raça inválida.');
        }

        return $data[$this->getValue()];
    }
}
