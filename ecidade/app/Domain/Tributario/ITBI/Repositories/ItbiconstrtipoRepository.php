<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbiconstrtipo;

class ItbiconstrtipoRepository
{
    private $itbiconstrtipo;

    public function __construct()
    {
        $this->itbiconstrtipo = new Itbiconstrtipo();
    }

    public function inserir(Itbiconstrtipo $entity)
    {
        $clitbiconstrtipo = new \cl_itbiconstrtipo();

        $clitbiconstrtipo->it10_codigo = $entity->getCodigo();
        $clitbiconstrtipo->it10_caract = $entity->getCaract();

        $clitbiconstrtipo->incluir($clitbiconstrtipo->it10_codigo);

        if ($clitbiconstrtipo->erro_status == "0") {
            throw new \Exception($clitbiconstrtipo->erro_msg);
        }
    }
}
