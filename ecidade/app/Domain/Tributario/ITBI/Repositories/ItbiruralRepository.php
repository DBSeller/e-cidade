<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbirural;

class ItbiruralRepository
{
    private $itbirural;

    public function __construct()
    {
        $this->itbirural = new Itbirural();
    }

    public function inserir(Itbirural $entity)
    {
        $clitbirural = new \cl_itbirural();

        $clitbirural->it18_guia = $entity->getGuia();
        $clitbirural->it18_frente = $entity->getFrente();
        $clitbirural->it18_fundos = $entity->getFundos();
        $clitbirural->it18_prof = $entity->getProf();
        $clitbirural->it18_localimovel = $entity->getLocalimovel();
        $clitbirural->it18_distcidade = $entity->getDistcidade();
        $clitbirural->it18_nomelograd = $entity->getNomelograd();
        $clitbirural->it18_area = $entity->getArea();
        $clitbirural->it18_coordenadas = $entity->getCoordenadas();

        $clitbirural->incluir($clitbirural->it18_guia);

        if ($clitbirural->erro_status == "0") {
            throw new \Exception($clitbirural->erro_msg);
        }
    }
}
