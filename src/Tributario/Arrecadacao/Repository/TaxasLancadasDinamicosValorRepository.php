<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\TaxasLancadasDinamicosValor;

class TaxasLancadasDinamicosValorRepository extends \BaseClassRepository
{
    public function persist(TaxasLancadasDinamicosValor $entity)
    {
        $dao = new \cl_taxaslancadasdinamicos;

        $dao->ar48_sequencial = $entity->getSequencial();
        $dao->ar48_codcam = $entity->getCodcam();
        $dao->ar48_conteudo = $entity->getConteudo();
        $dao->ar48_numnov = $entity->getNumnov();

        if (!empty($dao->ar48_sequencial)) {
            $dao->alterar($dao->ar48_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }
}
