<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Imovel;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Taxa;

final class TaxaRepository extends DataBaseRepository
{
    public function find($matricula, $filtro)
    {
        $valorIptu = 0;
        $quantIptu = 0;
        $valorTaxa = 0;
        $quantTaxa = 0;

        if ($filtro->hasIptu()) {
            
            $sql = "
                select sum(iptucalv.j21_valor) as valor,
                       iptucalv.j21_quant
                  from iptucalv
                 where iptucalv.j21_matric = ".$matricula."
                   and iptucalv.j21_anousu = ".$filtro->getAno()." 
                 group by iptucalv.j21_quant
            ";

            $result = $this->dataBase->execute($sql);

            $object = $this->dataBase->fetchRow($result);

            $valorIptu = $object->valor;
            $quantIptu = $object->j21_quant;
        }

        if ($filtro->hasTaxas()) {

            $sql = "
                select sum(j152_valor) as valor,
                       iptutaxacalv.j152_quant
                  from iptutaxacalv
                       inner join iptutaxanump on iptutaxanump.j151_codigo = iptutaxacalv.j152_iptutaxanump
                       inner join iptucadtaxaexe on iptucadtaxaexe.j08_iptucadtaxaexe = iptutaxanump.j151_iptucadtaxaexe
                 where iptutaxanump.j151_matric = ".$matricula."
                   and iptucadtaxaexe.j08_anousu = ".$filtro->getAno()."
                   and j151_iptucadtaxaexe in ( ".implode(',', $filtro->getTaxas())." ) 
                 group by iptutaxacalv.j152_quant
            ";

            $result = $this->dataBase->execute($sql);

            $object = $this->dataBase->fetchRow($result);

            $valorTaxa = $object->valor;
            $quantTaxa = $object->j152_quant;
        }

        $taxas = array();

        $taxa = new Taxa();
        $taxa->setValorTotal($valorIptu);
        $taxa->setQuantidade($quantIptu);

        $taxas['iptu'] = $taxa;

        $taxa = new Taxa();
        $taxa->setValorTotal($valorTaxa);
        $taxa->setQuantidade($quantTaxa);

        $taxas['taxa'] = $taxa;

        return $taxas;
    }
}
