<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\ImovelAnterior;
use ECidade\Tributario\Library\Entity;

final class ImovelAnteriorConverter extends Converter
{
    public function get(Entity $imovelAnterior)
    {
        $l = '';

        $size = $this->layout->getSize(ImovelAnterior::TESTADA_LOTE);
        $l .= str_pad(substr($imovelAnterior->getTestadaLote(),              0, $size), $size);
        
        $size = $this->layout->getSize(ImovelAnterior::AREA_LOTE);
        $l .= str_pad(substr($imovelAnterior->getAreaLote(),                 0, $size), $size);
        
        $size = $this->layout->getSize(ImovelAnterior::AREA_TOTAL_CONSTRUIDA);
        $l .= str_pad(substr($imovelAnterior->getAreaTotalConstruida(),      0, $size), $size);
        
        $size = $this->layout->getSize(ImovelAnterior::REFERENCIA_ANTERIOR);
        $l .= str_pad(substr($imovelAnterior->getReferenciaAnterior(),       0, $size), $size);
        
        $size = $this->layout->getSize(ImovelAnterior::AREA_LOTE_CALCULO);
        $l .= str_pad(substr($this->format->decimal($imovelAnterior->getAreaLoteCalculo(),  'f', ' ', $size),          0, $size), $size, ' ', STR_PAD_LEFT);
        
        $size = $this->layout->getSize(ImovelAnterior::VALOR_M2_CALCULO);
        $l .= str_pad(substr($this->format->decimal($imovelAnterior->getValorM2Calculo(),  'f', ' ', $size),           0, $size), $size, ' ', STR_PAD_LEFT);
        

        return $l;
    }
}
