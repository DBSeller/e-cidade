<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use Illuminate\Support\Facades\DB;
use App\Domain\Tributario\ITBI\Models\Itbiconstr;

final class ItbiconstrRepository
{
    /**
     * @var Itbiconstr
     */
    private $itbiconstr;

    public function __construct()
    {
        $this->itbiconstr = new Itbiconstr();
    }

    /**
     * Retorna os dados da construção com base no número da Guia de ITBI
     * @param $guia
     * @return \Illuminate\Support\Collection
     */
    public function getByGuia($guia)
    {
        $oItbimatric = $this->itbiconstr->join(
            "itbiconstrespecie",
            "it09_codigo",
            "=",
            "it08_codigo",
            "left"
        )->join(
            DB::raw("caracter c1"),
            DB::raw("c1.j31_codigo"),
            "=",
            "it09_caract",
            "left"
        )->join(
            "itbiconstrtipo",
            "it10_codigo",
            "=",
            "it08_codigo",
            "left"
        )->join(
            DB::raw("caracter c2"),
            DB::raw("c2.j31_codigo"),
            "=",
            "it10_caract",
            "left"
        )->where(
            "it08_guia",
            "=",
            $guia
        )->select([
            "itbiconstr.*",
            "c1.j31_descr AS carconstrespecie",
            "c2.j31_descr AS caritbiconstrtipo"
        ])->get();

        return $oItbimatric;
    }

    public function salvar(Itbiconstr $entity)
    {
        $clitbiconstr = new \cl_itbiconstr();

        $clitbiconstr->it08_codigo = $entity->getCodigo();
        $clitbiconstr->it08_guia = $entity->getGuia();
        $clitbiconstr->it08_area = $entity->getArea();
        $clitbiconstr->it08_areatrans = $entity->getAreatrans();
        $clitbiconstr->it08_ano = $entity->getAno();
        $clitbiconstr->it08_obs = $entity->getObs();
        $clitbiconstr->it08_coordenadas = $entity->getCoordenadas();

        if (!empty($clitbiconstr->it08_codigo)) {
            $clitbiconstr->alterar($clitbiconstr->it08_codigo);
        } else {
            $clitbiconstr->incluir(null);
        }

        if ($clitbiconstr->erro_status == "0") {
            throw new \Exception($clitbiconstr->erro_msg);
        }

        return $clitbiconstr->it08_codigo;
    }
}
