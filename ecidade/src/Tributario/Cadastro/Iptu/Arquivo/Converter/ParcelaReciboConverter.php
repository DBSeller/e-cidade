<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ParcelaRecibo;
use ECidade\Tributario\Library\EntityCollection;
use ECidade\Tributario\Library\Entity;
use \DateTime;

final class ParcelaReciboConverter extends Converter
{
    public function getArray($parcelaReciboCollection)
    {
        $s = '';

        if(count($parcelaReciboCollection) < ParcelaRecibo::MAXIMO_PARCELAS) {
            $parcelaReciboCollection = $this->ajustarQuantidade($parcelaReciboCollection);
        }
        
        foreach($parcelaReciboCollection as $parcelaRecibo) {

            $size = $this->layout->getSize(ParcelaRecibo::VENCIMENTO_PARCELA);
            if($parcelaRecibo->getVencimento() instanceof DateTime) {
                $vencimentoParcela = $parcelaRecibo->getVencimento()->format('d/m/Y');
            } else {
                $vencimentoParcela = '';
            }
            $s .= str_pad(substr($vencimentoParcela, 0, $size), $size);

            $size = $this->layout->getSize(ParcelaRecibo::VALOR_PARCELA);
            $s .= str_pad(substr($this->format->decimal($parcelaRecibo->getValor()), 0, $size), $size);
            
            $size = $this->layout->getSize(ParcelaRecibo::VALORJURO_PARCELA);
            $s .= str_pad(substr($this->format->decimal($parcelaRecibo->getJuros()), 0, $size), $size);
            
            $size = $this->layout->getSize(ParcelaRecibo::VALORMULTA_PARCELA);
            $s .= str_pad(substr($this->format->decimal($parcelaRecibo->getMulta()), 0, $size), $size);
            
            $size = $this->layout->getSize(ParcelaRecibo::NUMPRE_PARCELA);
            $s .= str_pad(($this->format->numpre($parcelaRecibo->getCodigoArrecadacao())), $size, '0', STR_PAD_LEFT);
            
            $size = $this->layout->getSize(ParcelaRecibo::CODIGOBARRAS_PARCELA);
            $s .= str_pad(substr($parcelaRecibo->getCodigoBarras(), 0, $size), $size, ' ', STR_PAD_LEFT);
            
            $size = $this->layout->getSize(ParcelaRecibo::PARCELA);
            $s .= str_pad($parcelaRecibo->getNumero(), $size, '0', STR_PAD_LEFT);
        }

        return $s;
    }

    public function get(Entity $parcelaReciboCollection)
    {
        $s = '';
        
        foreach($parcelaReciboCollection as $parcelaRecibo) {

            $s .= $this->format->date($parcelaRecibo->getVencimento()->format('d/m/Y'));
            $s .= $this->format->decimal($parcelaRecibo->getValor());
            $s .= $this->format->decimal($parcelaRecibo->getJuros());
            $s .= $this->format->decimal($parcelaRecibo->getMulta());
            
            $size = $this->layout->getSize(ParcelaRecibo::NUMPRE_PARCELA);
            $s .= $this->format->numpre($parcelaRecibo->getCodigoArrecadacao()) . str_pad(null, $size, '0', STR_PAD_LEFT);
            
            $s .= $parcelaRecibo->getCodigoBarras();
            
            $size = $this->layout->getSize(ParcelaRecibo::PARCELA);
            $s .= str_pad($parcelaRecibo->getNumero(), $size, '0', STR_PAD_LEFT);
        }

        return $s;
    }

    public function ajustarQuantidade($parcelaReciboCollection) 
    {
        $quantidadeFaltante = ParcelaRecibo::MAXIMO_PARCELAS - count($parcelaReciboCollection);
        $array              = array();

        if(!empty($parcelaReciboCollection)) {
            $array          = $parcelaReciboCollection;
        }

        for ($i=1; $i <= $quantidadeFaltante; $i++) {

            $parcelaRecibo = new ParcelaRecibo();

            $parcelaRecibo->setValor('');
            $parcelaRecibo->setJuros('');
            $parcelaRecibo->setMulta('');
            $parcelaRecibo->setCodigoArrecadacao('');
            $parcelaRecibo->setCodigoBarras('');
            $parcelaRecibo->setNumero('');

            $array[] = $parcelaRecibo;
        }

        return $array;
    }
}
