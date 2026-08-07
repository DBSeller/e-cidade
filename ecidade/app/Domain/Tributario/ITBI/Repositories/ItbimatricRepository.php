<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbimatric;

final class ItbimatricRepository
{
    /**
     * @var Itbimatric
     */
    private $itbimatric;

    public function __construct()
    {
        $this->itbimatric = new Itbimatric();
    }

    /**
     * Busca os dados com base no número da guia
     * @param $guia
     * @return Itbimatric|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function getByGuia($guia)
    {
        return $this->itbimatric->where(
            "it06_guia",
            "=",
            $guia
        )->first();
    }

    public function incluir(Itbimatric $entity)
    {
        $clitbimatric = new \cl_itbimatric();

        $clitbimatric->it06_guia = $entity->getGuia();
        $clitbimatric->it06_matric = $entity->getMatric();

        $clitbimatric->incluir($clitbimatric->it06_guia, $clitbimatric->it06_matric);

        if ($clitbimatric->erro_status == "0") {
            throw new \Exception($clitbimatric->erro_msg);
        }
    }
}
