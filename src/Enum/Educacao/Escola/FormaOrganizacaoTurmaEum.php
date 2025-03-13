<?php

namespace ECidade\Enum\Educacao\Escola;

use ECidade\Enum\Enum;
use Exception;

class FormaOrganizacaoTurmaEum extends Enum
{
    const SERIE_ANO = 1;
    const PERIODOS_SEMESTRAIS = 2;
    const CICLOS = 3;
    const GRUPOS_NAO_SERIADOS = 4;
    const MODULOS = 5;
    const ALTERNANCIA_REGULAR = 6;
    private static $descricoes = [
            self::SERIE_ANO => "Série/ano (séries anuais)",
            self::PERIODOS_SEMESTRAIS => "Períodos semestrais",
            self::CICLOS => "Ciclo(s)",
            self::GRUPOS_NAO_SERIADOS => "Grupos não seriados com base na idade ou competência",
            self::MODULOS => "Módulos",
            self::ALTERNANCIA_REGULAR => "Alternância regular de períodos de estudos",
    ];

    /**
     * @return string
     * @throws Exception
     */
    public function descricao()
    {
        if (empty(self::getAll()[$this->getValue()])) {
            throw new Exception('Forma de Organização não encontrada.');
        }

        return self::getAll()[$this->getValue()];
    }

    public static function getAll()
    {
        return self::$descricoes;
    }
}
