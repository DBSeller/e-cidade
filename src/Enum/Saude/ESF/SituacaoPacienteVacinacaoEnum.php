<?php

namespace ECidade\Enum\Saude\ESF;

use ECidade\Enum\Enum;

class SituacaoPacienteVacinacaoEnum extends Enum
{
    const COMUNICANTE_HANSENIASE = 1;
    const GESTANTE = 2;
    const PUERPERA = 3;
    const VIAJANTE = 4;

    /**
     * @return [type]
     */
    public function column()
    {
        $data = [
            self::COMUNICANTE_HANSENIASE => 'psf20_comunicante_hanseniase',
            self::GESTANTE => 'psf20_gestante',
            self::PUERPERA => 'psf20_puerpera',
            self::VIAJANTE => 'psf20_viajante'
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Opção inválida.');
        }

        return $data[$this->getValue()];
    }

    /**
     * @return [type]
     */
    public function name()
    {
        $data = [
            self::COMUNICANTE_HANSENIASE => 'Comunicante Hanseníase',
            self::GESTANTE => 'Gestante',
            self::PUERPERA => 'Puérpera',
            self::VIAJANTE => 'Viajante'
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Opção inválida.');
        }

        return $data[$this->getValue()];
    }
}
