<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Imovel;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Taxa;

final class TaxaDescricaoRepository extends DataBaseRepository
{
    public function find($matricula, $filtro, $unicas)
    {
        $numpreIptu = null;
        $valorDescontoUnicaIptu = 0;
        $numpreTaxa = null;
        $valorDescontoUnicaTaxa = 0;

        $unica = $unicas[0];

        if ($filtro->hasIptu()) {
            
            $sql = "
                select iptunump.j20_numpre
                  from iptunump
                 where iptunump.j20_matric = ".$matricula."
                   and iptunump.j20_anousu = ".$filtro->getAno()." ";

            $result = $this->dataBase->execute($sql);

            $object = $this->dataBase->fetchRow($result);

            $numpreIptu = $object->j20_numpre;

            if (!empty($numpreIptu) && !empty($unica)) {
                
                $sql = "
                    select sum(k00_valor) as valor
                      from recibopaga
                     where k00_numnov = {$unica->getNumpre()}
                       and k00_numpre = {$numpreIptu}
                       and k00_hist = 918
                ";

                $result = $this->dataBase->execute($sql);

                $object = $this->dataBase->fetchRow($result);

                $valorDescontoUnicaIptu = $object->valor;
            }
        }

        if ($filtro->hasTaxas()) {

            $sql = "
                select iptutaxanump.j151_numpre
                  from iptutaxacalv
                       inner join iptutaxanump on iptutaxanump.j151_codigo = iptutaxacalv.j152_iptutaxanump
                       inner join iptucadtaxaexe on iptucadtaxaexe.j08_iptucadtaxaexe = iptutaxanump.j151_iptucadtaxaexe
                 where iptutaxanump.j151_matric = ".$matricula."
                   and iptucadtaxaexe.j08_anousu = ".$filtro->getAno()."
                   and j151_iptucadtaxaexe in ( ".implode(',', $filtro->getTaxas())." ) 
            ";

            $result = $this->dataBase->execute($sql);

            $object = $this->dataBase->fetchRow($result);

            if (!empty($object->j151_numpre) && !empty($unica)) {

                $numpreTaxa = $object->j151_numpre;

                $sql = "
                    select sum(k00_valor) as valor
                      from recibopaga
                     where k00_numnov = {$unica->getNumpre()}
                       and k00_numpre = {$numpreTaxa}
                       and k00_hist = 918
                ";

                $result = $this->dataBase->execute($sql);

                $object = $this->dataBase->fetchRow($result);

                $valorDescontoUnicaTaxa = $object->valor;
            }
        }

        return array(
            'iptu' => array(
                'codigo_arrecadacao' => $numpreIptu,
                'valor' => $valorDescontoUnicaIptu
            ),
            'taxa' => array(
                'codigo_arrecadacao' => $numpreTaxa,
                'valor' => $valorDescontoUnicaTaxa
            )
        );
    }
}
