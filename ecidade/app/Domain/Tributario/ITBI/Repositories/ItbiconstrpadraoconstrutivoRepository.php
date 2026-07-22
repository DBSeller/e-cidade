<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbiconstrpadraoconstrutivo;

class ItbiconstrpadraoconstrutivoRepository
{
    private $itbiconstrpadraoconstrutivo;

    public function __construct()
    {
        $this->itbiconstrpadraoconstrutivo = new Itbiconstrpadraoconstrutivo();
    }

    public function inserir(Itbiconstrpadraoconstrutivo $entity)
    {
        $clitbiconstrpadraoconstrutivo = new \cl_itbiconstrpadraoconstrutivo();

        $clitbiconstrpadraoconstrutivo->it34_codigo = $entity->getCodigo();
        $clitbiconstrpadraoconstrutivo->it34_caract = $entity->getCaract();

        $clitbiconstrpadraoconstrutivo->incluir(
            $clitbiconstrpadraoconstrutivo->it34_codigo,
            $clitbiconstrpadraoconstrutivo->it34_caract
        );

        if ($clitbiconstrpadraoconstrutivo->erro_status == "0") {
            throw new \Exception($clitbiconstrpadraoconstrutivo->erro_msg);
        }
    }
}
