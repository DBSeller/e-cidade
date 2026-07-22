<?php

namespace App\Domain\Patrimonial\Patrimonio\Services;

use App\Domain\Patrimonial\Patrimonio\Contracts\EtiquetaBuilder;
use App\Domain\Patrimonial\Patrimonio\Models\Bem;

class EtiquetaService
{
    public function build(EtiquetaBuilder $builder, Bem $bem)
    {
        $builder->setCodigo($bem->t52_bem);
        $builder->setInstituicao($bem->instituicao->nomeinst);
        $builder->setPlaca($bem->t52_ident);
        $builder->setDescricao(utf8_encode($bem->t52_descr));
        $builder->setBarcode($bem->t52_ident);

        $builder->create();

        return $builder->getEtiqueta();
    }
}
