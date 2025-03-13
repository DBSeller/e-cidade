<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\Service;
use ECidade\Tributario\Caixa\Entity\Repository\DebitoRepository as EntityDebitoRepository;
use ECidade\Tributario\Cadastro\Entity\Matricula;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Filtro;

final class DebitoRepository extends Service
{
    private $entityDebitoRepository;
    
    public function __construct(EntityDebitoRepository $entityDebitoRepository)
    {
        $this->entityDebitoRepository = $entityDebitoRepository;
    }

    public function findAll(Matricula $matricula, Filtro $filtro)
    {
        $whereIptu = "";
        $whereTaxa = "";
        
        $where = " k00_numpre in ( ";

        if ($filtro->hasIptu()) {
            
            $whereIptu = "select j20_numpre 
                            from iptunump
                           where j20_matric = ".$matricula->getMatricula()."
                             and j20_anousu = ".$filtro->getAno()." ";
        }

        if ($filtro->hasTaxas()) {

            if (!empty($whereIptu)) {
                $whereIptu .= " union ";
            }

            $whereTaxa = "select j151_numpre 
                            from iptutaxanump
                           where j151_matric = ".$matricula->getMatricula()."
                             and j151_iptucadtaxaexe in ( ".implode(',', $filtro->getTaxas())." ) ";
        }

        $where .= $whereIptu;
        $where .= $whereTaxa;

        $where .= " ) ";

        $debitoCollection = $this->entityDebitoRepository->findAll($where);

        return $debitoCollection;
    }
}
