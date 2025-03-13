<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 03/05/2019
 * Time: 09:40
 */

namespace ECidade\Educacao\Escola\Censo\Helpers;

/**
 * Class Pessoa
 * @package ECidade\Educacao\Escola\Censo\Helpers
 */
class Pessoa
{
    const CODIGO_PROFISSIONAL = 333;
    const CODIGO_ALUNO = 666;

    /**
     * código do CPF do profissional
     * @param integer $vinculoEscola
     * @return string
     */
    public static function buildCodigoProfissional($vinculoEscola)
    {
        return self::CODIGO_PROFISSIONAL . $vinculoEscola;
    }

    /**
     * código do vínculo do aluno com a escola
     * @param integer $codigo
     * @return string
     */
    public static function buildCodigoAluno($codigo)
    {
        return self::CODIGO_ALUNO . $codigo;
    }

    public static function decodeCodigoProfissional($codigo)
    {
        $len = strlen(self::CODIGO_PROFISSIONAL);

        return substr($codigo, $len);
    }

    public static function decodeCodigoAluno($codigo)
    {
        $len = strlen(self::CODIGO_ALUNO);

        return substr($codigo, $len);
    }

    public static function isAluno($codigo)
    {
        $x = self::CODIGO_ALUNO;
        $regex = "/^{$x}/";
        return self::match($regex, $codigo);
    }

    private static function match($pattern, $codigoTurma)
    {
        return preg_match($pattern, $codigoTurma) === 1;
    }

    /**
     * @param $codigo
     * @return false|string
     */
    public static function decode($codigo)
    {
        return substr($codigo, 3);
    }
}
