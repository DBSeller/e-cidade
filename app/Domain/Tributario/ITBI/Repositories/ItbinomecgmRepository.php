<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbinomecgm;

class ItbinomecgmRepository
{
    private $itbinomecgm;

    public function __construct()
    {
        $this->itbinomecgm = new Itbinomecgm();
    }

    public function salvar(Itbinomecgm $entity)
    {
        $clitbinomecgm = new \cl_itbinomecgm();

        $clitbinomecgm->it21_sequencial = $entity->getSequencial();
        $clitbinomecgm->it21_itbinome = $entity->getItbinome();
        $clitbinomecgm->it21_numcgm = $entity->getNumcgm();

        if (!empty($clitbinomecgm->it21_sequencial)) {
            $clitbinomecgm->alterar($clitbinomecgm->it21_sequencial);
        } else {
            $clitbinomecgm->incluir(null);
        }

        if ($clitbinomecgm->erro_status == "0") {
            throw new \Exception($clitbinomecgm->erro_msg);
        }
    }
}
