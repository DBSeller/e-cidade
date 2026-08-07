<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use \DateTime;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Unica;
use ECidade\Tributario\Library\Entity;

final class UnicaConverter extends Converter
{
    public function getArray($unicas)
    {
        $s = "";
        
        foreach ($unicas as $unica) {

            $size = $this->layout->getSize(Unica::OPERACAO_UNICA);
            if($unica->getDataOperacao() instanceof DateTime) {
                $s .= str_pad(substr($unica->getDataOperacao()->format('d/m/Y'), 0, $size), $size);
            } else {
                $s .= str_pad(substr(' ', 0, $size), $size);
            }

            $size = $this->layout->getSize(Unica::VENCIMENTO_UNICA);
            if($unica->getDataVencimento() instanceof DateTime) {
                $s .= str_pad(substr($unica->getDataVencimento()->format('d/m/Y'), 0, $size), $size);
            } else {
                $s .= str_pad(substr(' ', 0, $size), $size);
            }

            $size = $this->layout->getSize(Unica::PERCENTUAL_DESCONTO_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getPorcentagem()),             0, $size), $size);

            $size = $this->layout->getSize(Unica::VALOR_HISTORICO_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getValorHistorico()),          0, $size), $size);

            $size = $this->layout->getSize(Unica::VALOR_CORRIGIDO_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getValorCorrigido()),          0, $size), $size);

            $size = $this->layout->getSize(Unica::JUROS_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getJuros()),                   0, $size), $size);

            $size = $this->layout->getSize(Unica::MULTA_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getMulta()),                   0, $size), $size);

            $size = $this->layout->getSize(Unica::DESCONTO_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getDesconto()),                0, $size), $size);

            $size = $this->layout->getSize(Unica::TOTAL_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getTotal()),                   0, $size), $size);

            $size = $this->layout->getSize(Unica::TOTAL_LIQUIDO_UNICA);
            $s .= str_pad(substr($this->format->decimal($unica->getTotalDesconto()),           0, $size), $size);

            $size  = $this->layout->getSize(Unica::CODIGO_ARRECADACAO);
            $s .= str_pad(substr($unica->getNumpre(), 0, $size), ($size-3), '0', STR_PAD_LEFT);
            $s .= '000';

            $size = $this->layout->getSize(Unica::BARRAS_UNICA);
            $s .= str_pad(substr($unica->getCodigoBarra(),             0, $size), $size);
        }

        $s .= "# FIM DAS UNICAS";

        return $s;
    }

    public function get(Entity $unica)
    {
        $s = "";
        
        return $s;
    }
}
