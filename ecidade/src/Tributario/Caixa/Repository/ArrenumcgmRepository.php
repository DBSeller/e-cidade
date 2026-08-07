<?php

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Caixa\Model\Arrenumcgm;

final class ArrenumcgmRepository extends ArrebaseRepository
{
    public function insert(Arrenumcgm $entity)
    {
        $this->dao->k00_numpre = $entity->getNumpre();
        $this->dao->k00_numcgm = $entity->getNumcgm();

        $this->dao->incluir();

        if ($this->dao == "0") {
            throw new \Exception($this->dao->erro_msg);
        }
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $arrenumcgm = new Arrenumcgm();

        $arrenumcgm->setNumcgm($object->k00_numcgm);
        $arrenumcgm->setNumpre($object->k00_numpre);

        return $arrenumcgm;
    }

    /**
     * Método sobrescrito pois a order dos parâmetros do sql_query_file é diferente
     */
    public function find($numcgm, $numpre)
    {
        $sql = $this->dao->sql_query_file($numcgm, $numpre);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $this->make($object);
    }
}
