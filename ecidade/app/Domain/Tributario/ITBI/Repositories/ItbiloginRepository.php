<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbilogin;

class ItbiloginRepository
{
    private $itbilogin;

    public function __construct()
    {
        $this->itbilogin = new Itbilogin();
    }

    public function inserir(Itbilogin $entity)
    {
        $clitbilogin = new \cl_itbilogin();

        $clitbilogin->it13_guia = $entity->getGuia();
        $clitbilogin->it13_id_usuario = $entity->getIdUsuario();

        $clitbilogin->incluir($clitbilogin->it13_guia);

        if ($clitbilogin->erro_status == "0") {
            throw new \Exception($clitbilogin->erro_msg);
        }
    }
}
