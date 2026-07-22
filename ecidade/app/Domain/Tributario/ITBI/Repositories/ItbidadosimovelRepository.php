<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbidadosimovel;

class ItbidadosimovelRepository
{
    private $itbidadosimovel;

    public function __construct()
    {
        $this->itbidadosimovel = new Itbidadosimovel();
    }

    public function getByGuia($guia)
    {
        return $this->itbidadosimovel->where(
            "it22_itbi",
            "=",
            $guia
        )->first();
    }

    public function salvar(Itbidadosimovel $entity)
    {
        $clitbidadosimovel = new \cl_itbidadosimovel();

        $clitbidadosimovel->it22_sequencial = $entity->getSequencial();
        $clitbidadosimovel->it22_itbi = $entity->getItbi();
        $clitbidadosimovel->it22_setor  = $entity->getSetor();
        $clitbidadosimovel->it22_quadra  = $entity->getQuadra();
        $clitbidadosimovel->it22_lote  = $entity->getLote();
        $clitbidadosimovel->it22_descrlograd  = $entity->getDescrlograd();
        $clitbidadosimovel->it22_numero = $entity->getNumero();
        $clitbidadosimovel->it22_compl  = $entity->getCompl();
        $clitbidadosimovel->it22_matricri  = $entity->getMatricri();
        $clitbidadosimovel->it22_setorri  = $entity->getSetorri();
        $clitbidadosimovel->it22_quadrari  = $entity->getQuadrari();
        $clitbidadosimovel->it22_loteri  = $entity->getLoteri();

        if (!empty($clitbidadosimovel->it22_sequencial)) {
            $clitbidadosimovel->alterar($clitbidadosimovel->it22_sequencial);
        } else {
            $clitbidadosimovel->incluir(null);
        }

        if ($clitbidadosimovel->erro_status == "0") {
            throw new \Exception($clitbidadosimovel->erro_msg);
        }

        return $clitbidadosimovel->it22_sequencial;
    }
}
