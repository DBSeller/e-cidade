<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\NossoNumeroUnica;
use ECidade\Tributario\Library\ArrayCollection;

final class NossoNumeroUnicaConverter extends Converter
{
    public function get(ArrayCollection $nossoNumeroCollection)
    {
        $l = '';
        foreach($nossoNumeroCollection as $nossoNumero) {

            $size = $this->layout->getSize(NossoNumeroUnica::NOSSO_NUMERO_UNICA);
            $l .= str_pad(substr($nossoNumero->getNossoNumeroUnica(),             0, $size), $size, ' ', STR_PAD_LEFT);

            $size = $this->layout->getSize(NossoNumeroUnica::DIGITO_NOSSO_NUMERO_UNICA);
            $l .= str_pad(substr($nossoNumero->getDigitoNossoNumeroUnica(),       0, $size), $size, ' ', STR_PAD_LEFT);
        }

        return $l;
    }
}
