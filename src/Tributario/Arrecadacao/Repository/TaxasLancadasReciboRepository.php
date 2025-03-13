<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\TaxasLancadasRecibo;

class TaxasLancadasReciboRepository extends \BaseClassRepository
{
    public function persist(TaxasLancadasRecibo $entity)
    {
        $dao = new \cl_taxaslancadasrecibo();

        $dao->ar46_sequencial = $entity->getSequencial();
        $dao->ar46_taxaslancadas = $entity->getTaxaslancadas();
        $dao->ar46_numnov = $entity->getNumnov();
        $dao->ar46_tipoemissao = $entity->getTipoemissao();
        $dao->ar46_departamento = $entity->getDepartamento();

        if (!empty($dao->ar46_sequencial)) {
            $dao->alterar($dao->ar46_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }
}
