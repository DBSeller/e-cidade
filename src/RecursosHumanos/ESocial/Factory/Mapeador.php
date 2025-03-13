<?php

namespace ECidade\RecursosHumanos\ESocial\Factory;

use ECidade\RecursosHumanos\ESocial\Mapeadores\Relatorios\TrabalhadorSemVinculoMapeador;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Relatorios\RubricaMapeador;
use Exception;

/**
 * Class FormatterFactory
 * @package ECidade\RecursosHumanos\ESocial\Factory
 */
class Mapeador
{

    /**
     * @param $tipo
     * @return ContribuinteFormatter|AdmissaoPreliminarFormatter|CargoFormatter|EmpregadorFormatter|EstabelecimentoFormatter|ExclusaoEventosFormatter|Formatter\Formatter|LotacaoTributariaFormatter|RubricaFormatter|AlteracaoContratualFormatter
     * @throws Exception
     */
    public static function get($tipo)
    {
        switch ($tipo) {
            case Tipo::RUBRICA:
                $mapeador = new RubricaMapeador();
                break;
            case Tipo::TRABALHADOR_SEM_VINCULO:
                $mapeador = new TrabalhadorSemVinculoMapeador();
                break;
            default:
                throw new Exception('Tipo de fomulário não encontrado.');
        }

        return $mapeador;
    }
}
