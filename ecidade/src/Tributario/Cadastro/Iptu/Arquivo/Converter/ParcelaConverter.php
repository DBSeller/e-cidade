<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter;

use ECidade\Tributario\Cadastro\Iptu\Arquivo\Converter\CollectionConverter;
use ECidade\Tributario\Library\EntityCollection;

final class ParcelaConverter extends CollectionConverter
{
    // public function get(EntityCollection $debitos)
    public function get(Entity $parcelaReciboCollection)
    {
        $parcelas = array();
        
        foreach ($debitos as $debito) {
            foreach ($debito->getParcelas() as $parcela) {
                foreach ($parcela->getReceitas() as $receita) {
                    $parcelas[$parcela->getNumero()][$receita->getCodigo()] += $receita->getValor();
                }
            }
        }

        $s = "";

        foreach ($parcelas as $numeroParcela => $receitas) {
            foreach ($receitas as $numeroReceita => $valor) {
                
                $s .= substr(str_pad($numeroParcela, 3, "0", STR_PAD_LEFT), 0, 3);
                $s .= substr(str_pad($numeroReceita, 3, "0", STR_PAD_LEFT), 0, 3);
                $s .= $this->format->decimal($valor);
            }
        }

        return $s;
    }
}
