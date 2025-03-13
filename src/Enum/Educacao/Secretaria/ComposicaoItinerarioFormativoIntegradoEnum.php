<?php

namespace ECidade\Enum\Educacao\Secretaria;

use ECidade\Enum\Enum;
use Exception;

class ComposicaoItinerarioFormativoIntegradoEnum extends Enum
{
    const LINGUAGEM_SUAS_TECNOLOGIAS = 1;
    const MATEMATICA_SUAS_TECNOLOGIAS = 2;
    const CIENCIAS_NATUREZA_SUAS_TECNOLOGIAS = 3;
    const CIENCIAS_HUMANAS_SUAS_TECNOLOGIAS = 4;
    const FORMACAO_TECNICA_PROFISSIONAL = 5;
    private static $descricoes = array(
            self::LINGUAGEM_SUAS_TECNOLOGIAS => "Linguagens e suas tecnologias",
            self::MATEMATICA_SUAS_TECNOLOGIAS => "Matemática e suas tecnologias",
            self::CIENCIAS_NATUREZA_SUAS_TECNOLOGIAS => "Ciências da natureza e suas tecnologias",
            self::CIENCIAS_HUMANAS_SUAS_TECNOLOGIAS => "Ciências humanas e sociais aplicadas",
            self::FORMACAO_TECNICA_PROFISSIONAL => "Formação técnica e profissional"
    );

    /**
     * @return string
     * @throws Exception
     */
    public function descricao()
    {
        if (empty(self::getAll()[$this->getValue()])) {
            throw new Exception('Composição do Itinerario não encontrada.');
        }

        return self::getAll()[$this->getValue()];
    }

    public static function getAll()
    {
        return self::$descricoes;
    }
}
