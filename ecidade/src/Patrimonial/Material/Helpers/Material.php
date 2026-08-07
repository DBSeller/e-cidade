<?php

namespace ECidade\Patrimonial\Material\Helpers;

/**
 * Class Material
 * @package Ecidade\Patrimonial\Material\Helpers
 */
class Material
{
    /**
     * @param $quantidade
     * @return float|integer
     */
    public static function arredondarQuantidade($quantidade)
    {
        if ((float)$quantidade === floor($quantidade)) {
            return (int)$quantidade;
        }

        $quantidade = rtrim($quantidade, 0);

        $decimais = explode('.', $quantidade);
        if (isset($decimais[1]) && strlen($decimais[1]) > 3) {
            $quantidade = number_format($quantidade, 3, '.', '');
            $quantidade = self::arredondarQuantidade($quantidade);
        }

        return $quantidade;
    }
}
