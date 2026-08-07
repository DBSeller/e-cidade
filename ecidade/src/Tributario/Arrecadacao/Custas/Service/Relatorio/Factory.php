<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 05/06/18
 * Time: 15:58
 */

namespace ECidade\Tributario\Arrecadacao\Custas\Service\Relatorio;

abstract class Factory
{
    /**
     * @param $tipoDebito
     * @param $cadTipo
     * @param $debitos
     * @return Inicial|Parcelamento
     * @throws \Exception
     */
    public static function create($tipoDebito, $cadTipo, $debitos)
    {
        switch ($cadTipo) {
            case 18:
                return new Inicial($tipoDebito, $cadTipo, $debitos);
            case 13:
                return new Parcelamento($tipoDebito, $cadTipo, $debitos);
            default:
                throw new \Exception("Não foi encontrado relatório de custas para este tipo de débito.");
        }
    }
}
