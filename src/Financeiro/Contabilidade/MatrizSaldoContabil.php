<?php

namespace ECidade\Financeiro\Contabilidade;

use DBException;

/**
 * Class MatrizSaldoContabil
 * @package ECidade\Financeiro\Contabilidade
 */
class MatrizSaldoContabil
{
    public static function foiProcessada($ano, $mes = 12)
    {
        $sql = "
            SELECT 1
            FROM conplanoatributosaldo
            WHERE c125_anousu = {$ano} AND c125_mesusu = {$mes} LIMIT 1
        ";

        if (!$resultado = db_query($sql)) {
            $msg = 'Não foi possível verificar se a matriz de saldo contábil foi processada. ';
            $msg .= 'Por favor contate o administrador do sistema.';
            throw new DBException($msg);
        }

        return pg_num_rows($resultado) > 0;
    }

    /**
     * Retorna os atributos que são processados no siconfi
     * @return array
     */
    public static function getAtributos($exercicio = 2021)
    {
        if ($exercicio <= 2021) {
            return array(1, 2, 3, 4, 5, 6, 7, 50, 51, 53);
        } else {
            return array(1, 2, 3, 4, 5, 6, 7, 50, 51, 60);
        }
    }
}
