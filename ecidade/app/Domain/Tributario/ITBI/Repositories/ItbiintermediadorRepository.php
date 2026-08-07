<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbiintermediador;

final class ItbiintermediadorRepository
{
    /**
     * @var Itbiintermediador
     */
    private $itbiintermediador;

    public function __construct()
    {
        $this->itbiintermediador = new Itbiintermediador();
    }

    /**
     * Retorna os dados do intermediador com base no número da guia de ITBI
     * @param $guia
     * @param false $principal
     * @return Itbiintermediador|Model|Builder|Collection|null
     */
    public function getByGuia($guia, $principal = false)
    {
        $oQuery = $this->itbiintermediador->where(
            "it35_itbi",
            "=",
            $guia
        );

        if ($principal) {
            return $oQuery->where(
                "it35_principal",
                "=",
                "t"
            )->first();
        }

        return $oQuery->get();
    }

    public function salvar(Itbiintermediador $entity)
    {
        $clitbiintermediador = new \cl_itbiintermediador();

        $clitbiintermediador->it35_sequencial = $entity->getSequencial();
        $clitbiintermediador->it35_itbi = $entity->getItbi();
        $clitbiintermediador->it35_cgm = $entity->getCgm();
        $clitbiintermediador->it35_nome = $entity->getNome();
        $clitbiintermediador->it35_cnpj_cpf = $entity->getCnpjCpf();
        $clitbiintermediador->it35_creci = $entity->getCreci();
        $clitbiintermediador->it35_principal = $entity->getPrincipal();

        if (!empty($clitbiintermediador->it35_sequencial)) {
            $clitbiintermediador->alterar($clitbiintermediador->it35_sequencial);
        } else {
            $clitbiintermediador->incluir(null);
        }

        if ($clitbiintermediador->erro_status == "0") {
            throw new \Exception($clitbiintermediador->erro_msg);
        }
    }
}
