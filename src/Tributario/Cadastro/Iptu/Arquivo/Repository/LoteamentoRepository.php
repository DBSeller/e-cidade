<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Repository;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity\Loteamento;

final class LoteamentoRepository extends DataBaseRepository
{
    public function find($matricula)
    {
        $sql = "
            select loteam.j34_descr
              from proprietario
                   inner join loteloteam on loteloteam.j34_idbql = proprietario.j01_idbql
                   inner join loteam on loteam.j34_loteam = loteloteam.j34_loteam
             where proprietario.j01_matric = $matricula
        ";
        
        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        $loteamento = new Loteamento();

        $loteamento->setDescricao($object->j34_descr);

        return $loteamento;
    }
}
