<?php 

namespace ECidade\Tributario\Caixa\Repository\Sequence;

use ECidade\Tributario\Library\SequenceRepository;

final class NumpreSequenceRepository extends SequenceRepository
{
    public function get()
    {
        $sql = "select last_value as numpre from numpref_k03_numpre_seq";

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $object->numpre;
    }

    public function next()
    {
        $sql = "select nextval('numpref_k03_numpre_seq') as numpre";

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $object->numpre;
    }
}
