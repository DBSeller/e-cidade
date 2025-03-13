<?php


namespace ECidade\RecursosHumanos\RH\Assentamento\Repository;

class AssentamentoRRA
{
    public static function salvar($assentamentoRRA, $somenteRRA)
    {

        if (empty($assentamentoRRA->getValorTotalDevido())) {
            throw new BusinessException("O campo Valor Total Devido é de preenchimento obrigatório");
        }

        if (empty($assentamentoRRA->getNumeroDeMeses())) {
            throw new BusinessException("O campo Número de Meses é de preenchimento obrigatório");
        }

        $assentamentoRRA->persist($somenteRRA);
    }
}
