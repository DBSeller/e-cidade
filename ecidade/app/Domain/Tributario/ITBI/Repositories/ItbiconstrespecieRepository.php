<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbiconstrespecie;

class ItbiconstrespecieRepository
{
    private $itbiconstrespecie;

    public function __construct()
    {
        $this->itbiconstrespecie = new Itbiconstrespecie();
    }

    public function inserir(Itbiconstrespecie $entity)
    {
        $clitbiconstrespecie = new \cl_itbiconstrespecie();

        $clitbiconstrespecie->it09_codigo = $entity->getCodigo();
        $clitbiconstrespecie->it09_caract = $entity->getCaract();

        $clitbiconstrespecie->incluir($clitbiconstrespecie->it09_codigo);

        if ($clitbiconstrespecie->erro_status == "0") {
            throw new \Exception($clitbiconstrespecie->erro_msg);
        }
    }
}
