<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itburbano;

class ItburbanoRepository
{
    private $itburbano;

    public function __construct()
    {
        $this->itburbano = new Itburbano();
    }

    public function incluir(Itburbano $entity)
    {
        $clitburbano = new \cl_itburbano();

        $clitburbano->it05_guia = $entity->getGuia();
        $clitburbano->it05_frente = $entity->getFrente();
        $clitburbano->it05_fundos = $entity->getFundos();
        $clitburbano->it05_direito = $entity->getDireito();
        $clitburbano->it05_esquerdo = $entity->getEsquerdo();
        $clitburbano->it05_itbisituacao = $entity->getItbisituacao();

        $clitburbano->incluir($clitburbano->it05_guia);

        if ($clitburbano->erro_status == "0") {
            throw new \Exception($clitburbano->erro_msg);
        }
    }
}
