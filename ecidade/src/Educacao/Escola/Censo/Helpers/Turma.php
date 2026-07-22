<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 03/05/2019
 * Time: 10:25
 */

namespace ECidade\Educacao\Escola\Censo\Helpers;

use Exception;

class Turma
{
    const TURMA_REGULAR = 111;
    const TURMA_AC = 222;
    const TURMA_UNIFICADA = 333;

    public static function buildCodigoTurmaRegular($codigo)
    {
        return self::TURMA_REGULAR . $codigo;
    }

    public static function buildCodigoTurmaAC($codigo)
    {
        return self::TURMA_AC . $codigo;
    }

    public static function buildCodigoTurmaUnificada($codigo)
    {
        return self::TURMA_UNIFICADA . $codigo;
    }

    public static function decodeCodigoTurma($codigo)
    {
        $len = strlen(self::TURMA_REGULAR);
        if ($len != strlen(self::TURMA_AC) || $len != strlen(self::TURMA_UNIFICADA)) {
            throw new Exception(sprintf(
                'O tamanho dos cуdigos das turmas estб diferente. %s',
                'Nгo й possнvel utilizar essa funзгo genйrica.'
            ));
        }

        return substr($codigo, $len);
    }

    public static function decodeCodigoTurmaRegular($codigo)
    {
        $len = strlen(self::TURMA_REGULAR);

        return substr($codigo, $len);
    }

    public static function decodeCodigoTurmaAC($codigo)
    {
        $len = strlen(self::TURMA_AC);

        return substr($codigo, $len);
    }

    public static function decodeCodigoTurmaUnificada($codigo)
    {
        $len = strlen(self::TURMA_UNIFICADA);

        return substr($codigo, $len);
    }

    public static function isTurmaAC($codigo)
    {
        $x = self::TURMA_AC;
        $regex = "/^{$x}/";
        return self::match($regex, $codigo);
    }

    public static function isTurmaUnificada($codigo)
    {
        $x = self::TURMA_UNIFICADA;
        $regex = "/^{$x}/";
        return self::match($regex, $codigo);
    }

    private static function match($pattern, $codigoTurma)
    {
        return preg_match($pattern, $codigoTurma) === 1;
    }
}
