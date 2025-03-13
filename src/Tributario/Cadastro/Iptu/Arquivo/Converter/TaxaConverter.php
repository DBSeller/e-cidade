<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\Converter;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Taxa;
use ECidade\Tributario\Library\Entity;

final class TaxaConverter extends Converter
{
    public function getArray($taxas)
    {
        $s = "";

        $taxa = $taxas['iptu'];

        $s .= str_pad($taxa->getDescricao(), 40);
        $s .= str_pad($this->format->decimal($taxa->getQuantidade(), "f", " ", 10), "0", STR_PAD_LEFT);
        $s .= str_pad($this->format->decimal($taxa->getValorTotal(), "f", " ", 18), 18, " ", STR_PAD_LEFT);
        $s .= str_pad($this->format->decimal($taxa->getValorParcela(), 'f', " ", 18), 18, " ", STR_PAD_LEFT);

        $taxa = $taxas['taxa'];
        
        $s .= str_pad($taxa->getDescricao(), 40);
        $s .= str_pad($this->format->decimal($taxa->getQuantidade(), "f", " ", 10), "0", STR_PAD_LEFT);
        $s .= str_pad($this->format->decimal($taxa->getValorTotal(), "f", " ", 18), 18, " ", STR_PAD_LEFT);
        $s .= str_pad($this->format->decimal($taxa->getValorParcela(), 'f', " ", 18), 18, " ", STR_PAD_LEFT);

        return $s;
    }

    public function get(Entity $taxa)
    {
        $s = str_pad($taxa->getDescricao(), 40);
        $s .= str_pad($this->format->decimal($taxa->getQuantidade(), "f", " ", 10), "0", STR_PAD_LEFT);
        $s .= str_pad($this->format->decimal($taxa->getValorTotal(), "f", " ", 18), 18, " ", STR_PAD_LEFT);
        $s .= str_pad($this->format->decimal($taxa->getValorParcela(), 'f', " ", 18), 18, " ", STR_PAD_LEFT);

        return $s;
    }

    public function getArray2($taxaDescricao)
    {
        // Array
        // (
        //     [iptu] => Array
        //         (
        //             [codigo_arrecadacao] => 5018012
        //             [valor] => 
        //         )
        //     [taxa] => Array
        //         (
        //             [codigo_arrecadacao] => 
        //             [valor] => 0
        //         )
        // )

        $s = "";

        $s .= str_pad($taxaDescricao['iptu']['codigo_arrecadacao'], 11, " ", STR_PAD_LEFT);
        $s .= $this->format->decimal(abs($taxaDescricao['iptu']['valor']), "f", " ", 11);

        $s .= str_pad($taxaDescricao['taxa']['codigo_arrecadacao'], 11, " ", STR_PAD_LEFT);
        $s .= $this->format->decimal(abs($taxaDescricao['taxa']['valor']), "f", " ", 11);
        return $s;
    }
}
