<?php

namespace ECidade\Enum\Saude\ESF;

use ECidade\Enum\Enum;

class EstrategiaVacinacaoEnum extends Enum
{
    const ROTINA = 1;
    const ESPECIAL = 2;
    const BLOQUEIO = 3;
    const INTENSIFICACAO = 4;
    const CAMPANHA = 5;
    const SOROTERAPIA = 6;
    const MULTIVACINACAO = 7;

    public function name()
    {
        $data = [
            self::ROTINA => 'Rotina',
            self::ESPECIAL => 'Especial',
            self::BLOQUEIO => 'Bloqueio',
            self::INTENSIFICACAO => 'Intensificação',
            self::CAMPANHA => 'Campanha',
            self::SOROTERAPIA => 'Soroterapia',
            self::MULTIVACINACAO => 'Multivacinação'
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Opção inválida.');
        }

        return $data[$this->getValue()];
    }
}
