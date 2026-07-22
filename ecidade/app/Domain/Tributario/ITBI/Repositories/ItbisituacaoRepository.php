<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbisituacao;

class ItbisituacaoRepository
{
    private $itbisituacao;

    public function __construct()
    {
        $this->itbisituacao = new Itbisituacao();
    }

    public function get()
    {
        return $this->itbisituacao->get();
    }
}
