<?php

namespace ECidade\Enum\Educacao\Secretaria;

use ECidade\Enum\Enum;
use Exception;

class TiposCursoItinFormacaoTecnicaProfissionalEnum extends Enum
{
    const CURSO_TECNICO = 1;
    const QUALIFICACAO_PROFISSIONAL_TECNICA = 2;
    private static $descricoes = array(
            self::CURSO_TECNICO => "Curso técnico",
            self::QUALIFICACAO_PROFISSIONAL_TECNICA => "Qualificação profissional técnica"
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
