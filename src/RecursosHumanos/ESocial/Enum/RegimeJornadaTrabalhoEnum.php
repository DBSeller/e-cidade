<?php

namespace ECidade\RecursosHumanos\ESocial\Enum;

/**
 * Class RegimeJornadaEmpregadoEnum
 * @package ECidade\RecursosHumanos\ESocial\Enum
 */
final class RegimeJornadaTrabalhoEnum
{
    /**
     * @var int
     */
    const NENHUM = 0;
    /**
     * @var int
     */
    const SUBMETIDOS_A_HORARIO_DE_TRABALHO = 1;
    /**
     * @var int
     */
    const ATIVIDADE_EXTERNA = 2;
    /**
     * @var int
     */
    const FUNCOES = 3;
    /**
     * @var int
     */
    const TELETRABALHO = 4;

    /**
     * @var string[]
     */
    private static $descricoes = array(
        self::NENHUM => 'Nenhum',
        self::SUBMETIDOS_A_HORARIO_DE_TRABALHO => '1 - Submetidos a Horário de Trabalho (Cap. II da CLT)',
        self::ATIVIDADE_EXTERNA => '2 - Atividade Externa especificada no Inciso I do Art. 62 da CLT',
        self::FUNCOES => '3 - Funções especificadas no Inciso II do Art. 62 da CLT',
        self::TELETRABALHO => '4 - Teletrabalho, previsto no Inciso III do Art. 62 da CLT'
    );

    /**
     * @param int $chave
     * @return string
     */
    public static function descricao($chave)
    {
        return self::$descricoes[(int)$chave];
    }

    /**
     * @return string[]
     */
    public static function todas()
    {
        return self::$descricoes;
    }

    /**
     * @param int $chave
     * @return bool
     */
    public static function existe($chave)
    {
        return array_key_exists((int)$chave, self::$descricoes);
    }
}
