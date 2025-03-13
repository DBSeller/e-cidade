<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\NossoNumero;
use ECidade\Tributario\Library\ArrayCollection;

final class NossoNumeroConverter extends Converter
{
    public function get(ArrayCollection $nossoNumeroCollection)
    {
        $l = '';
        foreach($nossoNumeroCollection as $nossoNumero) {

            $size = $this->layout->getSize(NossoNumero::NOSSO_NUMERO_PARCELA);
            $l .= str_pad(substr(str_replace('/', '', $nossoNumero->getNossoNumeroParcela()), 0, $size), $size, ' ', STR_PAD_LEFT);

            $size = $this->layout->getSize(NossoNumero::DIGITO_NOSSO_NUMERO_PARCELA);
            $l .= str_pad(substr($nossoNumero->getDigitoNossoNumeroParcela(),                 0, $size), $size, ' ', STR_PAD_LEFT);
        }

        return $l;
    }
}
