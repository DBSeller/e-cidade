<?php

namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Issqn\Model\Issvar;
use ECidade\Tributario\Library\Repository;

class IssvarRepository extends Repository
{
    public function persist(Issvar $entity)
    {
        $this->dao->q05_codigo = $entity->getCodigo();
        $this->dao->q05_numpre = $entity->getNumpre();
        $this->dao->q05_numpar = $entity->getNumpar();
        $this->dao->q05_valor = $entity->getValor();
        $this->dao->q05_ano = $entity->getAno();
        $this->dao->q05_mes = $entity->getMes();
        $this->dao->q05_histor = $entity->getHistor();
        $this->dao->q05_aliq = $entity->getAliq();
        $this->dao->q05_bruto = $entity->getBruto();
        $this->dao->q05_vlrinf = $entity->getVlrinf();

        if (!empty($this->dao->q05_codigo)) {
            $this->dao->alterar($this->dao->q05_codigo);
        } else {
            $this->dao->incluir(null);
        }

        if ($this->dao->erro_status == "0") {
            throw new \Exception($this->dao->erro_msg);
        }
    }
}
