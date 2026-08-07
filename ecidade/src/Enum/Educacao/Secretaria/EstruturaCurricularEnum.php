<?php


namespace ECidade\Enum\Educacao\Secretaria;

use ECidade\Enum\Enum;
use Exception;

class EstruturaCurricularEnum extends Enum
{
    const FORMACAO_GERAL_BASICA = 0;
    const ITINERARIO_FORMATIVO = 1;
    const NAO_APLICA = 2;
    private static $descricoes = array(
            self::FORMACAO_GERAL_BASICA => "Formação Geral Básica",
            self::ITINERARIO_FORMATIVO => "Itinerário Formativo",
            self::NAO_APLICA => "Não se aplica"
    );

    /**
     * @return string
     * @throws Exception
     */
    public function descricao()
    {
        if (empty(self::getAll()[$this->getValue()])) {
            throw new Exception('Estrutura curricular não encontrada.');
        }

        return self::getAll()[$this->getValue()];
    }

    public static function getAll()
    {
        return self::$descricoes;
    }
}
