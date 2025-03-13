<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbilocalidaderural;

class ItbilocalidaderuralRepository
{
    private $itbilocalidaderural;

    public function __construct()
    {
        $this->itbilocalidaderural = new Itbilocalidaderural();
    }

    public function salvar(Itbilocalidaderural $entity)
    {
        $clitbilocalidaderural = new \cl_itbilocalidaderural();

        $clitbilocalidaderural->it33_sequencial = $entity->getSequencial();
        $clitbilocalidaderural->it33_guia = $entity->getGuia();
        $clitbilocalidaderural->it33_localidaderural = $entity->getLocalidaderural();

        if (!empty($clitbilocalidaderural->it33_sequencial)) {
            $clitbilocalidaderural->alterar($clitbilocalidaderural->it33_sequencial);
        } else {
            $clitbilocalidaderural->incluir(null);
        }

        if ($clitbilocalidaderural->erro_status == "0") {
            throw new \Exception($clitbilocalidaderural->erro_msg);
        }
    }
}
