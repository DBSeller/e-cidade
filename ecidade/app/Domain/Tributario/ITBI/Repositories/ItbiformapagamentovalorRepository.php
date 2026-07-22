<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbiformapagamentovalor;

class ItbiformapagamentovalorRepository
{
    private $itbiformapagamentovalor;

    public function __construct()
    {
        $this->itbiformapagamentovalor = new Itbiformapagamentovalor();
    }

    public function salvar(Itbiformapagamentovalor $entity)
    {
        $clitbiformapagamentovalor = new \cl_itbiformapagamentovalor();

        $clitbiformapagamentovalor->it26_sequencial = $entity->getSequencial();
        $clitbiformapagamentovalor->it26_itbitransacaoformapag = $entity->getItbitransacaoformapag();
        $clitbiformapagamentovalor->it26_guia = $entity->getGuia();
        $clitbiformapagamentovalor->it26_valor = $entity->getValor();

        if (!empty($clitbiformapagamentovalor->it26_sequencial)) {
            $clitbiformapagamentovalor->alterar($clitbiformapagamentovalor->it26_sequencial);
        } else {
            $clitbiformapagamentovalor->incluir(null);
        }

        if ($clitbiformapagamentovalor->erro_status == "0") {
            throw new \Exception($clitbiformapagamentovalor->erro_msg);
        }
    }
}
