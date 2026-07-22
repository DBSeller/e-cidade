<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbidadosimovelsetorloc;

class ItbidadosimovelsetorlocRepository
{
    private $itbidadosimovelsetorloc;

    public function __construct()
    {
        $this->itbidadosimovelsetorloc = new Itbidadosimovelsetorloc();
    }

    public function salvar(Itbidadosimovelsetorloc $entity)
    {
        $clitbidadosimovelsetorloc = new \cl_itbidadosimovelsetorloc();

        $clitbidadosimovelsetorloc->it29_sequencial = $entity->getSequencial();
        $clitbidadosimovelsetorloc->it29_setorloc = $entity->getSetorloc();
        $clitbidadosimovelsetorloc->it29_itbidadosimovel = $entity->getItbidadosimovel();

        if (!empty($clitbidadosimovelsetorloc->it29_sequencial)) {
            $clitbidadosimovelsetorloc->alterar($clitbidadosimovelsetorloc->it29_sequencial);
        } else {
            $clitbidadosimovelsetorloc->incluir(null);
        }

        if ($clitbidadosimovelsetorloc->erro_status == "0") {
            throw new \Exception($clitbidadosimovelsetorloc->erro_msg);
        }
    }
}
