<?php

namespace ECidade\Tributario\Caixa\Entity\Repository;

use ECidade\Tributario\Caixa\Entity\Repository\DebitoRepository;
use ECidade\Tributario\Caixa\Entity\Lista;
use ECidade\Tributario\Caixa\Collection\ArrecadCollection;

final class ListaDebitoRepository extends DebitoRepository
{
    public function findAll($where)
    {
        $sql = "select * 
                  from listadeb 
                       inner join arrecad on k61_numpre = k00_numpre and k61_numpar = k00_numpar 
                 where $where";

        $arrecadCollection = new ArrecadCollection($this->arrecadRepository->dataBase->execute($sql));

        return $this->arrecadCollectionCast->toDebitoCollection($arrecadCollection);
    }

    public function find(Lista $lista)
    {
        $where = "k61_codigo = " . $lista->getCodigo();

        return $this->findAll($where);
    }
}
