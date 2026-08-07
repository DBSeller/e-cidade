<?php

namespace ECidade\Tributario\Caixa\Entity\Repository;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Caixa\Repository\RecibounicaRepository;
use ECidade\Tributario\Caixa\Entity\Collection\CotaUnicaCollection;

class CotaUnicaRepository extends DataBaseRepository
{
    private $recibounicaRepository;

    public function __construct(DataBase $dataBase, RecibounicaRepository $recibounicaRepository)
    {
        parent::__construct($dataBase);

        $this->recibounicaRepository = $recibounicaRepository;
    }

    public function findAll($where)
    {
        $reciboUnicas = $this->recibounicaRepository->findAll($where);

        return new CotaUnicaCollection($reciboUnicas);
    }
}
