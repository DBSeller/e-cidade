<?php

namespace ECidade\Tributario\Integracao\Civitas\Parser;


use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Lote;

/**
 * Class Lote Responsavel por gerar o parser do Objeto Lote
 *
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 * @package ECidade\Tributario\Integracao\Civitas\Logger
 */
class LoteParser
{

    /**
     * @param array $aLinha
     * @return Lote
     */
    public static function parser(array $aLinha)
    {

        $oLote = new Lote();

        $aCaracteristicas = array(
            $aLinha[15], $aLinha[17], $aLinha[19],
            $aLinha[21], $aLinha[23], $aLinha[25],
            $aLinha[27], $aLinha[29], $aLinha[31]
        );

        $aCaracteristicas = array_filter($aCaracteristicas);

        $oLote->setCaracteristicasLote($aCaracteristicas);


        if (!empty($aLinha[1])) {
            $oLote->setMatricula($aLinha[1]);
        }

        $oLote->setIdbql($aLinha[2]);
        $oLote->setSetor($aLinha[3]);
        $oLote->setLoteArea($aLinha[9]);
        $oLote->setValorTestada($aLinha[10]);

        return $oLote;
    }

}