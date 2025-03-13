<?php


namespace ECidade\Enum\Educacao\Censo;

use ECidade\Enum\Enum;
use Exception;

class TiposPosGraduacaoEnum extends Enum
{
    const ESPECIALIZACAO = 1;
    const MESTRADO = 2;
    const DOUTORADO = 3;

    private static $descricoes = [
            self::ESPECIALIZACAO => "Especialização",
            self::MESTRADO => "Mestrado",
            self::DOUTORADO => "Doutorado",
    ];

    /**
     * @return string
     * @throws Exception
     */
    public function descricao()
    {
        if (empty(self::getAll()[$this->getValue()])) {
            throw new Exception('Área não encontrada.');
        }

        return self::getAll()[$this->getValue()];
    }

    public static function getAll()
    {
        return self::$descricoes;
    }
}
