<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbiruralcaract;

final class ItbiruralcaractRepository
{
    /**
     * Retorna as caracteristicas de ITBI rural com base no número da guia
     * @param $guia
     * @param null $tipo
     * @return Itbiruralcaract|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function getByGuia($guia, $tipo = null)
    {
        $oQuery = Itbiruralcaract::joinCaracter()->guia($guia);

        if (!empty($tipo)) {
            $oQuery->tipo($tipo);
        }

        return $oQuery->get();
    }

    public function inserir(Itbiruralcaract $entity)
    {
        $clitbiruralcaract = new \cl_itbiruralcaract();

        $clitbiruralcaract->it19_guia = $entity->getGuia();
        $clitbiruralcaract->it19_codigo = $entity->getCodigo();
        $clitbiruralcaract->it19_valor = ($entity->getValor()!=null && $entity->getValor()!=0)?$entity->getValor():'0';
        $clitbiruralcaract->it19_tipocaract = $entity->getTipocaract();

        $clitbiruralcaract->incluir($clitbiruralcaract->it19_guia, $clitbiruralcaract->it19_codigo);

        if ($clitbiruralcaract->erro_status == "0") {
            throw new \Exception($clitbiruralcaract->erro_msg);
        }
    }
}
