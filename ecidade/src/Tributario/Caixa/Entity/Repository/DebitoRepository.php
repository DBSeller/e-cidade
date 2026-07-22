<?php

namespace ECidade\Tributario\Caixa\Entity\Repository;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Caixa\Repository\ArrecadRepository;
use ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection;
use ECidade\Tributario\Caixa\Cast\ArrecadCollectionCast;

class DebitoRepository extends DataBaseRepository
{
    protected $arrecadRepository;

    public function __construct(
        DataBase $dataBase,
        ArrecadRepository $arrecadRepository,
        ArrecadCollectionCast $arrecadCollectionCast
    ) {
        parent::__construct($dataBase);

        $this->arrecadRepository = $arrecadRepository;
        $this->arrecadCollectionCast = $arrecadCollectionCast;
    }

    public function findAll($where)
    {
        $arrecadCollection = $this->arrecadRepository->findAll($where);

        return $this->toDebitoCollection($arrecadCollection);
    }

    public function findByNumpre($numpre)
    {
        return $this->findAll("k00_numpre = {$numpre}");
    }

    public function findAllByNumpres($numpres)
    {
        return $this->findAll("k00_numpre in (".implode(',', $numpres).")");
    }

    protected function toDebitoCollection($arrecadCollection)
    {
        return $this->arrecadCollectionCast->toDebitoCollection($arrecadCollection);
    }
}
