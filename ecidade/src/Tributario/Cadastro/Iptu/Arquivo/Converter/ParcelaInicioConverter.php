<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaInicio;
use ECidade\Tributario\Library\Entity;

final class ParcelaInicioConverter extends Converter
{
    /**
     * @param ParcelaInicio $parcelaInicio
     * @return string
     *
     * Retorna os dados no layout para montagem do TXT
     */
    public function get(Entity $parcelaInicio)
    {
        $l = '';

        $size = $this->layout->getSize(ParcelaInicio::TOTAL_PARCELAS);
        $l   .= str_pad(substr($parcelaInicio->getTotalParcelas(), 0, $size), $size, '0', STR_PAD_LEFT);

        $size = $this->layout->getSize(ParcelaInicio::EXPRESAO_PARCELADOS);
        $l   .= str_pad(substr($parcelaInicio->getExpresaoParcelados(), 0, $size), $size);

        $size = $this->layout->getSize(ParcelaInicio::PERCENTUAL_MES_JURO_ATRASO);
        $l   .= str_pad(substr($this->format->decimal($parcelaInicio->getPercentualMesJuroAtraso()), 0, $size), $size, '', STR_PAD_LEFT);

        $size = $this->layout->getSize(ParcelaInicio::PERCENTUAL_GERAL_MULTA_ATRASO);
        $l   .= str_pad(substr($this->format->decimal($parcelaInicio->getPercentualGeralMultaAtraso()), 0, $size), $size, '', STR_PAD_LEFT);

        return $l;
    }
}
