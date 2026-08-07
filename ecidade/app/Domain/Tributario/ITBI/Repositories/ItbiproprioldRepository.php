<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbipropriold;

class ItbiproprioldRepository
{
    private $itbipropriold;

    public function __construct()
    {
        $this->itbipropriold = new Itbipropriold();
    }

    public function inserir(Itbipropriold $entity)
    {
        $clitbipropriold = new \cl_itbipropriold();

        $clitbipropriold->it20_guia = $entity->getGuia();
        $clitbipropriold->it20_numcgm = $entity->getNumcgm();
        $clitbipropriold->it20_pri = $entity->getPri();

        $clitbipropriold->incluir($clitbipropriold->it20_guia, $clitbipropriold->it20_numcgm);

        if ($clitbipropriold->erro_status == "0") {
            throw new \Exception($clitbipropriold->erro_msg);
        }
    }
}
